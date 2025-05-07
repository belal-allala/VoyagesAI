<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Billet;
use App\Models\Trajet;
use App\Models\User;
use App\Models\Bus;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trajet_id', 
        'date_depart',
        'ville_depart',
        'date_arrivee',
        'ville_arrivee',
        'nombre_passagers',
        'prix_total',
        'status',
        'note'
    ];

    protected $casts = [
        'date_depart' => 'datetime',
        'date_arrivee' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trajet() 
    {
        return $this->belongsTo(Trajet::class);
    }

    public function billet()
    {
        return $this->hasOne(Billet::class);
    }
}