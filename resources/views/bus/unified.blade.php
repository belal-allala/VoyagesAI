@extends('layouts.app')

@section('title', 'Mes Bus')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Gestion de mes Bus</h1>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Formulaire CRUD -->
        <div class="md:w-1/3 bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">
                {{ $selectedBus ? 'Modifier Bus' : 'Ajouter un Bus' }}
            </h2>
            
            <form method="POST" action="{{ route('buses.handle') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $selectedBus?->id }}">

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $selectedBus?->name) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="capacity" class="block text-gray-700 text-sm font-bold mb-2">Capacité:</label>
                    <input type="number" id="capacity" name="capacity" min="1" 
                           value="{{ old('capacity', $selectedBus?->capacity) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ $selectedBus ? 'Mettre à jour' : 'Ajouter' }}
                    </button>
                    
                    @if($selectedBus)
                        <a href="{{ route('buses.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Liste des bus -->
        <div class="md:w-2/3">
            <div class="bg-white shadow-md rounded">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nom</th>
                            <th class="py-3 px-6 text-center">Capacité</th>
                            <th class="py-3 px-6 text-center">Sièges disponibles</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @forelse($buses as $bus)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left">{{ $bus->name }}</td>
                            <td class="py-3 px-6 text-center">{{ $bus->capacity }}</td>
                            <td class="py-3 px-6 text-center">{{ $bus->available_seats }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="{{ route('buses.index', ['edit' => $bus->id]) }}" 
                                       class="text-blue-500 hover:text-blue-700" title="Modifier">
                                       ✏️
                                    </a>
                                    <form action="{{ route('buses.destroy', $bus) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer"
                                                onclick="return confirm('Supprimer ce bus?')">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 px-6 text-center">Aucun bus enregistré</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $buses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection