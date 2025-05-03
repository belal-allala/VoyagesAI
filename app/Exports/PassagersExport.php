<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PassagersExport implements FromCollection, WithHeadings
{
    protected $reservations;

    public function __construct($reservations)
    {
        $this->reservations = $reservations;
    }

    public function collection()
    {
        return $this->reservations->map(function ($reservation) {
            return [
                'Nom du passager' => $reservation->user->nom,
                'Email' => $reservation->user->email,
                'Nombre de passagers' => $reservation->nombre_passagers,
                'Trajet' => $reservation->trajet->name,
                'Ville de départ' => $reservation->ville_depart,
                'Ville d\'arrivée' => $reservation->ville_arrivee,
                'Date de départ' => $reservation->date_depart->format('d/m/Y H:i'),
                'Numéro de billet' => $reservation->billet ? $reservation->billet->numero_billet : 'N/A',
                'Statut du billet' => $reservation->billet ? $reservation->billet->status : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nom du passager',
            'Email',
            'Nombre de passagers',
            'Trajet',
            'Ville de départ',
            'Ville d\'arrivée',
            'Date de départ',
            'Numéro de billet',
            'Statut du billet',
        ];
    }

    public function download(string $filename, string $writerType = null, array $headers = []): BinaryFileResponse
    {
        $this->store($filename, 'local', $writerType);

        return response()->download(storage_path('app/' . $filename . '.xlsx'));
    }
}