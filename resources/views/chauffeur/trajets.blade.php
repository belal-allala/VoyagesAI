@extends('layouts.app')

@section('title', 'Trajets Assignés')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Trajets Assignés</h1>
            <p class="text-gray-500">Consultez et gérez vos trajets assignés</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 flex items-start shadow-sm">
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtrer les trajets</h2>
                <form method="GET" action="{{ route('chauffeur.trajets') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-full sm:w-auto">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="date" name="date" value="{{ $filterDate }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex items-center gap-2 mt-6 sm:mt-0">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Appliquer
                        </button>
                        @if($filterDate !== now()->format('Y-m-d'))
                            <a href="{{ route('chauffeur.trajets') }}" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                                Aujourd'hui
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($trajets->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun trajet trouvé</h3>
                <p class="text-gray-500">Aucun trajet assigné pour la date sélectionnée.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom du Trajet</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de Départ</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure de Départ</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($trajets as $trajet)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $trajet->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $trajet->firstDepartureTime()->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $trajet->firstDepartureTime()->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $trajet->bus->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('chauffeur.passagers', $trajet) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium transition-colors flex items-center">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Passagers
                                            </a>
                                            <button onclick="showTrajetDetails({{ $trajet->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 font-medium transition-colors flex items-center">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Détails
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Modal pour les détails du trajet -->
    <div id="trajetDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
        <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col transform transition-transform duration-300 scale-95">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-xl font-bold text-gray-900" id="trajetDetailsTitle"></h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-grow" id="trajetDetailsContent">
                <!-- Détails du trajet -->
            </div>
            <div class="flex justify-end px-6 py-4 border-t bg-gray-50">
                <button onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    
    <script>
    // Fonction pour afficher les détails d'un trajet
    function showTrajetDetails(trajetId) {
        const modal = document.getElementById('trajetDetailsModal');
        
        // Afficher le loader
        document.getElementById('trajetDetailsContent').innerHTML = `
            <div class="flex justify-center items-center py-16">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        `;
        
        // Afficher le modal avec animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.relative').classList.remove('scale-95');
        }, 10);
        
        // Charger les données via AJAX
        fetch(`/trajets/${trajetId}/details`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour le titre
                document.getElementById('trajetDetailsTitle').textContent = data.name;
                
                // Construire le contenu HTML
                let html = `
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                    </svg>
                                    <h4 class="font-semibold text-gray-900">Informations du Bus</h4>
                                </div>
                                <div class="ml-7 space-y-2">
                                    <p class="text-sm text-gray-700"><span class="font-medium">Nom:</span> ${data.bus.name}</p>
                                    <p class="text-sm text-gray-700"><span class="font-medium">Immatriculation:</span> ${data.bus.plate_number}</p>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <h4 class="font-semibold text-gray-900">Chauffeur</h4>
                                </div>
                                <div class="ml-7 space-y-2">
                                    <p class="text-sm text-gray-700"><span class="font-medium">Nom:</span> ${data.chauffeur.nom}</p>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h4 class="font-semibold text-gray-900">Type de Trajet</h4>
                                </div>
                                <div class="ml-7 space-y-2">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Type:</span> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.is_recurring ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                            ${data.is_recurring ? 'Récurrent' : 'Ponctuel'}
                                        </span>
                                    </p>
                                    ${data.is_recurring ? `
                                    <p class="text-sm text-gray-700"><span class="font-medium">Périodicité:</span> ${data.recurring_pattern.recurrence_description}</p>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                <h4 class="text-lg font-semibold text-gray-900">Étapes du trajet</h4>
                            </div>
                            
                            <div class="space-y-6">
                `;
                
                // Ajouter les étapes
                data.sous_trajets.forEach((etape, index) => {
                    html += `
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full text-xs font-bold mr-2">
                                        ${index + 1}
                                    </div>
                                    <h5 class="font-medium text-gray-900">${etape.departure_city} → ${etape.destination_city}</h5>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    ${etape.price} MAD
                                </span>
                            </div>
                            
                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm">
                                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="h-4 w-4 text-green-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                        </svg>
                                        Départ
                                    </h6>
                                    <div class="ml-6 space-y-3">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.departure_time}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.departure_city}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-100 rounded-lg p-4 shadow-sm">
                                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="h-4 w-4 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                                        </svg>
                                        Arrivée
                                    </h6>
                                    <div class="ml-6 space-y-3">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.arrival_time}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-700">${etape.destination_city}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                            </div>
                        </div>
                    </div>
                `;
                
                // Mettre à jour le contenu
                document.getElementById('trajetDetailsContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('trajetDetailsContent').innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 px-6">
                        <svg class="h-12 w-12 text-red-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Erreur de chargement</h3>
                        <p class="text-gray-500 text-center">Une erreur s'est produite lors du chargement des détails du trajet.</p>
                    </div>
                `;
            });
    }
    
    // Fonction pour fermer le modal
    function closeModal() {
        const modal = document.getElementById('trajetDetailsModal');
        
        // Animer la fermeture
        modal.classList.add('opacity-0');
        modal.querySelector('.relative').classList.add('scale-95');
        
        // Cacher après l'animation
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('trajetDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('trajetDetailsModal').classList.contains('hidden')) {
            closeModal();
        }
    });
    </script>
@endsection