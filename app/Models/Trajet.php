<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bus_id',
        'chauffeur_id'
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
}