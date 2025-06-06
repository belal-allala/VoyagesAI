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
use Stripe\Stripe;
use Stripe\PaymentIntent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;



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
        $dateAujourdhui = Carbon::today()->toDateString();
        if ($dateRecherche < $dateAujourdhui) {
            return back()->with('error', 'Vous ne pouvez pas rechercher des trajets avec une date passée.');
        }

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
        'sous_trajets' => 'required|array|min:1', 
        'sous_trajets.*.departure_city' => 'required|string',
        'sous_trajets.*.destination_city' => 'required|string',
        'sous_trajets.*.departure_time' => 'required|date',
        'sous_trajets.*.arrival_time' => 'required|date',
        'sous_trajets.*.price' => 'required|numeric',
    ]);

    $sousTrajets = $validated['sous_trajets'];
    $premierSousTrajet = reset($sousTrajets); 
    $dernierSousTrajet = end($sousTrajets); 

    $trajetData = [
        'trajet_id' => $validated['trajet_id'],
        'trajet_name' => $validated['trajet_name'],
        'bus' => [
            'name' => $validated['bus_name'],
            'plate_number' => $validated['bus_plate'],
        ],
        'chauffeur' => $validated['chauffeur'],
        'prix_partiel' => $validated['prix_partiel'],
        'sous_trajets_pertinents' => $sousTrajets, 
        'sous_trajets_complets' => count($sousTrajets),
        'ville_depart' => $premierSousTrajet['departure_city'], 
        'date_depart' => $premierSousTrajet['departure_time'], 
        'ville_arrivee' => $dernierSousTrajet['destination_city'], 
        'date_arrivee' => $dernierSousTrajet['arrival_time'], 
    ];

    return view('voyageur.reservation', compact('trajetData'));
}


public function storeReservation(Request $request)
{
    $validated = $request->validate([
        'trajet_id' => 'required|exists:trajets,id',
        'prix_partiel' => 'required|numeric|min:0',
        'nombre_passagers' => 'required|integer|min:1',
        'date_depart' => 'required|date',
        'ville_depart' => 'required|string',
        'date_arrivee' => 'required|date',
        'ville_arrivee' => 'required|string',
    ]);

    $trajet = Trajet::find($validated['trajet_id']);

    if (!$trajet) {
        return back()->with('error', 'Trajet non trouvé.');
    }

    $prixTotal = $validated['prix_partiel'] * $validated['nombre_passagers'];

    $reservation = new Reservation();
    $reservation->user_id = auth()->id();
    $reservation->trajet_id = $validated['trajet_id'];
    $reservation->date_depart = $validated['date_depart'];
    $reservation->ville_depart = $validated['ville_depart'];
    $reservation->date_arrivee = $validated['date_arrivee'];
    $reservation->ville_arrivee = $validated['ville_arrivee'];
    $reservation->nombre_passagers = $validated['nombre_passagers'];
    $reservation->prix_total = $prixTotal;
    $reservation->status = 'pending';
    $reservation->save();

    Stripe::setApiKey(config('services.stripe.secret'));
    try {
        $paymentIntent = PaymentIntent::create([
            'amount' => $prixTotal * 100, 
            'currency' => 'mad',
            'metadata' => [
                'reservation_id' => $reservation->id, 
                'user_id' => auth()->id(),
            ],
            'payment_method_types' => ['card'], 
        ]);
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la création du Payment Intent : ' . $e->getMessage());
        return back()->with('error', 'Une erreur s\'est produite lors de la préparation du paiement. Veuillez réessayer.');
    }
    return redirect()->route('paiement.index', $reservation)
                    ->with('stripe_client_secret', $paymentIntent->client_secret) 
                     ->with('success', 'Réservation créée. Veuillez procéder au paiement.');
}

   
public function confirmationPaiement(Reservation $reservation)
{
    if ($reservation->user_id !== auth()->id()) {
        abort(403);
    }

    return view('voyageur.confirmationPaiement', compact('reservation'));
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
        $itineraireCouvert = false;

        for ($i = 0; $i < $nbEtapes; $i++) {
            $etape = $etapes[$i];

            if ($etape->departure_city === $villeDepart && !$departTrouve) {
                $departTrouve = true;
            }

            if ($departTrouve ) {
                if ($etape->destination_city === $villeArrivee) {
                    $itineraireCouvert = true;
                    break;
                }
            }
        }

        return  $itineraireCouvert;
    }

    public function generateTicketPdf(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }
        if ($reservation->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Cette réservation n\'est pas confirmée et son billet ne peut pas être généré.');
        }
        if (!$reservation->billet) {
            return redirect()->back()->with('error', 'Aucun billet trouvé pour cette réservation.');
        }
        $qrCodeSvg = QrCode::size(200)->style('square')->generate($reservation->billet->numero_billet);
        $qrCodeBase64 = base64_encode($qrCodeSvg);
        $qrCodeDataUrl = 'data:image/svg+xml;base64,' . $qrCodeBase64;
        $pdf = Pdf::loadView('voyageur.ticket_pdf', [
            'reservation' => $reservation,
            'qrCodeDataUrl' => $qrCodeDataUrl, 
        ]);
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
        $numeroBillet = $reservation->billet->numero_billet;
        $villeDepartSlug = Str::slug($reservation->ville_depart);
        $villeArriveeSlug = Str::slug($reservation->ville_arrivee);
        $dateFormat = $reservation->date_depart->format('Ymd');

        $filename = "Ticket_{$numeroBillet}_{$villeDepartSlug}_to_{$villeArriveeSlug}_{$dateFormat}.pdf";
        return $pdf->download($filename);
    }


