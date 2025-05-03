@extends('layouts.app')

@section('title', 'Rechercher un trajet')

@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- En-tête avec villes -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-sm text-gray-500 mb-1">Ville de départ</h2>
            <h1 class="text-2xl font-bold text-gray-800">{{ request('ville_depart') ?: 'Sélectionnez' }}</h1>
        </div>
        <div class="flex items-center">
            <div class="w-16 h-0 border-t-2 border-dashed border-gray-300 mx-2"></div>
            <div class="bg-gray-200 rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div class="w-16 h-0 border-t-2 border-dashed border-gray-300 mx-2"></div>
        </div>
        <div class="text-right">
            <h2 class="text-sm text-gray-500 mb-1">Ville d'arrivée</h2>
            <h1 class="text-2xl font-bold text-gray-800">{{ request('ville_arrivee') ?: 'Sélectionnez' }}</h1>
        </div>
    </div>

    <!-- Sélecteur de dates -->
    @if(request()->has('ville_depart'))
    <div class="flex justify-center mb-8 overflow-x-auto">
        <div class="flex space-x-1">
            @php
                $date = request('date_depart') ? \Carbon\Carbon::parse(request('date_depart')) : \Carbon\Carbon::today();
                $startDate = $date->copy()->subDays(2);
            @endphp
            
            @for($i = 0; $i < 5; $i++)
                @php
                    $currentDate = $startDate->copy()->addDays($i);
                    $isActive = $currentDate->format('Y-m-d') === $date->format('Y-m-d');
                @endphp
                <a href="{{ route('trajets.recherche', [
                    'ville_depart' => request('ville_depart'),
                    'ville_arrivee' => request('ville_arrivee'),
                    'date_depart' => $currentDate->format('Y-m-d')
                ]) }}" 
                   class="flex flex-col items-center justify-center px-4 py-2 rounded-lg {{ $isActive ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <span class="text-xs {{ $isActive ? 'text-white' : 'text-gray-500' }}">{{ $currentDate->locale('fr')->format('M j') }}</span>
                    <span class="font-medium">{{ $currentDate->locale('fr')->format('D') }}</span>
                </a>
            @endfor
        </div>
    </div>
    @endif

    <!-- Formulaire de recherche -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('trajets.recherche') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="ville_depart" class="block text-gray-700 text-sm font-medium mb-2">Ville de départ</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="ville_depart" name="ville_depart" value="{{ request('ville_depart') }}"
                               class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                               placeholder="Entrez la ville de départ" required>
                    </div>
                </div>

                <div>
                    <label for="ville_arrivee" class="block text-gray-700 text-sm font-medium mb-2">Ville d'arrivée</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="ville_arrivee" name="ville_arrivee" value="{{ request('ville_arrivee') }}"
                               class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                               placeholder="Entrez la ville d'arrivée" required>
                    </div>
                </div>

                <div>
                    <label for="date_depart" class="block text-gray-700 text-sm font-medium mb-2">Date de départ</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="date" id="date_depart" name="date_depart" value="{{ request('date_depart') }}"
                               class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50"
                               required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Résultats de recherche -->
    @if(request()->has('ville_depart'))
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Résultats de la recherche</h2>
            
            @if(!$trajetsCorrespondants->isEmpty())
            <div class="text-sm text-gray-500">
                {{ $trajetsCorrespondants->count() }} trajet(s) trouvé(s)
            </div>
            @endif
        </div>

        @if(env('APP_DEBUG'))
        <div class="bg-gray-100 p-4 mb-6 rounded-lg">
            <details>
                <summary class="font-bold cursor-pointer">Debug: Format des données ({{ $trajetsCorrespondants->count() ?? 0 }} résultats)</summary>
                <pre class="bg-white p-2 mt-2 text-xs overflow-x-auto">{{ json_encode($trajetsCorrespondants->map(function($trajet) {
                    return [
                        'trajet_id' => $trajet->id,
                        'trajet_name' => $trajet->name,
                        'bus' => $trajet->bus->only(['name', 'plate_number']),
                        'chauffeur' => $trajet->chauffeur->nom,
                        'prix_partiel' => $trajet->prix_partiel,
                        'sous_trajets_pertinents' => $trajet->sousTrajetsPertinents->map(function($st) {
                            return $st->only(['departure_city', 'destination_city', 'departure_time', 'arrival_time', 'price']);
                        }),
                        'sous_trajets_complets' => $trajet->sousTrajets->count()
                    ];
                }), JSON_PRETTY_PRINT) }}</pre>
            </details>
        </div>
        @endif

        @if($trajetsCorrespondants->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun trajet trouvé</h3>
                <p class="text-gray-500">Aucun trajet ne correspond à vos critères de recherche.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($trajetsCorrespondants as $index => $trajet)
                    @php
                        $firstSousTrajet = $trajet->sousTrajetsPertinents->first();
                        $lastSousTrajet = $trajet->sousTrajetsPertinents->last();
                        $duration = $firstSousTrajet->departure_time->diffInMinutes($lastSousTrajet->arrival_time);
                        $hours = floor($duration / 60);
                        $minutes = $duration % 60;
                        $durationText = $hours . 'h' . ($minutes > 0 ? $minutes : '');
                        
                        // Déterminer si c'est le moins cher, le plus rapide, etc.
                        $tag = null;
                        $tagClass = '';
                        
                        if ($index === 0) {
                            $tag = 'RECOMMANDÉ';
                            $tagClass = 'bg-green-100 text-green-800';
                        } elseif ($trajet->prix_partiel === $trajetsCorrespondants->min('prix_partiel')) {
                            $tag = 'ÉCONOMIQUE';
                            $tagClass = 'bg-blue-100 text-blue-800';
                        } elseif ($duration === $trajetsCorrespondants->min(function($t) {
                            $first = $t->sousTrajetsPertinents->first();
                            $last = $t->sousTrajetsPertinents->last();
                            return $first->departure_time->diffInMinutes($last->arrival_time);
                        })) {
                            $tag = 'PLUS RAPIDE';
                            $tagClass = 'bg-purple-100 text-purple-800';
                        }
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                        <div class="p-4">
                            <!-- Compagnie et tag -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $trajet->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $trajet->bus->name }} ({{ $trajet->bus->plate_number }})</p>
                                        </div>
                                    </div>
                                </div>
                                @if($tag)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tagClass }}">
                                    {{ $tag }}
                                </span>
                                @endif
                            </div>
                            
                            <!-- Horaires et durée -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="text-left">
                                    <div class="text-xl font-bold">{{ $firstSousTrajet->departure_time->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">{{ $firstSousTrajet->departure_city }}</div>
                                </div>
                                
                                <div class="flex-1 px-4 flex flex-col items-center">
                                    <div class="text-xs text-gray-500 mb-1">{{ $durationText }}</div>
                                    <div class="w-full flex items-center">
                                        <div class="h-0.5 flex-1 bg-gray-300"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                        </svg>
                                        <div class="h-0.5 flex-1 bg-gray-300"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $trajet->sousTrajetsPertinents->count() > 1 ? $trajet->sousTrajetsPertinents->count() - 1 . ' arrêt(s)' : 'Direct' }}</div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-xl font-bold">{{ $lastSousTrajet->arrival_time->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">{{ $lastSousTrajet->destination_city }}</div>
                                </div>
                            </div>
                            
                            <!-- Informations supplémentaires -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $trajet->chauffeur->nom }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>{{ rand(5, 30) }} places disponibles</span>
                                </div>
                            </div>
                            
                            <!-- Étapes du trajet (collapsible) -->
                            <details class="mt-2">
                                <summary class="text-sm text-yellow-600 cursor-pointer font-medium flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    Voir les étapes du trajet
                                </summary>
                                <div class="mt-3 pl-4 border-l-2 border-gray-200">
                                    @foreach($trajet->sousTrajetsPertinents as $sousTrajet)
                                        <div class="flex items-start mb-2">
                                            <div class="w-2 h-2 rounded-full bg-gray-400 mt-1.5 mr-2"></div>
                                            <div class="flex-1">
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium">{{ $sousTrajet->departure_city }} → {{ $sousTrajet->destination_city }}</span>
                                                    <span class="text-sm text-gray-500">{{ $sousTrajet->price }} MAD</span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $sousTrajet->departure_time->format('H:i') }} - {{ $sousTrajet->arrival_time->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                        
                        <!-- Prix et bouton de réservation -->
                        <div class="flex justify-between items-center p-4 bg-gray-50 border-t border-gray-100">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($trajet->prix_partiel, 0) }} MAD</span>
                                <span class="text-xs text-gray-500 block">par personne</span>
                            </div>
                            
                            <form method="POST" action="{{ route('reservations.createTrajet') }}">
                                @csrf
                                <input type="hidden" name="trajet_id" value="{{ $trajet->id }}">
                                <input type="hidden" name="trajet_name" value="{{ $trajet->name }}">
                                <input type="hidden" name="bus_name" value="{{ $trajet->bus->name }}">
                                <input type="hidden" name="bus_plate" value="{{ $trajet->bus->plate_number }}">
                                <input type="hidden" name="chauffeur" value="{{ $trajet->chauffeur->nom }}">
                                <input type="hidden" name="prix_partiel" value="{{ $trajet->prix_partiel }}">
                                
                                @foreach($trajet->sousTrajetsPertinents as $index => $sousTrajet)
                                    <input type="hidden" name="sous_trajets[{{ $index }}][departure_city]" value="{{ $sousTrajet->departure_city }}">
                                    <input type="hidden" name="sous_trajets[{{ $index }}][destination_city]" value="{{ $sousTrajet->destination_city }}">
                                    <input type="hidden" name="sous_trajets[{{ $index }}][departure_time]" value="{{ $sousTrajet->departure_time }}">
                                    <input type="hidden" name="sous_trajets[{{ $index }}][arrival_time]" value="{{ $sousTrajet->arrival_time }}">
                                    <input type="hidden" name="sous_trajets[{{ $index }}][price]" value="{{ $sousTrajet->price }}">
                                @endforeach
                                
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                                    Réserver
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif
</div>
@endsection
