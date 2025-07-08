<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
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
        return $this->subject('Recuperação de Senha - Formula Sul')
                    ->markdown('emails.password-reset')
                    ->with([
                        'resetUrl' => route('password.reset', [
                            'token' => $this->token,
                            'email' => $this->email
                        ])
                    ]);
    }
} 