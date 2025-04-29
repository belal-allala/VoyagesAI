<nav class="sticky top-0 z-50 w-full border-b border-gray-200 bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-xl font-bold text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-purple-600"><path d="M12 4v16"></path><path d="M4 8l8 3 8-3"></path><path d="M4 12l8 3 8-3"></path><path d="M4 16l8 3 8-3"></path></svg>
                VoyageAI
            </a>

            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Ouvrir le menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="{{ route('welcome') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700 transition-colors">Accueil</a>
                
                @auth
                    <a href="{{ route('profile') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700 transition-colors">Mon Profil</a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700 transition-colors">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700 transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-purple-600 px-4 py-2 text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">Inscription</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state -->
    <div class="md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href="{{ route('welcome') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700">Accueil</a>
            
            @auth
                <a href="{{ route('profile') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700">Mon Profil</a>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700">Connexion</a>
                <a href="{{ route('register') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-purple-700">Inscription</a>
            @endauth
        </div>
    </div>
</nav>

@auth
    @if(auth()->user()->role === 'employe')
        <div class="bg-gray-900 text-white shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex items-center overflow-x-auto whitespace-nowrap py-2">
                    <a href="{{ route('employe.dashboard') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Tableau de bord</a>
                    
                    @if(auth()->user()->compagnie)
                        <a href="{{ route('buses.index') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Gestion des Bus</a>
                        <a href="{{ route('chauffeurs.index') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Gestion des chauffeur</a>
                        <a href="{{ route('trajets.create') }}" class="mr-4 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">Gestion des Trajets</a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endauth
