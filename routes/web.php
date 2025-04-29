<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\BusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name("welcome");

Route::get('/home', function () {
    return view('home');
})->name("home");

Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth'])->name('profile');


Route::get('/register', [WebAuthController::class, 'register'])->name('register');
Route::post('/register', [WebAuthController::class, 'handleRegister'])->name('handleRegister');

Route::get('/login', [WebAuthController::class, 'login'])->name('login');
Route::post('/login', [WebAuthController::class, 'handleLogin'])->name('handleLogin');

Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Route::get('/bus/search', [BusController::class, 'search'])->name('bus.search');
// Route::middleware('auth')->group(function () {
//     Route::prefix('buses')->group(function () {
//         // Route::get('/', [BusController::class, 'index'])->name('buses.index');
//         Route::post('/handle', [BusController::class, 'handle'])->name('buses.handle');
//         Route::delete('/{bus}', [BusController::class, 'destroy'])->name('buses.destroy');
//     });
// });
// Route::get('/buses', [BusController::class, 'index'])->name('buses.index');

Route::middleware(['auth', 'role:employe'])->group(function () {
    // Gestion Compagnie
    Route::get('/compagnies/create', [CompagnieController::class, 'create'])->name('compagnies.create');
    Route::post('/compagnies', [CompagnieController::class, 'store'])->name('compagnies.store');
    
    // Gestion Bus
    Route::get('/buses/create', [BusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [BusController::class, 'store'])->name('buses.store');
    
    // Gestion Trajets
    Route::get('/trajets/create', [TrajetController::class, 'create'])->name('trajets.create');
    Route::post('/trajets', [TrajetController::class, 'store'])->name('trajets.store');
    
    // Gestion Sous-trajets
    Route::get('/trajets/{trajet}/sous-trajets/create', [SousTrajetController::class, 'create'])->name('sous-trajets.create');
    Route::post('/trajets/{trajet}/sous-trajets', [SousTrajetController::class, 'store'])->name('sous-trajets.store');
});