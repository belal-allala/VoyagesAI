<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Resources\ReservationResource; 
use App\Http\Requests\UpdateReservationRequest;
use App\Services\StripeService;

class ReservationApiController extends Controller
{

    /**
     * @var \App\Services\StripeService
     */
    protected $stripeService;

    /**
     * Constructeur.
     * @param \App\Services\StripeService $stripeService
     */
    public function __construct(StripeService $stripeService) 
    {
        $this->stripeService = $stripeService; 
    }

    /**
     * Display a listing of the resource.
     */
    public function store(CreateReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated()); 

        return response()->json(['reservation' => $reservation, 'message' => 'Réservation créée avec succès.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation) 
    {
        return new ReservationResource($reservation); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        return response()->json(new UserResource($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation) 
    {
        $reservation->delete(); 

        return response()->json(['message' => 'Réservation supprimée avec succès.'], 200); 
    }

    /**
     * Create Stripe PaymentIntent.
     */
    public function confirmReservation(Request $request)
    {
        $reservationId = $request->input('reservation_id'); 
        $reservation = Reservation::findOrFail($reservationId); 

        $amount = $reservation->total_price * 100; 

        $stripeClient = $this->stripeService->getStripeClient(); 

        $paymentIntent = $stripeClient->paymentIntents->create([ 
            'amount' => $amount,
            'currency' => 'eur', 
            'automatic_payment_methods' => [
                'enabled' => true, 
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret, 
        ], 200);
    }
}