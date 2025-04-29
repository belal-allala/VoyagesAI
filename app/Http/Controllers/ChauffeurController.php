<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChauffeurController extends Controller
{
    public function index()
    {
        $chauffeurs = User::where('role', 'chauffeur')
                         ->where('company_id', auth()->user()->company_id)
                         ->get();
        
        return view('employe.chauffeurs.index', compact('chauffeurs'));
    }

    public function search(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $chauffeur = User::where('email', $request->email)
                       ->where('role', 'chauffeur')
                       ->first();

        $chauffeurs = User::where('role', 'chauffeur')
                        ->where('company_id', auth()->user()->company_id)
                        ->get();

        if (!$chauffeur) {
            return redirect()->route('chauffeurs.index')
                             ->with('error', 'Aucun chauffeur trouvé avec cet email');
        }

        return view('employe.chauffeurs.index', compact('chauffeur', 'chauffeurs'));
    }

    public function attach(User $user)
    {
        if ($user->role !== 'chauffeur') {
            abort(403, 'Cet utilisateur n\'est pas un chauffeur');
        }

        if ($user->company_id) {
            return redirect()->route('chauffeurs.index')
                             ->with('error', 'Ce chauffeur est déjà affilié à une compagnie');
        }

        $user->update(['company_id' => auth()->user()->company_id]);

        return redirect()->route('chauffeurs.index')
                         ->with('success', 'Chauffeur ajouté avec succès');
    }

    public function detach(User $user)
    {
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Action non autorisée');
        }

        $user->update(['company_id' => null]);

        return redirect()->route('chauffeurs.index')
                         ->with('success', 'Chauffeur retiré de la compagnie');
    }
}