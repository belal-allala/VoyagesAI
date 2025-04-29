@extends('layouts.app')

@section('title', 'Liste des Bus')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gestion des Bus</h1>
            <button onclick="toggleBusForm()" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Ajouter un bus
            </button>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulaire -->
        <div id="busFormContainer" class="hidden mb-6 bg-gray-50 p-4 rounded-lg transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4">Ajouter un nouveau bus</h2>
            <form method="POST" action="{{ route('buses.store') }}" id="busForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom du bus*</label>
                        <input type="text" id="name" name="name" 
                               class="w-full px-3 py-2 border rounded shadow appearance-none"
                               required minlength="3" maxlength="50">
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="capacity" class="block text-gray-700 text-sm font-bold mb-2">Capacité*</label>
                        <input type="number" id="capacity" name="capacity" min="1" max="100"
                               class="w-full px-3 py-2 border rounded shadow appearance-none"
                               required>
                        @error('capacity')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="plate_number" class="block text-gray-700 text-sm font-bold mb-2">Immatriculation*</label>
                    <input type="text" id="plate_number" name="plate_number"
                           class="w-full px-3 py-2 border rounded shadow appearance-none"
                           required pattern="[A-Za-z0-9]{6,10}">
                    @error('plate_number')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="button" onclick="toggleBusForm()" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 border text-left">Nom</th>
                        <th class="py-3 px-4 border text-left">Capacité</th>
                        <th class="py-3 px-4 border text-left">Immatriculation</th>
                        <th class="py-3 px-4 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buses as $bus)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border">{{ $bus->name }}</td>
                            <td class="py-3 px-4 border">{{ $bus->capacity }} places</td>
                            <td class="py-3 px-4 border">{{ $bus->plate_number }}</td>
                            <td class="py-3 px-4 border">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm mr-2">
                                    Modifier
                                </button>
                                <button class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded text-sm">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 border text-center text-gray-500">
                                Aucun bus enregistré
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleBusForm() {
        const form = document.getElementById('busFormContainer');
        const button = document.querySelector('button[onclick="toggleBusForm()"]');
        
        form.classList.toggle('hidden');
        
        if (form.classList.contains('hidden')) {
            button.textContent = 'Ajouter un bus';
            button.classList.replace('bg-gray-500', 'bg-blue-500');
        } else {
            button.textContent = 'Masquer le formulaire';
            button.classList.replace('bg-blue-500', 'bg-gray-500');
        }
    }

    // Soumission AJAX optionnelle
    document.getElementById('busForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                window.location.reload();
            } else {
                const data = await response.json();
                alert(data.message || 'Erreur lors de la soumission');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Erreur réseau');
        }
    });
</script>
@endsection