public function mesReservations(Request $request)
{
    $user = Auth::user();

    $filter = $request->filter ?? 'À venir'; 

    $reservations = Reservation::where('user_id', $user->id)
        ->with(['trajet', 'trajet.bus', 'trajet.chauffeur', 'billet', 'trajet.sousTrajets'])
        ->whereHas('trajet', function($query) use ($filter) {
            $query->where(function($q) use ($filter) {
                $now = Carbon::now();
                
                switch ($filter) {
                    case 'En cours':
                        $q->whereHas('sousTrajets', function($sq) use ($now) {
                            $sq->where('departure_time', '<=', $now)
                               ->where('arrival_time', '>=', $now);
                        });
                        break;
                    case 'Passé':
                        $q->whereHas('sousTrajets', function($sq) use ($now) {
                            $sq->where('arrival_time', '<', $now);
                        });
                        break;
                    default: 
                        $q->whereHas('sousTrajets', function($sq) use ($now) {
                            $sq->where('departure_time', '>', $now);
                        });
                }
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('voyageur.reservations.index', compact('reservations'));
}

    public function reservationDetails(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $reservation->load(['trajet.sousTrajets', 'billet']);

        return view('voyageur.reservations.details', compact('reservation'));
    }

    public function annulerReservation(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $reservation->update(['status' => 'cancelled']); 
        
        return redirect()->route('voyageur.reservations')->with('success', 'Réservation annulée avec succès.');
    }


    public function initierPaiement(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($reservation->status === 'confirmed') {
            return back()
                ->with('info', 'Cette réservation est déjà confirmée et payée');
        }

        if ($reservation->trajet->status !== 'À venir') {
            return back()
                ->with('error', 'Le trajet a déjà eu lieu, impossible de procéder au paiement');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $reservation->prix_total * 100,
                'currency' => 'mad',
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'user_id' => auth()->id(),
                ],
                'payment_method_types' => ['card'],
            ]);

            return redirect()
                ->route('paiement.index', $reservation)
                ->with([
                    'stripe_client_secret' => $paymentIntent->client_secret,
                    'success' => 'Veuillez compléter votre paiement'
                ]);

        } catch (\Exception $e) {
            
            return back()
                ->with('error', 'Une erreur est survenue lors de la préparation du paiement');
        }
    }
}