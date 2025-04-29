<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagnie;
use App\Models\Bus;
use App\Models\Trajet;

class EmployeController extends Controller
{
    public function dashboard()
    {
        $compagnie = auth()->user()->compagnie;
        $buses = 0;
        $trajets = 0;
        
        if ($compagnie) {
            $buses = Bus::where('company_id', $compagnie->id)->count();
            $trajets = Trajet::whereHas('bus', function($q) use ($compagnie) {
                $q->where('company_id', $compagnie->id);
            })->count();
        }
        
        return view('employe.dashboard', compact('compagnie', 'buses', 'trajets'));
    }
}