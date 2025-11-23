<?php

namespace App\Mail;

use App\Models\ApplicationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;
    public $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(ApplicationNotification $notification, $recipient)
    {
        $this->notification = $notification;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notification->title . ' - Taeab.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->notification->recipient_type === 'seeker' 
            ? 'emails.notification-seeker' 
            : 'emails.notification-company';

        return new Content(
            view: $view,
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
