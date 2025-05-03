@extends('layouts.app')

@section('title', 'Détails de la Réservation')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- En-tête avec étapes de réservation -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-6">Détails de votre réservation</h1>

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
                        <div class="text-xs text-gray-500">Complété</div>
                    </div>
                </div>
                
                <div class="w-16 h-0.5 bg-yellow-500"></div>
                
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-500 text-white font-bold text-sm">
                        3
                    </div>
                    <div class="ml-2">
                        <div class="text-sm font-medium text-gray-900">Paiement</div>
                        <div class="text-xs text-gray-500">Complété</div>
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
                        <h2 class="text-xl font-bold text-gray-900">{{ $reservation->trajet->name }}</h2>
                        <p class="text-gray-500 mt-1">
                            {{ $reservation->date_depart->format('d/m/Y') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $reservation->status }}
                    </span>
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
                            <div class="font-medium">{{ $reservation->trajet->bus->name }} ({{ $reservation->trajet->bus->plate_number }})</div>
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
                            <div class="font-medium">{{ $reservation->trajet->chauffeur->nom }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Horaires et durée -->
                @php
                    $firstSousTrajet = $reservation->trajet->sousTrajets->first();
                    $lastSousTrajet = $reservation->trajet->sousTrajets->last();
                    $duration = $firstSousTrajet->departure_time->diffInMinutes($lastSousTrajet->arrival_time);
                    $hours = floor($duration / 60);
                    $minutes = $duration % 60;
                    $durationText = $hours . 'h' . ($minutes > 0 ? $minutes : '');
                @endphp
                
                <div class="flex justify-between items-center mb-6">
                    <div class="text-left">
                        <div class="text-xl font-bold">{{ $firstSousTrajet->departure_time->format('H:i') }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->ville_depart }}</div>
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
                        <div class="text-xs text-gray-500 mt-1">{{ $reservation->trajet->sousTrajets->count() > 1 ? $reservation->trajet->sousTrajets->count() - 1 . ' arrêt(s)' : 'Direct' }}</div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-xl font-bold">{{ $lastSousTrajet->arrival_time->format('H:i') }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->ville_arrivee }}</div>
                    </div>
                </div>
                
                <!-- Itinéraire détaillé -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Itinéraire complet</h3>
                    <div class="space-y-3">
                        @foreach($reservation->trajet->sousTrajets as $index => $sousTrajet)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-6 h-6 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-xs font-medium">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium">{{ $sousTrajet->departure_city }} → {{ $sousTrajet->destination_city }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $sousTrajet->departure_time->format('H:i') }} - 
                                                {{ $sousTrajet->arrival_time->format('H:i') }}
                                            </div>
                                        </div>
                                        <div class="text-sm font-medium">
                                            {{ number_format($sousTrajet->price, 2) }} MAD
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($index < count($reservation->trajet->sousTrajets) - 1)
                                <div class="ml-3 pl-3 border-l-2 border-dashed border-gray-300 h-4"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Passagers, prix et billet -->
            <div class="p-6">
                <div class="md:flex md:justify-between items-center mb-6">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Passagers</h3>
                        <p class="text-gray-500">{{ $reservation->nombre_passagers }} passager(s)</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Prix Total</h3>
                        <p class="text-xl font-bold text-green-600">{{ number_format($reservation->prix_total, 2) }} MAD</p>
                    </div>
                </div>
                
                @if($reservation->billet)
                    <div class="grid md:grid-cols-2 gap-6 mb-4">
                        <!-- Informations du billet -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Billet</h3>
                            <div class="space-y-2">
                                <p><strong>Numéro de billet:</strong> {{ $reservation->billet->numero_billet }}</p>
                                <p>
                                    <strong>Statut du billet:</strong>
                                    <span class="inline-block py-1 px-2 rounded-full text-xs font-semibold {{ $reservation->billet->status === 'valide' ? 'bg-green-100 text-green-800' : ($reservation->billet->status === 'utilise' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $reservation->billet->status }}
                                    </span>
                                </p>
                                <p><strong>Date d'émission:</strong> {{ $reservation->billet->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <!-- QR Code -->
                        <div class="bg-gray-50 rounded-lg p-4 flex flex-col items-center justify-center">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Votre code d'embarquement</h3>
                            <div class="p-2 bg-white rounded-lg border border-gray-200 shadow-sm">
                                {!! QrCode::size(150)->style('square')->generate($reservation->billet->qr_code) !!}
                            </div>
                            <p class="text-xs text-gray-500 mt-3 text-center">
                                Présentez ce QR code au chauffeur lors de l'embarquement
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Actions -->
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                @if($reservation->status === 'confirmed')
                    <form action="{{ route('voyageur.reservations.annuler', $reservation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                        @csrf
                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors w-full sm:w-auto">Annuler la réservation</button>
                    </form>
                @endif
                <div class="flex gap-4 w-full sm:w-auto">
                    <a href="{{ route('voyageur.ticketPdf', $reservation) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded-lg transition-colors text-center w-full sm:w-auto">
                        Télécharger le billet
                    </a>
                    <a href="{{ route('voyageur.reservations') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors text-center w-full sm:w-auto">
                        Mes Réservations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection