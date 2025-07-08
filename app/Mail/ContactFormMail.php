<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        $subject = $this->emailData['subject'] ?? 'Nova Mensagem de Contato';
        
        return $this->subject($subject)
                    ->view('emails.contact-form')
                    ->with(['data' => $this->emailData]);
    }
} 