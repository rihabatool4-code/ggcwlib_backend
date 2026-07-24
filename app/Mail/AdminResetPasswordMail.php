<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        $resetUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/admin/reset-password?token=' . $this->token . '&email=' . urlencode($this->email);

        return $this->subject('Reset Your GGCW Library Admin Password')
                    ->view('emails.admin-reset-password')
                    ->with(['resetUrl' => $resetUrl]);
    }
}