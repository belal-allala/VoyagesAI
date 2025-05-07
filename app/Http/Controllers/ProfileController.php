<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile; 
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load('profile'); 
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validatedUserData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);
        $validatedProfileData = $request->validate([
            'phone_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255', 
            'date_of_birth' => 'nullable|date',
            'profile_picture_upload' => 'nullable|image|max:2048', 
        ]);
        $user->update($validatedUserData);
         $profileDataToSave = $validatedProfileData;
         if ($request->hasFile('profile_picture_upload')) {
             if ($user->profile && $user->profile->profile_picture) {
                 \Storage::disk('public')->delete($user->profile->profile_picture);
             }
             $path = $request->file('profile_picture_upload')->store('profile_pictures', 'public');
             $profileDataToSave['profile_picture'] = $path;
         }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileDataToSave       
        );


        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
         $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile')->with('success', 'Mot de passe mis à jour avec succès.');
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        Auth::user()->delete(); 

        return redirect()->route('welcome')->with('message', 'Compte supprimé avec succès.');
    }
}