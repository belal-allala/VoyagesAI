<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagnie;
use App\Models\User;
use App\Models\Bus;
use App\Models\Trajet;


class TrajetController extends Controller
{
    public function create()
    {
        $buses = Bus::where('company_id', auth()->user()->company_id)->get();
        $chauffeurs = User::where('role', 'chauffeur')
                         ->where('company_id', auth()->user()->company_id)
                         ->get();
        
        return view('employe.trajets.create', compact('buses', 'chauffeurs'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'bus_id' => 'required|exists:buses,id',
            'chauffeur_id' => 'required|exists:users,id'
        ]);
    
        Trajet::create($validated);
    
        return redirect()->route('trajets.create')
                         ->with('success', 'Trajet créé! Ajoutez maintenant des sous-trajets.');
    }
}
