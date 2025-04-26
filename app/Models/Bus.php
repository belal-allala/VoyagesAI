<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',  //Ajouter l'attribut name
        'capacity',
        'available_seats',
        'company_id', // <-- et ajoute la clefs secondaire comme fillables
    ];

    /**
     * Get the company that owns the bus.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


     /**
     * Get the trajets for the bus.
     */
    public function trajets(): HasMany
    {
        return $this->hasMany(Trajet::class);
    }
}