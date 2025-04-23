@component('mail::message')
# Retard de votre voyage avec VoyageAI

Bonjour {{ $reservation->passenger_name }},

Nous vous informons que votre voyage au départ de **{{ $reservation->bus->departure_city }}** vers **{{ $reservation->bus->destination_city }}** prévu le **{{ $reservation->reservation_date }}** subit un retard estimé à **{{ $delayMinutes }} minutes**.

Nous nous excusons pour ce désagrément.

Vous pouvez consulter les dernières informations sur votre réservation sur notre site web.

@component('mail::button', ['url' => url('/')])
Consulter votre réservation
@endcomponent

Cordialement,
L'équipe VoyageAI
@endcomponent