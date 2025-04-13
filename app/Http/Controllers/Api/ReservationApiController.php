<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationApiController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Pour l'instant, pas de validation

        $reservation = Reservation::create([
            'bus_id' => $request->input('bus_id'),
            'user_id' => null, 
            'passenger_name' => $request->input('passenger_name'),
            'passenger_email' => $request->input('passenger_email'),
            'seat_count' => $request->input('seat_count'),
            'total_price' => $request->input('total_price'),
            'reservation_date' => $request->input('reservation_date'),
        ]);

        return response()->json(['reservation' => $reservation, 'message' => 'Réservation créée avec succès.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // ...
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // ...
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // ...
    }
}