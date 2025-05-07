<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; 
use App\Models\Compagnie;
use App\Models\Trajet;
use App\Models\Reservation;
use App\Models\Notification;
use App\Models\Bus;
use App\Models\SousTrajet;
use App\Models\Profile;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'email',
        'password',
        'role',
        'company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class, 'company_id');
    }

    public function trajetsAsChauffeur()
    {
        return $this->hasMany(Trajet::class, 'chauffeur_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}