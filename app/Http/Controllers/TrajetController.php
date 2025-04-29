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
            'name' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'chauffeur_id' => 'required|exists:users,id',
            'sous_trajets' => 'required|array|min:1',
            'sous_trajets.*.departure_city' => 'required|string',
            'sous_trajets.*.destination_city' => 'required|string',
            'sous_trajets.*.departure_time' => 'required|date',
            'sous_trajets.*.arrival_time' => 'required|date|after:sous_trajets.*.departure_time',
            'sous_trajets.*.price' => 'required|numeric|min:0'
        ]);

        $trajet = Trajet::create([
            'name' => $validated['name'],
            'bus_id' => $validated['bus_id'],
            'chauffeur_id' => $validated['chauffeur_id']
        ]);

        foreach ($validated['sous_trajets'] as $sousTrajet) {
            $trajet->sousTrajets()->create($sousTrajet);
        }

        return redirect()->route('trajets.index')
                        ->with('success', 'Trajet créé avec succès!');
    }
}
