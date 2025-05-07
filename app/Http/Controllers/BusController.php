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

        // if ($request->ajax()) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Bus ajouté avec succès!',
        //         'bus' => $bus
        //     ]);
        // }

        return redirect()->route('buses.index')
                        ->with('success', 'Bus ajouté avec succès!');
    }


    public function edit(Bus $bus)
    {
        if ($bus->company_id !== auth()->user()->company_id) {
            abort(403, 'Accès non autorisé');
        }
        return view('employe.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        if ($bus->company_id !== auth()->user()->company_id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'plate_number' => 'required|string|unique:buses,plate_number,'.$bus->id
        ]);

        $bus->update($validated);

        return redirect()->route('buses.index')
                         ->with('success', 'Bus mis à jour avec succès!');
    }

    public function destroy(Bus $bus)
    {
        if ($bus->company_id !== auth()->user()->company_id) {
            abort(403, 'Accès non autorisé');
        }

        if ($bus->trajets()->exists()) {
            return redirect()->back()
                             ->with('error', 'Ce bus est utilisé dans des trajets et ne peut être supprimé');
        }

        $bus->delete();

        return redirect()->route('buses.index')
                         ->with('success', 'Bus supprimé avec succès!');
    }
}