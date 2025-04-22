@component('mail::message')
# Confirmation de votre réservation VoyageAI

Bonjour {{ $reservation->passenger_name }},

Votre réservation a été confirmée avec succès. Voici les détails de votre voyage :

Numéro de réservation: **{{ $reservation->id }}**
Ville de départ: **{{ $reservation->bus->departure_city }}**
Ville de destination: **{{ $reservation->bus->destination_city }}**
Date et heure de départ: **{{ $reservation->reservation_date }}**
Nombre de sièges: **{{ $reservation->seat_count }}**
Prix total: **{{ $reservation->total_price }} €**

Merci d'utiliser VoyageAI !

Cordialement,
L'équipe VoyageAI
@endcomponent