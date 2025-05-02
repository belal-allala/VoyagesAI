<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de réservation - VoyageAI</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket { width: 100%; max-width: 600px; margin: 0 auto; border: 2px solid #000; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 150px; }
        .info-section { margin-bottom: 15px; }
        .info-label { font-weight: bold; display: inline-block; width: 150px; }
        .qr-code { text-align: center; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>Ticket de réservation</h1>
            <p>VoyageAI - Votre compagnie de voyage</p>
        </div>

        <div class="info-section">
            <span class="info-label">Numéro de billet:</span>
            {{ $reservation->billet->numero_billet }}
        </div>

        <div class="info-section">
            <span class="info-label">Trajet:</span>
            {{ $reservation->trajet->name }}
        </div>

        <div class="info-section">
            <span class="info-label">Départ:</span>
            {{ $reservation->ville_depart }} - {{ $reservation->date_depart->format('d/m/Y H:i') }}
        </div>

        <div class="info-section">
            <span class="info-label">Arrivée:</span>
            {{ $reservation->ville_arrivee }} - {{ $reservation->date_arrivee->format('d/m/Y H:i') }}
        </div>

        <div class="info-section">
            <span class="info-label">Passagers:</span>
            {{ $reservation->nombre_passagers }}
        </div>

        <div class="info-section">
            <span class="info-label">Prix total:</span>
            {{ number_format($reservation->prix_total, 2) }} MAD
        </div>

        <div class="qr-code">
            <h3>Code QR</h3>
            {!! $qrCodeSvg !!}
        </div>

        <div class="footer">
            <p>Merci d'avoir choisi VoyageAI</p>
            <p>Présentez ce ticket à l'embarquement</p>
        </div>
    </div>
</body>
</html>