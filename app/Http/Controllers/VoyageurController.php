<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SousTrajet;
use App\Models\Reservation;
use App\Models\Billet;
use Carbon\Carbon;
use App\Models\Trajet;
use App\Models\Bus;
use App\Models\Chauffeur;
use App\Models\Compagnie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VoyageurController extends Controller
{
    public function index()
    {
        return view('voyageur.recherche');
    }

    public function recherche(Request $request)
    {
        $validated = $request->validate([
            'ville_depart' => 'required|string',
            'ville_arrivee' => 'required|string',
            'date_depart' => 'required|date',
        ]);

        $dateRecherche = Carbon::parse($validated['date_depart'])->toDateString();

        $trajetsCorrespondants=Trajet::whereHas('sousTrajets', function ($query) use ($validated, $dateRecherche) {
            $query->where('departure_city', $validated['ville_depart'])
                ->whereDate('departure_time', $dateRecherche);
        })
        ->whereHas('sousTrajets', function ($query) use ($validated) {
            $query->where('destination_city', $validated['ville_arrivee']);
        })
        ->with(['bus', 'chauffeur', 'sousTrajets' => function ($query) use ($dateRecherche) {
            $query->whereDate('departure_time', $dateRecherche);
        }])
        ->get()
        ->filter(function ($trajet) use ($validated) {
            return $this->trajetCouvreItineraire($trajet, $validated['ville_depart'], $validated['ville_arrivee']);
        })
            ->map(function ($trajet) use ($validated) {
                $prixPartiel = 0;
                $etapes = $trajet->sousTrajets;
                $enregistrerPrix = false;
                $sousTrajetsPertinents = [];
                foreach ($etapes as $etape) {
                    if ($etape->departure_city == $validated['ville_depart']) {
                        $enregistrerPrix = true;
                    }
                    if ($enregistrerPrix) {
                        $sousTrajetsPertinents[] = $etape;
                        $prixPartiel += $etape->price;
                    }
                    if ($etape->destination_city == $validated['ville_arrivee']) {
                        break;
                    }
                }
                $trajet->prix_partiel = $prixPartiel; 
                $trajet->sousTrajetsPertinents = collect($sousTrajetsPertinents);
                return $trajet;
            });

        return view('voyageur.recherche', compact('trajetsCorrespondants'));
    }

    public function createReservationTrajet(Request $request)
    {
        $validated = $request->validate([
            'trajet_id' => 'required|exists:trajets,id',
            'trajet_name' => 'required|string',
            'bus_name' => 'required|string',
            'bus_plate' => 'required|string',
            'chauffeur' => 'required|string',
            'prix_partiel' => 'required|numeric',
            'sous_trajets' => 'required|array',
            'sous_trajets.*.departure_city' => 'required|string',
            'sous_trajets.*.destination_city' => 'required|string',
            'sous_trajets.*.departure_time' => 'required|date',
            'sous_trajets.*.arrival_time' => 'required|date',
            'sous_trajets.*.price' => 'required|numeric',
        ]);

        $trajetData = [
            'trajet_id' => $validated['trajet_id'],
            'trajet_name' => $validated['trajet_name'],
            'bus' => [
                'name' => $validated['bus_name'],
                'plate_number' => $validated['bus_plate'],
            ],
            'chauffeur' => $validated['chauffeur'],
            'prix_partiel' => $validated['prix_partiel'],
            'sous_trajets_pertinents' => $validated['sous_trajets'],
            'sous_trajets_complets' => count($validated['sous_trajets'])
        ];

        return view('voyageur.reservation', compact('trajetData'));
    }


    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'sous_trajet_id' => 'required|exists:sous_trajets,id',
            'date_depart' => 'required|date',
            'ville_depart' => 'required|string',
            'date_arrivee' => 'required|date',
            'ville_arrivee' => 'required|string',
            'nombre_passagers' => 'required|integer|min:1',
        ]);

        $reservation = new Reservation();
        $reservation->user_id = auth()->id();
        $reservation->sous_trajet_id = $validated['sous_trajet_id'];
        $reservation->date_depart = $validated['date_depart'];
        $reservation->ville_depart = $validated['ville_depart'];
        $reservation->date_arrivee = $validated['date_arrivee'];
        $reservation->ville_arrivee = $validated['ville_arrivee'];
        $reservation->status = 'pending'; 
        $reservation->save();

        
        $billet = new Billet();
        $billet->reservation_id = $reservation->id;
        $billet->numero_billet = uniqid(); 
        $billet->qr_code = '...'; 
        $billet->status = 'valide';
        $billet->save();

        return redirect()->route('voyageur.confirmation', $reservation->id)
                         ->with('success', 'Réservation créée avec succès!');
    }

   
    public function confirmation($reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);
        return view('voyageur.confirmation', compact('reservation'));
    }

    public function historiqueReservations()
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();
        return view('voyageur.historique', compact('reservations'));
    }

    private function trajetCouvreItineraire($trajet, $villeDepart, $villeArrivee)
    {
        $etapes = $trajet->sousTrajets->sortBy('departure_time');
        $nbEtapes = count($etapes);

        $departTrouve = false;
        // $arriveeTrouvee = false;
        $itineraireCouvert = false;
        // $villePrecedente = null;

        for ($i = 0; $i < $nbEtapes; $i++) {
            $etape = $etapes[$i];

            if ($etape->departure_city === $villeDepart && !$departTrouve) {
                $departTrouve = true;
                // $villePrecedente = $etape->departure_city;
            }

            if ($departTrouve /*&& $villePrecedente === $etape->departure_city*/) {
                if ($etape->destination_city === $villeArrivee) {
                    // $arriveeTrouvee = true;
                    $itineraireCouvert = true;
                    break;
                }
                // $villePrecedente = $etape->destination_city; 
            }
        }

        return  $itineraireCouvert;
    }

}