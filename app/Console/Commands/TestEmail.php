<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalRequestConfirmed;
use App\Models\RentalRequest;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        
        $this->info('Testando configuração de email...');
        $this->info('Email de destino: ' . $email);
        $this->info('Configuração de email: ' . config('mail.default'));
        
        try {
            // Teste simples
            Mail::raw('Teste de email do Formula Sul', function($message) use ($email) {
                $message->to($email)
                        ->subject('Teste de Email - Formula Sul');
            });
            
            $this->info('✅ Email de teste enviado com sucesso!');
            
            // Teste com template
            $rentalRequest = RentalRequest::with(['user', 'car'])->first();
            if ($rentalRequest) {
                Mail::to($email)->send(new RentalRequestConfirmed($rentalRequest));
                $this->info('✅ Email de confirmação de reserva enviado com sucesso!');
            } else {
                $this->warn('⚠️ Nenhuma reserva encontrada para testar o template');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao enviar email: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
} 