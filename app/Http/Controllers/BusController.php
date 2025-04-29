<?php

namespace App\Http\Controllers;

use App\Models\Bus; 
use App\Services\BusSearchService; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\User;


class BusController extends Controller
{
    public function create()
    {
        $compagnie = auth()->user()->compagnie;
        return view('employe.buses.create', compact('compagnie'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'capacity' => 'required|integer',
            'plate_number' => 'required|unique:buses'
        ]);

        Bus::create($validated + [
            'company_id' => auth()->user()->company_id
        ]);

        return redirect()->route('buses.create')
                        ->with('success', 'Bus ajouté avec succès!');
    }
}