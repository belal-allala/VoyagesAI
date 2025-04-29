@extends('layouts.app')

@section('title', 'Modifier un Trajet')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Modifier le trajet: {{ $trajet->name }}</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('trajets.update', $trajet) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nom du trajet*</label>
                    <input type="text" name="name" value="{{ old('name', $trajet->name) }}" required
                           class="w-full px-3 py-2 border rounded shadow appearance-none">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Bus*</label>
                    <select name="bus_id" required class="w-full px-3 py-2 border rounded shadow appearance-none">
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ $bus->id == $trajet->bus_id ? 'selected' : '' }}>
                                {{ $bus->name }} ({{ $bus->plate_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Chauffeur*</label>
                    <select name="chauffeur_id" required class="w-full px-3 py-2 border rounded shadow appearance-none">
                        @foreach($chauffeurs as $chauffeur)
                            <option value="{{ $chauffeur->id }}" {{ $chauffeur->id == $trajet->chauffeur_id ? 'selected' : '' }}>
                                {{ $chauffeur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Sous-trajets -->
            <div id="sousTrajetsContainer" class="space-y-4 mb-4">
                @foreach($trajet->sousTrajets as $index => $sousTrajet)
                <div class="sousTrajet p-4 border rounded-lg bg-white">
                    <input type="hidden" name="sous_trajets[{{ $index }}][id]" value="{{ $sousTrajet->id }}">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-medium">Étape {{ $index + 1 }}</h3>
                        <button type="button" class="removeStep text-red-500 hover:text-red-700">
                            × Supprimer
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ville de départ*</label>
                            <input type="text" name="sous_trajets[{{ $index }}][departure_city]" 
                                   value="{{ old("sous_trajets.$index.departure_city", $sousTrajet->departure_city) }}"
                                   required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ville d'arrivée*</label>
                            <input type="text" name="sous_trajets[{{ $index }}][destination_city]"
                                   value="{{ old("sous_trajets.$index.destination_city", $sousTrajet->destination_city) }}"
                                   required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Heure de départ*</label>
                            <input type="datetime-local" name="sous_trajets[{{ $index }}][departure_time]"
                                   value="{{ old("sous_trajets.$index.departure_time", $sousTrajet->departure_time->format('Y-m-d\TH:i')) }}"
                                   required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Heure d'arrivée*</label>
                            <input type="datetime-local" name="sous_trajets[{{ $index }}][arrival_time]"
                                   value="{{ old("sous_trajets.$index.arrival_time", $sousTrajet->arrival_time->format('Y-m-d\TH:i')) }}"
                                   required class="w-full px-3 py-2 border rounded">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Prix (MAD)*</label>
                            <input type="number" step="0.01" min="0" 
                                   name="sous_trajets[{{ $index }}][price]"
                                   value="{{ old("sous_trajets.$index.price", $sousTrajet->price) }}"
                                   required class="w-full px-3 py-2 border rounded">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="flex items-center justify-between">
                <button type="button" id="addSousTrajet"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Ajouter une étape
                </button>
                
                <div>
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Mettre à jour
                    </button>
                    <a href="{{ route('trajets.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('sousTrajetsContainer');
    const addButton = document.getElementById('addSousTrajet');
    let stepCount = {{ $trajet->sousTrajets->count() }};
    
    function addSousTrajet() {
        const index = stepCount++;
        const html = `
        <div class="sousTrajet p-4 mb-4 border rounded-lg bg-white">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-medium">Étape ${index + 1}</h3>
                <button type="button" class="removeStep text-red-500 hover:text-red-700">
                    × Supprimer
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ville de départ*</label>
                    <input type="text" name="sous_trajets[${index}][departure_city]" required
                           class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ville d'arrivée*</label>
                    <input type="text" name="sous_trajets[${index}][destination_city]" required
                           class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Heure de départ*</label>
                    <input type="datetime-local" name="sous_trajets[${index}][departure_time]" required
                           class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Heure d'arrivée*</label>
                    <input type="datetime-local" name="sous_trajets[${index}][arrival_time]" required
                           class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Prix (MAD)*</label>
                    <input type="number" step="0.01" min="0" 
                           name="sous_trajets[${index}][price]" required
                           class="w-full px-3 py-2 border rounded">
                </div>
            </div>
        </div>`;
        
        container.insertAdjacentHTML('beforeend', html);
    }
    
    addButton.addEventListener('click', addSousTrajet);
    
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeStep')) {
            if (confirm('Supprimer cette étape ?')) {
                e.target.closest('.sousTrajet').remove();
                stepCount--;
            }
        }
    });
});
</script>
@endsection