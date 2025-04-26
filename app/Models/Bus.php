<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importez la relation BelongsTo

class Bus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'capacity',
        'available_seats',
        'company_name',  
        'company_id', 
    ];

    /**
     * Get the company that owns the bus.
     */
    public function company(): BelongsTo // Définir la relation BelongsTo avec Company
    {
        return $this->belongsTo(Company::class);
    }
}