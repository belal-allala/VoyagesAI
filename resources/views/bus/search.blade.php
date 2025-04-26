@extends('layouts.app')

@section('title', 'Recherche de bus')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-3xl font-semibold mb-4">Recherche de bus</h1>

        <form method="GET" action="{{ route('bus.search') }}" class="mb-4">
            <div class="mb-4">
                <label for="departure_city" class="block text-gray-700 text-sm font-bold mb-2">Ville de départ:</label>
                <input type="text" id="departure_city" name="departure_city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ request('departure_city') }}">
            </div>

            <div class="mb-4">
                <label for="destination_city" class="block text-gray-700 text-sm font-bold mb-2">Ville de destination:</label>
                <input type="text" id="destination_city" name="destination_city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ request('destination_city') }}">
            </div>

            <div class="mb-4">
                <label for="departure_time" class="block text-gray-700 text-sm font-bold mb-2">Date et heure de départ:</label>
                <input type="datetime-local" id="departure_time" name="departure_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ request('departure_time') }}">
            </div>

            <div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Rechercher</button>
            </div>
        </form>

        @if ($buses && count($buses) > 0)
            <h2 class="text-2xl font-semibold mb-2">Résultats de la recherche</h2>
            <ul class="list-disc list-inside">
                @foreach ($buses as $bus)
                    <li>
                        {{ $bus->departure_city }} à {{ $bus->destination_city }} - {{ $bus->departure_time }} - Prix: {{ $bus->price }} €
                    </li>
                @endforeach
            </ul>
        @elseif(request()->has('departure_city') || request()->has('destination_city') || request()->has('departure_time'))
            <p>Aucun bus trouvé pour votre recherche.</p>
        @endif
    </div>
@endsection