<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VoyageAI')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gray-100">
    <div class="container mx-auto">
        <x-navbar />

        <main class="p-4">
            @yield('content') 
        </main>

        <x-footer /> 
    </div>

    <script src="{{ asset('js/app.js') }}"></script> 
</body>
</html>