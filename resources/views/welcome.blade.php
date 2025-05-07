@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <!-- Messages de session -->
    {{-- J'ai conservé les messages de session pour qu'ils s'affichent au-dessus du contenu --}}
    <div class="container mx-auto px-4 py-4">
        <div class="space-y-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('message'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Section Héro SANS recherche directement -->
    <div class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white">
        <div class="absolute inset-0 overflow-hidden">
            {{-- Assurez-vous que l'image existe dans public/images --}}
            <img src="{{ asset('images/hero-background.jpg') }}" alt="Bus en voyage" class="w-full h-full object-cover opacity-30">
        </div>
        <div class="container mx-auto px-4 py-20 relative z-10">
            <div class="max-w-3xl mx-auto text-center"> {{-- mb-12 retiré ou ajusté car le formulaire en dessous est parti --}}
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Voyagez en toute simplicité avec Vega Go</h1>
                <p class="text-xl text-gray-200 mb-8">Réservez vos billets de bus en quelques clics et profitez d'une expérience de voyage sans stress</p>
                <div class="flex gap-4 justify-center">
                    {{-- Ce bouton redirige déjà vers la page de recherche --}}
                    <a href="{{ route('voyageur.recherche') }}" class="px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold rounded-lg transition-colors">
                        Rechercher un trajet
                    </a>
                    <a href="#comment-ca-marche" class="px-6 py-3 bg-transparent hover:bg-gray-700 border border-white text-white font-semibold rounded-lg transition-colors">
                        En savoir plus
                    </a>
                </div>
            </div>

            {{-- ANCIEN BLOC DE RECHERCHE - RETIRÉ --}}
            {{--
            <div class="bg-white rounded-xl shadow-xl p-6 max-w-4xl mx-auto">
                <h2 class="text-gray-800 text-xl font-semibold mb-4">Rechercher un trajet</h2>
                <form action="{{ route('voyageur.recherche') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="depart" class="block text-sm font-medium text-gray-700 mb-1">Ville de départ</label>
                        <select id="depart" name="depart" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Sélectionnez une ville</option>
                            <option value="casablanca">Casablanca</option>
                            <option value="rabat">Rabat</option>
                            <option value="marrakech">Marrakech</option>
                            <option value="fes">Fès</option>
                            <option value="tanger">Tanger</option>
                            <option value="agadir">Agadir</option>
                        </select>
                    </div>
                    <div>
                        <label for="arrivee" class="block text-sm font-medium text-gray-700 mb-1">Ville d'arrivée</label>
                        <select id="arrivee" name="arrivee" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">Sélectionnez une ville</option>
                            <option value="casablanca">Casablanca</option>
                            <option value="rabat">Rabat</option>
                            <option value="marrakech">Marrakech</option>
                            <option value="fes">Fès</option>
                            <option value="tanger">Tanger</option>
                            <option value="agadir">Agadir</option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                        <input type="date" id="date" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold rounded-lg transition-colors flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
            --}}
        </div>
    </div>

    <!-- Destinations populaires -->
    {{-- Cette section reste pour présenter des trajets --}}
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Destinations populaires</h2>
                {{-- Texte ajusté --}}
                <p class="text-gray-600 max-w-2xl mx-auto">Découvrez nos trajets les plus demandés et réservez facilement en cliquant sur "Réserver"</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Les cartes de destinations populaires restent --}}
                {{-- Assurez-vous que les liens "Réserver" pointent bien vers la page de recherche --}}

                <!-- Destination 1 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-md transition-transform hover:scale-105">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('images/casablanca.jpg') }}" alt="Casablanca" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xl font-semibold text-gray-900">Casablanca - Marrakech</h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Populaire</span>
                        </div>
                        <div class="flex items-center text-gray-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            <span>3h30 de trajet</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-900">120 MAD</span>
                             {{-- Le lien "Réserver" pointe vers la page de recherche avec paramètres --}}
                            <a href="{{ route('voyageur.recherche', ['ville_depart' => 'Casablanca', 'ville_arrivee' => 'Marrakech']) }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors">Réserver</a>
                        </div>
                    </div>
                </div>

                <!-- Destination 2 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-md transition-transform hover:scale-105">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('images/rabat.jpg') }}" alt="Rabat" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xl font-semibold text-gray-900">Rabat - Tanger</h3>
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Promotion</span>
                        </div>
                        <div class="flex items-center text-gray-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            <span>2h45 de trajet</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-900">90 MAD</span>
                             {{-- Le lien "Réserver" pointe vers la page de recherche avec paramètres --}}
                            <a href="{{ route('voyageur.recherche', ['ville_depart' => 'Rabat', 'ville_arrivee' => 'Tanger']) }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors">Réserver</a>
                        </div>
                    </div>
                </div>

                <!-- Destination 3 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-md transition-transform hover:scale-105">
                    <div class="h-48 overflow-hidden">
                        <img src="{{ asset('images/agadir.jpeg') }}" alt="Agadir" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xl font-semibold text-gray-900">Marrakech - Agadir</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Confort+</span>
                        </div>
                        <div class="flex items-center text-gray-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            <span>3h15 de trajet</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-900">150 MAD</span>
                            {{-- Le lien "Réserver" pointe vers la page de recherche avec paramètres --}}
                            <a href="{{ route('voyageur.recherche', ['ville_depart' => 'Marrakech', 'ville_arrivee' => 'Agadir']) }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors">Réserver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nos services -->
    {{-- Cette section reste --}}
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nos services</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">VoyageAI vous offre une expérience de voyage complète avec des services adaptés à tous vos besoins</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service 1 -->
                <div class="bg-gray-50 rounded-xl p-6 text-center shadow-md">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Horaires flexibles</h3>
                    <p class="text-gray-600">Choisissez parmi une large gamme d'horaires pour planifier votre voyage selon vos besoins.</p>
                </div>

                <!-- Service 2 -->
                <div class="bg-gray-50 rounded-xl p-6 text-center shadow-md">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Réservation facile</h3>
                    <p class="text-gray-600">Réservez vos billets en quelques clics, sans paperasse ni files d'attente.</p>
                </div>

                <!-- Service 3 -->
                <div class="bg-gray-50 rounded-xl p-6 text-center shadow-md">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Paiement sécurisé</h3>
                    <p class="text-gray-600">Profitez de méthodes de paiement sécurisées et variées pour régler vos billets.</p>
                </div>

                <!-- Service 4 -->
                <div class="bg-gray-50 rounded-xl p-6 text-center shadow-md">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 text-purple-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Voyages sécurisés</h3>
                    <p class="text-gray-600">Voyagez en toute sécurité avec nos partenaires de transport rigoureusement sélectionnés.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment ça marche -->
    {{-- Cette section reste --}}
    <div id="comment-ca-marche" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Comment ça marche</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Réserver votre prochain voyage n'a jamais été aussi simple</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <!-- Étape 1 -->
                <div class="relative">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-500 text-white font-bold text-xl absolute -top-6 left-1/2 transform -translate-x-1/2">1</div>
                    <div class="bg-white rounded-xl p-6 pt-10 text-center shadow-md h-full">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Recherchez</h3>
                        <p class="text-gray-600">Indiquez votre ville de départ, votre destination et la date souhaitée sur notre page de recherche.</p> {{-- Texte ajusté --}}
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="relative">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-500 text-white font-bold text-xl absolute -top-6 left-1/2 transform -translate-x-1/2">2</div>
                    <div class="bg-white rounded-xl p-6 pt-10 text-center shadow-md h-full">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Réservez</h3>
                        <p class="text-gray-600">Choisissez le trajet qui vous convient et effectuez votre paiement en toute sécurité.</p>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div class="relative">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-500 text-white font-bold text-xl absolute -top-6 left-1/2 transform -translate-x-1/2">3</div>
                    <div class="bg-white rounded-xl p-6 pt-10 text-center shadow-md h-full">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Voyagez</h3>
                        <p class="text-gray-600">Recevez votre billet électronique et présentez-le au chauffeur le jour du départ.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    {{-- Cette section reste --}}
    <div class="py-16 bg-gray-900 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <!-- Stat 1 -->
                <div>
                    <div class="text-4xl font-bold text-yellow-500 mb-2">50+</div>
                    <div class="text-xl font-semibold mb-1">Villes desservies</div>
                    <p class="text-gray-400">Partout au Maroc</p>
                </div>

                <!-- Stat 2 -->
                <div>
                    <div class="text-4xl font-bold text-yellow-500 mb-2">200+</div>
                    <div class="text-xl font-semibold mb-1">Trajets quotidiens</div>
                    <p class="text-gray-400">À des horaires variés</p>
                </div>

                <!-- Stat 3 -->
                <div>
                    <div class="text-4xl font-bold text-yellow-500 mb-2">100K+</div>
                    <div class="text-xl font-semibold mb-1">Voyageurs satisfaits</div>
                    <p class="text-gray-400">Chaque mois</p>
                </div>

                <!-- Stat 4 -->
                <div>
                    <div class="text-4xl font-bold text-yellow-500 mb-2">4.8/5</div>
                    <div class="text-xl font-semibold mb-1">Note moyenne</div>
                    <p class="text-gray-400">Basée sur 10K+ avis</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Témoignages -->
    {{-- Cette section reste --}}
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Ce que disent nos clients</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Découvrez les expériences de nos voyageurs satisfaits</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Témoignage 1 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-gray-300 overflow-hidden mr-4">
                            {{-- Image ou avatar --}}
                            <img src="https://static1.purepeople.com/articles/0/48/73/60/@/7051885-exclusif-karim-bennani-sur-le-platea-1200x0-2.jpg" alt="Photo de profil" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Karim Benali</h4>
                            <div class="flex text-yellow-400">
                                {{-- étoiles --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Service impeccable ! J'utilise VoyageAI régulièrement pour mes déplacements professionnels entre Casablanca et Rabat. La réservation est simple et les bus sont toujours ponctuels."</p>
                </div>

                <!-- Témoignage 2 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-gray-300 overflow-hidden mr-4">
                            {{-- Image ou avatar --}}
                            <img src="https://boursenews.ma/uploads/actualites/60363cf8db4c5.jpg" alt="Photo de profil" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Fatima Zahra</h4>
                            <div class="flex text-yellow-400">
                                {{-- étoiles --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"J'adore la facilité d'utilisation de l'application. En tant qu'étudiante, je voyage souvent entre Marrakech et ma ville natale. Les prix sont abordables et le service client est très réactif."</p>
                </div>

                <!-- Témoignage 3 -->
                <div class="bg-gray-50 rounded-xl p-6 shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-gray-300 overflow-hidden mr-4">
                            {{-- Image ou avatar --}}
                            <img src="https://media.licdn.com/dms/image/v2/C5603AQGktBQ1ldW8kQ/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1516945526331?e=2147483647&v=beta&t=6kD3TTLw_E3fDfIstRTzPb6ngxjWfdXGcJybQ8GkxLw" alt="Photo de profil" class="h-full w-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Ahmed Tazi</h4>
                            <div class="flex text-yellow-400">
                                {{-- étoiles --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Très pratique pour les voyages d'affaires. Les bus sont confortables et bien équipés. J'apprécie particulièrement la possibilité de choisir mon siège à l'avance. Un petit bémol sur la connexion Wi-Fi qui est parfois instable."</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    {{-- Ce call to action reste et le bouton mène à la page de recherche --}}
    <div class="py-16 bg-gray-900 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Prêt à voyager avec nous ?</h2>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">Réservez dès maintenant votre prochain trajet et profitez d'une expérience de voyage exceptionnelle</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                 {{-- Le lien "Rechercher un trajet" pointe vers la page de recherche --}}
                <a href="{{ route('voyageur.recherche') }}" class="px-8 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold rounded-lg transition-colors">
                    Rechercher un trajet
                </a>
                 {{-- Le lien "Créer un compte" reste --}}
                <a href="{{ route('register') }}" class="px-8 py-3 bg-transparent hover:bg-gray-800 border border-white text-white font-semibold rounded-lg transition-colors">
                    Créer un compte
                </a>
            </div>
        </div>
    </div>

    <!-- Partenaires -->
    {{-- Cette section reste --}}
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nos partenaires</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Nous collaborons avec les meilleures compagnies de transport pour vous offrir un service de qualité</p>
            </div>

            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
                 {{-- Logos des partenaires --}}
                <div class="w-32 h-20 flex items-center justify-center  hover:grayscale-0 transition-all">
                    <img src="https://upload.wikimedia.org/wikipedia/ar/9/99/%D8%B4%D8%B9%D8%A7%D8%B1_%D8%B4%D8%B1%D9%83%D8%A9_CTM.png" alt="Logo partenaire" class="max-h-full">
                </div>
                <div class="w-32 h-20 flex items-center justify-center  hover:grayscale-0 transition-all">
                    <img src="https://transghazala.ma/assets/logo.svg" alt="Logo partenaire" class="max-h-full">
                </div>
                <div class="w-32 h-20 flex items-center justify-center   hover:grayscale-0 transition-all">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQlg0FSQJM2oPV2JgG7SESIAxFLzgj4HUkjyg&s" alt="Logo partenaire" class="max-h-full">
                </div>
                <div class="w-32 h-20 flex items-center justify-center  hover:grayscale-0 transition-all">
                    <img src="https://markoub.ma/assets/companies/achkid.webp" alt="Logo partenaire" class="max-h-full">
                </div>
                <div class="w-32 h-20 flex items-center justify-center hover:grayscale-0 transition-all">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbe3mnsOD3hS5YzkKzIdRHPPcm4T141HifBw&s" alt="Logo partenaire" class="max-h-full">
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour la FAQ -->
    {{-- Le script reste car il gère le comportement de la FAQ --}}
    <script>
        function toggleFAQ(element) {
            const content = element.nextElementSibling;
            const icon = element.querySelector('svg');

            // Toggle content visibility
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }
    </script>
@endsection