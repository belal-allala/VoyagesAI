@extends('layouts.app')

@section('title', 'Statistiques Employé')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête avec navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-xl font-bold mr-3">
                        {{ substr($user->nom, 0, 1) }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Statistiques Employé</h1>
                </div>
                <p class="text-gray-500 mt-1 ml-13">Analyse des performances et gestion de la compagnie</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>

        <!-- Carte d'information utilisateur -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-purple-400 px-6 py-4">
                <div class="text-white text-sm font-medium">
                    Profil Employé
                </div>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <!-- Photo et informations de base -->
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-3xl font-bold mr-5 shadow-md">
                            {{ substr($user->nom, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->nom }}</h2>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-2">
                                <a href="mailto:{{ $user->email }}" class="text-gray-500 hover:text-purple-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $user->email }}
                                </a>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                                @if($company)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $company->nom }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($company)
                    <!-- Statistiques résumées -->
                    <div class="flex-grow mt-4 md:mt-0 md:ml-6 border-l pl-6 hidden md:block">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $busCount }}</div>
                                <div class="text-xs text-gray-500">Bus gérés</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $trajetCount }}</div>
                                <div class="text-xs text-gray-500">Trajets créés</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ number_format($totalRevenue, 0) }} MAD</div>
                                <div class="text-xs text-gray-500">Revenu total</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($company)
        <!-- Tableau de bord principal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Carte des revenus -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Revenus et réservations</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <svg class="w-32 h-32" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <!-- Cercle de fond -->
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#e6e6e6" stroke-width="2"></circle>
                                
                                <!-- Cercle de progression (calculé en fonction du taux de confirmation) -->
                                @php
                                    $confirmationRate = $totalReservations > 0 ? ($confirmedReservationsCount / $totalReservations) * 100 : 0;
                                @endphp
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#10b981" stroke-width="2" 
                                        stroke-dasharray="{{ 100.8 * $confirmationRate / 100 }} 100" 
                                        stroke-dashoffset="0" 
                                        transform="rotate(-90 18 18)"></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center flex-col">
                                <span class="text-3xl font-bold text-gray-800">{{ round($confirmationRate) }}%</span>
                                <span class="text-sm text-gray-500">Confirmées</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">Confirmées</span>
                                <span class="text-sm font-medium text-gray-700">{{ $confirmedReservationsCount }}/{{ $totalReservations }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($confirmedReservationsCount / max(1, $totalReservations)) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">En attente</span>
                                <span class="text-sm font-medium text-gray-700">{{ $pendingReservationsCount }}/{{ $totalReservations }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ ($pendingReservationsCount / max(1, $totalReservations)) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">Annulées</span>
                                <span class="text-sm font-medium text-gray-700">{{ $cancelledReservationsCount }}/{{ $totalReservations }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ ($cancelledReservationsCount / max(1, $totalReservations)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Revenu total</p>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue, 2) }} MAD</p>
                            </div>
                            <div class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $totalReservations }} réservations
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte des ressources -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Ressources de la compagnie</div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-blue-100 p-3 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Bus gérés</h3>
                                <div class="text-2xl font-bold text-blue-600">{{ $busCount }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="rounded-full bg-purple-100 p-3 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Chauffeurs affiliés</h3>
                                <div class="text-2xl font-bold text-purple-600">{{ $chauffeurCount }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="rounded-full bg-green-100 p-3 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Trajets créés</h3>
                                <div class="text-2xl font-bold text-green-600">{{ $trajetCount }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Compagnie</p>
                                <p class="text-base font-medium text-gray-900">{{ $company->nom }}</p>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte des billets -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-teal-600 to-teal-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Billets et validation</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <svg class="w-32 h-32" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <!-- Cercle de fond -->
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#e6e6e6" stroke-width="2"></circle>
                                
                                <!-- Cercle de progression (calculé en fonction du taux de validation) -->
                                @php
                                    $validationRate = $totalIssuedTickets > 0 ? ($validatedTicketsCount / $totalIssuedTickets) * 100 : 0;
                                @endphp
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#0d9488" stroke-width="2" 
                                        stroke-dasharray="{{ 100.8 * $validationRate / 100 }} 100" 
                                        stroke-dashoffset="0" 
                                        transform="rotate(-90 18 18)"></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center flex-col">
                                <span class="text-3xl font-bold text-gray-800">{{ round($validationRate) }}%</span>
                                <span class="text-sm text-gray-500">Validés</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-teal-600">{{ $totalIssuedTickets }}</div>
                            <div class="text-xs text-gray-500">Billets émis</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">{{ $validatedTicketsCount }}</div>
                            <div class="text-xs text-gray-500">Billets validés</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Billets non validés</p>
                                <p class="text-base font-medium text-gray-900">{{ $totalIssuedTickets - $validatedTicketsCount }}</p>
                            </div>
                            <div class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                À surveiller
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @else
        <!-- Message si l'employé n'a pas de compagnie -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="rounded-full bg-yellow-100 p-4 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Aucune compagnie associée</h2>
                <p class="text-gray-600 mb-6 max-w-md">
                    Cet employé n'est actuellement pas affilié à une compagnie. Les statistiques de compagnie ne sont pas disponibles.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Associer à une compagnie
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
