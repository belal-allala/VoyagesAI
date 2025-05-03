@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Gestion des Utilisateurs</h1>

        <!-- Section Barre de recherche et filtre -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex-grow">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher par email</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par rôle</label>
                    <select id="role" name="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="all">Tous les rôles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors duration-200">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Section Tableau des utilisateurs -->
        <div class="bg-white rounded-xl shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compagnie</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 editable" data-field="nom" data-user-id="{{ $user->id }}">{{ $user->nom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 editable" data-field="email" data-user-id="{{ $user->id }}">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <select class="form-select text-sm rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 role-select" 
                                            data-user-id="{{ $user->id }}" data-field="role"
                                            onchange="showSaveButton({{ $user->id }})">
                                        @foreach(['voyageur', 'employe', 'chauffeur', 'admin'] as $role)
                                            <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                        @endforeach
                                    </select>
                                    <button id="save-button-{{ $user->id }}" 
                                            onclick="saveRole({{ $user->id }})" 
                                            class="hidden ml-2 px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1 transition-colors">
                                        Enregistrer
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->compagnie)
                                    <div class="text-sm text-gray-500">{{ $user->compagnie->nom }}</div>
                                @else
                                    <div class="text-sm text-gray-500">Aucune</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <button 
                                            class="text-red-500 hover:text-red-700 focus:outline-none"
                                            onclick="confirmDelete({{ $user->id }})">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-3a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <script>
        // Suppression d'un utilisateur
        function confirmDelete(userId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }

        // Afficher le bouton "Enregistrer"
        function showSaveButton(userId) {
            document.getElementById('save-button-' + userId).classList.remove('hidden');
        }
        
        // Fonction de sauvegarde
        function saveRole(userId) {
            const selectElement = document.querySelector(`.role-select[data-user-id="${userId}"]`);
            const value = selectElement.value;

            updateUser(userId, 'role', value);
            
            // Cacher le bouton après la sauvegarde
            document.getElementById('save-button-' + userId).classList.add('hidden');
        }
    </script>
    
    <script>
    // Fonction de mise à jour AJAX (déplacée en dehors du DOMContentLoaded)
    function updateUser(userId, field, value) {
    // Récupérer les valeurs actuelles de l'utilisateur
    const nom = document.querySelector(`.editable[data-field="nom"][data-user-id="${userId}"]`).innerText;
    const email = document.querySelector(`.editable[data-field="email"][data-user-id="${userId}"]`).innerText;
    const role = document.querySelector(`.role-select[data-user-id="${userId}"]`).value;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch(`/admin/users/${userId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest', 
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            nom: nom,
            email: email,
            role: role,
            compagnie_id: null // ou la valeur appropriée si vous gérez les compagnies
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (!data.success) {
            alert('Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la communication avec le serveur: ' + (error.message || JSON.stringify(error)));
    });
}

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Gestion de l'édition en ligne (nom et email)
        document.querySelectorAll('.editable').forEach(element => {
            element.addEventListener('blur', function() {
                const userId = this.dataset.userId;
                const field = this.dataset.field;
                const value = this.innerText.trim();

                updateUser(userId, field, value);
            });
        });
    });
</script>

@endsection