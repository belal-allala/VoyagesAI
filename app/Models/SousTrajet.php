<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousTrajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'trajet_id',
        'departure_city',
        'departure_time',
        'destination_city',
        'arrival_time',
        'price'
    ];

    // Relations
    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}