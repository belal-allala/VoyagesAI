<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SousTrajetController extends Controller
{
    public function create(Trajet $trajet)
{
    return view('employe.sous-trajets.create', compact('trajet'));
}

public function store(Request $request, Trajet $trajet)
{
    $validated = $request->validate([
        'departure_city' => 'required|string',
        'departure_time' => 'required|date',
        'destination_city' => 'required|string',
        'arrival_time' => 'required|date|after:departure_time',
        'price' => 'required|numeric|min:0'
    ]);

    $trajet->sousTrajets()->create($validated);

    return redirect()->route('sous-trajets.create', $trajet)
                     ->with('success', 'Sous-trajet ajouté!');
}
}
