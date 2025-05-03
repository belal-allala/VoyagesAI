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
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        return view('auth.register'); 
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request  //Request de validation
     */
    public function handleRegister(RegisterRequest $request)
    {
        $data = [
            'nom' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        // // Si c'est un employé, on peut associer une compagnie
        // if ($request->role === 'employe' && $request->has('company_id')) {
        //     $data['company_id'] = $request->company_id;
        // }

        User::create($data);

        return redirect()->route('welcome')->with('message', 'Compte créé avec succès. Veuillez vous connecter.');
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