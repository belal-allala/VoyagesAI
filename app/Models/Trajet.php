<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecurringPattern;

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

    // Relations
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

// Relation avec les trajets enfants
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
}