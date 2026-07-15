<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $subjectLine,
        public string $messageBody
    ) {}

    public function build()
    {
        return $this->subject('New Contact Message: ' . $this->subjectLine)
            ->replyTo($this->senderEmail, $this->senderName)
            ->view('emails.contact-message');
    }
}