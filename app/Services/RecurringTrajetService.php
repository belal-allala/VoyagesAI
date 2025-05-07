<?php

namespace App\Services;

use App\Models\Trajet;
use App\Models\RecurringPattern;
use Carbon\Carbon;

class RecurringTrajetService
{
    public function generateUpcomingTrajets(int $daysAhead = 30)
    {
        $patterns = RecurringPattern::with('trajet')
            ->where(function($query) use ($daysAhead) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->get();

        foreach ($patterns as $pattern) {
            $this->generateTrajetsForPattern($pattern, $daysAhead);
        }
    }

    public function generateTrajetsForPattern(RecurringPattern $pattern, int $daysAhead)
    {
        $startDate = now();
        $endDate = now()->addDays($daysAhead);
        
        $currentDate = Carbon::parse($pattern->start_date);
        $endLimit = $pattern->end_date ? min(Carbon::parse($pattern->end_date), $endDate) : $endDate;

        $generatedDates = [];

        while ($currentDate->lte($endLimit)) {
            if ($currentDate->gte($startDate) && $pattern->shouldGenerateForDate($currentDate)) {
                $this->createTrajetOccurrence($pattern->trajet, $currentDate);
                $generatedDates[] = $currentDate->toDateString();
            }

            $currentDate->addDay();
        }

        return $generatedDates;
    }

    protected function createTrajetOccurrence(Trajet $trajet, Carbon $date)
    {
        $firstSousTrajet = $trajet->sousTrajets()->first();
        
        if (!$firstSousTrajet) {
            throw new \Exception("Le trajet doit avoir au moins un sous-trajet");
        }
        $departureDateTime = $date->copy()->setTimeFrom(
            Carbon::parse($firstSousTrajet->departure_time)->toTimeString()
        );
        $exists = Trajet::where('parent_id', $trajet->id)
            ->whereHas('sousTrajets', function($query) use ($departureDateTime) {
                $query->whereDate('departure_time', $departureDateTime);
            })
            ->exists();

        if ($exists) {
            return null;
        }
        $newTrajet = $trajet->replicate();
        $newTrajet->parent_id = $trajet->id;
        $newTrajet->is_recurring = false;
        $newTrajet->save();
        foreach ($trajet->sousTrajets as $sousTrajet) {
            $newSousTrajet = $sousTrajet->replicate();
            $daysOffset = Carbon::parse($sousTrajet->arrival_time)
                ->diffInDays(Carbon::parse($sousTrajet->departure_time));
            $newDeparture = $date->copy()->setTimeFrom(
                Carbon::parse($sousTrajet->departure_time)->toTimeString()
            );
            $newArrival = $date->copy()->addDays($daysOffset)->setTimeFrom(
                Carbon::parse($sousTrajet->arrival_time)->toTimeString()
            );

            $newSousTrajet->departure_time = $newDeparture;
            $newSousTrajet->arrival_time = $newArrival;
            $newSousTrajet->trajet_id = $newTrajet->id;
            $newSousTrajet->save();
        }

        return $newTrajet;
    }
}