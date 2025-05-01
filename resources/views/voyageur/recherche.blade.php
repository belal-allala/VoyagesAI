@extends('layouts.app')

@section('title', 'Rechercher un trajet')

@section('content')
<div class="container mx-auto py-8">
    <!-- Formulaire de recherche -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h1 class="text-2xl font-bold mb-6">Rechercher un trajet</h1>

        <form method="GET" action="{{ route('trajets.recherche') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="ville_depart" class="block text-gray-700 text-sm font-bold mb-2">Ville de départ</label>
                    <input type="text" id="ville_depart" name="ville_depart" value="{{ request('ville_depart') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           placeholder="Entrez la ville de départ" required>
                </div>

                <div>
                    <label for="ville_arrivee" class="block text-gray-700 text-sm font-bold mb-2">Ville d'arrivée</label>
                    <input type="text" id="ville_arrivee" name="ville_arrivee" value="{{ request('ville_arrivee') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           placeholder="Entrez la ville d'arrivée" required>
                </div>

                <div>
                    <label for="date_depart" class="block text-gray-700 text-sm font-bold mb-2">Date de départ</label>
                    <input type="date" id="date_depart" name="date_depart" value="{{ request('date_depart') }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           required>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <!-- Résultats de recherche -->
    @if(request()->has('ville_depart'))
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-xl font-bold mb-6">Résultats de la recherche</h2>

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
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Aucun trajet trouvé!</strong>
                <span class="block sm:inline">Aucun trajet ne correspond à vos critères de recherche.</span>
            </div>
        @else
            @foreach($trajetsCorrespondants as $trajet)
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                    <div class="px-4 py-3">
                        <div class="text-lg font-semibold mb-2">{{ $trajet->name }}</div>
                        <p class="text-gray-700">Bus: {{ $trajet->bus->name }} ({{ $trajet->bus->plate_number }})</p>
                        <p class="text-gray-700">Chauffeur: {{ $trajet->chauffeur->nom }}</p>

                        <h3 class="text-md font-semibold mt-4 mb-2">Étapes:</h3>
                        <ul class="list-disc list-inside">
                            @foreach($trajet->sousTrajetsPertinents as $sousTrajet)
                                <li>
                                    {{ $sousTrajet->departure_city }} ({{ $sousTrajet->departure_time->format('H:i') }}) →
                                    {{ $sousTrajet->destination_city }} ({{ $sousTrajet->arrival_time->format('H:i') }}) -
                                    {{ $sousTrajet->price }} MAD
                                </li>
                            @endforeach
                        </ul>
                        
                        @if(env('APP_DEBUG') && $trajet->sousTrajets->count() > $trajet->sousTrajetsPertinents->count())
                        <details class="mt-2 text-sm">
                            <summary class="text-gray-500 cursor-pointer">Toutes les étapes (debug)</summary>
                            <ul class="list-disc list-inside">
                                @foreach($trajet->sousTrajets as $sousTrajet)
                                    <li class="{{ $trajet->sousTrajetsPertinents->contains('id', $sousTrajet->id) ? 'font-bold' : 'text-gray-400' }}">
                                        {{ $sousTrajet->departure_city }} → {{ $sousTrajet->destination_city }}
                                        ({{ $sousTrajet->departure_time->format('H:i') }})
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                        @endif
                    </div>
                    <div class="bg-gray-50 px-4 py-2 flex justify-between items-center">
                        <span class="text-lg font-bold">
                            Prix: {{ number_format($trajet->prix_partiel, 2) }} MAD
                        </span>
                        <form method="POST" action="{{ route('reservations.createTraj') }}">
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
                            
                            <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Réserver ce trajet
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @endif
</div>
@endsection