@extends('layouts.app')

@section('title', 'Statistiques Chauffeur')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête avec navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold mr-3">
                        {{ substr($user->nom, 0, 1) }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Statistiques Chauffeur</h1>
                </div>
                <p class="text-gray-500 mt-1 ml-13">Analyse des performances de conduite et des trajets</p>
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
            <div class="bg-gradient-to-r from-blue-600 to-blue-400 px-6 py-4">
                <div class="text-white text-sm font-medium">
                    Profil Chauffeur
                </div>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <!-- Photo et informations de base -->
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold mr-5 shadow-md">
                            {{ substr($user->nom, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->nom }}</h2>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-2">
                                <a href="mailto:{{ $user->email }}" class="text-gray-500 hover:text-blue-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $user->email }}
                                </a>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                                @if($user->compagnie)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $user->compagnie->nom }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques résumées -->
                    <div class="flex-grow mt-4 md:mt-0 md:ml-6 border-l pl-6 hidden md:block">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $totalAssignedTrajets }}</div>
                                <div class="text-xs text-gray-500">Trajets assignés</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $completedTrajetsCount }}</div>
                                <div class="text-xs text-gray-500">Trajets terminés</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ $totalPassengersTransported }}</div>
                                <div class="text-xs text-gray-500">Passagers transportés</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé des performances -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Performance globale</div>
                </div>
                <div class="p-6 flex flex-col items-center justify-center">
                    <div class="relative">
                        <svg class="w-32 h-32" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                            <!-- Cercle de fond -->
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#e6e6e6" stroke-width="2"></circle>
                            
                            <!-- Cercle de progression (calculé en fonction du taux de validation) -->
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#10b981" stroke-width="2" 
                                    stroke-dasharray="{{ 100.8 * $validationRate / 100 }} 100" 
                                    stroke-dashoffset="0" 
                                    transform="rotate(-90 18 18)"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center flex-col">
                            <span class="text-3xl font-bold text-gray-800">{{ $validationRate }}%</span>
                            <span class="text-sm text-gray-500">Validation</span>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            {{ $totalValidatedTickets }} billets validés sur {{ $totalConfirmedReservationsOnCompletedTrajets }} réservations
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Statut des trajets</div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">Terminés</span>
                                <span class="text-sm font-medium text-gray-700">{{ $completedTrajetsCount }}/{{ $totalAssignedTrajets }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($completedTrajetsCount / max(1, $totalAssignedTrajets)) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">À venir</span>
                                <span class="text-sm font-medium text-gray-700">{{ $upcomingTrajetsCount }}/{{ $totalAssignedTrajets }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ ($upcomingTrajetsCount / max(1, $totalAssignedTrajets)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Prochain trajet</p>
                                <p class="text-base font-medium text-gray-900">
                                    @if($upcomingTrajetsCount > 0)
                                    Casablanca - Marrakech
                                    @else
                                    Aucun trajet prévu
                                    @endif
                                </p>
                            </div>
                            @if($upcomingTrajetsCount > 0)
                            <div class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Demain, 08:30
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Passagers transportés</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-purple-600">{{ $totalPassengersTransported }}</div>
                            <div class="text-sm text-gray-500 mt-2">Passagers au total</div>
                            
                            <div class="mt-6 flex items-center justify-center gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ round($totalPassengersTransported / max(1, $completedTrajetsCount), 1) }}</div>
                                    <div class="text-xs text-gray-500">Moyenne par trajet</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $totalValidatedTickets }}</div>
                                    <div class="text-xs text-gray-500">Billets validés</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques détaillées -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Statistiques détaillées
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Nombre Total de Trajets Assignés -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-blue-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Trajets assignés</h3>
                            <div class="text-2xl font-bold text-blue-600">{{ $totalAssignedTrajets }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Tous les trajets assignés à ce chauffeur
                    </div>
                </div>

                <!-- Nombre de Trajets Terminés -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-green-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Trajets terminés</h3>
                            <div class="text-2xl font-bold text-green-600">{{ $completedTrajetsCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Trajets complétés avec succès
                    </div>
                </div>

                <!-- Nombre de Trajets Prévus (À venir/En cours) -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 shadow-sm border border-yellow-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-yellow-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Trajets prévus</h3>
                            <div class="text-2xl font-bold text-yellow-600">{{ $upcomingTrajetsCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Trajets à venir ou en cours
                    </div>
                </div>

                <!-- Nombre Total de Passagers Transportés -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 shadow-sm border border-purple-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-purple-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656.126-1.283.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Passagers transportés</h3>
                            <div class="text-2xl font-bold text-purple-600">{{ $totalPassengersTransported }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Nombre total de passagers
                    </div>
                </div>

                <!-- Nombre de Billets Validés -->
                <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 shadow-sm border border-teal-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-teal-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Billets validés</h3>
                            <div class="text-2xl font-bold text-teal-600">{{ $totalValidatedTickets }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Sur {{ $totalConfirmedReservationsOnCompletedTrajets }} réservations confirmées
                    </div>
                </div>

                <!-- Taux de Validation des Billets -->
                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-6 shadow-sm border border-pink-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-pink-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Taux de validation</h3>
                            <div class="text-2xl font-bold text-pink-600">{{ $validationRate }}%</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Pourcentage de billets validés
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
