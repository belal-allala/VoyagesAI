@extends('layouts.app')

@section('title', 'Confirmation de paiement')

@section('content')
    <div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen">
        <!-- Header avec navigation -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-700 hover:text-yellow-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="text-sm font-medium">Retour</span>
                    </a>
                    <h1 class="text-xl font-bold text-gray-900">Confirmation de paiement</h1>
                    <div class="w-24"></div> <!-- Élément vide pour équilibrer le header -->
                </div>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class="container mx-auto px-4 py-12">
            <div class="max-w-lg mx-auto">
                <!-- Carte de confirmation -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <!-- En-tête de la carte -->
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-400 px-6 py-4">
                        <div class="text-white text-sm font-medium">
                            Statut de la transaction
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Animation de succès -->
                        <div class="flex justify-center mb-8">
                            @if(isset($reservation) && $reservation->status === 'confirmed')
                                <div class="relative">
                                    <div class="rounded-full bg-green-50 p-6 w-32 h-32 flex items-center justify-center">
                                        <div class="rounded-full bg-gradient-to-br from-green-500 to-green-600 w-24 h-24 flex items-center justify-center shadow-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white animate-scale" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="absolute -inset-2 rounded-full bg-green-100 opacity-75 animate-ping"></div>
                                </div>
                            @elseif(isset($reservation) && $reservation->status === 'payment_failed')
                                <div class="rounded-full bg-red-50 p-6 w-32 h-32 flex items-center justify-center">
                                    <div class="rounded-full bg-gradient-to-br from-red-500 to-red-600 w-24 h-24 flex items-center justify-center shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white animate-scale" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-full bg-yellow-50 p-6 w-32 h-32 flex items-center justify-center">
                                    <div class="rounded-full bg-gradient-to-br from-yellow-500 to-yellow-600 w-24 h-24 flex items-center justify-center shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white animate-scale" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Texte de confirmation -->
                        <div class="text-center mb-8">
                            @if(isset($reservation) && $reservation->status === 'confirmed')
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Paiement réussi !</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    Votre paiement de <span class="font-semibold text-green-600">{{ number_format($reservation->prix_total, 2) }} MAD</span> a été traité avec succès et votre billet est confirmé.
                                </p>
                                <p class="text-gray-500 text-sm mt-2">
                                    Un email de confirmation a été envoyé à <span class="font-medium">{{ auth()->user()->email }}</span>
                                </p>
                            @elseif(isset($reservation) && $reservation->status === 'payment_failed')
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Paiement échoué</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    Votre paiement n'a pas pu être traité. Veuillez vérifier vos informations de paiement et réessayer.
                                </p>
                            @else
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Statut indéterminé</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    Nous n'avons pas pu déterminer le statut de votre paiement. Veuillez vérifier vos réservations.
                                </p>
                            @endif
                        </div>

                        <!-- Détails de la réservation -->
                        @if(isset($reservation))
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Détails de la réservation</h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">Trajet</p>
                                        <p class="font-medium">{{ $reservation->trajet->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Date</p>
                                        <p class="font-medium">{{ $reservation->date_depart->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Départ</p>
                                        <p class="font-medium">{{ $reservation->ville_depart }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Arrivée</p>
                                        <p class="font-medium">{{ $reservation->ville_arrivee }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Passagers</p>
                                        <p class="font-medium">{{ $reservation->nombre_passagers }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Référence</p>
                                        <p class="font-medium">{{ $reservation->id }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="space-y-3">
                            @if(isset($reservation) && $reservation->status === 'confirmed')
                                <a href="{{ route('voyageur.ticketPdf', $reservation) }}" 
                                   class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow hover:shadow-md">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                        Voir mon billet
                                    </div>
                                </a>
                                <a href="{{ route('voyageur.reservations') }}" 
                                   class="block w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow-sm hover:shadow">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Mes réservations
                                    </div>
                                </a>
                            @elseif(isset($reservation) && $reservation->status === 'payment_failed')
                                <a href="{{ route('voyageur.paiement', $reservation) }}" 
                                   class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow hover:shadow-md">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Réessayer le paiement
                                    </div>
                                </a>
                                <a href="{{ route('voyageur.recherche') }}" 
                                   class="block w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow-sm hover:shadow">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Retour à la recherche
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('voyageur.reservations') }}" 
                                   class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow hover:shadow-md">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Voir mes réservations
                                    </div>
                                </a>
                                <a href="{{ route('voyageur.recherche') }}" 
                                   class="block w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow-sm hover:shadow">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Nouvelle recherche
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Aide et support -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Besoin d'aide ?</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Contactez-nous par email</p>
                                    <p class="text-sm text-gray-500">
                                        <a href="mailto:support@voyageai.ma" class="text-yellow-600 hover:text-yellow-700">support@voyageai.ma</a>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Appelez notre service client</p>
                                    <p class="text-sm text-gray-500">
                                        <a href="tel:+212522123456" class="text-yellow-600 hover:text-yellow-700">+212 522 123 456</a>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Consultez notre FAQ</p>
                                    <p class="text-sm text-gray-500">
                                        <a href="#" class="text-yellow-600 hover:text-yellow-700">Voir les questions fréquentes</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .animate-scale {
            animation: scale 0.5s ease-in-out;
        }
        @keyframes scale {
            0% { transform: scale(0.8); opacity: 0; }
            70% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-ping {
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        @keyframes ping {
            0% { transform: scale(0.95); opacity: 1; }
            75%, 100% { transform: scale(1.05); opacity: 0; }
        }
    </style>
    
@endsection