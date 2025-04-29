<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousTrajet extends Model
{
    use HasFactory;

    protected $table = 'sous_trajets';
    
    protected $fillable = [
        'trajet_id',
        'departure_city',
        'destination_city',
        'departure_time',
        'arrival_time',
        'price',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
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