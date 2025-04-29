<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrajetController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        $trajets = Trajet::with(['bus', 'chauffeur', 'sousTrajets'])
                        ->whereHas('bus', fn($q) => $q->where('company_id', $companyId))
                        ->latest()
                        ->get();
        
        $buses = Bus::where('company_id', $companyId)->get();
        $chauffeurs = User::where('role', 'chauffeur')
                         ->where('company_id', $companyId)
                         ->get();
        
        return view('employe.trajets.index', compact('trajets', 'buses', 'chauffeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bus_id' => [
                'required',
                'exists:buses,id',
                function ($attr, $value, $fail) {
                    if (!Bus::where('id', $value)
                          ->where('company_id', auth()->user()->company_id)
                          ->exists()) {
                        $fail('Ce bus ne fait pas partie de votre compagnie');
                    }
                }
            ],
            'chauffeur_id' => [
                'required',
                'exists:users,id',
                function ($attr, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || $user->role !== 'chauffeur' || $user->company_id !== auth()->user()->company_id) {
                        $fail('Chauffeur invalide');
                    }
                }
            ],
            'sous_trajets' => 'required|array|min:1',
            'sous_trajets.*.departure_city' => 'required|string|max:100',
            'sous_trajets.*.destination_city' => 'required|string|max:100',
            'sous_trajets.*.departure_time' => 'required|date',
            'sous_trajets.*.arrival_time' => 'required|date|after:sous_trajets.*.departure_time',
            'sous_trajets.*.price' => 'required|numeric|min:0|max:10000'
        ]);

        DB::beginTransaction();
        try {
            $trajet = Trajet::create([
                'name' => $validated['name'],
                'bus_id' => $validated['bus_id'],
                'chauffeur_id' => $validated['chauffeur_id']
            ]);

            foreach ($validated['sous_trajets'] as $sousTrajet) {
                $trajet->sousTrajets()->create($sousTrajet);
            }

            DB::commit();
            
            return redirect()->route('trajets.index')
                             ->with('success', 'Trajet créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(Trajet $trajet)
    {
        if ($trajet->bus->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée');
        }

        $trajet->delete();
        
        return redirect()->route('trajets.index')
                         ->with('success', 'Trajet supprimé');
    }
}