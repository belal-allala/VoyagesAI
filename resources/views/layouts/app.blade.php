<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VoyageAI')</title> 
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
</head>
<body class="bg-gray-100"> 
    <div class="container mx-auto"> 
        <header class="bg-gray-200 p-4">
            <nav>
                @auth
                    <a href="{{ url('/profile') }}">Mon Profil</a> |
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}">Connexion</a> |
                    <a href="{{ url('/register') }}">Inscription</a>
                @endauth
            </nav>
        </header>

        <main class="p-4">
            @yield('content') 
        </main>

        <footer class="bg-gray-200 p-4 text-center">
            <p>© {{ date('Y') }} VoyageAI - Tous droits réservés</p>
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>