<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Compagnie;
use App\Models\Trajet;
use App\Models\Reservation;
use App\Models\Billet;
use App\Models\Profile;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('compagnie'); 
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('email', 'like', '%' . $searchTerm . '%');
        }
        if ($request->has('role') && $request->input('role') !== 'all') {
            $query->where('role', $request->input('role'));
        }

        $users = $query->paginate(10);
        $roles = ['voyageur', 'employe', 'chauffeur', 'admin']; 
        $compagnies = Compagnie::all();

        return view('admin.users.index', compact('users', 'roles', 'compagnies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'form_name' => 'required|string|in:add_employee', 
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)], 
            'role' => ['required', Rule::in(['employe'])], 
        ]);
        $user = User::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        $user->profile()->create([
         ]);
        return redirect()->route('admin.users.index')->with('success', 'Employé créé avec succès.');
    }
    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'nom' => 'sometimes|string|max:255',
        'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
        'role' => ['sometimes', Rule::in(['voyageur', 'employe', 'chauffeur', 'admin'])],
        'compagnie_id' => 'nullable|exists:compagnies,id', 
        'password' => 'nullable|min:8|confirmed',
    ]);

    if ($request->filled('password')) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']); 
    }

    $user->update($validated);

    return response()->json(['success' => true, 'message' => 'Utilisateur mis à jour avec succès.']);
}

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function stats(User $user)
    {
        $reservationsCount = $user->reservations()->count();

        return view('admin.users.stats', compact('user', 'reservationsCount')); 
    }
    public function chauffeurStats(User $user)
    {
        if (!$user->hasRole('chauffeur')) {
             return redirect()->route('admin.users.index')->with('error', 'Cette page de statistiques est uniquement pour les chauffeurs.');
        }
        $user->load(['trajetsAsChauffeur.reservations' => function($query) {
            $query->where('status', 'confirmed'); 
        }, 'trajetsAsChauffeur.reservations.billet', 'compagnie']);


        $assignedTrajets = $user->trajetsAsChauffeur;
        $totalAssignedTrajets = $assignedTrajets->count();
        $completedTrajets = $assignedTrajets->filter(fn($trajet) => $trajet->status == 'Passé');
        $upcomingTrajets = $assignedTrajets->filter(fn($trajet) => $trajet->status == 'À venir' || $trajet->status == 'En cours');


        $completedTrajetsCount = $completedTrajets->count();
        $upcomingTrajetsCount = $upcomingTrajets->count();
        $totalPassengersTransported = 0;
        $totalConfirmedReservationsOnCompletedTrajets = 0;
        $totalValidatedTickets = 0;

        foreach ($completedTrajets as $trajet) {
            $confirmedReservations = $trajet->reservations;

            $totalConfirmedReservationsOnCompletedTrajets += $confirmedReservations->count();
            $totalPassengersTransported += $confirmedReservations->sum('nombre_passagers');
            foreach ($confirmedReservations as $reservation) {
                if ($reservation->billet && $reservation->billet->status === 'utilise') {
                    $totalValidatedTickets++;
                }
            }
        }
        $validationRate = $totalConfirmedReservationsOnCompletedTrajets > 0
                          ? round(($totalValidatedTickets / $totalConfirmedReservationsOnCompletedTrajets) * 100, 2)
                          : 0;
        return view('admin.users.chauffeur-stats', compact(
            'user',
            'totalAssignedTrajets',
            'completedTrajetsCount',
            'upcomingTrajetsCount',
            'totalPassengersTransported',
            'totalValidatedTickets',
            'totalConfirmedReservationsOnCompletedTrajets',
            'validationRate'
        ));
    }

    public function employeStats(User $user)
    {
         if (!$user->hasRole('employe')) {
             return redirect()->route('admin.users.index')->with('error', 'Cette page de statistiques est uniquement pour les employés.');
         }
        $user->load('compagnie');
        $company = $user->compagnie;
        $busCount = 0;
        $trajetCount = 0;
        $chauffeurCount = 0;
        $totalReservations = 0;
        $confirmedReservationsCount = 0;
        $cancelledReservationsCount = 0;
        $pendingReservationsCount = 0;
        $totalRevenue = 0;
        $totalIssuedTickets = 0; 
        $validatedTicketsCount = 0; 


        if ($company) {
            $busCount = $company->buses()->count();
            $trajetCount = Trajet::whereHas('bus', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->count();
            $chauffeurCount = User::where('company_id', $company->id)->where('role', 'chauffeur')->count();
            $companyReservationsQuery = Reservation::whereHas('trajet.bus', function($q) use ($company) {
                $q->where('company_id', $company->id);
            });

            $totalReservations = $companyReservationsQuery->count();
            $confirmedReservationsCount = $companyReservationsQuery->where('status', 'confirmed')->count();
            $cancelledReservationsCount = $companyReservationsQuery->where('status', 'cancelled')->count();
            $pendingReservationsCount = $companyReservationsQuery->where('status', 'pending')->count();
            $totalRevenue = $companyReservationsQuery->where('status', 'confirmed')->sum('prix_total'); 
            $companyTicketsQuery = Billet::whereHas('reservation.trajet.bus', function($q) use ($company) {
                $q->where('company_id', $company->id);
            });

            $totalIssuedTickets = $companyTicketsQuery->count();
            $validatedTicketsCount = $companyTicketsQuery->where('status', 'utilise')->count();
        }

        return view('admin.users.employe-stats', compact(
            'user',
            'company',
            'busCount',
            'trajetCount',
            'chauffeurCount',
            'totalReservations', 
            'confirmedReservationsCount',
            'cancelledReservationsCount',
            'pendingReservationsCount', 
            'totalRevenue', 
            'totalIssuedTickets', 
            'validatedTicketsCount' 
        ));
    }
}