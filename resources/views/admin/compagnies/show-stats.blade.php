@extends('layouts.app')

@section('title', 'Admin - Stats Compagnie')

@section('content')

<div class="container mx-auto py-8 px-4">
    
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 text-xl font-bold mr-3">
                        {{ substr($compagnie->nom, 0, 1) }}
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Statistiques Compagnie</h1>
                </div>
                <p class="text-gray-500 mt-1 ml-13">Analyse des performances et activités</p>
            </div>

            <a href="{{ route('admin.compagnies') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-teal-600 to-teal-400 px-6 py-4">
                <div class="text-white text-sm font-medium">
                    Profil Compagnie
                </div>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 text-3xl font-bold mr-5 shadow-md">
                            {{ substr($compagnie->nom, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $compagnie->nom }}</h2>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mt-2">
                                <a href="mailto:{{ $compagnie->email }}" class="text-gray-500 hover:text-teal-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $compagnie->email }}
                                </a>
                                <span class="text-sm text-gray-500">ID: {{ $compagnie->id }}</span>
                                <span class="text-sm text-gray-500">Créée le: {{ $compagnie->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Revenus et réservations</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <svg class="w-32 h-32" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="18" cy="18" r="16" fill="none" stroke="#e6e6e6" stroke-width="2"></circle>
                                
                                @php
                                    $confirmationRate = $totalReservations > 0 ? ($confirmedResTotalCount / $totalReservations) * 100 : 0;
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
                                <span class="text-sm font-medium text-gray-700">{{ $confirmedResTotalCount }}/{{ $totalReservations }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($confirmedResTotalCount / max(1, $totalReservations)) * 100 }}%"></div>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Utilisateurs affiliés</h3>
                                <div class="text-2xl font-bold text-purple-600">{{ $usersCount }}</div>
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
                </div>

            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-teal-600 to-teal-400 px-6 py-4">
                    <div class="text-white text-sm font-medium">Activités aujourd'hui</div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-teal-600">{{ $confirmedResTodayCount }}</div>
                            <div class="text-xs text-gray-500">Réservations</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">{{ $passengersToday }}</div>
                            <div class="text-xs text-gray-500">Passagers</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Revenu aujourd'hui</p>
                                <p class="text-xl font-medium text-green-700">{{ number_format($revenueToday, 2) }} MAD</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Ce mois</p>
                            <p class="text-xl font-medium text-blue-700">{{ number_format($revenueThisMonth, 2) }} MAD</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Statistiques détaillées
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-blue-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Bus gérés</h3>
                            <div class="text-2xl font-bold text-blue-600">{{ $busCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Flotte de bus de la compagnie
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-green-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Trajets créés</h3>
                            <div class="text-2xl font-bold text-green-600">{{ $trajetCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Nombre total de trajets
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 shadow-sm border border-purple-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-purple-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total utilisateurs</h3>
                            <div class="text-2xl font-bold text-purple-600">{{ $usersCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Tous utilisateurs affiliés
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 shadow-sm border border-indigo-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-indigo-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Chauffeurs</h3>
                            <div class="text-2xl font-bold text-indigo-600">{{ $chauffeurCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Chauffeurs de la compagnie
                    </div>
                </div>

                <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-6 shadow-sm border border-pink-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-pink-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Employés</h3>
                            <div class="text-2xl font-bold text-pink-600">{{ $employeCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Employés de la compagnie
                    </div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 shadow-sm border border-orange-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-orange-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total réservations</h3>
                            <div class="text-2xl font-bold text-orange-600">{{ $totalReservations }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Toutes réservations confondues
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-6 shadow-sm border border-emerald-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-emerald-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Réservations confirmées</h3>
                            <div class="text-2xl font-bold text-emerald-600">{{ $confirmedResTotalCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Réservations avec paiement validé
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 shadow-sm border border-yellow-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-yellow-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Réservations en attente</h3>
                            <div class="text-2xl font-bold text-yellow-600">{{ $pendingReservationsCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        En attente de paiement
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 shadow-sm border border-red-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-red-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Réservations annulées</h3>
                            <div class="text-2xl font-bold text-red-600">{{ $cancelledReservationsCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Réservations annulées
                    </div>
                </div>

                <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl p-6 shadow-sm border border-cyan-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-cyan-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Billets émis</h3>
                            <div class="text-2xl font-bold text-cyan-600">{{ $totalIssuedTickets }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Total des billets émis
                    </div>
                </div>

                <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl p-6 shadow-sm border border-teal-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-teal-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Billets validés</h3>
                            <div class="text-2xl font-bold text-teal-600">{{ $validatedTicketsCount }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Billets scannés par les chauffeurs
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-blue-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656.126-1.283.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Passagers transportés</h3>
                            <div class="text-2xl font-bold text-blue-600">{{ $passengersTotal }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Sur réservations confirmées
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-sm border border-green-200 col-span-full md:col-span-1">
                    <div class="flex items-center mb-4">
                        <div class="rounded-full bg-green-200 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Revenu total</h3>
                            <div class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue, 2) }} MAD</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Revenu généré par les réservations confirmées
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">

            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Statistiques Temporelles
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-sm border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Aujourd'hui</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Réservations</span>
                            </div>
                            <div class="text-2xl font-bold text-blue-600 ml-7">{{ $confirmedResTodayCount }}</div>
                        </div>
                        <div>
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656.126-1.283.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Passagers</span>
                            </div>
                            <div class="text-2xl font-bold text-blue-600 ml-7">{{ $passengersToday }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Revenu</span>
                            </div>
                            <div class="text-2xl font-bold text-green-600 ml-7">{{ number_format($revenueToday, 2) }} MAD</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 shadow-sm border border-purple-200">
                    <h3 class="text-lg font-semibold text-purple-800 mb-4">Ce mois-ci</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Réservations</span>
                            </div>
                            <div class="text-2xl font-bold text-purple-600 ml-7">{{ $confirmedResThisMonthCount }}</div>
                        </div>
                        <div>
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656.126-1.283.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Passagers</span>
                            </div>
                            <div class="text-2xl font-bold text-purple-600 ml-7">{{ $passengersThisMonth }}</div>
                        </div>
                        <div class="col-span-2">
                            <div class="flex items-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Revenu</span>
                            </div>
                            <div class="text-2xl font-bold text-green-600 ml-7">{{ number_format($revenueThisMonth, 2) }} MAD</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
