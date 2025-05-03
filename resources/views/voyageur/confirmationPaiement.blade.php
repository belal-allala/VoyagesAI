@extends('layouts.app')
@section('title', 'Confirmation de paiement')
@section('content')
    <div class="bg-gray-50 min-h-screen">
        <!-- Header avec bouton de retour -->
        <header class="bg-white p-4 shadow-sm sticky top-0 z-10">
            <div class="container mx-auto flex items-center justify-center relative">
                <a href="{{ url()->previous() }}" class="absolute left-4 transition-transform hover:scale-110" aria-label="Retour">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-xl font-semibold text-gray-900">Statut de paiement</h1>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class="container mx-auto px-4 py-8 flex flex-col items-center">
            <!-- Animation de succès -->
            <div class="mb-8 animate-bounce">
                <div class="relative">
                    <div class="rounded-full bg-green-50 p-6 w-32 h-32 flex items-center justify-center shadow-inner">
                        <div class="rounded-full bg-gradient-to-br from-green-500 to-green-600 w-24 h-24 flex items-center justify-center shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white animate-scale" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="absolute -inset-2 rounded-full bg-green-100 opacity-75 animate-ping"></div>
                </div>
            </div>

            <!-- Texte de confirmation -->
            <section class="text-center mb-8 max-w-md">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Paiement réussi !</h2>
                <p class="text-gray-600 leading-relaxed">
                    Votre paiement a été traité avec succès et votre billet est confirmé. 
                    Un email de confirmation vous a été envoyé.
                </p>
            </section>

            <!-- Actions -->
            <section class="w-full max-w-xs space-y-4">
                @if(isset($reservation) && $reservation->status === 'confirmed')
                    <div class="space-y-3">
                        <a href="{{ route('voyageur.ticketPdf', $reservation) }}" 
                           class="block w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow hover:shadow-md">
                            Voir mon billet
                        </a>
                        <a href="{{ route('voyageur.ticketPdf', $reservation) }}" 
                           class="block w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow-sm hover:shadow">
                            Mes réservations
                        </a>
                    </div>
                @elseif(isset($reservation) && $reservation->status === 'payment_failed')
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">Paiement échoué</h3>
                                <p class="text-sm mt-1">Votre paiement n'a pas pu être traité. Veuillez réessayer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('voyageur.paiement', $reservation) }}" 
                           class="block w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow hover:shadow-md">
                            Réessayer le paiement
                        </a>
                        <a href="{{ route('voyageur.recherche') }}" 
                           class="block w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow-sm hover:shadow">
                            Retour à la recherche
                        </a>
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-yellow-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">Statut inconnu</h3>
                                <p class="text-sm mt-1">Nous n'avons pas pu déterminer le statut de votre paiement.</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('voyageur.reservations') }}" 
                       class="block w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 text-center shadow hover:shadow-md">
                        Voir mes réservations
                    </a>
                @endif
            </section>
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
    </style>
@endsection