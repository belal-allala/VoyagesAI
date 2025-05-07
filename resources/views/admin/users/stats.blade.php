@extends('layouts.app')

@section('title', 'Statistiques de l\'utilisateur')

@section('content')
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- En-tête avec navigation -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-800">Statistiques de l'utilisateur</h1>
                
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste des utilisateurs
                </a>
            </div>

            <!-- Carte d'information utilisateur -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">
                        Profil utilisateur
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Informations de l'utilisateur -->
                        <div class="flex items-center">
                            <div class="h-16 w-16 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-2xl font-bold mr-4">
                                {{ substr($user->nom, 0, 1) }}
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $user->nom }}</h2>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-1">
                                    <a href="mailto:{{ $user->email }}" class="text-gray-500 hover:text-yellow-600 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $user->email }}
                                    </a>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    @if($user->compagnie)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $user->compagnie->nom }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistiques -->
                        <div class="flex flex-wrap gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-center min-w-[120px]">
                                <div class="text-3xl font-bold text-yellow-600">{{ $reservationsCount }}</div>
                                <div class="text-gray-500 text-sm">Réservations</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4 text-center min-w-[120px]">
                                <div class="text-3xl font-bold text-green-600">{{ $user->reservations->where('status', 'confirmed')->count() }}</div>
                                <div class="text-gray-500 text-sm">Confirmées</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4 text-center min-w-[120px]">
                                <div class="text-3xl font-bold text-red-600">{{ $user->reservations->where('status', 'cancelled')->count() }}</div>
                                <div class="text-gray-500 text-sm">Annulées</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-2">Filtrer par statut:</span>
                        <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50 text-sm">
                            <option value="all">Tous</option>
                            <option value="confirmed">Confirmés</option>
                            <option value="pending">En attente</option>
                            <option value="cancelled">Annulés</option>
                        </select>
                    </div>
                    
                    <div class="relative">
                        <input type="text" id="search-reservations" placeholder="Rechercher..." class="pl-10 rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50 w-full sm:w-auto text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des réservations -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trajet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status de Trajet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Billet</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="reservations-table">
                            @forelse($user->reservations as $reservation)
                                <tr class="hover:bg-gray-50 transition-colors reservation-row" data-status="{{ $reservation->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->trajet->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $reservation->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($reservation->trajet->status === 'À venir') bg-blue-100 text-blue-800
                                                    @elseif($reservation->trajet->status === 'En cours') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                            {{ $reservation->trajet->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $reservation->date_depart->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $reservation->date_depart->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $reservation->ville_depart }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $reservation->ville_arrivee }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-green-600">{{ number_format($reservation->prix_total, 2) }} MAD</div>
                                        <div class="text-xs text-gray-500">{{ $reservation->nombre_passagers }} passager(s)</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($reservation->billet)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $reservation->billet->numero_billet }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Non émis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $reservation->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                              ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-gray-500 text-sm">Aucune réservation pour cet utilisateur</p>
                                            <p class="text-gray-400 text-xs mt-1">Les réservations apparaîtront ici une fois créées</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('status-filter');
            const searchInput = document.getElementById('search-reservations');
            const reservationRows = document.querySelectorAll('.reservation-row');

            function filterReservations() {
                const statusValue = statusFilter.value;
                const searchValue = searchInput.value.toLowerCase();

                reservationRows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    const text = row.textContent.toLowerCase();
                    
                    const statusMatch = statusValue === 'all' || status === statusValue;
                    const searchMatch = searchValue === '' || text.includes(searchValue);
                    
                    if (statusMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            statusFilter.addEventListener('change', filterReservations);
            searchInput.addEventListener('input', filterReservations);
        });
    </script>
@endsection