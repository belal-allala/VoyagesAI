<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sous_trajet_id',
        'date_depart',
        'ville_depart',
        'date_arrivee',
        'ville_arrivee',
        'status',
        'note'
    ];

    protected $casts = [
        'date_depart' => 'datetime',
        'date_arrivee' => 'datetime'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sousTrajet()
    {
        return $this->belongsTo(SousTrajet::class);
    }

    public function billet()
    {
        return $this->hasOne(Billet::class);
    }
}