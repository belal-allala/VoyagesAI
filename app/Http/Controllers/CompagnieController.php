<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagnie;

class CompagnieController extends Controller
{
    public function create()
    {
        return view('employe.compagnies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:compagnies'
        ]);

        $compagnie = Compagnie::create($validated);
        
        // Associer l'employé à la compagnie
        auth()->user()->update(['company_id' => $compagnie->id]);

        return redirect()->route('compagnies.create')
                        ->with('success', 'Compagnie créée avec succès!');
    }
}
