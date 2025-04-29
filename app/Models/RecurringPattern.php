<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class RecurringPattern extends Model
{
    use HasFactory;

    protected $fillable = [
        'trajet_id',
        'type',
        'interval',
        'days_of_week',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }

    public function getRecurrenceDescriptionAttribute()
    {
        switch ($this->type) {
            case 'daily':
                return "Tous les {$this->interval} jours";
            case 'weekly':
                $days = collect($this->days_of_week)
                    ->map(fn($day) => $this->getDayName($day))
                    ->join(', ');
                return "Toutes les {$this->interval} semaines le(s) {$days}";
            case 'monthly':
                return "Tous les {$this->interval} mois";
            case 'custom':
                return "Personnalisé (tous les {$this->interval} jours)";
            default:
                return "Modèle de récurrence";
        }
    }

    protected function getDayName($dayNumber)
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        return $days[$dayNumber - 1] ?? '';
    }

    public function shouldGenerateForDate(Carbon $date)
    {
        if ($date->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $date->gt($this->end_date)) {
            return false;
        }

        $daysDiff = $this->start_date->diffInDays($date);

        if ($this->type === 'daily') {
            return $daysDiff % $this->interval === 0;
        }

        if ($this->type === 'weekly') {
            return in_array($date->dayOfWeekIso, $this->days_of_week) && 
                   ($this->interval === 1 || $this->start_date->diffInWeeks($date) % $this->interval === 0);
        }

        if ($this->type === 'monthly') {
            return $this->start_date->day === $date->day && 
                   ($this->interval === 1 || $this->start_date->diffInMonths($date) % $this->interval === 0);
        }
        return $daysDiff % $this->interval === 0;
    }

}