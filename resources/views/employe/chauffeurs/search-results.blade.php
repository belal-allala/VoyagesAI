@extends('layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-6">Chauffeur trouvé</h1>
        
        <div class="mb-4 p-4 border rounded-lg">
            <div class="flex items-center mb-2">
                <div class="font-semibold">Nom:</div>
                <div class="ml-2">{{ $chauffeur->nom }}</div>
            </div>
            <div class="flex items-center">
                <div class="font-semibold">Email:</div>
                <div class="ml-2">{{ $chauffeur->email }}</div>
            </div>
        </div>
        
        <form action="{{ route('chauffeurs.attach', $chauffeur) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Ajouter à ma compagnie
            </button>
        </form>
        
        <a href="{{ route('chauffeurs.search') }}" 
           class="block text-center mt-4 text-blue-500 hover:text-blue-700">
            Nouvelle recherche
        </a>
    </div>
</div>
@endsection