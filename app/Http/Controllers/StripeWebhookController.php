<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Reservation;
use App\Notifications\ReservationConfirmation;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signatureHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret'); 

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signatureHeader,
                $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            return response('Signature invalide.', 400); 
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentPaymentFailed($paymentIntent);
                break;
            default:
                \Log::info('Événement Stripe non géré : ' . $event->type);
        }

        return response('Webhook reçu', 200); 
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $reservationId = $paymentIntent->metadata->reservation_id;

        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            // Mettre à jour le statut de la réservation à "confirmed"
            if ($reservation->status !== 'confirmed') { // Vérification d'idempotence
                $reservation->update(['status' => 'confirmed']);

                // Créer le billet
                if (!$reservation->billet) { // Vérification d'idempotence
                    $numeroBillet = uniqid('Billet_');
                    $qrCode = 'QRCODE_' . $numeroBillet;

                    $reservation->billet()->create([
                        'numero_billet' => $numeroBillet,
                        'qr_code' => $qrCode,
                        'status' => 'valide',
                    ]);
                    \Log::info('Billet créé pour la réservation #' . $reservation->id);
                }

                \Log::info('Paiement réussi pour la réservation #' . $reservation->id);

                if ($reservation->user) { 
                    $reservation->user->notify(new ReservationConfirmation($reservation));
                }
            }
        } else {
            \Log::error('Réservation non trouvée : ' . $reservationId);
        }
    }

    protected function handlePaymentIntentPaymentFailed($paymentIntent)
    {
        $reservationId = $paymentIntent->metadata->reservation_id;
        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            if ($reservation->status !== 'payment_failed') { // Vérification d'idempotence
                $reservation->status = 'payment_failed';
                $reservation->save();
            }
            \Log::error('Paiement échoué pour la réservation #' . $reservation->id);
        } else {
            \Log::error('Réservation non trouvée : ' . $reservationId);
        }
    }
}