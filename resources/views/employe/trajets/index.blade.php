@extends('layouts.app')

@section('title', 'Gestion des Trajets')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Gestion des Trajets</h1>
        
        <!-- Messages de statut -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Section Création -->
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Créer un nouveau trajet</h2>
            
            <form id="trajetForm" method="POST" action="{{ route('trajets.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nom du trajet*</label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 border rounded shadow appearance-none">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Bus*</label>
                        <select name="bus_id" required
                                class="w-full px-3 py-2 border rounded shadow appearance-none">
                            <option value="">Sélectionnez</option>
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}">
                                    {{ $bus->name }} ({{ $bus->plate_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Chauffeur*</label>
                        <select name="chauffeur_id" required
                                class="w-full px-3 py-2 border rounded shadow appearance-none">
                            <option value="">Sélectionnez</option>
                            @foreach($chauffeurs as $chauffeur)
                                <option value="{{ $chauffeur->id }}">
                                    {{ $chauffeur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Sous-trajets -->
                <div id="sousTrajetsContainer" class="space-y-4 mb-4">
                    <!-- Étapes seront ajoutées ici -->
                </div>

                <div class="mb-6 p-4 border rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Options de récurrence</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Type de récurrence</label>
                        <select name="recurring_type" id="recurringType" 
                                class="w-full px-3 py-2 border rounded">
                            <option value="">Aucune récurrence</option>
                            <option value="daily">Quotidien</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly">Mensuel</option>
                        </select>
                    </div>
                    
                    <div id="recurringOptions" class="hidden space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Intervalle</label>
                            <input type="number" name="recurring_interval" min="1" value="1" 
                                   class="w-full px-3 py-2 border rounded">
                            <p class="text-xs text-gray-500 mt-1">Ex: 2 = Tous les 2 jours/semaines/mois</p>
                        </div>
                        
                        <div id="weeklyOptions" class="hidden">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jours de la semaine</label>
                            <div class="grid grid-cols-7 gap-2">
                                @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $index => $day)
                                <label class="flex items-center">
                                    <input type="checkbox" name="days_of_week[]" value="{{ $index + 1 }}" class="mr-2">
                                    {{ $day }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Date de début*</label>
                                <input type="date" name="start_date" min="{{ date('Y-m-d') }}" 
                                       class="w-full px-3 py-2 border rounded">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Date de fin (optionnel)</label>
                                <input type="date" name="end_date" 
                                       class="w-full px-3 py-2 border rounded">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="button" id="addSousTrajet"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Ajouter une étape
                    </button>
                    
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Enregistrer le trajet
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Liste des trajets -->
        <div>
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Liste des trajets</h2>
                    
                    <!-- Filtre par date -->
                    <form method="GET" action="{{ route('trajets.index') }}" class="flex items-center">
                        <label for="date" class="mr-2 text-sm font-medium text-gray-700">Filtrer par date :</label>
                        <input type="date" id="date" name="date" value="{{ $filterDate }}"
                               class="px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                        <button type="submit" 
                                class="ml-2 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Filtrer
                        </button>
                        @if($filterDate !== now()->format('Y-m-d'))
                            <a href="{{ route('trajets.index') }}" 
                               class="ml-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Aujourd'hui
                            </a>
                        @endif
                    </form>
                </div>
            
            @if($trajets->isEmpty())
                <p class="text-gray-500">Aucun trajet enregistré</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">Date de départ</th>
                                <th class="py-3 px-4 text-left">Nom</th>
                                <th class="py-3 px-4 text-left">Bus</th>
                                <th class="py-3 px-4 text-left">Chauffeur</th>
                                <th class="py-3 px-4 text-left">Récurrence</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trajets as $trajet)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    {{ $trajet->sousTrajets->first()->departure_time->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-4">{{ $trajet->name }}</td>
                                <td class="py-3 px-4">{{ $trajet->bus->name }}</td>
                                <td class="py-3 px-4">{{ $trajet->chauffeur->nom }}</td>
                                <td class="py-3 px-4">
                                    @if($trajet->is_recurring)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $trajet->recurringPattern->recurrence_description }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Ponctuel</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="showTrajetDetails({{ $trajet->id }})" 
                                                class="text-purple-600 hover:text-purple-800 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Détails
                                        </button>
                                        <a href="{{ route('trajets.edit', $trajet) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Modifier
                                        </a>
                                        <form action="{{ route('trajets.destroy', $trajet) }}" method="POST"
                                              onsubmit="return confirm('Supprimer ce trajet ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 font-medium">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour les détails du trajet -->
<div id="trajetDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-xl font-bold text-gray-900" id="trajetDetailsTitle"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mt-4" id="trajetDetailsContent">
            <!-- Le contenu sera chargé dynamiquement ici -->
        </div>
        
        <div class="mt-4 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('sousTrajetsContainer');
    const addButton = document.getElementById('addSousTrajet');
    
    if (container && addButton) {
        let stepCount = container.querySelectorAll('.sousTrajet').length;
        
        function addSousTrajet() {
            const index = stepCount++;
            const html = `
            <div class="sousTrajet p-4 border rounded-lg bg-white mb-4">
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
                               class="w-full px-3 py-2 border rounded" >
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Heure d'arrivée*</label>
                        <input type="datetime-local" name="sous_trajets[${index}][arrival_time]" required
                               class="w-full px-3 py-2 border rounded" ">
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
        
        addButton.addEventListener('click', function(e) {
            e.preventDefault();
            addSousTrajet();
        });
        
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeStep')) {
                e.preventDefault();
                e.target.closest('.sousTrajet').remove();
                stepCount--;
            }
        });
        
        if (stepCount === 0) {
            addSousTrajet();
        }
    }

    // Gestion des options de récurrence
    const recurringType = document.getElementById('recurringType');
    if (recurringType) {
        recurringType.addEventListener('change', function() {
            const recurringOptions = document.getElementById('recurringOptions');
            const weeklyOptions = document.getElementById('weeklyOptions');
            
            if (this.value) {
                recurringOptions.classList.remove('hidden');
                weeklyOptions.classList.toggle('hidden', this.value !== 'weekly');
            } else {
                recurringOptions.classList.add('hidden');
            }
        });
    }
});

    // Fonction pour afficher les détails d'un trajet
    function showTrajetDetails(trajetId) {
        document.getElementById('trajetDetailsContent').innerHTML = `
            <div class="flex justify-center py-8">
                <svg class="animate-spin h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;
        document.getElementById('trajetDetailsModal').classList.remove('hidden');
        
        fetch(`/trajets/${trajetId}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('trajetDetailsTitle').textContent = `Détails du trajet: ${data.name}`;
                let html = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-700">Bus:</h4>
                            <p>${data.bus.name} (${data.bus.plate_number})</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700">Chauffeur:</h4>
                            <p>${data.chauffeur.nom}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700">Type:</h4>
                            <p>${data.is_recurring ? 'Récurrent' : 'Ponctuel'}</p>
                        </div>
                        ${data.is_recurring ? `
                        <div>
                            <h4 class="font-semibold text-gray-700">Périodicité:</h4>
                            <p>${data.recurring_pattern.recurrence_description}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="border-t pt-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Étapes du trajet:</h4>
                        <div class="space-y-4">
                `;
                data.sous_trajets.forEach((etape, index) => {
                    html += `
                        <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="font-medium text-lg">Étape ${index + 1}: ${etape.departure_city} → ${etape.destination_city}</h5>
                                <span class="text-sm font-semibold bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                    ${etape.price} MAD
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white p-3 rounded border">
                                    <h6 class="font-semibold text-gray-700 mb-1">Départ</h6>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>${etape.departure_time}</span>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>${etape.departure_city}</span>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-3 rounded border">
                                    <h6 class="font-semibold text-gray-700 mb-1">Arrivée</h6>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>${etape.arrival_time}</span>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>${etape.destination_city}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
                document.getElementById('trajetDetailsContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('trajetDetailsContent').innerHTML = `
                    <div class="text-red-500 text-center py-8">
                        Une erreur s'est produite lors du chargement des détails du trajet.
                    </div>
                `;
            });
    }
    function closeModal() {
        document.getElementById('trajetDetailsModal').classList.add('hidden');
    }
    </script>


@endsection