<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\User;

class EventTicket extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public User $user;
    public string $typeTicket;
    public string $finalPrice;
    public string $number;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, User $user, string $typeTicket, string $finalPrice, $number)
    {
        $this->event = $event;
        $this->user = $user;
        $this->typeTicket = $typeTicket;
        $this->finalPrice = $finalPrice;
        $this->number = $number;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Квиток на подію',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'api.emails.event-ticket', // Blade-шаблон для листа
            with: [
                'event' => $this->event,
                'user' => $this->user,
                'typeTicket' => $this->typeTicket,
                'finalPrice' => $this->finalPrice,
                'number' => $this->number,
            ],
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
