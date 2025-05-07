<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagnie;

class CompagnieController extends Controller
{
    public function create()
    {
        return view('employe.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:compagnies', 
            'telephone' => 'nullable|string|max:50',       
            'adresse' => 'nullable|string|max:255',        
            'description' => 'nullable|string',           
            'logo' => 'nullable|image|max:2048',      
        ]);

       
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('compagnie_logos', 'public');
            $validated['logo'] = $path; 
        } else {
            $validated['logo'] = null; 
        }

        $compagnie = Compagnie::create($validated);

        auth()->user()->update(['company_id' => $compagnie->id]);

        return redirect()->route('employe.dashboard')
                        ->with('success', 'Compagnie créée avec succès et affiliée à votre compte!');
    }

}
