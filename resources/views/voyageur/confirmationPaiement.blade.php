@extends('layouts.app')

@section('title', 'Confirmation de paiement')

@section('content')
    <div class="container mx-auto py-6">
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6 text-center">Confirmation de paiement</h1>

            @if($reservation->status === 'confirmed')
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Votre paiement a été effectué avec succès !</p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-center mt-6">
                        <button id="download-pdf" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Télécharger le ticket (PDF)
                        </button>
                    </div>
                </div>
            @elseif($reservation->status === 'payment_failed')
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Votre paiement a échoué. Veuillez réessayer ou utiliser un autre moyen de paiement.</p>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-gray-700 text-center">Le statut du paiement est inconnu.</p>
            @endif
        </div>
    </div>

    <!-- Ajouter le CDN de jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const { jsPDF } = window.jspdf;

            document.getElementById('download-pdf').addEventListener('click', function () {
                const doc = new jsPDF();

                // Ajout du titre du ticket
                doc.setFontSize(20);
                doc.text('Ticket de Réservation', 20, 20);
                doc.setFontSize(12);

                // Ajout des informations du ticket
                doc.text('Numéro de Billet: {{ $reservation->billet->numero_billet }}', 20, 30);
                doc.text('Trajet: {{ $reservation->trajet->name }}', 20, 40);
                doc.text('Départ: {{ $reservation->ville_depart }} - {{ $reservation->date_depart->format('d/m/Y H:i') }}', 20, 50);
                doc.text('Arrivée: {{ $reservation->ville_arrivee }} - {{ $reservation->date_arrivee->format('d/m/Y H:i') }}', 20, 60);
                doc.text('Passagers: {{ $reservation->nombre_passagers }}', 20, 70);
                doc.text('Prix total: {{ number_format($reservation->prix_total, 2) }} MAD', 20, 80);

                // Ajouter le QR Code (si applicable)
                doc.text('QR Code:', 20, 90);
                // Ici, il faut encoder ton QR Code en base64 ou l'intégrer en tant qu'image
                // doc.addImage('{{ $qrCodeSvg }}', 'SVG', 20, 100, 50, 50);

                // Ajout d'une note de bas de page
                doc.text('Merci d\'avoir choisi VoyageAI', 20, 120);
                doc.text('Présentez ce ticket à l\'embarquement', 20, 130);

                // Sauvegarde du PDF avec un nom spécifique
                doc.save('ticket-{{ $reservation->billet->numero_billet }}.pdf');
            });
        });
    </script>
@endsection
