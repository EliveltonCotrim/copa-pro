<?php

namespace App\Notifications;

use App\Mail\SuccessfullyRegisteredMail;
use App\Models\Championship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class SuccessfullyRegistered extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 30, 60];

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Championship $championship
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): SuccessfullyRegisteredMail
    {
        return (new SuccessfullyRegisteredMail($this->championship, $notifiable))
            ->subject('Inscrição realizada com sucesso! ⚽')
            ->from('suporte@gabiferreiraterapeuta.com.br')
            ->to($notifiable->email);
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
