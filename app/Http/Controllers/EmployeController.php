<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compagnie;
use App\Models\Bus;
use App\Models\Trajet;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Billet; 
use App\Exports\EmployeTrajetsExport; 
use Maatwebsite\Excel\Facades\Excel; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB; 

class EmployeController extends Controller
{
    public function dashboard(Request $request)
    {
        $compagnie = auth()->user()->compagnie;
        $busesCount = 0;
        $trajetsTotalCount = 0; 
        $chauffeursCount = 0;
        $totalReservations = 0;
        $confirmedResTotalCount = 0;
        $cancelledReservationsCount = 0;
        $pendingReservationsCount = 0;
        $totalRevenue = 0;
        $passengersTotal = 0;
        $totalIssuedTickets = 0;
        $validatedTicketsCount = 0;
        $confirmedResTodayCount = 0;
        $revenueToday = 0;
        $passengersToday = 0;
        $confirmedResThisMonthCount = 0;
        $revenueThisMonth = 0;
        $passengersThisMonth = 0;
        $trajetsList = collect();
        $filterDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        if ($compagnie) {
            $busesCount = Bus::where('company_id', $compagnie->id)->count();
            $trajetsTotalCount = Trajet::whereHas('bus', function($q) use ($compagnie) {
                $q->where('company_id', $compagnie->id);
            })->count();
            $chauffeursCount = User::where('company_id', $compagnie->id)
                              ->where('role', 'chauffeur')
                              ->count();
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

            $confirmedResThisMonthQuery = (clone $baseConfirmedReservationsQuery)->whereBetween('date_depart', [$startOfMonth, $endOfMonth]);
            $confirmedResThisMonthCount = $confirmedResThisMonthQuery->count();
            $revenueThisMonth = $confirmedResThisMonthQuery->sum('prix_total');
            $passengersThisMonth = $confirmedResThisMonthQuery->sum('nombre_passagers');

            $trajetsForFilteredDate = Trajet::whereHas('bus', function($q) use ($compagnie) {
                                    $q->where('company_id', $compagnie->id);
                                })

                                ->whereHas('sousTrajets', function($q) use ($filterDate) {
                                    $q->whereDate('departure_time', $filterDate);
                                })
                                ->with(['bus', 'sousTrajets' => function($query) use ($filterDate) {
                                     $query->whereDate('departure_time', $filterDate)->orderBy('departure_time');
                                }, 'reservations' => function($query) use ($filterDate) {
                                     $query->where('status', 'confirmed')
                                           ->whereDate('date_depart', $filterDate);
                                }])
                                ->get();

            $trajetsList = $trajetsForFilteredDate->map(function($trajet) use ($filterDate) {
                $firstSousTrajet = $trajet->sousTrajets->first();
                $lastSousTrajet = $trajet->sousTrajets->last();
                if (!$firstSousTrajet || !$lastSousTrajet) {
                    return null; 
                }

                $confirmedReservations = $trajet->reservations;

                $confirmedReservationsCount = $confirmedReservations->count();
                $totalPassengersBooked = $confirmedReservations->sum('nombre_passagers'); 
                $totalRevenueForTrajet = $confirmedReservations->sum('prix_total'); 

                $busCapacity = $trajet->bus ? $trajet->bus->capacity : 0; 
                $availableSeats = $busCapacity - $totalPassengersBooked; 

                $trajet->confirmed_reservations_count = $confirmedReservationsCount;
                $trajet->total_passengers_booked = $totalPassengersBooked; 
                $trajet->available_seats = $availableSeats;
                $trajet->total_revenue = $totalRevenueForTrajet;
                $trajet->departure_time_for_date = $firstSousTrajet->departure_time; 
                $trajet->arrival_time_for_date = $lastSousTrajet->arrival_time; 
                $trajet->departure_city_for_date = $firstSousTrajet->departure_city; 
                $trajet->arrival_city_for_date = $lastSousTrajet->destination_city;


                return $trajet;
            })->filter();

             $trajetsList = $trajetsList->sortBy(fn($trajet) => $trajet->departure_time_for_date);

        }

        return view('employe.dashboard', compact(
            'compagnie',
            'busesCount',
            'trajetsTotalCount', 
            'chauffeursCount',
            'trajetsList',
            'filterDate', 
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

    public function exportTrajetsExcel(Request $request)
    {
        $filterDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $compagnie = auth()->user()->compagnie;

        if (!$compagnie) {
             return redirect()->back()->with('error', 'Vous devez être affilié à une compagnie pour exporter des données.');
        }
         $trajetsToExport = Trajet::whereHas('bus', function($q) use ($compagnie) {
                                    $q->where('company_id', $compagnie->id);
                                })
                                ->whereHas('sousTrajets', function($q) use ($filterDate) {
                                    $q->whereDate('departure_time', $filterDate);
                                })
                                ->with(['bus', 'sousTrajets' => function($query) use ($filterDate) {
                                     $query->whereDate('departure_time', $filterDate)->orderBy('departure_time');
                                }, 'reservations' => function($query) use ($filterDate) {
                                     $query->where('status', 'confirmed')
                                           ->whereDate('date_depart', $filterDate);
                                }])
                                ->get();
         $trajetsToExport = $trajetsToExport->map(function($trajet) use ($filterDate) {
             $sousTrajetsForDate = $trajet->sousTrajets; 

             $firstSousTrajet = $sousTrajetsForDate->first();
             $lastSousTrajet = $sousTrajetsForDate->last();

             if (!$firstSousTrajet || !$lastSousTrajet) {
                 return null; 
             }

             $confirmedReservations = $trajet->reservations->where('status', 'confirmed'); // Déjà filtrées par date et statut via with()

             $confirmedReservationsCount = $confirmedReservations->count();
             $totalPassengersBooked = $confirmedReservations->sum('nombre_passagers');
             $totalRevenueForTrajet = $confirmedReservations->sum('prix_total');

             $busCapacity = $trajet->bus ? $trajet->bus->capacity : 0;
             $availableSeats = $busCapacity - $totalPassengersBooked;
             $trajet->confirmed_reservations_count = $confirmedReservationsCount;
             $trajet->total_passagers_booked = $totalPassengersBooked;
             $trajet->available_seats = $availableSeats;
             $trajet->total_revenue = $totalRevenueForTrajet;
             $trajet->departure_time_for_date = $firstSousTrajet->departure_time;
             $trajet->arrival_time_for_date = $lastSousTrajet->arrival_time;
             $trajet->departure_city_for_date = $firstSousTrajet->departure_city;
             $trajet->arrival_city_for_date = $lastSousTrajet->destination_city;

             return $trajet;
         })->filter(); 
         
         $fileName = 'trajets_employe_' . $filterDate . '.xlsx';

         return Excel::download(new EmployeTrajetsExport($trajetsToExport), $fileName);
    }
}