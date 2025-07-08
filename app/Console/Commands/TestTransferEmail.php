<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Transfer;
use App\Models\User;
use App\Mail\TransferCreated;
use App\Mail\TransferConfirmed;
use App\Mail\TransferRejected;

class TestTransferEmail extends Command
{
    protected $signature = 'test:transfer-email {type=created} {--transfer-id=}';
    protected $description = 'Testar envio de emails de transfer';

    public function handle()
    {
        $type = $this->argument('type');
        $transferId = $this->option('transfer-id');

        if ($transferId) {
            $transfer = Transfer::with('user')->find($transferId);
            if (!$transfer) {
                $this->error("Transfer com ID {$transferId} não encontrado!");
                return 1;
            }
        } else {
            $transfer = Transfer::with('user')->first();
            if (!$transfer) {
                $this->error("Nenhum transfer encontrado no banco de dados!");
                return 1;
            }
        }

        $this->info("Testando email de transfer {$type}...");
        $this->info("Transfer ID: {$transfer->id}");
        $this->info("Cliente: {$transfer->user->name} ({$transfer->user->email})");
        $this->info("Tipo: {$transfer->tipo}");
        $this->info("Origem: {$transfer->origem}");
        $this->info("Destino: {$transfer->destino}");

        try {
            switch ($type) {
                case 'created':
                    // Testar email para o cliente
                    Mail::to($transfer->user->email)->send(new TransferCreated($transfer));
                    $this->info("✅ Email de transfer criado enviado para o cliente: {$transfer->user->email}");

                    // Testar email para administradores
                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new TransferCreated($transfer, true));
                        $this->info("✅ Email de transfer criado enviado para admin: {$admin->email}");
                    }
                    break;

                case 'confirmed':
                    Mail::to($transfer->user->email)->send(new TransferConfirmed($transfer));
                    $this->info("✅ Email de transfer confirmado enviado para: {$transfer->user->email}");
                    break;

                case 'rejected':
                    Mail::to($transfer->user->email)->send(new TransferRejected($transfer));
                    $this->info("✅ Email de transfer rejeitado enviado para: {$transfer->user->email}");
                    break;

                default:
                    $this->error("Tipo de email inválido. Use: created, confirmed, ou rejected");
                    return 1;
            }

            $this->info("🎉 Teste de email concluído com sucesso!");
            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar email: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
} 