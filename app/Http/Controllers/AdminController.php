<?php

namespace App\Http\Controllers;

use App\Models\RentalRequest;
use App\Models\Transfer;
use App\Models\Car;
use App\Models\PriceTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalRequestCreated;
use App\Mail\RentalRequestConfirmed;
use App\Mail\TransferConfirmed;
use App\Mail\TransferRejected;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Diferenciação baseada no papel do usuário
        if ($user->role === 'admin') {
            // Admin vê todas as reservas e transfers
            $rentalRequests = RentalRequest::with(['user', 'car'])->get();
            $transfers = Transfer::with('user')->get();
            $cars = Car::all();
        } else {
            // Cliente vê apenas suas próprias reservas e transfers
            $rentalRequests = $user->rentalRequests()->with(['car'])->get();
            $transfers = $user->transfers()->get();
            $cars = Car::where('status', 'disponivel')->get(); // Apenas carros disponíveis para clientes
        }

        return view('dashboard', compact('rentalRequests', 'transfers', 'cars'));
    }

    public function getPendingCounts()
    {
        try {
            $pendingRentals = RentalRequest::where('status', 'pendente')->count();
            $pendingTransfers = Transfer::where('status', 'pendente')->count();

            // Gerar HTML para as listas
            $pendingRentalsHtml = '';
            $pendingTransfersHtml = '';

            if ($pendingRentals > 0) {
                $rentals = RentalRequest::with(['user', 'car'])
                    ->where('status', 'pendente')
                    ->latest()
                    ->take(5)
                    ->get();

                $pendingRentalsHtml = View::make('admin.partials.pending-rentals', [
                    'rentals' => $rentals
                ])->render();
            }

            if ($pendingTransfers > 0) {
                $transfers = Transfer::with('user')
                    ->where('status', 'pendente')
                    ->latest()
                    ->take(5)
                    ->get();

                $pendingTransfersHtml = View::make('admin.partials.pending-transfers', [
                    'transfers' => $transfers
                ])->render();
            }

            Log::info('Contadores pendentes recuperados com sucesso', [
                'pending_rentals' => $pendingRentals,
                'pending_transfers' => $pendingTransfers
            ]);

            return response()->json([
                'pending_rentals' => $pendingRentals,
                'pending_transfers' => $pendingTransfers,
                'pending_rentals_html' => $pendingRentalsHtml,
                'pending_transfers_html' => $pendingTransfersHtml
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao recuperar contadores pendentes', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Erro ao recuperar contadores pendentes'
            ], 500);
        }
    }

    // Métodos para gerenciamento de carros
    public function storeCar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'caixa' => 'required|string|in:Manual,Automática',
            'tracao' => 'required|string|in:FWD,RWD,4WD,AWD',
            'lugares' => 'required|integer|min:1|max:15',
            'combustivel' => 'required|string|in:Gasolina,Diesel,Elétrico,Híbrido,GPL',
            'status' => 'required|string|in:disponivel,indisponivel,manutencao',
            'price' => 'nullable|numeric|min:0',
            'image_cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cor' => 'nullable|string|max:255',
            'transmissao' => 'nullable|string|in:manual,automatico',
            'descricao' => 'nullable|string|max:1000',
            // Campos da tabela de preços
            'preco_dentro_com_motorista' => 'required|numeric|min:0',
            'preco_dentro_sem_motorista' => 'required|numeric|min:0',
            'preco_fora_com_motorista' => 'required|numeric|min:0',
            'preco_fora_sem_motorista' => 'required|numeric|min:0',
            'taxa_entrega_recolha' => 'nullable|numeric|min:0',
            'plafond_km_dia' => 'nullable|integer|min:1',
            'preco_km_extra' => 'nullable|numeric|min:0',
            'caucao' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $car = new Car();
            $car->marca = $request->marca;
            $car->modelo = $request->modelo;
            $car->caixa = $request->caixa;
            $car->tracao = $request->tracao;
            $car->lugares = $request->lugares;
            $car->combustivel = $request->combustivel;
            $car->status = $request->status;
            $car->price = $request->price;
            $car->cor = $request->cor;
            $car->transmissao = $request->transmissao;
            $car->descricao = $request->descricao;

            // Upload da imagem principal
            if ($request->hasFile('image_cover')) {
                $imagePath = $request->file('image_cover')->store('cars', 'public');
                $car->image_cover = $imagePath;
            }

            // Upload das imagens adicionais
            $additionalImages = ['image_1', 'image_2', 'image_3'];
            foreach ($additionalImages as $imageField) {
                if ($request->hasFile($imageField)) {
                    $imagePath = $request->file($imageField)->store('cars', 'public');
                    $car->$imageField = $imagePath;
                }
            }

            // Verificar se tem galeria
            $car->has_gallery = $request->hasFile('image_1') || $request->hasFile('image_2') || $request->hasFile('image_3');

            $car->save();

            // Criar tabela de preços
            $priceTable = new PriceTable();
            $priceTable->car_id = $car->id;
            $priceTable->preco_dentro_com_motorista = $request->preco_dentro_com_motorista;
            $priceTable->preco_dentro_sem_motorista = $request->preco_dentro_sem_motorista;
            $priceTable->preco_fora_com_motorista = $request->preco_fora_com_motorista;
            $priceTable->preco_fora_sem_motorista = $request->preco_fora_sem_motorista;
            $priceTable->taxa_entrega_recolha = $request->taxa_entrega_recolha;
            $priceTable->plafond_km_dia = $request->plafond_km_dia ?? 100;
            $priceTable->preco_km_extra = $request->preco_km_extra;
            $priceTable->caucao = $request->caucao;
            $priceTable->save();

            return response()->json([
                'success' => true,
                'message' => 'Carro adicionado com sucesso!',
                'car' => $car
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao adicionar carro', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar carro'
            ], 500);
        }
    }

    public function editCar($id)
    {
        try {
            $car = Car::with('priceTable')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'car' => $car,
                'price_table' => $car->priceTable
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Carro não encontrado'
            ], 404);
        }
    }

    public function updateCar(Request $request, $id)
    {
        // Log dos dados recebidos para debug
        \Log::info('Dados recebidos para atualização:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'caixa' => 'required|string|max:255', // Simplificado
            'tracao' => 'required|string|max:255', // Simplificado
            'lugares' => 'required|integer|min:1|max:15',
            'combustivel' => 'required|string|max:255', // Simplificado
            'status' => 'required|string|in:disponivel,indisponivel,manutencao',
            'price' => 'nullable|numeric|min:0',
            'image_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cor' => 'nullable|string|max:255',
            'transmissao' => 'nullable|string|max:255', // Simplificado
            'descricao' => 'nullable|string|max:1000',
            // Campos da tabela de preços
            'preco_dentro_com_motorista' => 'required|numeric|min:0',
            'preco_dentro_sem_motorista' => 'required|numeric|min:0',
            'preco_fora_com_motorista' => 'required|numeric|min:0',
            'preco_fora_sem_motorista' => 'required|numeric|min:0',
            'taxa_entrega_recolha' => 'nullable|numeric|min:0',
            'plafond_km_dia' => 'nullable|integer|min:1',
            'preco_km_extra' => 'nullable|numeric|min:0',
            'caucao' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            \Log::error('Erros de validação:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $car = Car::findOrFail($id);
            
            $car->marca = $request->marca;
            $car->modelo = $request->modelo;
            $car->caixa = $request->caixa;
            $car->tracao = $request->tracao;
            $car->lugares = (int) $request->lugares;
            $car->combustivel = $request->combustivel;
            $car->status = $request->status;
            $car->price = $request->price ? (float) $request->price : null;
            $car->cor = $request->cor;
            $car->transmissao = $request->transmissao;
            $car->descricao = $request->descricao;

            // Upload da imagem principal
            if ($request->hasFile('image_cover')) {
                // Deletar imagem antiga se existir
                if ($car->image_cover) {
                    Storage::disk('public')->delete($car->image_cover);
                }
                $imagePath = $request->file('image_cover')->store('cars', 'public');
                $car->image_cover = $imagePath;
            }

            // Upload das imagens adicionais
            $additionalImages = ['image_1', 'image_2', 'image_3'];
            foreach ($additionalImages as $imageField) {
                if ($request->hasFile($imageField)) {
                    // Deletar imagem antiga se existir
                    if ($car->$imageField) {
                        Storage::disk('public')->delete($car->$imageField);
                    }
                    $imagePath = $request->file($imageField)->store('cars', 'public');
                    $car->$imageField = $imagePath;
                }
            }

            // Verificar se tem galeria
            $car->has_gallery = $car->image_1 || $car->image_2 || $car->image_3;

            $car->save();

            // Atualizar ou criar tabela de preços
            $priceTable = PriceTable::where('car_id', $car->id)->first();
            if (!$priceTable) {
                $priceTable = new PriceTable();
                $priceTable->car_id = $car->id;
            }

            $priceTable->preco_dentro_com_motorista = (float) $request->preco_dentro_com_motorista;
            $priceTable->preco_dentro_sem_motorista = (float) $request->preco_dentro_sem_motorista;
            $priceTable->preco_fora_com_motorista = (float) $request->preco_fora_com_motorista;
            $priceTable->preco_fora_sem_motorista = (float) $request->preco_fora_sem_motorista;
            $priceTable->taxa_entrega_recolha = $request->taxa_entrega_recolha ? (float) $request->taxa_entrega_recolha : null;
            $priceTable->plafond_km_dia = $request->plafond_km_dia ? (int) $request->plafond_km_dia : 100;
            $priceTable->preco_km_extra = $request->preco_km_extra ? (float) $request->preco_km_extra : null;
            $priceTable->caucao = $request->caucao ? (float) $request->caucao : null;
            $priceTable->save();

            return response()->json([
                'success' => true,
                'message' => 'Carro atualizado com sucesso!',
                'car' => $car
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar carro', [
                'error' => $e->getMessage(),
                'car_id' => $id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar carro'
            ], 500);
        }
    }

    public function destroyCar($id)
    {
        try {
            $car = Car::findOrFail($id);
            
            // Deletar imagens do storage
            $images = ['image_cover', 'image_1', 'image_2', 'image_3'];
            foreach ($images as $imageField) {
                if ($car->$imageField) {
                    Storage::disk('public')->delete($car->$imageField);
                }
            }
            
            // Deletar tabela de preços
            PriceTable::where('car_id', $car->id)->delete();
            
            // Deletar carro
            $car->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carro excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao excluir carro', [
                'error' => $e->getMessage(),
                'car_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir carro'
            ], 500);
        }
    }

    // Métodos para gerenciamento de reservas
    public function getRentalRequest($id)
    {
        try {
            $rentalRequest = \App\Models\RentalRequest::with(['user', 'car'])->findOrFail($id);
            return view('admin.rental_requests.show', compact('rentalRequest'));
        } catch (\Exception $e) {
            return redirect()->route('admin.rental-requests.index')->with('error', 'Reserva não encontrada');
        }
    }

    public function confirmRentalRequest($id)
    {
        try {
            Log::info('Tentativa de confirmar reserva', ['rental_request_id' => $id]);
            
            $rentalRequest = RentalRequest::with(['user', 'car'])->findOrFail($id);
            
            if ($rentalRequest->status !== 'pendente') {
                Log::warning('Tentativa de confirmar reserva não pendente', [
                    'rental_request_id' => $id,
                    'status' => $rentalRequest->status
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'A reserva já foi processada'
                ], 422);
            }

            // Verifica novamente a disponibilidade
            if (!$rentalRequest->car->isAvailable($rentalRequest->data_inicio, $rentalRequest->data_fim)) {
                return response()->json([
                    'success' => false,
                    'message' => 'O carro não está mais disponível para o período selecionado.'
                ], 422);
            }
            
            $rentalRequest->update([
                'status' => 'confirmado',
                'email_enviado' => true
            ]);

            // Atualiza o status do carro
            $rentalRequest->car->update(['status' => 'alugado']);

            // Envia email de confirmação para o cliente
            try {
                // Verificar se o email do usuário existe
                if (!$rentalRequest->user->email) {
                    Log::warning('Usuário sem email para envio de confirmação', [
                        'rental_request_id' => $id,
                        'user_id' => $rentalRequest->user->id
                    ]);
                } else {
                    Log::info('Tentando enviar email de confirmação', [
                        'rental_request_id' => $id,
                        'user_email' => $rentalRequest->user->email,
                        'mail_config' => config('mail.default')
                    ]);
                    
                    Mail::to($rentalRequest->user->email)
                        ->send(new \App\Mail\RentalRequestConfirmed($rentalRequest));
                    
                    Log::info('Email de confirmação enviado com sucesso', [
                        'rental_request_id' => $id,
                        'user_email' => $rentalRequest->user->email
                    ]);
                }
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de confirmação', [
                    'rental_request_id' => $id,
                    'user_email' => $rentalRequest->user->email ?? 'N/A',
                    'error' => $emailError->getMessage(),
                    'mail_config' => config('mail.default'),
                    'trace' => $emailError->getTraceAsString()
                ]);
                
                // Continua o processo mesmo se o email falhar
                // A reserva foi confirmada, apenas o email não foi enviado
            }

            return response()->json([
                'success' => true,
                'message' => 'Reserva confirmada com sucesso! Email de confirmação enviado.',
                'rental_request' => $rentalRequest
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao confirmar reserva', [
                'error' => $e->getMessage(),
                'rental_request_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar reserva: ' . $e->getMessage()
            ], 500);
        }
    }

    // Métodos para gerenciamento de transfers
    public function getTransfer($id)
    {
        try {
            $transfer = \App\Models\Transfer::with('user')->findOrFail($id);
            
            // Se for uma requisição AJAX, retorna JSON
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'transfer' => $transfer
                ]);
            }
            
            // Caso contrário, retorna a view
            return view('admin.transfers.show', compact('transfer'));
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer não encontrado'
                ], 404);
            }
            
            return redirect()->route('admin.transfers.index')->with('error', 'Transfer não encontrado');
        }
    }

    public function confirmTransfer($id)
    {
        try {
            $transfer = Transfer::with('user')->findOrFail($id);
            $transfer->status = 'confirmado';
            $transfer->admin_id = auth()->id();
            $transfer->confirmed_at = now();
            $transfer->save();

            // Enviar email de confirmação para o cliente
            try {
                Mail::to($transfer->user->email)->send(new \App\Mail\TransferConfirmed($transfer));
                Log::info('Email de transfer confirmado enviado para o cliente (AdminController)', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de transfer confirmado para o cliente (AdminController)', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email,
                    'error' => $emailError->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transfer confirmado com sucesso! Email de confirmação enviado.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao confirmar transfer', [
                'error' => $e->getMessage(),
                'transfer_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar transfer'
            ], 500);
        }
    }

    public function rejectTransfer(Request $request, $id)
    {
        try {
            $transfer = Transfer::with('user')->findOrFail($id);
            $transfer->status = 'rejeitado';
            $transfer->admin_id = auth()->id();
            $transfer->rejected_at = now();
            $transfer->reject_reason = $request->reject_reason;
            $transfer->save();

            // Enviar email de rejeição para o cliente
            try {
                Mail::to($transfer->user->email)->send(new \App\Mail\TransferRejected($transfer));
                Log::info('Email de transfer rejeitado enviado para o cliente (AdminController)', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de transfer rejeitado para o cliente (AdminController)', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email,
                    'error' => $emailError->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transfer rejeitado com sucesso! Email de rejeição enviado.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao rejeitar transfer', [
                'error' => $e->getMessage(),
                'transfer_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao rejeitar transfer'
            ], 500);
        }
    }
} 