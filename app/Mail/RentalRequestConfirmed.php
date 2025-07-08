<?php

namespace App\Mail;

use App\Models\RentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalRequestConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $rentalRequest;

    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function build()
    {
        return $this->subject('Sua Reserva foi Confirmada!')
            ->view('emails.rental-requests.confirmed');
    }
} 