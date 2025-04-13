<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BusSearchService;
use App\Http\Requests\BusSearchRequest;

class BusApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(BusSearchRequest $request)
    {
        $destinationCity = $request->query('destination_city');
        $departureCity = $request->query('departure_city');
        $departureTime = $request->query('departure_time');

        $filters = [];

        if ($destinationCity) {
            $filters['destination_city'] = $destinationCity;
        }
        if ($departureCity) {
            $filters['departure_city'] = $departureCity;
        }
        if ($departureTime) {
            $filters['departure_time'] = $departureTime;
        }

        $busSearchService = new BusSearchService();
        $buses = $busSearchService->searchBuses($filters);

        if ($buses->isEmpty()) {
            return response()->json(['message' => 'Aucun bus trouvé pour votre recherche.'], 200);
        } else {
            return BusResource::collection($buses);
        }
    }
}
