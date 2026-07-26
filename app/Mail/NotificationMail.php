<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notifTitle;
    public $notifSubtitle;
    public $recipientName;

    public function __construct($notifTitle, $notifSubtitle, $recipientName = null)
    {
        $this->notifTitle    = $notifTitle;
        $this->notifSubtitle = $notifSubtitle;
        $this->recipientName = $recipientName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notifTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
        );
    }
}