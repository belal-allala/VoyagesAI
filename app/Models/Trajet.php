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
}