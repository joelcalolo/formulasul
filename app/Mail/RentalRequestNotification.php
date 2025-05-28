<?php
namespace App\Mail;

use App\Models\RentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rentalRequest;

    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function build()
    {
        return $this->subject('Nova Solicitação de Aluguel')
                    ->view('emails.rental_request')
                    ->with(['rentalRequest' => $this->rentalRequest]);
    }
}