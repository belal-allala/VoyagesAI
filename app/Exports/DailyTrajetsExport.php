<?php

namespace App\Exports;

use App\Models\Trajet;
use Illuminate\Support\Collection; // Assurez-vous d'importer Collection
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Pour mapper les données

class DailyTrajetsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $trajets;

    public function __construct(Collection $trajets)
    {
        $this->trajets = $trajets;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // Retourne la collection de trajets passée au constructeur
        return $this->trajets;
    }

    /**
     * @return array Les en-têtes des colonnes dans le fichier Excel
     */
    public function headings(): array
    {
        return [
            'ID Trajet', // Utile pour le débogage
            'Nom du Trajet',
            'Départ (Date & Heure)',
            'Arrivée (Date & Heure)',
            'Bus',
            'Bus Capacité', // Ajouté, utile pour calculer les places dispo
            'Réservations Confirmées',
            'Passagers Confirmés',
            'Places Disponibles',
            'Revenu Journalier (MAD)'
        ];
    }

    /**
     * @var Trajet $trajet Le modèle Trajet pour chaque ligne
     * @return array Une ligne de données pour le fichier Excel
     */
    public function map($trajet): array
    {
        // Calculez les stats nécessaires pour chaque trajet
        $confirmedReservations = $trajet->reservations; // Les réservations confirmées sont déjà chargées
        $confirmedReservationsCount = $confirmedReservations->count();
        $confirmedPassengersCount = $confirmedReservations->sum('nombre_passagers');
        $dailyRevenue = $confirmedReservations->sum('prix_total');
        $busCapacity = $trajet->bus ? $trajet->bus->capacity : 0; // Utiliser la capacité du bus
        $availableSeats = $busCapacity - $confirmedPassengersCount; // Calculer les places disponibles

        // Récupérer la première et la dernière étape pour les horaires
        $firstSousTrajet = $trajet->sousTrajets->first(); // Assurez-vous qu'ils sont triés par departure_time dans l'eager loading
        $lastSousTrajet = $trajet->sousTrajets->last();

        $departureTime = $firstSousTrajet ? $firstSousTrajet->departure_time->format('Y-m-d H:i') : 'N/A';
        $arrivalTime = $lastSousTrajet ? $lastSousTrajet->arrival_time->format('Y-m-d H:i') : 'N/A';
        $busName = $trajet->bus ? $trajet->bus->name : 'N/A';


        return [
            $trajet->id,
            $trajet->name,
            $departureTime,
            $arrivalTime,
            $busName,
            $busCapacity,
            $confirmedReservationsCount,
            $confirmedPassengersCount,
            $availableSeats,
            number_format($dailyRevenue, 2, '.', '') // Formater le revenu avec 2 décimales, point décimal, sans séparateur de milliers pour Excel
        ];
    }
}