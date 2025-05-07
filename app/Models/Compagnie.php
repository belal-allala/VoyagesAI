<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compagnie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'telephone',   
        'adresse',      
        'description',  
        'logo'          
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function buses()
    {
        return $this->hasMany(Bus::class,'company_id');
    }
}