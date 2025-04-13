<?php

use App\Http\Controllers\Api\BusApiController;
use App\Http\Controllers\Api\RealTimeTrackingApiController;
use App\Http\Controllers\Api\ReservationApiController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/buses/search', [BusApiController::class, 'search']);
Route::get('/bus-locations', [RealTimeTrackingApiController::class, 'getBusLocations']);

Route::post('/reservations', [ReservationApiController::class, 'store']);