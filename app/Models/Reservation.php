<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importez BelongsTo

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bus_id',
        'user_id', 
        'passenger_name',
        'passenger_email',
        'seat_count',
        'total_price',
        'reservation_date',
        'status',
    ];

    /**
     * Get the bus that this reservation is for.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the user that made this reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); 
    }
}