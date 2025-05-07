<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WebAuthController extends Controller
{
    public function register()
    {
        return view('auth.register'); 
    }

    public function handleRegister(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create([
            'nom' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
        ]);
        $user->profile()->create([]);
        return redirect()->route('login')->with('message', 'Compte créé avec succès. Veuillez vous connecter.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('welcome')) 
                            ->with('message', 'Connexion réussie.');
        }

        return back()->withErrors([
            'email' => 'Les informations d identification fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }
}