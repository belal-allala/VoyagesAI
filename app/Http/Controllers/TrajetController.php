<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SousTrajet;
use App\Http\Controllers\SousTrajetController;
use App\Services\RecurringTrajetService;
use Carbon\Carbon;
use App\Models\RecurringPattern;


class TrajetController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $filterDate = $request->input('date', now()->format('Y-m-d'));
        
        // Trajets ponctuels pour la date sélectionnée
        $trajetsPonctuels = Trajet::with(['bus', 'chauffeur', 'sousTrajets'])
            ->whereHas('bus', fn($q) => $q->where('company_id', $companyId))
            ->where('is_recurring', false)
            ->whereHas('sousTrajets', function($q) use ($filterDate) {
                $q->whereDate('departure_time', $filterDate);
            });
        
        // Trajets récurrents qui correspondent à la date
        $trajetsRecurrents = Trajet::with(['bus', 'chauffeur', 'sousTrajets', 'recurringPattern'])
            ->whereHas('bus', fn($q) => $q->where('company_id', $companyId))
            ->where('is_recurring', true)
            ->whereHas('recurringPattern', function($q) use ($filterDate) {
                $q->where('start_date', '<=', $filterDate)
                ->where(function($query) use ($filterDate) {
                    $query->whereNull('end_date')
                            ->orWhere('end_date', '>=', $filterDate);
                });
            })
            ->get()
            ->filter(function($trajet) use ($filterDate) {
                return $trajet->recurringPattern->shouldGenerateForDate(Carbon::parse($filterDate));
            });
        
        // Fusionner les résultats
        $trajets = $trajetsPonctuels->get()->merge($trajetsRecurrents);
        
        $buses = Bus::where('company_id', $companyId)->get();
        $chauffeurs = User::where('role', 'chauffeur')
                        ->where('company_id', $companyId)
                        ->get();
        
        return view('employe.trajets.index', compact('trajets', 'buses', 'chauffeurs', 'filterDate'));
    }

    public function store(Request $request, RecurringTrajetService $recurringService)
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
            'sous_trajets.*.departure_time' => 'required|date_format:Y-m-d\TH:i',
            'sous_trajets.*.arrival_time' => 'required|date_format:Y-m-d\TH:i|after:sous_trajets.*.departure_time',
            'sous_trajets.*.price' => 'required|numeric|min:0|max:10000',
            'recurring_type' => 'nullable|in:daily,weekly,monthly,custom',
            'recurring_interval' => 'nullable|integer|min:1|required_if:recurring_type,daily,weekly,monthly,custom',
            'days_of_week' => 'nullable|array|required_if:recurring_type,weekly',
            'days_of_week.*' => 'integer|min:1|max:7',
            'start_date' => 'nullable|date|required_if:recurring_type,daily,weekly,monthly,custom',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        DB::beginTransaction();
        // try {
            $trajet = Trajet::create([
                'name' => $validated['name'],
                'bus_id' => $validated['bus_id'],
                'chauffeur_id' => $validated['chauffeur_id'],
                'is_recurring' => isset($validated['recurring_type'])
            ]);

            foreach ($validated['sous_trajets'] as $sousTrajet) {
                // Assurez-vous que les dates sont correctement formatées
                $departureTime = Carbon::createFromFormat('Y-m-d\TH:i', $sousTrajet['departure_time']);
                $arrivalTime = Carbon::createFromFormat('Y-m-d\TH:i', $sousTrajet['arrival_time']);
                
                // Création explicite avec tous les champs
                $trajet->sousTrajets()->create([
                    'departure_city' => $sousTrajet['departure_city'],
                    'destination_city' => $sousTrajet['destination_city'],
                    'departure_time' => $departureTime,
                    'arrival_time' => $arrivalTime,
                    'price' => $sousTrajet['price']
                ]);
            }

            // Gestion de la récurrence si activée
            if (isset($validated['recurring_type'])) {
                $patternData = [
                    'type' => $validated['recurring_type'],
                    'interval' => $validated['recurring_interval'],
                    'days_of_week' => $validated['days_of_week'] ?? null,
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'] ?? null
                ];

            $pattern = $trajet->recurringPattern()->create($patternData);
                
                // Génération des occurrences pour les 60 prochains jours
                $recurringService->generateTrajetsForPattern($pattern, 60);
            }

            DB::commit();
            
            return redirect()->route('trajets.index')
                            ->with('success', 'Trajet créé avec succès');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->withInput()
        //                 ->with('error', 'Erreur: ' . $e->getMessage());
        // }
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

    public function edit(Trajet $trajet)
    {
        if ($trajet->bus->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée');
        }

        $buses = Bus::where('company_id', auth()->user()->company_id)->get();
        $chauffeurs = User::where('role', 'chauffeur')
                        ->where('company_id', auth()->user()->company_id)
                        ->get();

        return view('employe.trajets.edit', compact('trajet', 'buses', 'chauffeurs'));
    }

    public function update(Request $request, Trajet $trajet)
    {
        if ($trajet->bus->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'chauffeur_id' => 'required|exists:users,id',
            'sous_trajets' => 'required|array|min:1',
            'sous_trajets.*.id' => 'sometimes|exists:sous_trajets,id', // Pour les étapes existantes
            'sous_trajets.*.departure_city' => 'required|string',
            'sous_trajets.*.destination_city' => 'required|string',
            'sous_trajets.*.departure_time' => 'required|date',
            'sous_trajets.*.arrival_time' => 'required|date|after:sous_trajets.*.departure_time',
            'sous_trajets.*.price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $trajet->update([
                'name' => $validated['name'],
                'bus_id' => $validated['bus_id'],
                'chauffeur_id' => $validated['chauffeur_id']
            ]);

            foreach ($validated['sous_trajets'] as $sousTrajetData) {
                if (isset($sousTrajetData['id'])) {
                    $sousTrajet = SousTrajet::find($sousTrajetData['id']);
                    $sousTrajet->update($sousTrajetData);
                } else {
                    $trajet->sousTrajets()->create($sousTrajetData);
                }
            }

            DB::commit();
            
            return redirect()->route('trajets.index')
                            ->with('success', 'Trajet mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    public function recurringPattern()
    {
        return $this->hasOne(RecurringPattern::class);
    }

    public function isRecurring()
    {
        return $this->recurringPattern !== null;
    }

    public function details(Trajet $trajet)
    {
        // Vérification d'accès
        if ($trajet->bus->company_id !== auth()->user()->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $trajet->load(['bus', 'chauffeur', 'sousTrajets', 'recurringPattern']);

        return response()->json([
            'name' => $trajet->name,
            'bus' => [
                'name' => $trajet->bus->name,
                'plate_number' => $trajet->bus->plate_number,
            ],
            'chauffeur' => [
                'nom' => $trajet->chauffeur->nom,
            ],
            'is_recurring' => $trajet->is_recurring,
            'recurring_pattern' => $trajet->is_recurring ? [
                'recurrence_description' => $trajet->recurringPattern->recurrence_description
            ] : null,
            'sous_trajets' => $trajet->sousTrajets->map(function($sousTrajet) {
                return [
                    'departure_city' => $sousTrajet->departure_city,
                    'destination_city' => $sousTrajet->destination_city,
                    'departure_time' => $sousTrajet->departure_time->format('d/m/Y H:i'),
                    'arrival_time' => $sousTrajet->arrival_time->format('d/m/Y H:i'),
                    'price' => $sousTrajet->price,
                ];
            })
        ]);
    }
}