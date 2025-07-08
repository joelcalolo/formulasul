<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RentalRequest;
use App\Models\User;
use App\Mail\RentalRequestCreated;
use App\Mail\RentalRequestConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestRentalEmail extends Command
{
    protected $signature = 'test:rental-email {--type=created} {--user-id=1}';
    protected $description = 'Testar envio de emails de aluguel';

    public function handle()
    {
        $type = $this->option('type');
        $userId = $this->option('user-id');

        try {
            // Buscar usuário
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuário com ID {$userId} não encontrado");
                return 1;
            }

            $this->info("Testando email para usuário: {$user->name} ({$user->email})");

            // Buscar uma reserva de teste
            $rentalRequest = RentalRequest::with(['user', 'car'])->first();
            if (!$rentalRequest) {
                $this->error("Nenhuma reserva encontrada no sistema");
                return 1;
            }

            $this->info("Usando reserva ID: {$rentalRequest->id}");

            if ($type === 'created') {
                $this->testCreatedEmail($rentalRequest, $user);
            } elseif ($type === 'confirmed') {
                $this->testConfirmedEmail($rentalRequest, $user);
            } else {
                $this->error("Tipo de email inválido. Use 'created' ou 'confirmed'");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Erro: " . $e->getMessage());
            Log::error('Erro no teste de email de aluguel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    private function testCreatedEmail($rentalRequest, $user)
    {
        $this->info("=== Testando Email de Reserva Criada ===");
        
        // Teste 1: Email para cliente (simulando o RentalRequestController)
        $this->info("1. Simulando envio do RentalRequestController:");
        $this->info("   - Cliente logado: {$user->email}");
        $this->info("   - Cliente da reserva: {$rentalRequest->user->email}");
        
        try {
            // Simular exatamente o que o RentalRequestController faz
            Mail::to($user->email)->send(new RentalRequestCreated($rentalRequest));
            $this->info("✓ Email enviado com sucesso para cliente logado: {$user->email}");
        } catch (\Exception $e) {
            $this->error("✗ Erro ao enviar email para cliente: " . $e->getMessage());
        }

        // Teste 2: Email para admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->info("2. Enviando email para admin: {$admin->email}");
            try {
                Mail::to($admin->email)->send(new RentalRequestCreated($rentalRequest, true));
                $this->info("✓ Email enviado com sucesso para admin");
            } catch (\Exception $e) {
                $this->error("✗ Erro ao enviar email para admin: " . $e->getMessage());
            }
        } else {
            $this->warn("Nenhum admin encontrado no sistema");
        }

        // Teste 3: Verificar configuração do mail
        $this->info("3. Configuração do mail:");
        $this->info("   - Driver: " . config('mail.default'));
        $this->info("   - From: " . config('mail.from.address'));
        $this->info("   - Host: " . config('mail.mailers.smtp.host'));
        
        // Teste 4: Verificar se há algum problema na configuração
        $this->info("4. Verificando configurações:");
        $this->info("   - MAIL_MAILER: " . env('MAIL_MAILER', 'não definido'));
        $this->info("   - MAIL_HOST: " . env('MAIL_HOST', 'não definido'));
        $this->info("   - MAIL_USERNAME: " . env('MAIL_USERNAME', 'não definido'));
        $this->info("   - MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS', 'não definido'));
    }

    private function testConfirmedEmail($rentalRequest, $user)
    {
        $this->info("=== Testando Email de Reserva Confirmada ===");
        
        $this->info("Simulando envio do AdminController:");
        $this->info("   - Cliente da reserva: {$rentalRequest->user->email}");
        
        try {
            // Simular exatamente o que o AdminController faz
            Mail::to($rentalRequest->user->email)->send(new RentalRequestConfirmed($rentalRequest));
            $this->info("✓ Email de confirmação enviado com sucesso para: {$rentalRequest->user->email}");
        } catch (\Exception $e) {
            $this->error("✗ Erro ao enviar email de confirmação: " . $e->getMessage());
        }

        // Verificar configuração do mail
        $this->info("Configuração do mail:");
        $this->info("   - Driver: " . config('mail.default'));
        $this->info("   - From: " . config('mail.from.address'));
        $this->info("   - Host: " . config('mail.mailers.smtp.host'));
    }
} 