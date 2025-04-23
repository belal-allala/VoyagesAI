<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $company = Company::create([
            'name' => $request->input('name'), 
            'email' => $request->input('email'), 
            'phone' => $request->input('phone'), 
            'address' => $request->input('address'), 
            'description' => $request->input('description'), 
        ]);

          return response()->json(['company' => $company, 'message' => 'Entreprise créée avec succès.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company) 
    {
         return response()->json(['company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company) 
    {
        $company->update($request->validated()); 

        return response()->json(['company' => $company, 'message' => 'Entreprise mise à jour avec succès.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company) 
    {
        $company->delete();

        return response()->noContent(204);
    }
}
