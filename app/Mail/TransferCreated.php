<?php

namespace App\Mail;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $transfer;
    public $isAdminNotification;

    public function __construct(Transfer $transfer, $isAdminNotification = false)
    {
        $this->transfer = $transfer;
        $this->isAdminNotification = $isAdminNotification;
    }

    public function build()
    {
        if ($this->isAdminNotification) {
            return $this->subject('Nova Solicitação de Transfer/Passeio - Admin')
                        ->view('emails.transfers.created-admin')
                        ->with(['transfer' => $this->transfer]);
        }

        return $this->subject('Solicitação de Transfer/Passeio Enviada')
                    ->view('emails.transfers.created')
                    ->with(['transfer' => $this->transfer]);
    }
} 