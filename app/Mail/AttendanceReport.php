<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class AttendanceReport extends Mailable
{
    use Queueable, SerializesModels;
    public  $events;
    public $pdf;

    /**
     * Create a new message instance.
     */
    public function __construct($events, $pdf)
    {
        $this->events = $events;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Звіт із статистикою відвідуваності подій',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'api.emails.mail-attendance-report', // Blade-шаблон для листа
            with: [
                'events' => $this->events,                
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
        return [
            Attachment::fromData(fn () => $this->pdf->output(), 'Report.pdf')
                ->withMime('application/pdf')
        ];
    }
}
