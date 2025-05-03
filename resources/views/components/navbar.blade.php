<nav class="sticky top-0 z-50 w-full bg-gray-900 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-xl font-bold text-gray-900">
                <img src="{{ asset('images/logo.png') }}" alt="Logo VoyageAI" class="h-[100px] w-auto">  
            </a>

            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-300 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-yellow-500" aria-controls="mobile-menu" aria-expanded="false" onclick="toggleMobileMenu()">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="{{ route('welcome') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Accueil</a>
                
                @auth
                    <a href="{{ route('profile') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Mon Profil</a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-yellow-500 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors">Inscription</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state -->
    <div class="hidden md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href="{{ route('welcome') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">Accueil</a>
            
            @auth
                <a href="{{ route('profile') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">Mon Profil</a>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">Connexion</a>
                <a href="{{ route('register') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-800 hover:text-white">Inscription</a>
            @endauth
        </div>
    </div>
</nav>

@auth
    @if(auth()->user()->role === 'employe')
        <div class="bg-gray-800 text-white shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex items-center overflow-x-auto whitespace-nowrap py-2">
                    <a href="{{ route('employe.dashboard') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Tableau de bord
                    </a>
                    
                    @if(auth()->user()->compagnie)
                        <a href="{{ route('buses.index') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v7M2 11h20M4 11v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-9M12 2v7M10 7l2-2 2 2"></path></svg>
                            Gestion des Bus
                        </a>
                        <a href="{{ route('chauffeurs.index') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Gestion des chauffeur
                        </a>
                        <a href="{{ route('trajets.index') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18M3 6h18M3 18h18"></path></svg>
                            Gestion des Trajets
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role === 'voyageur')  
        <div class="bg-gray-800 text-white shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex items-center overflow-x-auto whitespace-nowrap py-2">
                    <a href="{{ route('voyageur.recherche') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        Rechercher un trajet
                    </a>
                    <a href="{{ route('voyageur.reservations') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        Mes Réservations
                    </a> 
                   <a href="{{ route('voyageur.recherche') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"></path></svg>
                        Historique des Réservations
                    </a> 
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role === 'chauffeur')
        <div class="bg-gray-800 text-white shadow-md">  
            <div class="container mx-auto px-4">
                <div class="flex items-center overflow-x-auto whitespace-nowrap py-2">
                    <a href="{{ route('chauffeur.trajets') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Trajets Assignés
                    </a>  
                    <a href="{{ route('chauffeur.passagers') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        Liste des passagers
                    </a>  
                    <a href="{{ route('chauffeur.scan') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path><rect x="7" y="7" width="10" height="10" rx="2"></rect></svg>
                        Scanner Billet
                    </a>
                </div>
            </div>
        </div>
    @endif
@endauth

<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
}
</script>
