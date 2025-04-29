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
        $exists = Trajet::where('parent_id', $trajet->id)
            ->whereDate('departure_time', $date)
            ->exists();

        if ($exists) {
            return null;
        }

        $newTrajet = $trajet->replicate();
        $newTrajet->parent_id = $trajet->id;
        $newTrajet->is_recurring = false;
        
        $newTrajet->departure_time = $date->copy()->setTimeFrom(
            Carbon::parse($trajet->departure_time)
        );
        
        $newTrajet->arrival_time = $date->copy()->setTimeFrom(
            Carbon::parse($trajet->arrival_time)
        );

        $newTrajet->save();

        return $newTrajet;
    }
}