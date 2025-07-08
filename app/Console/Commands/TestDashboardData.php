<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transfer;
use App\Models\RentalRequest;
use App\Models\Car;

class TestDashboardData extends Command
{
    protected $signature = 'test:dashboard-data {--user-id=1}';
    protected $description = 'Testar dados do dashboard';

    public function handle()
    {
        $userId = $this->option('user-id');

        try {
            // Buscar usuário
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuário com ID {$userId} não encontrado");
                return 1;
            }

            $this->info("Testando dados para usuário: {$user->name} ({$user->email}) - Role: {$user->role}");

            // Simular o que o AdminController faz
            if ($user->role === 'admin') {
                $this->info("=== DADOS PARA ADMIN ===");
                $rentalRequests = RentalRequest::with(['user', 'car'])->get();
                $transfers = Transfer::with('user')->get();
                $cars = Car::all();
            } else {
                $this->info("=== DADOS PARA CLIENTE ===");
                $rentalRequests = $user->rentalRequests()->with(['car'])->get();
                $transfers = $user->transfers()->get();
                $cars = Car::where('status', 'disponivel')->get();
            }

            // Mostrar estatísticas
            $this->info("Estatísticas:");
            $this->info("  - Reservas: {$rentalRequests->count()}");
            $this->info("  - Transfers: {$transfers->count()}");
            $this->info("  - Carros: {$cars->count()}");

            // Mostrar detalhes dos transfers
            if ($transfers->count() > 0) {
                $this->info("\n=== DETALHES DOS TRANSFERS ===");
                foreach ($transfers as $transfer) {
                    $this->info("Transfer ID: {$transfer->id}");
                    $this->info("  - Origem: {$transfer->origem}");
                    $this->info("  - Destino: {$transfer->destino}");
                    $this->info("  - Data/Hora: " . ($transfer->data_hora ? $transfer->data_hora->format('d/m/Y H:i') : 'N/A'));
                    $this->info("  - Status: {$transfer->status}");
                    $this->info("  - Tipo: {$transfer->tipo}");
                    if ($user->role === 'admin') {
                        $this->info("  - Usuário: " . ($transfer->user ? $transfer->user->name : 'N/A'));
                    }
                    $this->info("  ---");
                }
            } else {
                $this->warn("Nenhum transfer encontrado para este usuário.");
            }

            // Mostrar detalhes das reservas
            if ($rentalRequests->count() > 0) {
                $this->info("\n=== DETALHES DAS RESERVAS ===");
                foreach ($rentalRequests as $request) {
                    $this->info("Reserva ID: {$request->id}");
                    $this->info("  - Carro: " . ($request->car ? $request->car->marca . ' ' . $request->car->modelo : 'N/A'));
                    $this->info("  - Data Início: " . ($request->data_inicio ? $request->data_inicio->format('d/m/Y') : 'N/A'));
                    $this->info("  - Status: {$request->status}");
                    if ($user->role === 'admin') {
                        $this->info("  - Usuário: " . ($request->user ? $request->user->name : 'N/A'));
                    }
                    $this->info("  ---");
                }
            } else {
                $this->warn("Nenhuma reserva encontrada para este usuário.");
            }

        } catch (\Exception $e) {
            $this->error("Erro: " . $e->getMessage());
            return 1;
        }
    }
} 