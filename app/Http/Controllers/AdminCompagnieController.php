<?php

namespace App\Http\Controllers;

use App\Models\Compagnie;
use App\Models\Bus;
use App\Models\User;
use App\Models\Trajet;
use App\Models\Reservation;
use App\Models\Billet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon; 

class AdminCompagnieController extends Controller
{
    public function index()
    {
        $compagnies = Compagnie::withCount(['buses', 'users'])->paginate(10);
        $companyIds = $compagnies->pluck('id');

        $trajetCounts = Trajet::select('buses.company_id', \DB::raw('count(*) as count'))
            ->join('buses', 'trajets.bus_id', '=', 'buses.id')
            ->whereIn('buses.company_id', $companyIds)
            ->groupBy('buses.company_id')
            ->pluck('count', 'buses.company_id'); 

        $chauffeurCounts = User::whereIn('company_id', $companyIds)
            ->where('role', 'chauffeur')
            ->select('company_id', \DB::raw('count(*) as count'))
            ->groupBy('company_id')
            ->pluck('count', 'company_id'); 

        $compagnies->getCollection()->each(function ($compagnie) use ($trajetCounts, $chauffeurCounts) {
            $compagnie->trajets_count = $trajetCounts->get($compagnie->id, 0); 
            $compagnie->chauffeurs_count = $chauffeurCounts->get($compagnie->id, 0);
        });

        return view('admin.compagnies.index', compact('compagnies'));
    }

    public function create()
    {
        return view('admin.compagnies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:compagnies',
        ]);

        Compagnie::create($validated);

        return redirect()->route('admin.compagnies.index')
                         ->with('success', 'Compagnie créée avec succès.');
    }

    public function showStats(Compagnie $compagnie)
    {
        $busCount = $compagnie->buses()->count();
        $usersCount = $compagnie->users()->count();
        $employeCount = $compagnie->users()->where('role', 'employe')->count();
        $chauffeurCount = $compagnie->users()->where('role', 'chauffeur')->count();
        $trajetCount = Trajet::whereHas('bus', function($q) use ($compagnie) {
            $q->where('company_id', $compagnie->id);
        })->count();
        $baseCompanyReservationsQuery = Reservation::whereHas('trajet.bus', function($q) use ($compagnie) {
            $q->where('company_id', $compagnie->id);
        });
        $totalReservations = (clone $baseCompanyReservationsQuery)->count();
        $confirmedResTotalCount = (clone $baseCompanyReservationsQuery)->where('status', 'confirmed')->count();
        $cancelledReservationsCount = (clone $baseCompanyReservationsQuery)->where('status', 'cancelled')->count();
        $pendingReservationsCount = (clone $baseCompanyReservationsQuery)->where('status', 'pending')->count();
        $baseConfirmedReservationsQuery = (clone $baseCompanyReservationsQuery)->where('status', 'confirmed');
        $passengersTotal = (clone $baseConfirmedReservationsQuery)->sum('nombre_passagers');
        $totalRevenue = (clone $baseConfirmedReservationsQuery)->sum('prix_total');
        $companyTicketsQuery = Billet::whereHas('reservation.trajet.bus', function($q) use ($compagnie) {
            $q->where('company_id', $compagnie->id);
        });

        $totalIssuedTickets = (clone $companyTicketsQuery)->count();
        $validatedTicketsCount = (clone $companyTicketsQuery)->where('status', 'utilise')->count();

        $now = Carbon::now();
        $today = $now->copy()->startOfDay(); 

        $confirmedResTodayQuery = (clone $baseConfirmedReservationsQuery)->whereDate('date_depart', $today); 
        $confirmedResTodayCount = $confirmedResTodayQuery->count();
        $revenueToday = $confirmedResTodayQuery->sum('prix_total');
        $passengersToday = $confirmedResTodayQuery->sum('nombre_passagers');

        $startOfMonth = $now->copy()->startOfMonth(); 
        $endOfMonth = $now->copy()->endOfMonth(); 

        $confirmedResThisMonthQuery = (clone $baseConfirmedReservationsQuery)->whereBetween('date_depart', [$startOfMonth, $endOfMonth]); // Filtrer par l'intervalle du mois
        $confirmedResThisMonthCount = $confirmedResThisMonthQuery->count();
        $revenueThisMonth = $confirmedResThisMonthQuery->sum('prix_total');
        $passengersThisMonth = $confirmedResThisMonthQuery->sum('nombre_passagers');

        return view('admin.compagnies.show-stats', compact(
            'compagnie',
            'busCount',
            'usersCount',
            'employeCount',
            'chauffeurCount',
            'trajetCount',
            'totalReservations',
            'confirmedResTotalCount',
            'cancelledReservationsCount',
            'pendingReservationsCount',
            'totalRevenue',
            'passengersTotal',
            'totalIssuedTickets',
            'validatedTicketsCount',
            'confirmedResTodayCount',
            'revenueToday',
            'passengersToday',
            'confirmedResThisMonthCount',
            'revenueThisMonth',
            'passengersThisMonth'
        ));
    }

    public function edit(Compagnie $compagnie)
    {
        return view('admin.compagnies.edit', compact('compagnie'));
    }

    public function update(Request $request, Compagnie $compagnie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('compagnies')->ignore($compagnie->id)],
        ]);

        $compagnie->update($validated);

        return redirect()->route('admin.compagnies.showStats', $compagnie)
                         ->with('success', 'Compagnie mise à jour avec succès.');
    }

    public function destroy(Compagnie $compagnie)
    {
        if ($compagnie->buses()->exists()) {
             return redirect()->back()->with('error', 'Impossible de supprimer cette compagnie car elle a des bus associés.');
        }
        if ($compagnie->users()->exists()) {
             return redirect()->back()->with('error', 'Impossible de supprimer cette compagnie car elle a des utilisateurs (employés/chauffeurs) associés.');
        }
        $compagnie->delete();
        return redirect()->route('admin.compagnies.index')
                         ->with('success', 'Compagnie supprimée avec succès.');
    }
}