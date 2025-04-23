<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class BusDelayNotification extends Notification
{
    use Queueable;

    protected $reservation;
    protected $delayMinutes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation, int $delayMinutes)
    {
        $this->reservation = $reservation;
        $this->delayMinutes = $delayMinutes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Information importante : Retard de votre voyage VoyageAI')
            ->markdown('mail.reservation.delay', [ 
                'reservation' => $this->reservation,
                'delayMinutes' => $this->delayMinutes,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
