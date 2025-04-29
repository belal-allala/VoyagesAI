<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagnie;
use App\Models\Bus;
use App\Models\Trajet;
use App\Models\User;

class EmployeController extends Controller
{
    public function dashboard()
    {
        $compagnie = auth()->user()->compagnie;
        $buses = 0;
        $trajets = 0;
        $chauffeurs = 0;
        
        if ($compagnie) {
            $buses = Bus::where('company_id', $compagnie->id)->count();
            $trajets = Trajet::whereHas('bus', function($q) use ($compagnie) {
                $q->where('company_id', $compagnie->id);
            })->count();
            $chauffeurs = User::where('company_id', $compagnie->id)
                              ->where('role', 'chauffeur')
                              ->count();
        }
        
        return view('employe.dashboard', compact('compagnie', 'buses', 'trajets', 'chauffeurs'));
    }
}