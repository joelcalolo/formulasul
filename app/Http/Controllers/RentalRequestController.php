<?php
namespace App\Http\Controllers;

use App\Models\RentalRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RentalRequestController extends Controller
{
    // Listar solicitações do usuário
    public function index(Request $request)
    {
        try {
            $rentalRequests = $request->user()->rentalRequests()
                ->with(['carroPrincipal', 'carroSecundario'])
                ->latest()
                ->paginate(10);
    
            if ($request->wantsJson()) {
                return response()->json($rentalRequests);
            }
    
            return view('rental_requests.index', compact('rentalRequests'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar solicitações', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
    
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Erro ao listar solicitações'], 500);
            }
    
            return back()->with('error', 'Erro ao carregar suas solicitações');
        }
    }
    
    public function destroy(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::findOrFail($id);
            
            // Verifica se o usuário tem permissão
            if ($rentalRequest->user_id != $request->user()->id) {
                throw new \Exception('Acesso não autorizado');
            }
    
            $rentalRequest->delete();
    
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Solicitação cancelada com sucesso']);
            }
    
            return redirect()->route('rental-requests.index')
                           ->with('success', 'Solicitação cancelada com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar solicitação', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
    
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Erro ao cancelar solicitação'], 500);
            }
    
            return back()->with('error', 'Erro ao cancelar solicitação');
        }
    }

    // Mostrar formulário de criação
    public function create(Request $request)
    {
        $availableCars = Car::where('status', 'disponivel')->get();
        
        return view('rental_requests.create', [
            'availableCars' => $availableCars,
            'user' => $request->user()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'carro_principal_id' => 'required|exists:cars,id',
                'carro_secundario_id' => 'nullable|exists:cars,id',
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after:data_inicio',
                'local_entrega' => 'required|string',
                'observacoes' => 'nullable|string'
            ]);

            $rentalRequest = RentalRequest::create([
                'user_id' => $request->user()->id,
                'carro_principal_id' => $request->carro_principal_id,
                'carro_secundario_id' => $request->carro_secundario_id,
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
                'local_entrega' => $request->local_entrega,
                'observacoes' => $request->observacoes,
                'status' => 'pendente'
            ]);

            try {
                Mail::to('empresa@formulasul.com')->send(new \App\Mail\RentalRequestNotification($rentalRequest));
                $rentalRequest->update(['email_enviado' => true]);
                Log::info('E-mail enviado para a empresa', ['rental_request_id' => $rentalRequest->id]);
            } catch (\Exception $e) {
                Log::error('Falha ao enviar e-mail', [
                    'rental_request_id' => $rentalRequest->id,
                    'error' => $e->getMessage()
                ]);
                $rentalRequest->update(['email_enviado' => false]);
            }

            Log::info('Solicitação de aluguel criada', [
                'user_id' => $request->user()->id,
                'rental_request_id' => $rentalRequest->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($rentalRequest, 201);
            }

            return redirect()->route('rental-requests.index')
                           ->with('success', 'Solicitação criada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar solicitação de aluguel', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao processar solicitação'], 500);
            }

            return back()->withInput()
                        ->with('error', 'Erro ao criar solicitação: ' . $e->getMessage());
        }
    }

    // Mostrar detalhes de uma solicitação
    public function show(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::with(['carroPrincipal', 'carroSecundario', 'user'])
                                        ->findOrFail($id);

            // Verificar permissão
            if ($rentalRequest->user_id != $request->user()->id && !$request->user()->hasRole('admin')) {
                throw new \Exception('Acesso não autorizado');
            }

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($rentalRequest, 200);
            }

            return view('rental_requests.show', [
                'rentalRequest' => $rentalRequest,
                'isAdmin' => $request->user()->hasRole('admin')
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Solicitação de aluguel não encontrada', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Solicitação não encontrada'], 404);
            }

            return back()->with('error', 'Solicitação não encontrada');
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar solicitação', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => $e->getMessage()], 403);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    // Mostrar formulário de edição (apenas para admin)
    public function edit(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::findOrFail($id);
            
            if (!$request->user()->hasRole('admin')) {
                throw new \Exception('Acesso não autorizado');
            }

            return view('rental_requests.edit', [
                'rentalRequest' => $rentalRequest,
                'statusOptions' => ['pendente', 'aceita', 'recusada']
            ]);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::findOrFail($id);

            $request->validate([
                'status' => 'required|in:pendente,aceita,recusada'
            ]);

            $rentalRequest->update([
                'status' => $request->status
            ]);

            Log::info('Solicitação de aluguel atualizada', [
                'rental_request_id' => $rentalRequest->id,
                'status' => $rentalRequest->status,
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($rentalRequest, 200);
            }

            return redirect()->route('rental-requests.show', $id)
                           ->with('success', 'Status atualizado com sucesso!');

        } catch (ModelNotFoundException $e) {
            Log::error('Solicitação de aluguel não encontrada', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Solicitação não encontrada'], 404);
            }

            return back()->with('error', 'Solicitação não encontrada');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar solicitação de aluguel', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao atualizar solicitação'], 500);
            }

            return back()->with('error', 'Erro ao atualizar solicitação');
        }
    }

    // Confirmar solicitação (admin)
    public function confirm(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'pendente') {
                Log::warning('Tentativa de confirmar solicitação não pendente', [
                    'rental_request_id' => $rentalRequest->id,
                    'status' => $rentalRequest->status,
                    'user_id' => $request->user()->id
                ]);

                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['error' => 'A solicitação já foi processada'], 422);
                }

                return back()->with('error', 'A solicitação já foi processada');
            }

            $rentalRequest->update(['status' => 'aceita']);

            Log::info('Solicitação de aluguel confirmada', [
                'rental_request_id' => $rentalRequest->id,
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($rentalRequest, 200);
            }

            return redirect()->route('rental-requests.show', $id)
                           ->with('success', 'Solicitação confirmada com sucesso!');

        } catch (ModelNotFoundException $e) {
            Log::error('Solicitação de aluguel não encontrada', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Solicitação não encontrada'], 404);
            }

            return back()->with('error', 'Solicitação não encontrada');
        } catch (\Exception $e) {
            Log::error('Erro ao confirmar solicitação de aluguel', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao confirmar solicitação'], 500);
            }

            return back()->with('error', 'Erro ao confirmar solicitação');
        }
    }

    // Listar solicitações para admin
    public function adminIndex(Request $request)
    {
        try {
            $rentalRequests = RentalRequest::with(['user', 'carroPrincipal', 'carroSecundario'])
                                         ->latest()
                                         ->get();

            Log::info('Admin visualizou solicitações', [
                'user_id' => $request->user()->id,
                'count' => $rentalRequests->count()
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($rentalRequests, 200);
            }

            return view('admin.rental_requests.index', [
                'rentalRequests' => $rentalRequests
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao listar solicitações para admin', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao listar solicitações'], 500);
            }

            return back()->with('error', 'Erro ao listar solicitações');
        }
    }
}