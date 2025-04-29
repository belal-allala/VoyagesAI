<nav class="bg-gray-200 p-4">
    <div class="container mx-auto flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-xl font-semibold">VoyageAI</a>
        <div>
            <ul class="flex space-x-4">
                <li><a href="{{ route('welcome') }}" class="hover:text-gray-700">Accueil</a></li>
                @auth
                    <li><a href="{{ route('profile') }}" class="hover:text-gray-700">Mon Profil</a></li>
                    {{-- <li><a href="{{ route('bus.search') }}" class="hover:text-gray-700">Recherche de Bus</a></li> --}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-gray-700">Déconnexion</button>
                        </form>
                    </li> 
                    
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-gray-700">Connexion</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-gray-700">Inscription</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
