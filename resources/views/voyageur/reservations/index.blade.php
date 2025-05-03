@extends('layouts.app')

@section('title', 'Mes Réservations')

@section('content')
<div class="container mx-auto py-8 px-4">
    <h1 class="text-2xl font-semibold mb-6">Mes Réservations</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Réservations à venir -->
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Réservations à venir</h2>
            
            @if(!$reservationsActives->isEmpty())
            <div class="text-sm text-gray-500">
                {{ $reservationsActives->count() }} réservation(s)
            </div>
            @endif
        </div>

        @if($reservationsActives->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucune réservation à venir</h3>
                <p class="text-gray-500">Réservez votre prochain voyage dès maintenant !</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($reservationsActives as $index => $reservation)
                    @php
                        $firstSousTrajet = $reservation->trajet->sousTrajets->first();
                        $lastSousTrajet = $reservation->trajet->sousTrajets->last();
                        $duration = $firstSousTrajet->departure_time->diffInMinutes($lastSousTrajet->arrival_time);
                        $hours = floor($duration / 60);
                        $minutes = $duration % 60;
                        $durationText = $hours . 'h' . ($minutes > 0 ? $minutes : '');
                        
                        // Déterminer si c'est le moins cher, le plus rapide, etc. (vous pouvez adapter cette logique)
                        $tag = null;
                        $tagClass = '';
                        if ($index === 0) { // Juste un exemple, adaptez votre logique de recommandation
                            $tag = 'RECOMMANDÉ';
                            $tagClass = 'bg-green-100 text-green-800';
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
                                            <h3 class="font-semibold text-gray-900">{{ $reservation->trajet->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $reservation->trajet->bus->name }} ({{ $reservation->trajet->bus->plate_number }})</p>
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
                            
                            <!-- Informations supplémentaires -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ $reservation->trajet->chauffeur->nom }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>{{ $reservation->nombre_passagers }} passagers</span>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex justify-between items-center p-4 bg-gray-50 border-t border-gray-100">
                                <div>
                                    <span class="text-xl font-bold text-gray-900">{{ number_format($reservation->prix_total, 0) }} MAD</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('voyageur.reservations.details', $reservation) }}" class="text-yellow-600 hover:text-yellow-800 font-medium py-2 px-4 rounded-lg inline-flex items-center">
                                        Voir les détails
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    @if($reservation->status === 'confirmed')
                                        <form action="{{ route('voyageur.reservations.annuler', $reservation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                            @csrf
                                            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors">Annuler</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="container mx-auto py-8 px-4">
        <!-- Historique des réservations -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Historique des réservations</h2>
                
                @if(!$reservationsHistorique->isEmpty())
                <div class="text-sm text-gray-500">
                    {{ $reservationsHistorique->count() }} réservation(s) passée(s)
                </div>
                @endif
            </div>

            @if($reservationsHistorique->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-gray-500"><circle cx="12" cy="12" r="10"></circle><path d="M10 14l4-4M14 14l-4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun historique</h3>
                    <p class="text-gray-500">Votre historique de réservations est vide.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reservationsHistorique as $reservation)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                        <div class="p-4">
                            <!-- Informations de base -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $reservation->trajet->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $reservation->trajet->bus->name }} ({{ $reservation->trajet->bus->plate_number }})</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $reservation->status }}
                                </span>
                            </div>
                            
                            <!-- Horaires -->
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                <div>
                                    {{ $reservation->ville_depart }}
                                    <span class="mx-2">-</span>
                                    {{ $reservation->ville_arrivee }}
                                </div>
                                <div>
                                    {{ $reservation->date_depart->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            
                            <!-- Passagers et prix -->
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $reservation->nombre_passagers }} passagers
                                </div>
                                <div>
                                    <span class="font-medium">{{ number_format($reservation->prix_total, 0) }} MAD</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="p-4 bg-gray-50 border-t border-gray-100 text-right">
                            <a href="{{ route('voyageur.reservations.details', $reservation) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection