@extends('layouts.app')

@section('title', 'Ajouter un Chauffeur')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-500 py-4 px-6">
            <h1 class="text-xl font-semibold text-white">Rechercher un Chauffeur</h1>
        </div>
        
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('chauffeurs.search') }}" method="GET">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                        Adresse Email du Chauffeur
                    </label>
                    <input type="email" id="email" name="email" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           placeholder="email@exemple.com">
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Rechercher
                    </button>
                    
                    <a href="{{ route('employe.dashboard') }}" 
                       class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection