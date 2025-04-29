@extends('layouts.app')

@section('title', 'Modifier un Bus')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 flex items-center">
            <a href="{{ route('buses.index') }}" class="mr-4 flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-500 shadow-sm hover:text-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M19 12H5"></path><path d="M12 19l-7-7 7-7"></path></svg>
                <span class="sr-only">Retour</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Modifier le bus</h1>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-purple-600 mr-2"><rect x="2" y="8" width="20" height="12" rx="2" ry="2"></rect><path d="M6 8V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"></path><line x1="2" y1="12" x2="22" y2="12"></line></svg>
                    <h2 class="text-lg font-semibold text-gray-900">Informations du bus</h2>
                </div>
            </div>
            
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('buses.update', $bus) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du bus*</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $bus->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                   required minlength="3" maxlength="50">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacité*</label>
                            <input type="number" id="capacity" name="capacity" 
                                   value="{{ old('capacity', $bus->capacity) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                                   required min="1" max="100">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="plate_number" class="block text-sm font-medium text-gray-700 mb-1">Immatriculation*</label>
                        <input type="text" id="plate_number" name="plate_number" 
                               value="{{ old('plate_number', $bus->plate_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                               required pattern="[A-Za-z0-9]{6,10}">
                        <p class="mt-1 text-xs text-gray-500">Format: 6-10 caractères alphanumériques</p>
                        @error('plate_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('buses.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                Enregistrer les modifications
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-red-600 mr-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <h2 class="text-lg font-semibold text-gray-900">Zone de danger</h2>
                </div>
            </div>
            <div class="px-6 py-5">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Supprimer ce bus</h3>
                <p class="text-sm text-gray-500 mb-4">Une fois supprimé, toutes les données associées à ce bus seront définitivement effacées.</p>
                <form action="{{ route('buses.destroy', $bus) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bus?')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Supprimer ce bus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
