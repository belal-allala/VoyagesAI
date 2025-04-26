<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAuthController;

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