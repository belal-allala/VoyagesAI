<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SousTrajet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'trajet_id',
        'departure_city',
        'departure_time',
        'destination_city',
        'arrival_time',
        'price',
    ];

    /**
     * Get the trajet that this sous-trajet belongs to.
     */
    public function trajet(): BelongsTo
    {
        return $this->belongsTo(Trajet::class);
    }
}