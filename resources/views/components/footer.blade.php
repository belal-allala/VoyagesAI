<footer class="bg-gray-900 text-white pt-16 pb-8">
    <div class="container mx-auto px-4">
        <!-- Section principale du footer -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Colonne 1: À propos -->
            <div>
                <div class="flex items-center mb-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 text-xl font-bold text-gray-900">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo VoyageAI" class="h-[100px] w-auto">  
                    </a>
                </div>
                <p class="text-gray-400 mb-4">VoyageAI est une plateforme innovante qui simplifie vos déplacements en bus à travers le Maroc. Réservez facilement, voyagez confortablement.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Colonne 2: Liens rapides -->
            <div>
                <h3 class="text-lg font-semibold mb-6">Liens rapides</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-yellow-500 transition-colors">Accueil</a></li>
                    <li><a href="{{ route('voyageur.recherche') }}" class="text-gray-400 hover:text-yellow-500 transition-colors">Rechercher un trajet</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Destinations populaires</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Nos services</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">À propos de nous</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Contactez-nous</a></li>
                </ul>
            </div>

            <!-- Colonne 3: Informations utiles -->
            <div>
                <h3 class="text-lg font-semibold mb-6">Informations utiles</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">FAQ</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Comment ça marche</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Conditions de remboursement</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Politique de bagages</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Nos partenaires</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Blog voyage</a></li>
                </ul>
            </div>

            <!-- Colonne 4: Contact et newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-6">Contactez-nous</h3>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-3 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-400">123 Avenue Mohammed V, Casablanca, Maroc</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span class="text-gray-400">+212 522 123 456</span>
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <span class="text-gray-400">contact@voyageai.ma</span>
                    </li>
                </ul>

                <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                <p class="text-gray-400 mb-4">Inscrivez-vous pour recevoir nos offres spéciales</p>
                <form action="#" method="POST" class="flex">
                    <input type="email" name="email" placeholder="Votre email" required class="px-4 py-2 w-full rounded-l-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold px-4 py-2 rounded-r-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Séparateur -->
        <div class="border-t border-gray-800 my-8"></div>

        <!-- Section du bas -->
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-400 mb-4 md:mb-0">
                <p>&copy; {{ date('Y') }} VoyageAI - Tous droits réservés</p>
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Politique de confidentialité</a>
                <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Conditions d'utilisation</a>
                <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Mentions légales</a>
            </div>
        </div>
    </div>
</footer>
