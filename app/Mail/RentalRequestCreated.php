<?php

namespace App\Mail;

use App\Models\RentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalRequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $rentalRequest;
    public $isAdminNotification;

    public function __construct(RentalRequest $rentalRequest, bool $isAdminNotification = false)
    {
        $this->rentalRequest = $rentalRequest;
        $this->isAdminNotification = $isAdminNotification;
    }

    public function build()
    {
        $subject = $this->isAdminNotification 
            ? 'Nova Solicitação de Aluguel Recebida'
            : 'Sua Solicitação de Aluguel foi Recebida';

        return $this->subject($subject)
            ->markdown('emails.rental-requests.created');
    }
} 