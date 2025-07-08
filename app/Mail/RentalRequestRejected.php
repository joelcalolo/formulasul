<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\RentalRequest;

class RentalRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $rentalRequest;

    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function build()
    {
        return $this->subject('Sua solicitação de aluguel foi rejeitada')
            ->view('emails.rental_request_rejected');
    }
} 