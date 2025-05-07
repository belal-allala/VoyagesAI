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
        $trajetsPonctuels = Trajet::with(['bus', 'chauffeur', 'sousTrajets'])
            ->whereHas('bus', fn($q) => $q->where('company_id', $companyId))
            ->where('is_recurring', false)
            ->whereHas('sousTrajets', function($q) use ($filterDate) {
                $q->whereDate('departure_time', $filterDate);
            });
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
            'sous_trajets' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $sousTrajets, $fail) {
                    if (!is_array($sousTrajets) || count($sousTrajets) < 2) {
                        return;
                    }
                    for ($i = 0; $i < count($sousTrajets) - 1; $i++) {
                        $currentStep = $sousTrajets[$i];
                        $nextStep = $sousTrajets[$i + 1];
                        if (
                            !isset($currentStep['destination_city']) ||
                            !isset($nextStep['departure_city']) ||
                            $currentStep['destination_city'] !== $nextStep['departure_city']
                        ) {
                            $fail("La ville d'arrivée de l'étape " . ($i + 1) . " ('" . ($currentStep['destination_city'] ?? 'N/A') . "') doit correspondre à la ville de départ de l'étape " . ($i + 2) . " ('" . ($nextStep['departure_city'] ?? 'N/A') . "').");
                            break;
                        }
                    }
                },
            ],
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
        try {
            $trajet = Trajet::create([
                'name' => $validated['name'],
                'bus_id' => $validated['bus_id'],
                'chauffeur_id' => $validated['chauffeur_id'],
                'is_recurring' => isset($validated['recurring_type']),
                'parent_id' => null, 
            ]);
            foreach ($validated['sous_trajets'] as $sousTrajet) {
                $departureTime = Carbon::createFromFormat('Y-m-d\TH:i', $sousTrajet['departure_time']);
                $arrivalTime = Carbon::createFromFormat('Y-m-d\TH:i', $sousTrajet['arrival_time']);

                $trajet->sousTrajets()->create([
                    'departure_city' => $sousTrajet['departure_city'],
                    'destination_city' => $sousTrajet['destination_city'],
                    'departure_time' => $departureTime, 
                    'arrival_time' => $arrivalTime, 
                    'price' => $sousTrajet['price']
                ]);
            }
            if (isset($validated['recurring_type'])) {
                 $startDate = Carbon::parse($validated['start_date']);
                $patternData = [
                    'type' => $validated['recurring_type'],
                    'interval' => $validated['recurring_interval'],
                    'days_of_week' => $validated['days_of_week'] ?? null,
                    'start_date' => $startDate,
                    'end_date' => isset($validated['end_date']) ? Carbon::parse($validated['end_date']) : null
                ];

                $pattern = $trajet->recurringPattern()->create($patternData);
                $recurringService->generateTrajetsForPattern($pattern, 60, $pattern->start_date);
            }

            DB::commit();

            return redirect()->route('trajets.index')
                            ->with('success', 'Trajet créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Erreur lors de la création du trajet: ' . $e->getMessage());
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
            'sous_trajets' => [
                'required',
                'array',
                'min:1',
                 function ($attribute, $sousTrajets, $fail) {
                     if (!is_array($sousTrajets) || count($sousTrajets) < 2) {
                         return;
                     }
                     for ($i = 0; $i < count($sousTrajets) - 1; $i++) {
                         $currentStep = $sousTrajets[$i];
                         $nextStep = $sousTrajets[$i + 1];

                         if (
                             !isset($currentStep['destination_city']) ||
                             !isset($nextStep['departure_city']) ||
                             $currentStep['destination_city'] !== $nextStep['departure_city']
                         ) {
                             $fail("La ville d'arrivée de l'étape " . ($i + 1) . " ('" . ($currentStep['destination_city'] ?? 'N/A') . "') ne correspond pas à la ville de départ de l'étape " . ($i + 2) . " ('" . ($nextStep['departure_city'] ?? 'N/A') . "').");
                             break;
                         }
                     }
                 },
            ],
            'sous_trajets.*.id' => 'sometimes|exists:sous_trajets,id', 
            'sous_trajets.*.departure_city' => 'required|string|max:100',
            'sous_trajets.*.destination_city' => 'required|string|max:100',
            'sous_trajets.*.departure_time' => 'required|date_format:Y-m-d\TH:i', 
            'sous_trajets.*.arrival_time' => 'required|date_format:Y-m-d\TH:i|after:sous_trajets.*.departure_time',
            'sous_trajets.*.price' => 'required|numeric|min:0|max:10000',
        ]);

        DB::beginTransaction();
        try {

            $trajet->update([
                'name' => $validated['name'],
                'bus_id' => $validated['bus_id'],
                'chauffeur_id' => $validated['chauffeur_id'],
            ]);
            $existingSousTrajetIds = collect($validated['sous_trajets'])->pluck('id')->filter()->toArray();
            $trajet->sousTrajets()->whereNotIn('id', $existingSousTrajetIds)->delete();
            foreach ($validated['sous_trajets'] as $sousTrajetData) {
                 $departureTime = Carbon::createFromFormat('Y-m-d\TH:i', $sousTrajetData['departure_time']);
                 $arrivalTime = Carbon::createFromFormat('Y-m-d\TH:i', $sousTrajetData['arrival_time']);

                 $dataToSave = [
                    'departure_city' => $sousTrajetData['departure_city'],
                    'destination_city' => $sousTrajetData['destination_city'],
                    'departure_time' => $departureTime,
                    'arrival_time' => $arrivalTime,
                    'price' => $sousTrajetData['price']
                 ];

                if (isset($sousTrajetData['id'])) {
                    $trajet->sousTrajets()->where('id', $sousTrajetData['id'])->update($dataToSave);
                } else {
                    $trajet->sousTrajets()->create($dataToSave);
                }
            }
            DB::commit();

            return redirect()->route('trajets.index')
                            ->with('success', 'Trajet mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                        ->with('error', 'Erreur lors de la mise à jour du trajet: ' . $e->getMessage());
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

    public function getStatusClass($status)
    {
        switch ($status) {
            case 'À venir':
                return 'bg-blue-100 text-blue-800';
            case 'En cours':
                return 'bg-yellow-100 text-yellow-800';
            case 'Passé':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}