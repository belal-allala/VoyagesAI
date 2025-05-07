<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
        'capacity',
        'plate_number'
    ];

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
}