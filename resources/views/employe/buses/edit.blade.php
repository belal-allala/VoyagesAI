@extends('layouts.app')

@section('title', 'Modifier un Bus')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Modifier le bus</h1>
        
        <form method="POST" action="{{ route('buses.update', $bus) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom du bus*</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $bus->name) }}"
                           class="w-full px-3 py-2 border rounded shadow appearance-none"
                           required minlength="3" maxlength="50">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="capacity" class="block text-gray-700 text-sm font-bold mb-2">Capacité*</label>
                    <input type="number" id="capacity" name="capacity" 
                           value="{{ old('capacity', $bus->capacity) }}"
                           class="w-full px-3 py-2 border rounded shadow appearance-none"
                           required min="1" max="100">
                    @error('capacity')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label for="plate_number" class="block text-gray-700 text-sm font-bold mb-2">Immatriculation*</label>
                <input type="text" id="plate_number" name="plate_number" 
                       value="{{ old('plate_number', $bus->plate_number) }}"
                       class="w-full px-3 py-2 border rounded shadow appearance-none"
                       required pattern="[A-Za-z0-9]{6,10}">
                @error('plate_number')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Mettre à jour
                </button>
                <a href="{{ route('buses.index') }}" class="text-gray-600 hover:text-gray-800">
                    Retour à la liste
                </a>
            </div>
        </form>
    </div>
</div>
@endsection