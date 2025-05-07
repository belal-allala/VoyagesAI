<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de réservation - Vega Go</title>
    <style>
        /* Styles optimisés pour DOMPDF */
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.4; /* Réduit l'espacement des lignes */
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 8.5pt; /* Taille de police de base légèrement plus petite */
            background-color: #ffffff;
        }

        .ticket-container {
            width: 100%;
            height: 100%;
            padding: 8mm; /* Réduit la marge de page */
            box-sizing: border-box;
        }

        .ticket {
            width: 100%; /* Utilise la largeur disponible moins le padding du conteneur */
            max-width: 190mm; /* Reste dans les limites d'un A4 portrait standard */
            margin: 0 auto;
            border: 1px solid #cccccc;
            background-color: #ffffff;
            padding: 6mm; /* Réduit le padding interne du ticket */
            box-shadow: 0 1mm 3mm rgba(0, 0, 0, 0.08); /* Ombre plus légère */
            border-radius: 4mm; /* Coins légèrement moins arrondis */
            overflow: hidden;
        }

        .header {
            text-align: center;
            margin-bottom: 4mm; /* Espacement réduit */
            padding-bottom: 3mm; /* Espacement réduit */
            border-bottom: 1px dashed #cccccc;
        }

        .header img.logo {
            max-width: 35mm; /* Taille max du logo réduite */
            height: auto;
            display: block;
            margin: 0 auto 3mm auto; /* Espacement réduit */
        }

        .header h1 {
            margin: 0 0 1.5mm 0; /* Espacement réduit */
            font-size: 13pt; /* Titre plus petit */
            font-weight: 800;
            color: #333333;
        }

        .header p {
            margin: 0;
            font-size: 9pt; /* Taille réduite */
            color: #555555;
        }

        /* Styles pour les sections d'information */
        .info-section {
            margin-bottom: 4mm; /* Espacement réduit entre les sections */
            padding-bottom: 3mm; /* Espacement réduit */
            border-bottom: 1px dashed #eeeeee;
        }

        .info-section:last-of-type {
             border-bottom: none;
             margin-bottom: 0;
             padding-bottom: 0;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
            /* Pas de marge en bas du tableau, c'est la div .info-section qui gère l'espacement */
        }

        table.info-table tr td {
            padding: 1mm 0; /* Réduit le padding dans les cellules */
            vertical-align: top;
            font-size: 8.5pt; /* Taille de police dans les tables */
        }

        td.info-label {
            font-weight: bold;
            width: 35mm; /* Réduit la largeur des labels */
            min-width: 35mm;
            color: #555555;
        }

        td.info-value strong {
            color: #000000;
        }

        .qr-code-section {
            text-align: center;
            margin: 6mm 0; /* Espacement réduit */
            padding: 3mm; /* Réduit le padding */
            background-color: #f9f9f9;
            border: 1px solid #eeeeee;
            border-radius: 3mm;
        }

        .qr-code-section h3 {
            margin: 0 0 2mm 0; /* Espacement réduit */
            font-size: 11pt;
            color: #333333;
        }

        .qr-code-section img {
            display: block;
            margin: 0 auto;
            width: 30mm; /* Taille de l'image QR réduite */
            height: 30mm;
            border: 1px solid #ffffff; /* Bordure plus fine */
            box-shadow: 0 0 3px rgba(0,0,0,0.08); /* Ombre plus légère */
        }

        .qr-code-section p {
            font-size: 7.5pt; /* Taille réduite */
            margin-top: 2mm; /* Espacement réduit */
            color: #777777;
        }

        .footer {
            text-align: center;
            margin-top: 4mm; /* Espacement réduit */
            padding-top: 3mm; /* Espacement réduit */
            border-top: 1px dashed #cccccc;
            font-size: 7.5pt; /* Taille réduite */
            color: #777777;
        }

        .footer p {
            margin: 0.5mm 0; /* Espacement très réduit */
        }

        .status-tag {
            display: inline-block;
            padding: 0.8mm 2mm; /* Padding réduit */
            background-color: #e0e0e0;
            color: #333;
            font-size: 7pt; /* Taille réduite */
            border-radius: 2mm;
            font-weight: bold;
            margin-left: 2mm; /* Espacement réduit */
            vertical-align: middle;
        }
         .status-tag.confirmed { background-color: #d4edda; color: #155724; }
         .status-tag.cancelled { background-color: #f8d7da; color: #721c24; }
         .status-tag.valide { background-color: #d1ecf1; color: #0c5460; }
         .status-tag.utilise { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket">
            <div class="header">
                 {{-- Assurez-vous de passer $logoDataUrl depuis le contrôleur si vous utilisez un logo --}}
                 {{-- <img src="{!! $logoDataUrl ?? '' !!}" alt="Logo Vega Go" class="logo"> --}}
                <h1>BILLET ÉLECTRONIQUE</h1>
                <p>Vega Go - Votre compagnie de voyage</p>
            </div>

            <!-- Section Informations du Billet -->
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Numéro billet:</td>
                        <td class="info-value">{{ $reservation->billet->numero_billet }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Statut billet:</td>
                        <td class="info-value">
                             <span class="status-tag {{ strtolower($reservation->billet->status) }}">
                                {{ ucfirst($reservation->billet->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Section Informations du Trajet -->
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Trajet:</td>
                        <td class="info-value">{{ $reservation->trajet->name }}</td>
                    </tr>
                    <tr>
                         <td class="info-label">Compagnie:</td>
                         <td class="info-value">{{ $reservation->trajet->bus->compagnie->nom ?? 'N/A' }}</td> {{-- Afficher la compagnie du bus --}}
                     </tr>
                    <tr>
                        <td class="info-label">Bus:</td>
                        <td class="info-value">{{ $reservation->trajet->bus->name }} ({{ $reservation->trajet->bus->plate_number }})</td>
                    </tr>
                    <tr>
                         <td class="info-label">Chauffeur:</td>
                         <td class="info-value">{{ $reservation->trajet->chauffeur->nom }}</td>
                     </tr>
                    <tr>
                        <td class="info-label">Date & Heure:</td>
                        <td class="info-value">{{ $reservation->date_depart->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">De:</td>
                        <td class="info-value">{{ $reservation->ville_depart }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">À:</td>
                        <td class="info-value">{{ $reservation->ville_arrivee }}</td>
                    </tr>
                     {{-- Inclure les étapes intermédiaires si elles sont pertinentes pour le voyageur --}}
                     {{-- Vous aurez besoin de passer la liste des sous-trajets pertinents à la vue --}}
                     {{-- <table class="info-table" style="margin-top: 2mm;">
                          <tr><td colspan="2" style="font-weight: bold; padding: 1mm 0; color: #555; font-size: 9pt;">Étapes:</td></tr>
                          @foreach($reservation->trajet->sousTrajets->sortBy('departure_time') as $step) // Assurez-vous que les sous-trajets sont liés à l'occurrence et triés
                                <tr>
                                    <td style="padding: 0.5mm 0 0.5mm 5mm; font-weight: normal; color: #777; font-size: 8pt;">
                                        {{ $step->departure_time->format('H:i') }}:
                                    </td>
                                    <td style="padding: 0.5mm 0; font-size: 8.5pt;">
                                        {{ $step->departure_city }} → {{ $step->destination_city }}
                                    </td>
                                </tr>
                          @endforeach
                     </table> --}}
                </table>
            </div>

            <!-- Section Informations du Passager et Prix -->
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Passager(s):</td>
                        <td class="info-value">{{ $reservation->user->nom }} ({{ $reservation->nombre_passagers }} pers.)</td>
                    </tr>
                    <tr>
                        <td class="info-label">Prix total payé:</td>
                        <td class="info-value"><strong>{{ number_format($reservation->prix_total, 2) }} MAD</strong></td>
                    </tr>
                </table>
            </div>

            <div class="qr-code-section">
                <h3>Code de Validation</h3>
                {{-- La variable passée au loadView doit être $qrCodeDataUrl --}}
                <img src="{!! $qrCodeDataUrl !!}" alt="QR Code du Billet">
                <p>Présentez ce code au chauffeur pour validation.</p> {{-- Texte légèrement plus long --}}
                <p style="font-weight: bold;">(Code: {{ $reservation->billet->numero_billet }})</p>
            </div>

            <div class="footer">
                <p>Merci d'avoir choisi Vega Go pour votre voyage.</p>
                <p style="font-weight: bold;">Conditions et informations:</p>
                <p>Ce billet est nominatif et non transférable sans autorisation.</p>
                <p>Consultez notre site web pour les politiques de bagages, d'annulation et de modification.</p>
                <p>En cas de problème, contactez-nous: support@vegago.ma | +212 522 123 456</p>
            </div>
        </div>
    </div>
</body>
</html>