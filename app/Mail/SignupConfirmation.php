<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignupConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(private Event $event)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Eseményre való jelentkezés megerősítése',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.signup-confirmation',
            with: [
                'event' => $this->event,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
