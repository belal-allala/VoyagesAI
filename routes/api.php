<?php

use App\Http\Controllers\Api\BusApiController;
use App\Http\Controllers\Api\RealTimeTrackingApiController;
use App\Http\Controllers\Api\ReservationApiController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Reservation;
use App\Http\Controllers\Api\AuthController;

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

Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']); 
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/buses/search', [BusApiController::class, 'search']);
Route::get('/bus-locations', [RealTimeTrackingApiController::class, 'getBusLocations']);

Route::post('/reservations', [ReservationApiController::class, 'store']);
Route::get('/reservations/{reservation}', [ReservationApiController::class, 'show']);
Route::put('/reservations/{reservation}', [ReservationApiController::class, 'update']);
Route::delete('/reservations/{reservation}', [ReservationApiController::class, 'destroy']);