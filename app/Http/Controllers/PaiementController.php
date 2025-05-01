<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Exception;

class PaiementController extends Controller
{
    public function index(Reservation $reservation)
    {
        return view('voyageur.paiement', compact('reservation'));
    }

    public function traitement(Request $request, Reservation $reservation)
    {
        $this->validate($request, [
            'stripeToken' => 'required',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret')); // Utilisez la clé secrète depuis config/services.php

            $charge = Charge::create([
                'amount' => $reservation->sousTrajet->price * 100, // Montant en centimes
                'currency' => 'mad',
                'description' => 'Paiement de la réservation #' . $reservation->id,
                'source' => $request->stripeToken,
            ]);

            // Si le paiement est réussi, mettez à jour le statut de la réservation, créez le billet, etc.
            $reservation->update(['status' => 'confirmed']);

            // Générez le billet
            $numeroBillet = uniqid('Billet_');
            $qrCode = 'QRCODE_' . $numeroBillet; // Générez un QR Code (vous pouvez utiliser une librairie pour cela)

            $reservation->billet()->create([
                'numero_billet' => $numeroBillet,
                'qr_code' => $qrCode,
                'status' => 'valide',
            ]);

            return redirect()->route('home')->with('success', 'Paiement effectué avec succès. Votre billet a été généré.');

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}