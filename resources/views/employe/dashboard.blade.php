@extends('layouts.app')

@section('title', 'Tableau de bord Employé')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Tableau de bord Employé</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        @if(auth()->user()->compagnie)
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-2">Votre Compagnie</h2>
                <p><strong>Nom:</strong> {{ auth()->user()->compagnie->nom }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->compagnie->email }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Bus</h3>
                    <p class="text-2xl">{{ $buses }}</p>
                    <a href="{{ route('buses.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Ajouter un bus</a>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Trajets</h3>
                    <p class="text-2xl">{{ $trajets }}</p>
                    <a href="{{ route('trajets.create') }}" class="text-green-600 hover:underline mt-2 inline-block">Créer un trajet</a>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Vous n'avez pas encore créé de compagnie. 
                            <a href="{{ route('compagnies.create') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                Créez votre compagnie maintenant
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection