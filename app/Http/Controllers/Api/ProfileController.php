<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource; 
use App\Http\Requests\UpdateProfileRequest;


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

    /**
     * Update the specified resource in storage, validated by UpdateProfileRequest.
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request // <-- Utilisez UpdateProfileRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user(); 
        $user->update($request->validated()); 
        return response()->json(new UserResource($user), 200); 
    }
}