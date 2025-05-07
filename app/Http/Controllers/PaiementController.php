<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Exception;

class PaiementController extends Controller
{
    public function index(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
    
        if ($reservation->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Cette réservation a déjà été traitée.');
        }

        return view('voyageur.paiement', compact('reservation'));
    }

    public function traitement(Request $request, Reservation $reservation)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($request->input('payment_intent'));

        if ($paymentIntent->status === 'succeeded') {
            $reservation->update(['status' => 'confirmed']);
            $numeroBillet = 'BLT'.strtoupper(uniqid());
            $qrCode = 'QR_'.$numeroBillet;
            $reservation->billet()->create([
                'numero_billet' => $numeroBillet,
                'qr_code' => $qrCode,
                'status' => 'valide',
            ]);

            return redirect()->route('voyageur.confirmationPaiement', $reservation)
                            ->with('success', 'Paiement effectué avec succès!');
        } else {
            \Log::error('Payment Intent échoué: '.$paymentIntent->id);
            return back()->with('error', 'Le paiement n\'a pas pu être confirmé');
        }
    } catch (\Exception $e) {
        \Log::error('Erreur paiement: '.$e->getMessage());
        return back()->with('error', 'Erreur lors du traitement du paiement');
    }
}
}