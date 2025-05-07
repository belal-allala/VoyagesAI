@extends('layouts.app')

@section('title', 'Confirmer la réservation')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- En-tête avec étapes de réservation -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <a href="{{ route('voyageur.recherche') }}" class="text-yellow-500 hover:text-yellow-600 flex items-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Retour à la recherche
                </a>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">Confirmer votre réservation</h1>
            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-bold text-sm">
                        1
                    </div>
                    <div class="ml-2">
                        <div class="text-sm font-medium text-gray-900">Recherche</div>
                        <div class="text-xs text-gray-500">Complété</div>
                    </div>
                </div>
                
                <div class="w-16 h-0.5 bg-yellow-500"></div>
                
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-bold text-sm">
                        2
                    </div>
                    <div class="ml-2">
                        <div class="text-sm font-medium text-gray-900">Confirmation</div>
                        <div class="text-xs text-gray-500">En cours</div>
                    </div>
                </div>
                
                <div class="w-16 h-0.5 bg-gray-300"></div>
                
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-bold text-sm">
                        3
                    </div>
                    <div class="ml-2">
                        <div class="text-sm font-medium text-gray-500">Paiement</div>
                        <div class="text-xs text-gray-500">À venir</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <!-- En-tête de la carte -->
            <div class="bg-gray-50 border-b border-gray-100 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $trajetData['trajet_name'] }}</h2>
                        <p class="text-gray-500 mt-1">
                            @if(!empty($trajetData['sous_trajets_pertinents']))
                                {{ \Carbon\Carbon::parse($trajetData['sous_trajets_pertinents'][0]['departure_time'])->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                        Réservation en cours
                    </div>
                </div>
            </div>
            
            <!-- Détails du trajet -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Bus</div>
                            <div class="font-medium">{{ $trajetData['bus']['name'] }} ({{ $trajetData['bus']['plate_number'] }})</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Chauffeur</div>
                            <div class="font-medium">{{ $trajetData['chauffeur'] }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Horaires et durée -->
                @if(!empty($trajetData['sous_trajets_pertinents']))
                    @php
                        $firstSousTrajet = $trajetData['sous_trajets_pertinents'][0];
                        $lastSousTrajet = $trajetData['sous_trajets_pertinents'][count($trajetData['sous_trajets_pertinents']) - 1];
                        $departureTime = \Carbon\Carbon::parse($firstSousTrajet['departure_time']);
                        $arrivalTime = \Carbon\Carbon::parse($lastSousTrajet['arrival_time']);
                        $duration = $departureTime->diffInMinutes($arrivalTime);
                        $hours = floor($duration / 60);
                        $minutes = $duration % 60;
                        $durationText = $hours . 'h' . ($minutes > 0 ? $minutes : '');
                    @endphp
                    
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-left">
                            <div class="text-xl font-bold">{{ $departureTime->format('H:i') }}</div>
                            <div class="text-sm text-gray-500">{{ $firstSousTrajet['departure_city'] }}</div>
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
                            <div class="text-xs text-gray-500 mt-1">{{ count($trajetData['sous_trajets_pertinents']) > 1 ? count($trajetData['sous_trajets_pertinents']) - 1 . ' arrêt(s)' : 'Direct' }}</div>
                        </div>
                        
                        <div class="text-right">
                            <div class="text-xl font-bold">{{ $arrivalTime->format('H:i') }}</div>
                            <div class="text-sm text-gray-500">{{ $lastSousTrajet['destination_city'] }}</div>
                        </div>
                    </div>
                @endif
                
                <!-- Itinéraire détaillé -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Itinéraire complet</h3>
                    <div class="space-y-3">
                        @foreach($trajetData['sous_trajets_pertinents'] as $index => $sousTrajet)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-xs font-medium">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $sousTrajet['departure_city'] }} → {{ $sousTrajet['destination_city'] }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($sousTrajet['departure_time'])->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($sousTrajet['arrival_time'])->format('H:i') }}
                                            </div>
                                        </div>
                                        <div class="text-sm font-medium">
                                            {{ number_format($sousTrajet['price'], 2) }} MAD
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($index < count($trajetData['sous_trajets_pertinents']) - 1)
                                <div class="ml-3 pl-3 border-l-2 border-dashed border-gray-300 h-4"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Formulaire de réservation -->
            <form method="POST" action="{{ route('reservations.store') }}" class="p-6">
                @csrf
                <input type="hidden" name="trajet_id" value="{{ $trajetData['trajet_id'] }}">
                <input type="hidden" name="prix_partiel" value="{{ $trajetData['prix_partiel'] }}"> 
                <input type="hidden" name="date_depart" value="{{ $trajetData['date_depart'] }}"> 
                <input type="hidden" name="ville_depart" value="{{ $trajetData['ville_depart'] }}">
                <input type="hidden" name="date_arrivee" value="{{ $trajetData['date_arrivee'] }}">
                <input type="hidden" name="ville_arrivee" value="{{ $trajetData['ville_arrivee'] }}">
                
                
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Détails de la réservation</h3>
                
                <div class="mb-6">
                    <label for="nombre_passagers" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de passagers
                    </label>
                    <div class="flex items-center">
                        <button type="button" onclick="decrementPassengers()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-l-lg px-3 py-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <input type="number" id="nombre_passagers" name="nombre_passagers" min="1" value="1"
                               class="w-16 text-center py-2 border-t border-b border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300"
                               readonly>
                        <button type="button" onclick="incrementPassengers()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-r-lg px-3 py-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Récapitulatif du prix -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Prix par personne</span>
                        <span>{{ number_format($trajetData['prix_partiel'], 2) }} MAD</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Nombre de passagers</span>
                        <span id="passenger-count">1</span>
                    </div>
                    <div class="border-t border-gray-200 my-2 pt-2"></div>
                    <div class="flex justify-between items-center font-bold text-lg">
                        <span>Total</span>
                        <span id="total-price">{{ number_format($trajetData['prix_partiel'], 2) }} MAD</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('voyageur.recherche') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg transition-colors flex items-center">
                        Confirmer et payer
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Informations supplémentaires -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations importantes</h3>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Politique d'annulation</h4>
                        <p class="text-sm text-gray-500">Annulation gratuite jusqu'à 24 heures avant le départ. Des frais peuvent s'appliquer pour les annulations tardives.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Bagages</h4>
                        <p class="text-sm text-gray-500">Chaque passager a droit à un bagage à main et une valise de 20kg maximum. Des frais supplémentaires peuvent s'appliquer pour les bagages excédentaires.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12zm1-6a1 1 0 11-2 0 1 1 0 012 0zm-1-4a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">COVID-19</h4>
                        <p class="text-sm text-gray-500">Le port du masque est recommandé à bord des bus. Veuillez respecter les mesures sanitaires en vigueur.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function incrementPassengers() {
        const input = document.getElementById('nombre_passagers');
        const count = parseInt(input.value) + 1;
        input.value = count;
        updatePassengerCount(count);
    }
    
    function decrementPassengers() {
        const input = document.getElementById('nombre_passagers');
        const count = Math.max(1, parseInt(input.value) - 1);
        input.value = count;
        updatePassengerCount(count);
    }
    
    function updatePassengerCount(count) {
        const countElement = document.getElementById('passenger-count');
        const totalElement = document.getElementById('total-price');
        const pricePerPerson = {{ $trajetData['prix_partiel'] }};
        
        countElement.textContent = count;
        totalElement.textContent = (pricePerPerson * count).toFixed(2) + ' MAD';
    }
</script>
@endsection