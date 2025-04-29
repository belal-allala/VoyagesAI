@extends('layouts.app')
@section('title', 'Créer un Trajet')
@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Créer un nouveau trajet</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire Principal (DOIT être visible) -->
        <form id="trajetForm" method="POST" action="{{ route('trajets.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Champs de base -->
                <div>
                    <label class="block text-gray-700">Nom du trajet*</label>
                    <input type="text" name="name" required
                           class="w-full px-3 py-2 border rounded">
                </div>
                
                <div>
                    <label class="block text-gray-700">Bus*</label>
                    <select name="bus_id" required class="w-full px-3 py-2 border rounded">
                        <option value="">Sélectionner un bus</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700">Chauffeur*</label>
                    <select name="chauffeur_id" required class="w-full px-3 py-2 border rounded">
                        <option value="">Sélectionner un chauffeur</option>
                        @foreach($chauffeurs as $chauffeur)
                            <option value="{{ $chauffeur->id }}">{{ $chauffeur->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Container pour les sous-trajets (visible mais peut être vide) -->
            <div id="sousTrajetsContainer" class="space-y-4">
                <!-- Les sous-trajets seront ajoutés ici dynamiquement -->
            </div>

            <!-- Bouton pour ajouter des étapes -->
            <button type="button" id="addSousTrajet" 
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                + Ajouter une étape
            </button>

            <!-- Bouton de soumission -->
            <div class="pt-4">
                <button type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded">
                    Créer le trajet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('sousTrajetsContainer');
        const addButton = document.getElementById('addSousTrajet');
        let stepCount = 0;
    
        // Fonction pour ajouter un sous-trajet
        function addSousTrajet() {
            const stepIndex = stepCount++;
            const stepHTML = `
            <div class="sousTrajet bg-gray-50 p-4 rounded-lg border">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-medium">Étape ${stepIndex + 1}</h3>
                    <button type="button" class="removeStep text-red-500">× Supprimer</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">Ville de départ*</label>
                        <input type="text" name="sous_trajets[${stepIndex}][departure_city]" required
                               class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Ville d'arrivée*</label>
                        <input type="text" name="sous_trajets[${stepIndex}][destination_city]" required
                               class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Heure de départ*</label>
                        <input type="datetime-local" name="sous_trajets[${stepIndex}][departure_time]" required
                               class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Heure d'arrivée*</label>
                        <input type="datetime-local" name="sous_trajets[${stepIndex}][arrival_time]" required
                               class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700">Prix (MAD)*</label>
                        <input type="number" step="0.01" min="0" name="sous_trajets[${stepIndex}][price]" required
                               class="w-full px-3 py-2 border rounded">
                    </div>
                </div>
            </div>`;
            
            container.insertAdjacentHTML('beforeend', stepHTML);
        }
    
        // Gestion des événements
        addButton.addEventListener('click', addSousTrajet);
        
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeStep')) {
                e.target.closest('.sousTrajet').remove();
                stepCount--;
            }
        });
    
        // Ajouter un premier sous-trajet par défaut
        addSousTrajet();
    });
    </script>

    @endsection