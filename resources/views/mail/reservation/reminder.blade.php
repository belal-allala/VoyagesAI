@component('mail::message')
# Rappel de votre voyage avec VoyageAI

Bonjour {{ $reservation->passenger_name }},

Ceci est un rappel de votre voyage prévu avec VoyageAI.

Départ de: **{{ $reservation->bus->departure_city }}**
Destination: **{{ $reservation->bus->destination_city }}**
Date et heure de départ: **{{ $reservation->reservation_date }}**
Nombre de sièges: **{{ $reservation->seat_count }}**

Nous vous souhaitons un agréable voyage !

@component('mail::button', ['url' => url('/')])
Gérer votre réservation
@endcomponent

Cordialement,
L'équipe VoyageAI
@endcomponent