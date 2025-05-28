<?php

namespace App\Mail;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $transfer;

    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    public function build()
    {
        return $this->subject('Nova Solicitação de Transfer/Passeio')
                    ->view('emails.transfer')
                    ->with(['transfer' => $this->transfer]);
    }
}