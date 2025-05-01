@extends('layouts.app')

@section('title', 'Paiement de la réservation')

@section('content')
    <div class="container mx-auto py-6 px-4">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <!-- Progress Steps -->
            <div class="mb-8 hidden sm:block">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-green-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="text-sm font-medium text-green-500 ml-2">Réservation</div>
                    </div>
                    <div class="w-12 h-1 bg-green-500 mx-2"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-green-500 text-white font-bold">2</div>
                        <div class="text-sm font-medium text-green-500 ml-2">Paiement</div>
                    </div>
                    <div class="w-12 h-1 bg-gray-300 mx-2"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-bold">3</div>
                        <div class="text-sm font-medium text-gray-600 ml-2">Confirmation</div>
                    </div>
                </div>
            </div>

            <h1 class="text-2xl font-bold mb-6 text-center">Paiement de votre réservation</h1>

            <!-- Messages de session -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Détails de la réservation -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4 h-full">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Détails de la réservation</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Trajet</p>
                                <p class="font-medium">{{ $reservation->trajet->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Départ</p>
                                <p class="font-medium">{{ $reservation->ville_depart }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Arrivée</p>
                                <p class="font-medium">{{ $reservation->ville_arrivee }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date</p>
                                <p class="font-medium">{{ $reservation->date_depart->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Passagers</p>
                                <p class="font-medium">{{ $reservation->nombre_passagers }}</p>
                            </div>
                            <div class="pt-3 border-t mt-2">
                                <p class="text-sm text-gray-500">Total à payer</p>
                                <p class="font-bold text-green-600 text-xl">{{ number_format($reservation->prix_total, 2) }} MAD</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement -->
                <div class="md:col-span-2">
                    <form action="{{ route('paiement.traitement', $reservation) }}" method="POST" id="payment-form" class="space-y-4">
                        @csrf
                        
                        <!-- Méthodes de paiement -->
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Méthode de paiement</h2>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="border border-gray-200 bg-white rounded-lg p-3 cursor-pointer hover:border-green-500 hover:bg-green-50 transition-colors flex items-center space-x-3 payment-method active" data-method="card">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Carte bancaire</p>
                                        <p class="text-xs text-gray-500">Visa, Mastercard, etc.</p>
                                    </div>
                                </div>
                                
                                {{-- <div class="border border-gray-200 bg-white rounded-lg p-3 cursor-not-allowed opacity-60 flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Mobile</p>
                                        <p class="text-xs text-gray-500">Bientôt disponible</p>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        
                        <!-- Informations de carte -->
                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                            <div class="mb-4">
                                <label for="card-number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Numéro de carte
                                </label>
                                <div id="card-number" class="stripe-element"></div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="card-expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                        Date d'expiration
                                    </label>
                                    <div id="card-expiry" class="stripe-element"></div>
                                </div>
                                <div>
                                    <label for="card-cvc" class="block text-sm font-medium text-gray-700 mb-1">
                                        Code de sécurité (CVC)
                                    </label>
                                    <div id="card-cvc" class="stripe-element"></div>
                                </div>
                            </div>
                            
                            <div id="card-errors" class="text-red-500 text-sm mt-2 min-h-[20px]"></div>
                        </div>
                        
                        <!-- Options supplémentaires -->
                        {{-- <div class="flex items-center">
                            <input id="save-card" name="save_card" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="save-card" class="ml-2 block text-sm text-gray-700">
                                Sauvegarder cette carte pour mes prochains paiements
                            </label>
                        </div> --}}
                        
                        <!-- Sécurité -->
                        <div class="flex items-center space-x-2 text-sm text-gray-500 mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Paiement 100% sécurisé</span>
                        </div>
                        
                        <!-- Bouton de paiement -->
                        <button id="submit-button" type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-green-300 transition-colors flex items-center justify-center">
                            <span id="button-text">Payer {{ number_format($reservation->prix_total, 2) }} MAD</span>
                            <svg id="button-spinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                    
                    <!-- Logos de paiement -->
                    <div class="flex items-center justify-center space-x-4 mt-6">
                        <img src="https://cdn-icons-png.flaticon.com/128/349/349221.png" alt="Visa" class="h-8">
                        <img src="https://cdn-icons-png.flaticon.com/128/349/349228.png" alt="Mastercard" class="h-8">
                        <img src="https://cdn-icons-png.flaticon.com/128/349/349230.png" alt="American Express" class="h-8">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Support -->
        {{-- <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Besoin d'aide ? <a href="#" class="text-green-600 hover:text-green-800 font-medium">Contactez notre support</a>
            </p>
        </div> --}}
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialiser Stripe
            const stripe = Stripe('{{ env('STRIPE_KEY') }}');
            if (!stripe) {
                document.getElementById('card-errors').textContent = 'Erreur de configuration du paiement.';
                return;
            }
            
            const elements = stripe.elements();
            
            // Style personnalisé pour les éléments Stripe
            const style = {
                base: {
                    color: '#4B5563',
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#9CA3AF'
                    },
                    padding: '10px 12px',
                },
                invalid: {
                    color: '#EF4444',
                    iconColor: '#EF4444'
                }
            };
            
            // Créer et monter les éléments Stripe
            const cardNumber = elements.create('cardNumber', { 
                style: style,
                placeholder: '1234 1234 1234 1234'
            });
            
            const cardExpiry = elements.create('cardExpiry', { 
                style: style 
            });
            
            const cardCvc = elements.create('cardCvc', { 
                style: style,
                placeholder: 'CVC'
            });
            
            cardNumber.mount('#card-number');
            cardExpiry.mount('#card-expiry');
            cardCvc.mount('#card-cvc');
            
            // Gérer les erreurs en temps réel
            const handleError = (event) => {
                const errorDiv = document.getElementById('card-errors');
                errorDiv.textContent = event.error ? event.error.message : '';
            };
            
            cardNumber.on('change', handleError);
            cardExpiry.on('change', handleError);
            cardCvc.on('change', handleError);
            
            // Gérer la soumission du formulaire
            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const buttonSpinner = document.getElementById('button-spinner');
            
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Désactiver le bouton et afficher l'indicateur de chargement
                submitButton.disabled = true;
                buttonText.textContent = 'Traitement en cours...';
                buttonSpinner.classList.remove('hidden');
                
                // Créer un token avec Stripe
                stripe.createToken(cardNumber).then((result) => {
                    if (result.error) {
                        // Afficher l'erreur et réactiver le bouton
                        document.getElementById('card-errors').textContent = result.error.message;
                        submitButton.disabled = false;
                        buttonText.textContent = 'Payer {{ number_format($reservation->prix_total, 2) }} MAD';
                        buttonSpinner.classList.add('hidden');
                    } else {
                        // Ajouter le token au formulaire et soumettre
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', result.token.id);
                        form.appendChild(hiddenInput);
                        
                        // Soumettre le formulaire
                        form.submit();
                    }
                });
            });
            
            // Gestion des méthodes de paiement (pour une future implémentation)
            const paymentMethods = document.querySelectorAll('.payment-method');
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    if (!this.classList.contains('cursor-not-allowed')) {
                        // Supprimer la classe active de toutes les méthodes
                        paymentMethods.forEach(m => {
                            if (!m.classList.contains('cursor-not-allowed')) {
                                m.classList.remove('active', 'border-green-500', 'bg-green-50');
                            }
                        });
                        
                        // Ajouter la classe active à la méthode cliquée
                        this.classList.add('active', 'border-green-500', 'bg-green-50');
                    }
                });
            });
        });
    </script>
    
    <style>
        /* Styles pour les éléments Stripe */
        .stripe-element {
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: box-shadow 150ms ease, border-color 150ms ease;
            height: 42px;
        }

        .stripe-element--focus {
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
            border-color: #10B981;
        }

        .stripe-element--invalid {
            border-color: #EF4444;
        }

        .stripe-element--webkit-autofill {
            background-color: #fefde5 !important;
        }
        
        /* Animation pour le spinner */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        /* Style pour la méthode de paiement active */
        .payment-method.active {
            border-color: #10B981;
            background-color: #ECFDF5;
        }
    </style>
@endsection
