<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Trajet;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Billet;
use App\Exports\PassagersExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function trajetsAssignes(Request $request)
    {
        $chauffeur = Auth::user();
        $filterDate = $request->input('date', now()->format('Y-m-d'));

        $trajets = Trajet::where('chauffeur_id', $chauffeur->id)
            ->with(['bus', 'sousTrajets'])
            ->whereHas('sousTrajets', function ($query) use ($filterDate) {
                $query->whereDate('departure_time', $filterDate);
            })
            ->get();

        return view('chauffeur.trajets', compact('trajets', 'filterDate')); // Passer $filterDate à la vue
    }

    public function listePassagers(Request $request)
    {
        $chauffeur = Auth::user();
        $selectedTrajetDate = $request->input('trajet_date');

        $trajets = Trajet::where('chauffeur_id', $chauffeur->id)->get();

        $reservations = collect(); // Initialiser $reservations
    
        if ($selectedTrajetDate) {
            list($trajetId, $date) = explode('_', $selectedTrajetDate);
            $reservations = Reservation::where('status', 'confirmed')
                ->where('trajet_id', $trajetId)
                ->whereDate('date_depart', $date)
                ->with(['user', 'billet', 'trajet'])
                ->get();
        }
        

        if ($request->input('export') === 'excel') {
            return Excel::download(new PassagersExport($reservations), 'passagers.xlsx');
        }
        
        // Formater les options du select pour chaque trajet (Nom du trajet + Date de départ)
        $trajetOptions = collect();
        foreach ($trajets as $trajet) {
            foreach ($trajet->sousTrajets as $sousTrajet) {
                $trajetOptions->push([
                    'id' => $trajet->id . '_' . $sousTrajet->departure_time->format('Y-m-d'),
                    'name' => $trajet->name . ' - ' . $sousTrajet->departure_time->format('d/m/Y')
                ]);
            }
        }

        return view('chauffeur.passagers', compact('reservations', 'trajetOptions', 'selectedTrajetDate'));
    }

    public function scan()
    {
        $chauffeur = Auth::user();
        $trajets = Trajet::where('chauffeur_id', $chauffeur->id)
            ->with('sousTrajets') // Charger les sous-trajets pour afficher la date de départ
            ->get();
        return view('chauffeur.scan', compact('trajets'));
    }

    public function validerBillet(Request $request)
    {
        $validated = $request->validate([
            'numero_billet' => 'required|string',
        ]);

        $billet = Billet::where('numero_billet', $validated['numero_billet'])->first();

        if (!$billet) {
            return response()->json(['success' => false, 'message' => 'Billet non trouvé.']);
        }

        if ($billet->status !== 'valide') {
            return response()->json(['success' => false, 'message' => 'Billet invalide ou déjà utilisé.']);
        }

        $billet->update(['status' => 'utilise']); // Marquer le billet comme utilisé

        return response()->json(['success' => true, 'message' => 'Billet validé avec succès.']);
    }
}