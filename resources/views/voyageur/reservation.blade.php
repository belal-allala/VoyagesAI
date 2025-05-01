@extends('layouts.app')

@section('title', 'Confirmer la réservation')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md p-8 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Confirmer la réservation</h1>

        <div class="border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Détails du trajet</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">{{ $trajetData['trajet_name'] }}</h3>
                    <p class="text-gray-700"><strong>Bus:</strong> {{ $trajetData['bus']['name'] }} ({{ $trajetData['bus']['plate_number'] }})</p>
                    <p class="text-gray-700"><strong>Chauffeur:</strong> {{ $trajetData['chauffeur'] }}</p>
                </div>
                
                <div>
                    @if(!empty($trajetData['sous_trajets_pertinents']))
                        <p class="text-gray-700"><strong>Date de départ:</strong> 
                            {{ \Carbon\Carbon::parse($trajetData['sous_trajets_pertinents'][0]['departure_time'])->format('d/m/Y H:i') }}</p>
                        <p class="text-gray-700"><strong>Date d'arrivée:</strong> 
                            {{ \Carbon\Carbon::parse($trajetData['sous_trajets_pertinents'][count($trajetData['sous_trajets_pertinents']) - 1]['arrival_time'])->format('d/m/Y H:i') }}</p>
                    @endif
                    <p class="text-gray-700 font-bold"><strong>Prix total:</strong> 
                        {{ number_format($trajetData['prix_partiel'], 2) }} MAD</p>
                </div>
            </div>

            <h3 class="text-md font-semibold mt-6 mb-3">Itinéraire complet:</h3>
            <ul class="space-y-2">
                @foreach($trajetData['sous_trajets_pertinents'] as $sousTrajet)
                <li class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 text-purple-500 mr-2 mt-0.5">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">{{ $sousTrajet['departure_city'] }}</span>
                        <span class="text-gray-500 mx-2">→</span>
                        <span class="font-medium">{{ $sousTrajet['destination_city'] }}</span>
                        <div class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($sousTrajet['departure_time'])->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($sousTrajet['arrival_time'])->format('H:i') }} | 
                            {{ number_format($sousTrajet['price'], 2) }} MAD
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <form method="POST" action="{{ route('reservations.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="trajet_id" value="{{ $trajetData['trajet_id'] }}">
            <input type="hidden" name="prix_partiel" value="{{ $trajetData['prix_partiel'] }}"> 
        
            <input type="hidden" name="date_depart" value="{{ $trajetData['date_depart'] }}"> 
            <input type="hidden" name="ville_depart" value="{{ $trajetData['ville_depart'] }}">
            <input type="hidden" name="date_arrivee" value="{{ $trajetData['date_arrivee'] }}">
            <input type="hidden" name="ville_arrivee" value="{{ $trajetData['ville_arrivee'] }}">
            
            <div class="border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Détails de la réservation</h2>
                
                <div class="mb-4">
                    <label for="nombre_passagers" class="block text-gray-700 text-sm font-bold mb-2">
                        Nombre de passagers
                    </label>
                    <input type="number" id="nombre_passagers" name="nombre_passagers" min="1" value="1"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           required>
                </div>
                
            </div>
        
            <div class="flex justify-between pt-4">
                <a href="{{ route('voyageur.recherche') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Retour
                </a>
                <button type="submit"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                    Confirmer la réservation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection