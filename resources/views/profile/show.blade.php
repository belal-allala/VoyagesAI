@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gray-900 px-8 py-6 text-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Vega Go Logo" class="h-[200px] w-[200px] rounded-full">
            </div>
            <h1 class="text-2xl font-bold text-white">Mon Profil</h1>
        </div>

        <!-- Carte principale -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Section informations profil -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-900">Informations personnelles</h2>
            </div>

            {{-- Messages de session --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-4 rounded-lg">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                 <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mx-6 mt-4 rounded-lg">
                     <div class="flex items-center">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                         </svg>
                         <span>{{ session('error') }}</span>
                     </div>
                 </div>
             @endif

            <div class="p-6">
                <div class="flex flex-col md:flex-row items-center gap-6 mb-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $user->profile?->profile_picture ? asset('storage/' . $user->profile->profile_picture) : asset('images/default-profile.png') }}"
                             alt="Photo de profil"
                             class="h-24 w-24 rounded-full object-cover border-2 border-yellow-500 shadow-md">
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-900">{{ $user->nom }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-2">
                             {{ ucfirst($user->role) }}
                         </span>
                         @if($user->compagnie)
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2 ml-2">
                                 Compagnie: {{ $user->compagnie->nom }}
                             </span>
                         @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Numéro de téléphone</label>
                        <p class="text-gray-900 font-medium">{{ $user->profile?->phone_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date de naissance</label>
                        <p class="text-gray-900 font-medium">{{ $user->profile?->date_of_birth?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                     <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Adresse</label>
                        <p class="text-gray-900 font-medium">{{ $user->profile?->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <button onclick="toggleEditForm()"
                        class="w-full md:w-auto bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors duration-200">
                    Modifier mes informations
                </button>
            </div>

            <!-- Formulaire d'édition (caché par défaut) -->
            {{-- Utilisez 'id="editFormContainer"' pour le JS --}}
            <div id="editFormContainer" class="hidden bg-gray-50 p-6 border-t border-gray-200 transition-all duration-300">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Modifier mes informations</h2>
                {{-- Assurez-vous de l'attribut enctype pour l'upload de fichier --}}
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Champs de base (Nom et Email) --}}
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom complet*</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('nom') border-red-500 @enderror"
                                   required>
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email*</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                         {{-- Champs d'édition du profil. Utilise l'opérateur nullsafe ?-> --}}
                         <div>
                             <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                             <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->profile?->phone_number ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('phone_number') border-red-500 @enderror">
                             @error('phone_number')
                                 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                             @enderror
                         </div>

                         <div>
                             <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                             {{-- Double ?-> et format pour la valeur de l'input date --}}
                             <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->profile?->date_of_birth?->format('Y-m-d') ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('date_of_birth') border-red-500 @enderror">
                             @error('date_of_birth')
                                 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                             @enderror
                         </div>

                          <div class="md:col-span-2">
                             <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                             <textarea id="address" name="address" rows="2"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('address') border-red-500 @enderror">{{ old('address', $user->profile?->address ?? '') }}</textarea>
                              @error('address')
                                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                              @enderror
                          </div>

                          <div class="md:col-span-2">
                            <label for="profile_picture_upload" class="block text-sm font-medium text-gray-700 mb-1">Photo de profil</label>
                            {{-- Le champ de fichier n'affiche pas la valeur actuelle, seulement un contrôle d'upload --}}
                            <input type="file" id="profile_picture_upload" name="profile_picture_upload" accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('profile_picture_upload') border-red-500 @enderror">
                             @error('profile_picture_upload')
                                 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                             @enderror
                              <p class="mt-1 text-xs text-gray-500">Maximum 2MB. Les formats acceptés sont : JPEG, PNG, JPG, GIF, SVG.</p>
                         </div>
                         {{-- Ajoutez d'autres champs de profil ici --}}
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="toggleEditForm()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-colors duration-200">
                            Annuler
                        </button>
                        <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors duration-200">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section changement de mot de passe -->
            <div class="bg-gray-50 p-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Changer mon mot de passe</h2>
                <form method="POST" action="{{ route('profile.updatePassword') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('current_password') border-red-500 @enderror"
                                   required>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error('password') border-red-500 @enderror"
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmation</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                   required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors duration-200">
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEditForm() {
        const form = document.getElementById('editFormContainer');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' }); 
        }
    }

     function toggleDeleteAccountForm() {
        const form = document.getElementById('deleteAccountFormContainer');
        form.classList.toggle('hidden');
         if (form.classList.contains('hidden')) {
              document.getElementById('password_delete').value = '';
         } else {
             form.scrollIntoView({ behavior: 'smooth', block: 'start' });
         }
    }

    document.addEventListener('DOMContentLoaded', function() {
             document.getElementById('editFormContainer').classList.remove('hidden');
             document.getElementById('editFormContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
         @endif

          @if($errors->any() && old('_method') === 'PUT' && (array_key_exists('current_password', $errors->getBags()['default']->getMessages()) || array_key_exists('password', $errors->getBags()['default']->getMessages())))

          @endif



         @if($errors->any() && old('_method') === 'DELETE')
             document.getElementById('deleteAccountFormContainer').classList.remove('hidden');
       
              document.getElementById('deleteAccountFormContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
         @endif
    });

</script>
@endsection