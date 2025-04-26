<?php

namespace App\Http\Controllers;

use App\Models\Bus; 
use App\Services\BusSearchService; 
use Illuminate\Http\Request;

class BusController extends Controller
{
    protected $busSearchService;

    public function __construct(BusSearchService $busSearchService)
    {
        $this->busSearchService = $busSearchService;
    }

    public function search(Request $request)
    {
        return view('bus.search',  [
            'buses' => [],
            'destinationCity' => '',
            'departureCity' => '',
            'departureTime' => '',
         ]);
    }
}