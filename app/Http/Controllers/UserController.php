<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Compagnie; 

class UserController extends Controller
{
    /**
     * Affiche le tableau des utilisateurs avec recherche et filtrage.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('compagnie'); // Chargement anticipé de la relation compagnie

        // Recherche par email
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('email', 'like', '%' . $searchTerm . '%');
        }

        // Filtrage par rôle
        if ($request->has('role') && $request->input('role') !== 'all') {
            $query->where('role', $request->input('role'));
        }

        $users = $query->paginate(10);
        $roles = ['voyageur', 'employe', 'chauffeur', 'admin']; // Liste des rôles pour le filtre
        
        // Récupérer toutes les compagnies
        $compagnies = Compagnie::all();

        return view('admin.users.index', compact('users', 'roles', 'compagnies')); // Passer $roles et $compagnies à la vue
    }

    /**
     * Met à jour dynamiquement un utilisateur (mode édition en ligne).
     */
    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'nom' => 'sometimes|string|max:255',
        'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
        'role' => ['sometimes', Rule::in(['voyageur', 'employe', 'chauffeur', 'admin'])],
        'compagnie_id' => 'nullable|exists:compagnies,id', // Changé de company_id à compagnie_id
        'password' => 'nullable|min:8|confirmed',
    ]);

    if ($request->filled('password')) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']); 
    }

    $user->update($validated);

    return response()->json(['success' => true, 'message' => 'Utilisateur mis à jour avec succès.']);
}


    /**
     * Supprime un utilisateur (destroy).
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}