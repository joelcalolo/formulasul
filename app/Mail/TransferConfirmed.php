<?php

namespace App\Mail;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $transfer;

    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    public function build()
    {
        return $this->subject('Transfer/Passeio Confirmado')
                    ->view('emails.transfers.confirmed')
                    ->with(['transfer' => $this->transfer]);
    }
} 