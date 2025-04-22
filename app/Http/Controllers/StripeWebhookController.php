<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Reservation; 
use App\Notifications\ReservationConfirmation;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhooks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signatureHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret'); 

        $event = null;

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
                break;
        }

        return response('Webhook reçu', 200); 
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $reservationId = $paymentIntent->metadata->reservation_id;

        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            $reservation->status = 'confirmed';
            $reservation->save();
            if ($reservation->user) { 
                $reservation->user->notify(new ReservationConfirmation($reservation));
            }

        }
    }

    protected function handlePaymentIntentPaymentFailed($paymentIntent)
    {
        $reservationId = $paymentIntent->metadata->reservation_id;
        $reservation = Reservation::find($reservationId);
        if ($reservation) {
            $reservation->status = 'payment_failed';
            $reservation->save();
        }
    }
}