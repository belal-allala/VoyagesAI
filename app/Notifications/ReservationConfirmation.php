<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation; // Importez le modèle Reservation

class ReservationConfirmation extends Notification
{
    use Queueable;

    /**
     * The reservation instance.
     *
     * @var \App\Models\Reservation
     */
    protected $reservation; 

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Reservation  $reservation 
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Confirmation de votre réservation VoyageAI') 
            ->markdown('mail.reservation.confirmation', [ 
                'reservation' => $this->reservation, 
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}