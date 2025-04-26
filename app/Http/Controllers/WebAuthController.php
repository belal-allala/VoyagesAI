
**Contenu du fichier modifié :** `app/Http/Controllers/WebAuthController.php` (méthode `handleRegister` implémentée)

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
    public function handleRegister(RegisterRequest $request)  // On utilise la bonne request
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('home')->with('message', 'Compte créé avec succès. Veuillez vous connecter.');
    }
}