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
    public function index()
    {
        $buses = Bus::where('company_id', auth()->user()->company_id)->get();
        return view('employe.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('employe.buses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'plate_number' => 'required|string|unique:buses,plate_number'
        ]);

        $bus = Bus::create([
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'plate_number' => $validated['plate_number'],
            'company_id' => auth()->user()->company_id
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bus ajouté avec succès!',
                'bus' => $bus
            ]);
        }

        return redirect()->route('buses.index')
                        ->with('success', 'Bus ajouté avec succès!');
    }


    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string',
    //         'capacity' => 'required|integer',
    //         'plate_number' => 'required|unique:buses'
    //     ]);

    //     Bus::create($validated + [
    //         'company_id' => auth()->user()->company_id
    //     ]);

    //     return redirect()->route('buses.create')
    //                     ->with('success', 'Bus ajouté avec succès!');
    // }
}