<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\CompagnieController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\SousTrajetController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\VoyageurController;

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
    Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
    Route::get('/buses/create', [BusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [BusController::class, 'store'])->name('buses.store');
    Route::get('/buses/{bus}/edit', [BusController::class, 'edit'])->name('buses.edit');
    Route::put('/buses/{bus}', [BusController::class, 'update'])->name('buses.update');
    Route::delete('/buses/{bus}', [BusController::class, 'destroy'])->name('buses.destroy');
    
    // Gestion Trajets
    Route::prefix('trajets')->group(function () {
        Route::get('/', [TrajetController::class, 'index'])->name('trajets.index');
        Route::post('/', [TrajetController::class, 'store'])->name('trajets.store');
        Route::delete('/{trajet}', [TrajetController::class, 'destroy'])->name('trajets.destroy');
        Route::get('/trajets/{trajet}/edit', [TrajetController::class, 'edit'])->name('trajets.edit');
        Route::put('/trajets/{trajet}', [TrajetController::class, 'update'])->name('trajets.update');
        Route::get('/{trajet}/details', [TrajetController::class, 'details'])->name('trajets.details');
    });
    // Gestion Sous-trajets
    Route::get('/trajets/{trajet}/sous-trajets/create', [SousTrajetController::class, 'create'])->name('sous-trajets.create');
    Route::post('/trajets/{trajet}/sous-trajets', [SousTrajetController::class, 'store'])->name('sous-trajets.store');

    Route::prefix('chauffeurs')->group(function () {
        Route::get('/', [ChauffeurController::class, 'index'])->name('chauffeurs.index');
        Route::get('/search', [ChauffeurController::class, 'search'])->name('chauffeurs.search');
        Route::post('/{user}/attach', [ChauffeurController::class, 'attach'])->name('chauffeurs.attach');
        Route::delete('/{user}/detach', [ChauffeurController::class, 'detach'])->name('chauffeurs.detach');
    });
});

Route::get('/employe/dashboard', [EmployeController::class, 'dashboard'])->name('employe.dashboard');

// Route::get('/voyageur', [VoyageurController::class, 'index'])->name('voyageur.recherche');
// Route::get('/trajets/recherche', [VoyageurController::class, 'recherche'])->name('trajets.recherche');
// Routes pour les voyageurs
Route::middleware('auth')->group(function () {
    Route::prefix('reservations')->group(function () {
        Route::post('/', [VoyageurController::class, 'storeReservation'])->name('reservations.store');
        // Vous pouvez ajouter d'autres routes de réservation ici si nécessaire
    });
    
    Route::get('/voyageur', [VoyageurController::class, 'index'])->name('voyageur.recherche');
    Route::get('/trajets/recherche', [VoyageurController::class, 'recherche'])->name('trajets.recherche');
    // Route::post('/reservations/create', [VoyageurController::class, 'createReservationTrajet'])
    //  ->name('reservations.createTrajet');
});

Route::post('/reservations/create', [VoyageurController::class, 'createReservationTrajet'])
     ->name('reservations.createTrajet');