<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecurringPattern;
use App\Models\SousTrajet;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Bus;
use Carbon\Carbon;
use App\Models\Billet;
use App\Models\Compagnie;
use App\Models\Trajet as ParentTrajet;


class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bus_id',
        'chauffeur_id',
        'is_recurring',
        'parent_id',
    ];

    protected $appends = ['status'];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    public function sousTrajets()
    {
        return $this->hasMany(SousTrajet::class);
    }

    public function recurringPattern()
    {
        return $this->hasOne(RecurringPattern::class);
    }

    public function parent()
    {
        return $this->belongsTo(Trajet::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Trajet::class, 'parent_id');
    }

    public function firstDepartureTime()
    {
        return $this->sousTrajets()->orderBy('departure_time')->first()->departure_time;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getStatusAttribute()
    {
        $now = Carbon::now();
        $firstDepartureTime = $this->firstDepartureTime();

        if (!$firstDepartureTime) {
            return 'Inconnu'; 
        }

        $lastArrivalTime = $this->sousTrajets()->orderBy('arrival_time', 'desc')->first()->arrival_time;

        if ($now->lt($firstDepartureTime)) {
            return 'À venir';
        } elseif ($now->gt($lastArrivalTime)) {
            return 'Passé';
        } else {
            return 'En cours';
        }
    }

    public function getStatusClass()
    {
        return match($this->status) {
            'À venir' => 'bg-blue-100 text-blue-800',
            'En cours' => 'bg-yellow-100 text-yellow-800',
            'Passé' => 'bg-green-100 text-green-800',
        };
    }
}