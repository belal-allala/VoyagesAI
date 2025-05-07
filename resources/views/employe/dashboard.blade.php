@extends('layouts.app')

@section('title', 'Tableau de bord Employé')

@section('content')

@php
use Carbon\Carbon;
@endphp

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-purple-600 mr-3"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord Employé</h1>
        </div>

        {{-- Section si l'employé est affilié à une compagnie --}}
        @if(auth()->user()->compagnie)
            {{-- Carte d'information sur la compagnie --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-purple-600 mr-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <h2 class="text-xl font-semibold text-gray-900">Votre Compagnie</h2>
                    </div>
                </div>
                <div class="px-6 py-5 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nom de la compagnie</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ auth()->user()->compagnie->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email de contact</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ auth()->user()->compagnie->email }}</p>
                        </div>
                        {{-- Ajoutez ici d'autres infos de compagnie si souhaité (téléphone, adresse, description) --}}
                        {{-- <div>
                            <p class="text-sm font-medium text-gray-500">Téléphone</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ auth()->user()->compagnie->telephone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Adresse</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ auth()->user()->compagnie->adresse ?? 'N/A' }}</p>
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- Cartes de statistiques générales --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Bus Count Card --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-blue-600 mr-2"><rect x="2" y="8" width="20" height="12" rx="2" ry="2"></rect><path d="M6 8V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"></path><line x1="2" y1="12" x2="22" y2="12"></line></svg>
                            <h3 class="text-lg font-semibold text-gray-900">Bus</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5 flex flex-col items-center">
                        <p class="text-4xl font-bold text-blue-600">{{ $busesCount }}</p>
                        <p class="text-sm text-gray-500 mb-4">Bus enregistrés</p>
                        <a href="{{ route('buses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            Gérer les bus
                        </a>
                    </div>
                </div>

                {{-- Trajets Count Card (Total all time) --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-600 mr-2"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg>
                            <h3 class="text-lg font-semibold text-gray-900">Trajets (Total)</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5 flex flex-col items-center">
                        <p class="text-4xl font-bold text-green-600">{{ $trajetsTotalCount }}</p>
                        <p class="text-sm text-gray-500 mb-4">Trajets enregistrés</p>
                        <a href="{{ route('trajets.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Gérer les trajets
                        </a>
                    </div>
                </div>

                {{-- Chauffeurs Count Card --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-purple-600 mr-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <h3 class="text-lg font-semibold text-gray-900">Chauffeurs</h3>
                        </div>
                    </div>
                    <div class="px-6 py-5 flex flex-col items-center">
                        <p class="text-4xl font-bold text-purple-600">{{ $chauffeursCount }}</p>
                        <p class="text-sm text-gray-500 mb-4">Chauffeurs affiliés</p>
                        <a href="{{ route('chauffeurs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            Gérer les chauffeurs
                        </a>
                    </div>
                </div>
            </div>

            {{-- *** NOUVEAU : Cartes de statistiques Réservations/Revenu/Passagers/Billets (Total et Temporel) *** --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 transform transition-all hover:shadow-lg">
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Total All Time --}}
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-5 col-span-full shadow-sm border border-indigo-100">
                            <h4 class="text-sm font-bold text-indigo-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                TOTAL (DEPUIS CRÉATION)
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-indigo-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-600 mb-3 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect><path d="M16 2V6M8 2V6M7 7H17"></path></svg>
                                    </div>
                                    <div class="text-2xl font-bold text-orange-600">{{ $totalReservations }}</div>
                                    <div class="text-gray-500 text-sm font-medium">Réservations</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-indigo-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600 mb-3 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    </div>
                                    <div class="text-2xl font-bold text-green-600">{{ $confirmedResTotalCount }}</div>
                                    <div class="text-gray-500 text-sm font-medium">Confirmées</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-indigo-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mb-3 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                    <div class="text-2xl font-bold text-blue-600">{{ $passengersTotal }}</div>
                                    <div class="text-gray-500 text-sm font-medium">Passagers</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-indigo-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 mb-3 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <div class="text-2xl font-bold text-emerald-600">{{ number_format($totalRevenue, 0) }}</div>
                                    <div class="text-gray-500 text-sm font-medium">Revenu MAD</div>
                                </div>
                            </div>
                        </div>

                        {{-- Aujourd'hui --}}
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 col-span-full md:col-span-1 shadow-sm border border-blue-100">
                            <h4 class="text-sm font-bold text-blue-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                AUJOURD'HUI
                            </h4>
                            <div class="space-y-4">
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-blue-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    </div>
                                    <div class="text-xl font-bold text-green-600">{{ $confirmedResTodayCount }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Résas Confirmées</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-blue-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                    <div class="text-xl font-bold text-blue-600">{{ $passengersToday }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Passagers</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-blue-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <div class="text-xl font-bold text-emerald-600">{{ number_format($revenueToday, 0) }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Revenu MAD</div>
                                </div>
                            </div>
                        </div>

                        {{-- Ce Mois-ci --}}
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 col-span-full md:col-span-1 shadow-sm border border-purple-100">
                            <h4 class="text-sm font-bold text-purple-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                CE MOIS-CI
                            </h4>
                            <div class="space-y-4">
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-purple-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    </div>
                                    <div class="text-xl font-bold text-green-600">{{ $confirmedResThisMonthCount }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Résas Confirmées</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-purple-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    </div>
                                    <div class="text-xl font-bold text-blue-600">{{ $passengersThisMonth }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Passagers</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-purple-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                    <div class="text-xl font-bold text-emerald-600">{{ number_format($revenueThisMonth, 0) }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Revenu MAD</div>
                                </div>
                            </div>
                        </div>

                        {{-- Billets Validés (Total) --}}
                        <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-5 col-span-full md:col-span-1 shadow-sm border border-teal-100">
                            <h4 class="text-sm font-bold text-teal-800 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                BILLETS (TOTAL)
                            </h4>
                            <div class="space-y-4">
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-teal-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                    </div>
                                    <div class="text-xl font-bold text-teal-600">{{ $totalIssuedTickets }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Billets émis</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-teal-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    </div>
                                    <div class="text-xl font-bold text-cyan-600">{{ $validatedTicketsCount }}</div>
                                    <div class="text-gray-500 text-xs font-medium">Billets validés</div>
                                </div>
                                <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-teal-50 transform transition-transform hover:scale-105">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-600 mb-2 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                                    </div>
                                    @php
                                        $validationRate = $totalIssuedTickets > 0 ? round(($validatedTicketsCount / $totalIssuedTickets) * 100) : 0;
                                    @endphp
                                    <div class="text-xl font-bold text-amber-600">{{ $validationRate }}%</div>
                                    <div class="text-gray-500 text-xs font-medium">Taux de validation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- *** Section Tableau des trajets filtrés par date *** --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                 <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-orange-600 mr-2"><path d="M3 12h18M3 6h18M3 18h18"></path></svg>
                            <h3 class="text-lg font-semibold text-gray-900">Trajets du {{ Carbon::parse($filterDate)->format('d/m/Y') }}</h3> {{-- Afficher la date filtrée --}}
                        </div>
                    </div>
                </div>
                 <div class="p-6">
                    {{-- Formulaire de filtre par date et bouton d'export --}}
                    <form method="GET" action="{{ route('employe.dashboard') }}" class="flex flex-col sm:flex-row items-end gap-4 mb-6">
                        <div class="w-full sm:w-auto">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par date :</label>
                            <input type="date" id="date" name="date" value="{{ $filterDate }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit"
                                    class="flex-1 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors justify-center">
                                Filtrer
                            </button>
                            {{-- Bouton d'export Excel --}}
                            <a href="{{ route('employe.exportDailyTrajets', ['date' => $filterDate]) }}"
                                class="flex-1 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors justify-center">
                                <div class="flex items-center justify-center">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 14h-1.26a8 8 0 1 0-11.62 0H6a5 5 0 0 0 4.39 4.39"></path><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                                    Exporter Excel
                                </div>
                            </a>
                        </div>
                    </form>

                     {{-- Tableau des trajets pour la date filtrée --}}
                    @if($trajetsList->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-12 w-12 mx-auto text-gray-300 mb-3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <p>Aucun trajet trouvé pour le {{ Carbon::parse($filterDate)->format('d/m/Y') }}.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trajet</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Résas Confirmées</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Places Dispo</th>
                                         <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenu</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($trajetsList as $trajet)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $trajet->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $trajet->is_recurring ? 'Récurrent' : 'Ponctuel' }}</div> {{-- Afficher type --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                 {{-- Utiliser les propriétés calculées dans le contrôleur --}}
                                                 <div>{{ Carbon::parse($trajet->departure_time_for_date)->format('d/m H:i') }}</div>
                                                 <div class="text-xs text-gray-500">{{ $trajet->departure_city_for_date }}</div> {{-- Utiliser la propriété calculée --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{-- Utiliser les propriétés calculées dans le contrôleur --}}
                                                <div>{{ Carbon::parse($trajet->arrival_time_for_date)->format('d/m H:i') }}</div>
                                                <div class="text-xs text-gray-500">{{ $trajet->arrival_city_for_date }}</div> {{-- Utiliser la propriété calculée --}}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                 {{ $trajet->bus->name ?? 'N/A' }}
                                                <div class="text-xs text-gray-500">{{ $trajet->bus->plate_number ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $trajet->confirmed_reservations_count }}
                                                </span>
                                                 <div class="text-xs text-gray-500">{{ $trajet->total_passengers_booked }} passagers</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                                 @php
                                                     $seatsColor = $trajet->available_seats > 5 ? 'bg-green-100 text-green-800' : ($trajet->available_seats > 0 ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800');
                                                 @endphp
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $seatsColor }}">
                                                    {{ $trajet->available_seats }}
                                                </span>
                                                 <div class="text-xs text-gray-500">sur {{ $trajet->bus->capacity ?? 0 }} places</div>
                                            </td>
                                             <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-700">
                                                {{ number_format($trajet->total_revenue, 2) }} MAD
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                 </div>
            </div>
        @else
            {{-- Message si pas de compagnie affiliée (reste inchangé) --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-yellow-400"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-yellow-800">Compagnie requise</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Vous n'avez pas encore créé de compagnie. Pour accéder à toutes les fonctionnalités, veuillez créer votre compagnie.</p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('compagnies.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    Créer votre compagnie
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Pourquoi créer une compagnie ?</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex flex-col items-center text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-purple-600 mb-3"><rect x="2" y="8" width="20" height="12" rx="2" ry="2"></rect><path d="M6 8V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"></path><line x1="2" y1="12" x2="22" y2="12"></line></svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">Gérer vos bus</h4>
                            <p class="text-gray-500">Ajoutez et gérez facilement votre flotte de bus.</p>
                        </div>
                        <div class="flex flex-col items-center text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-purple-600 mb-3"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">Créer des trajets</h4>
                            <p class="text-gray-500">Planifiez et organisez vos trajets et horaires.</p>
                        </div>
                        <div class="flex flex-col items-center text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-purple-600 mb-3"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-1">Gérer les chauffeurs</h4>
                            <p class="text-gray-500">Assignez des chauffeurs à vos bus et trajets.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div id="trajetDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
    <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col transform transition-transform duration-300 scale-95">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-xl font-bold text-gray-900" id="trajetDetailsTitle"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="overflow-y-auto flex-grow" id="trajetDetailsContent">
             <div class="flex justify-center items-center py-16">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        </div>
        <div class="flex justify-end px-6 py-4 border-t bg-gray-50">
            <button onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
    function showTrajetDetails(trajetId) {
        const modal = document.getElementById('trajetDetailsModal');

        document.getElementById('trajetDetailsContent').innerHTML = `
            <div class="flex justify-center items-center py-16">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        `;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.relative').classList.remove('scale-95');
        }, 10);

        fetch(`/trajets/${trajetId}/details`)
            .then(response => {
                 if (!response.ok) {
                     throw new Error('Network response was not ok');
                 }
                 return response.json();
             })
            .then(data => {
                document.getElementById('trajetDetailsTitle').textContent = data.name;

                let html = `
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                    </svg>
                                    <h4 class="font-semibold text-gray-900">Informations du Bus</h4>
                                </div>
                                <div class="ml-7 space-y-2">
                                    <p class="text-sm text-gray-700"><span class="font-medium">Nom:</span> ${data.bus.name}</p>
                                    <p class="text-sm text-gray-700"><span class="font-medium">Immatriculation:</span> ${data.bus.plate_number}</p>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h4 class="font-semibold text-gray-900">Chauffeur</h4>
                                </div>
                                <div class="ml-7 space-y-2">
                                    <p class="text-sm text-gray-700"><span class="font-medium">Nom:</span> ${data.chauffeur.nom}</p>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h4 class="font-semibold text-gray-900">Type de Trajet</h4>
                                </div>
                                <div class="ml-7 space-y-2">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Type:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.is_recurring ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                            ${data.is_recurring ? 'Récurrent' : 'Ponctuel'}
                                        </span>
                                    </p>
                                    ${data.is_recurring ? `
                                    <p class="text-sm text-gray-700"><span class="font-medium">Périodicité:</span> ${data.recurring_pattern.recurrence_description}</p>
                                    ` : ''}
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                <h4 class="text-lg font-semibold text-gray-900">Étapes du trajet</h4>
                            </div>

                            <div class="space-y-6">
                `;

                data.sous_trajets.forEach((etape, index) => {
                    html += `
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full text-xs font-bold mr-2">
                                        ${index + 1}
                                    </div>
                                    <h5 class="font-medium text-gray-900">${etape.departure_city} → ${etape.destination_city}</h5>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    ${etape.price} MAD
                                </span>
                            </div>

                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm">
                                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                        </svg>
                                        Départ
                                    </h6>
                                    <div class="ml-6 space-y-3">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.departure_time}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.departure_city}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm">
                                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="h-4 w-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                                        </svg>
                                        Arrivée
                                    </h6>
                                    <div class="ml-6 space-y-3">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.arrival_time}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.destination_city}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                html += `
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('trajetDetailsContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading trajet details:', error);
                document.getElementById('trajetDetailsContent').innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 px-6">
                        <svg class="h-12 w-12 text-red-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de chargement</h3>
                        <p class="text-gray-500 text-center">Une erreur s'est produite lors du chargement des détails du trajet.</p>
                    </div>
                `;
            });
    }

    function closeModal() {
        const modal = document.getElementById('trajetDetailsModal');

        modal.classList.add('opacity-0');
        modal.querySelector('.relative').classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    document.getElementById('trajetDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('trajetDetailsModal').classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endsection