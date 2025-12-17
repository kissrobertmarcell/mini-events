<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventSignupNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(private Event $event, private User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Új jelentkező az eseményedhez',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-signup',
            with: [
                'event' => $this->event,
                'user' => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
