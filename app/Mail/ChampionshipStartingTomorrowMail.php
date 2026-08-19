<?php

namespace App\Mail;

use App\Models\Championship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChampionshipStartingTomorrowMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $address = null;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Championship $championship,
        public string $userName,
    ) {
        if ($this->championship?->address) {
            $address = $this->championship->address;
            $this->address = $address->street . ', ' . $address->number . ' - ' . $address->neighborhood . ' - ' . $address->city . ' - ' . $address->state;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'O campeonato comeca amanha!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.championship-starting',
            with: [
                'userName' => $this->userName,
                'championship' => $this->championship,
                'regulationUrl' => $this->championship->regulation_url,
                'address' => $this->address,
                'groupUrl' => $this->championship->wpp_group_link,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
