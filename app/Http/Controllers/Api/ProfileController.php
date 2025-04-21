<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource; // Importez UserResource

class ProfileController extends Controller
{
    /**
     * Get user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request) 
    {
        $user = $request->user(); 

        return new UserResource($user); 
    }
}