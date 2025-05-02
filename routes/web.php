<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    WebAuthController,
    BusController,
    CompagnieController,
    EmployeController,
    TrajetController,
    SousTrajetController,
    ChauffeurController,
    VoyageurController,
    ReservationController,
    PaiementController,
    StripeWebhookController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', fn() => view('welcome'))->name("welcome");
Route::get('/home', fn() => view('welcome'))->name("home");

// Profil (authentification requise)
Route::get('/profile', fn() => view('profile'))->middleware(['auth'])->name('profile');

// Authentification
Route::get('/register', [WebAuthController::class, 'register'])->name('register');
Route::post('/register', [WebAuthController::class, 'handleRegister'])->name('handleRegister');
Route::get('/login', [WebAuthController::class, 'login'])->name('login');
Route::post('/login', [WebAuthController::class, 'handleLogin'])->name('handleLogin');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Espace Employé (authentifié avec rôle "employe")
Route::middleware(['auth', 'role:employe'])->group(function () {
    
    // Compagnies
    Route::get('/compagnies/create', [CompagnieController::class, 'create'])->name('compagnies.create');
    Route::post('/compagnies', [CompagnieController::class, 'store'])->name('compagnies.store');

    // Bus
    Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
    Route::get('/buses/create', [BusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [BusController::class, 'store'])->name('buses.store');
    Route::get('/buses/{bus}/edit', [BusController::class, 'edit'])->name('buses.edit');
    Route::put('/buses/{bus}', [BusController::class, 'update'])->name('buses.update');
    Route::delete('/buses/{bus}', [BusController::class, 'destroy'])->name('buses.destroy');

    // Trajets
    Route::prefix('trajets')->group(function () {
        Route::get('/', [TrajetController::class, 'index'])->name('trajets.index');
        Route::post('/', [TrajetController::class, 'store'])->name('trajets.store');
        Route::get('/{trajet}/edit', [TrajetController::class, 'edit'])->name('trajets.edit');
        Route::put('/{trajet}', [TrajetController::class, 'update'])->name('trajets.update');
        Route::delete('/{trajet}', [TrajetController::class, 'destroy'])->name('trajets.destroy');
        Route::get('/{trajet}/details', [TrajetController::class, 'details'])->name('trajets.details');
    });

    // Sous-trajets
    Route::get('/trajets/{trajet}/sous-trajets/create', [SousTrajetController::class, 'create'])->name('sous-trajets.create');
    Route::post('/trajets/{trajet}/sous-trajets', [SousTrajetController::class, 'store'])->name('sous-trajets.store');

    // Chauffeurs
    Route::prefix('chauffeurs')->group(function () {
        Route::get('/', [ChauffeurController::class, 'index'])->name('chauffeurs.index');
        Route::get('/search', [ChauffeurController::class, 'search'])->name('chauffeurs.search');
        Route::post('/{user}/attach', [ChauffeurController::class, 'attach'])->name('chauffeurs.attach');
        Route::delete('/{user}/detach', [ChauffeurController::class, 'detach'])->name('chauffeurs.detach');
    });
});

// Tableau de bord Employé
Route::get('/employe/dashboard', [EmployeController::class, 'dashboard'])->name('employe.dashboard');

// Espace Voyageur (authentifié)
Route::middleware('auth')->group(function () {
    // Réservation et Paiement
    Route::prefix('reservations')->group(function () {
        Route::post('/', [VoyageurController::class, 'storeReservation'])->name('reservations.store');
        Route::get('/{reservation}/paiement', [PaiementController::class, 'index'])->name('paiement.index');
        Route::post('/{reservation}/paiement', [PaiementController::class, 'traitement'])->name('paiement.traitement');
        Route::get('/{reservation}/confirmation-paiement', [VoyageurController::class, 'confirmationPaiement'])->name('voyageur.confirmationPaiement');
        Route::get('/{reservation}/ticket-pdf', [VoyageurController::class, 'generateTicketPdf'])->name('voyageur.ticketPdf');
    });

    Route::post('/reservations/create', [VoyageurController::class, 'createReservationTrajet'])->name('reservations.createTrajet');

    // Recherche
    Route::get('/voyageur', [VoyageurController::class, 'index'])->name('voyageur.recherche');
    Route::get('/trajets/recherche', [VoyageurController::class, 'recherche'])->name('trajets.recherche');
});

// Stripe Webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
