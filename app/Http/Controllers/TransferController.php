<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Events\TransferStatusChanged;
use App\Events\TransferStatusUpdated;
use App\Mail\TransferCreated;
use App\Mail\TransferConfirmed;
use App\Mail\TransferRejected;

class TransferController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transfers = $user->role === 'admin' ? Transfer::with('user')->get() : $user->transfers()->with('user')->get();
        return view('transfers.index', compact('transfers'));
    }

    public function store(StoreTransferRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pendente';
        $validated['email_enviado'] = false;

        try {
            $transfer = Transfer::create($validated);

            // Enviar notificação em tempo real
            event(new TransferStatusChanged($transfer, 'created'));

            // Enviar email para o cliente
            try {
                Mail::to(auth()->user()->email)->send(new TransferCreated($transfer));
                Log::info('Email de transfer criado enviado para o cliente', [
                    'transfer_id' => $transfer->id,
                    'user_email' => auth()->user()->email
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de transfer criado para o cliente', [
                    'transfer_id' => $transfer->id,
                    'user_email' => auth()->user()->email,
                    'error' => $emailError->getMessage()
                ]);
            }

            // Enviar email para os administradores
            try {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new TransferCreated($transfer, true));
                }
                Log::info('Email de transfer criado enviado para administradores', [
                    'transfer_id' => $transfer->id,
                    'admin_count' => $admins->count()
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de transfer criado para administradores', [
                    'transfer_id' => $transfer->id,
                    'error' => $emailError->getMessage()
                ]);
            }

            Log::info('Transfer request created successfully', [
                'transfer_id' => $transfer->id,
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitação de transfer criada com sucesso!',
                    'transfer' => $transfer
                ]);
            }

            return redirect()->route('transfers.index')->with('success', 'Solicitação de transfer criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error creating transfer request', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar solicitação de transfer. Tente novamente.'
                ], 500);
            }

            return back()->with('error', 'Erro ao criar solicitação de transfer. Tente novamente.');
        }
    }

    public function show(Transfer $transfer)
    {
        $this->authorize('view', $transfer);
        return view('transfers.show', compact('transfer'));
    }

    public function confirm(Transfer $transfer)
    {
        try {
            $this->authorize('confirm', Transfer::class);
            
            if ($transfer->status !== 'pendente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este transfer não pode ser confirmado pois não está pendente.'
                ], 422);
            }
            
            $transfer->update([
                'status' => 'confirmado',
                'admin_id' => auth()->id(),
                'confirmed_at' => now(),
                'confirmation_method' => 'admin_panel',
                'updated_at' => now()
            ]);

            // Envia notificação em tempo real
            event(new TransferStatusChanged($transfer, 'confirmed', auth()->user()->name));
            event(new TransferStatusUpdated($transfer));

            // Enviar email de confirmação para o cliente
            try {
                Mail::to($transfer->user->email)->send(new TransferConfirmed($transfer));
                Log::info('Email de transfer confirmado enviado para o cliente', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de transfer confirmado para o cliente', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email,
                    'error' => $emailError->getMessage()
                ]);
            }
            
            Log::info('Transfer confirmado com sucesso', [
                'transfer_id' => $transfer->id,
                'admin_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Transfer confirmado com sucesso! Email de confirmação enviado.',
                'transfer' => $transfer->load('user', 'admin')
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao confirmar transfer', [
                'transfer_id' => $transfer->id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao confirmar o transfer. Por favor, tente novamente.'
            ], 500);
        }
    }

    public function reject(Request $request, Transfer $transfer)
    {
        try {
            $this->authorize('confirm', Transfer::class);
            
            if ($transfer->status !== 'pendente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este transfer não pode ser rejeitado pois não está pendente.'
                ], 422);
            }
            
            $transfer->update([
                'status' => 'rejeitado',
                'admin_id' => auth()->id(),
                'rejected_at' => now(),
                'admin_notes' => $request->input('admin_notes'),
                'confirmation_method' => 'admin_panel',
                'updated_at' => now()
            ]);

            // Envia notificação em tempo real
            event(new TransferStatusChanged($transfer, 'rejected', auth()->user()->name));
            event(new TransferStatusUpdated($transfer));

            // Enviar email de rejeição para o cliente
            try {
                Mail::to($transfer->user->email)->send(new TransferRejected($transfer));
                Log::info('Email de transfer rejeitado enviado para o cliente', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email
                ]);
            } catch (\Exception $emailError) {
                Log::error('Erro ao enviar email de transfer rejeitado para o cliente', [
                    'transfer_id' => $transfer->id,
                    'user_email' => $transfer->user->email,
                    'error' => $emailError->getMessage()
                ]);
            }
            
            Log::info('Transfer rejeitado com sucesso', [
                'transfer_id' => $transfer->id,
                'admin_id' => auth()->id(),
                'notes' => $request->input('admin_notes')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Transfer rejeitado com sucesso! Email de rejeição enviado.',
                'transfer' => $transfer->load('user', 'admin')
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao rejeitar transfer', [
                'transfer_id' => $transfer->id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao rejeitar o transfer. Por favor, tente novamente.'
            ], 500);
        }
    }

    public function updateStatus(UpdateTransferStatusRequest $request, Transfer $transfer)
    {
        try {
            $this->authorize('confirm', Transfer::class);
            
            $status = $request->input('status');
            $adminNotes = $request->input('admin_notes');
            $confirmationMethod = $request->input('confirmation_method', 'admin_panel');
            
            $updateData = [
                'admin_id' => auth()->id(),
                'confirmation_method' => $confirmationMethod,
                'updated_at' => now()
            ];
            
            if ($status === 'confirmado') {
                $updateData['status'] = 'confirmado';
                $updateData['confirmed_at'] = now();
                $updateData['rejected_at'] = null;
                $action = 'confirmed';
            } elseif ($status === 'rejeitado') {
                $updateData['status'] = 'rejeitado';
                $updateData['rejected_at'] = now();
                $updateData['confirmed_at'] = null;
                $updateData['admin_notes'] = $adminNotes;
                $action = 'rejected';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status inválido.'
                ], 422);
            }
            
            $transfer->update($updateData);

            // Envia notificação em tempo real
            event(new TransferStatusChanged($transfer, $action, auth()->user()->name));
            event(new TransferStatusUpdated($transfer));
            
            Log::info("Transfer {$action} com sucesso", [
                'transfer_id' => $transfer->id,
                'admin_id' => auth()->id(),
                'status' => $status,
                'notes' => $adminNotes
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Transfer {$action} com sucesso!",
                'transfer' => $transfer->load('user', 'admin')
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status do transfer', [
                'transfer_id' => $transfer->id,
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao atualizar o status. Por favor, tente novamente.'
            ], 500);
        }
    }

    public function adminIndex()
    {
        $this->authorize('admin', Transfer::class);
        $transfers = Transfer::with('user')->get();
        return view('admin.transfers.index', compact('transfers'));
    }

    public function getAllTransfers()
    {
        $this->authorize('admin', Transfer::class);
        $transfers = Transfer::with(['user', 'admin'])->orderBy('created_at', 'desc')->get();
        return response()->json($transfers);
    }

    public function getTransferHistory()
    {
        $this->authorize('admin', Transfer::class);
        $transfers = Transfer::with(['user', 'admin'])
            ->whereNotNull('admin_id')
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json($transfers);
    }
}