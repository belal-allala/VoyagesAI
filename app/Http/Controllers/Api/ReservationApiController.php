<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\CreateReservationRequest;
use App\Http\Resources\ReservationResource; 

class ReservationApiController extends Controller
{


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