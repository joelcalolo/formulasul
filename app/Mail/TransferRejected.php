<?php

namespace App\Mail;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $transfer;

    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    public function build()
    {
        return $this->subject('Transfer/Passeio Rejeitado')
                    ->view('emails.transfers.rejected')
                    ->with(['transfer' => $this->transfer]);
    }
} 