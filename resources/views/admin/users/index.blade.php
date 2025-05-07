@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
    <div class="container mx-auto py-8 px-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6"> 
            <h1 class="text-3xl font-bold text-slate-800 mb-4 md:mb-0">Gestion des Utilisateurs</h1>

            <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                
                <button id="toggle-add-user-form" 
                        class="w-full md:w-auto inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14c-1.49 0-2.918.613-3.952 1.648A3.992 3.992 0 007 19h10a3.992 3.992 0 001.952-3.352C14.918 14.613 13.49 14 12 14z" />
                    </svg>
                    Ajouter un Employé
                </button>

                <a href="{{ route('home') }}" class="w-full md:w-auto inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour tableau de bord
                </a>
            </div>
        </div>

        <div id="add-user-form-container" class="bg-white rounded-xl shadow-md p-6 mb-8 hidden">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14c-1.49 0-2.918.613-3.952 1.648A3.992 3.992 0 007 19h10a3.992 3.992 0 001.952-3.352C14.918 14.613 13.49 14 12 14z" />
                </svg>
                Ajouter un nouvel employé
            </h2>

             @if ($errors->any() && old('form_name') === 'add_employee') 
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="form_name" value="add_employee">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_user_nom" class="block text-sm font-medium text-gray-700 mb-1">Nom complet*</label>
                        <input type="text" id="new_user_nom" name="nom" value="{{ old('nom') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                         @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="new_user_email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email*</label>
                        <input type="email" id="new_user_email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                         @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_user_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe*</label>
                        <input type="password" id="new_user_password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                         @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="new_user_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe*</label>
                        <input type="password" id="new_user_password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <input type="hidden" name="role" value="employe">
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-add-user-form" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <div class="flex items-center">
                             <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Créer l'employé
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1 md:col-span-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher par email ou nom</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                    </div>
                </div>
                
                <div class="col-span-1 md:col-span-1">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par rôle</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select id="role" name="role"
                                class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                            <option value="all">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors duration-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtrer les résultats
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compagnie</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 font-medium">
                                            {{ substr($user->nom, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 editable" data-field="nom" data-user-id="{{ $user->id }}">{{ $user->nom }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 editable" data-field="email" data-user-id="{{ $user->id }}">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <select class="form-select text-sm rounded-md border-gray-300 shadow-sm focus:ring-yellow-500 focus:border-yellow-500 role-select" 
                                                data-user-id="{{ $user->id }}" data-field="role"
                                                onchange="showSaveButton({{ $user->id }})">
                                            @foreach(['voyageur', 'employe', 'chauffeur', 'admin'] as $role)
                                                <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                            @endforeach
                                        </select>
                                        <button id="save-button-{{ $user->id }}" 
                                                onclick="saveRole({{ $user->id }})" 
                                                class="hidden ml-2 px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->compagnie)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $user->compagnie->nom }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Aucune
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        @if ($user->hasRole('voyageur'))
                                            <a href="{{ route('admin.users.stats', $user) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Stats Voyageur
                                            </a>
                                        @elseif ($user->hasRole('chauffeur'))
                                            <a href="{{ route('admin.users.chauffeur.stats', $user) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Stats Chauffeur
                                            </a>
                                        @elseif ($user->hasRole('employe'))
                                            <a href="{{ route('admin.users.employe.stats', $user) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Stats Employé
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-400 bg-white opacity-50 cursor-not-allowed">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                Stats Admin
                                            </span>
                                        @endif

                                        <button 
                                                class="inline-flex items-center px-2.5 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                                onclick="confirmDelete({{ $user->id }})">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-3a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
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
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
        
        <div class="mt-6 bg-white rounded-xl shadow-md p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Aide</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Cliquez sur le nom ou l'email pour modifier
                    </p>
                </div>
                <div>
                    <p class="text-gray-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Changez le rôle et cliquez sur "Enregistrer"
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmer la suppression</h3>
                <p class="text-sm text-gray-500 mt-2">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-delete" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    Annuler
                </button>
                <button type="button" id="confirm-delete" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <script>
        let userIdToDelete = null;
        const modal = document.getElementById('delete-modal');
        const addUserFormContainer = document.getElementById('add-user-form-container');
        const toggleAddUserFormButton = document.getElementById('toggle-add-user-form');
        const cancelAddUserFormButton = document.getElementById('cancel-add-user-form');

        function confirmDelete(userId) {
            userIdToDelete = userId;
            modal.classList.remove('hidden');
        }
        
        document.getElementById('cancel-delete').addEventListener('click', function() {
            modal.classList.add('hidden');
            userIdToDelete = null;
        });

        document.getElementById('confirm-delete').addEventListener('click', function() {
            if (userIdToDelete) {
                document.getElementById('delete-form-' + userIdToDelete).submit();
            }
        });

        function showSaveButton(userId) {
            document.getElementById('save-button-' + userId).classList.remove('hidden');
        }

        function updateUser(userId, field, value) {
            const userRow = document.querySelector(`tr [data-user-id="${userId}"]`).closest('tr');
            const nomElement = userRow.querySelector(`.editable[data-field="nom"]`);
            const emailElement = userRow.querySelector(`.editable[data-field="email"]`);
            const roleSelectElement = userRow.querySelector(`.role-select`);

            const nom = nomElement.innerText.trim();
            const email = emailElement.innerText.trim();
            const role = roleSelectElement.value;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

             const saveButton = document.getElementById('save-button-' + userId);
             if (saveButton && !saveButton.classList.contains('hidden') && field === 'role') {
                 saveButton.innerHTML = `
                     <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                         <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                         <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                     </svg>
                 `;
             }


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
                })
            })
            .then(response => {
                 if (saveButton && field === 'role') {
                    saveButton.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    `;
                 }

                if (!response.ok) {
                    return response.json().then(err => {
                        if (field === 'nom' || field === 'email') {
                           nomElement.innerText = nomElement.getAttribute('data-original-value');
                           emailElement.innerText = emailElement.getAttribute('data-original-value');
                        }
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
                } else {
                    showNotification('Utilisateur mis à jour avec succès', 'success');
                    nomElement.setAttribute('data-original-value', nom);
                    emailElement.setAttribute('data-original-value', email);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la communication avec le serveur', 'error');
                 if (field === 'nom' || field === 'email') {
                    const originalValue = document.querySelector(`.editable[data-field="${field}"][data-user-id="${userId}"]`).getAttribute('data-original-value');
                     document.querySelector(`.editable[data-field="${field}"][data-user-id="${userId}"]`).innerText = originalValue;
                 }
            });
        }
        function showNotification(message, type = 'success') {
             const notification = document.createElement('div');
            notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white flex items-center z-50`;

            const icon = document.createElement('span');
            icon.className = 'mr-2';
            icon.innerHTML = type === 'success'
                ? '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
                : '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';

            const text = document.createElement('span');
            text.textContent = message;

            notification.appendChild(icon);
            notification.appendChild(text);
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.editable').forEach(element => {
                element.setAttribute('contenteditable', 'true');
                element.addEventListener('focus', function() {
                    this.classList.add('bg-yellow-50', 'border', 'border-yellow-300', 'rounded', 'px-1');
                    this.setAttribute('data-original-value', this.innerText.trim());
                });

                element.addEventListener('blur', function() {
                    this.classList.remove('bg-yellow-50', 'border', 'border-yellow-300', 'rounded', 'px-1');

                    const userId = this.dataset.userId;
                    const field = this.dataset.field;
                    const value = this.innerText.trim();
                    if (value !== this.getAttribute('data-original-value') && value !== '') {
                        updateUser(userId, field, value);
                    } else {
                         const originalValue = this.getAttribute('data-original-value');
                         if (originalValue === '' && value !== '') {
                             updateUser(userId, field, value);
                         } else if (originalValue !== '' && value === '') {
                             this.innerText = originalValue;
                         } else if (originalValue !== '' && value !== '' && value !== originalValue) {
                              updateUser(userId, field, value);
                         } else {
                            this.innerText = originalValue; 
                         }
                    }
                });

                element.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.blur(); 
                    }
                });
            });

            toggleAddUserFormButton.addEventListener('click', function() {
                addUserFormContainer.classList.toggle('hidden');
                const formWasVisibleOnError = {{ $errors->any() && old('form_name') === 'add_employee' ? 'true' : 'false' }};
                if (!addUserFormContainer.classList.contains('hidden') && !formWasVisibleOnError) {
                     const form = addUserFormContainer.querySelector('form');
                     form.reset();
                }
            });
            cancelAddUserFormButton.addEventListener('click', function() {
                addUserFormContainer.classList.add('hidden');
                 const form = addUserFormContainer.querySelector('form');
                 form.reset(); 
            });
             const formWasVisibleOnError = {{ $errors->any() && old('form_name') === 'add_employee' ? 'true' : 'false' }};
             if (formWasVisibleOnError) {
                addUserFormContainer.classList.remove('hidden');
             }

        });
    </script>

    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        .editable {
            position: relative;
            transition: all 0.2s ease;
        }
        
        .editable:hover {
            background-color: #FFFBEB;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        
        .editable:focus {
            outline: none;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .notification-enter {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
@endsection