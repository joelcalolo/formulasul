<?php
namespace App\Http\Controllers;

use App\Models\RentalRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use App\Http\Requests\StoreRentalRequest;
use App\Mail\RentalRequestCreated;
use App\Mail\RentalRequestConfirmed;
use App\Models\User;

class RentalRequestController extends Controller
{
    // Listar solicitações do usuário
    public function index(Request $request)
    {
        try {
            $rentalRequests = $request->user()->rentalRequests()
                ->with(['car', 'carroSecundario'])
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

    public function store(StoreRentalRequest $request)
    {
        try {
            $car = Car::find($request->carro_principal_id);
            if (!$car) {
                return back()->withInput()->with('error', 'O carro selecionado não foi encontrado. Por favor, escolha outro veículo.');
            }
            // Verifica disponibilidade
            if (!$car->isAvailable($request->data_inicio, $request->data_fim)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O carro não está disponível para o período selecionado. Por favor, escolha outras datas.'
                    ], 422);
                }
                return back()->withInput()->with('error', 'O carro não está disponível para o período selecionado. Por favor, escolha outras datas.');
            }

            // Cria a solicitação
            $rentalRequest = RentalRequest::create([
                'user_id' => auth()->id(),
                'carro_principal_id' => $request->carro_principal_id,
                'carro_secundario_id' => $request->carro_secundario_id,
                'data_inicio' => Carbon::parse($request->data_inicio),
                'data_fim' => Carbon::parse($request->data_fim),
                'local_entrega' => $request->local_entrega,
                'observacoes' => $request->observacoes,
                'status' => 'pendente'
            ]);

            // Envia email para o cliente
            Mail::to(auth()->user()->email)->send(new RentalRequestCreated($rentalRequest));

            // Envia email para os administradores
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new RentalRequestCreated($rentalRequest, true));
            }

            Log::info('Solicitação de aluguel criada com sucesso', [
                'rental_request_id' => $rentalRequest->id,
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitação enviada com sucesso! Em breve entraremos em contato.',
                    'rental_request' => $rentalRequest
                ]);
            }

            return redirect()->route('rental-requests.index')
                ->with('success', 'Solicitação enviada com sucesso! Em breve entraremos em contato.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->with('error', 'Dados inválidos. Verifique os campos e tente novamente.');
        } catch (\Exception $e) {
            \Log::error('Erro ao criar solicitação de aluguel', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro inesperado. Por favor, tente novamente ou entre em contato com o suporte.'
                ], 500);
            }
            return back()->withInput()->with('error', 'Ocorreu um erro inesperado. Por favor, tente novamente ou entre em contato com o suporte.');
        }
    }

    // Mostrar detalhes de uma solicitação
    public function show(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::with(['car', 'carroSecundario', 'user'])
                                        ->findOrFail($id);

            // Verificar permissão
            if ($rentalRequest->user_id != $request->user()->id && !$request->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso não autorizado'
                ], 403);
            }

            return response()->json($rentalRequest);

        } catch (ModelNotFoundException $e) {
            Log::error('Solicitação de aluguel não encontrada', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Solicitação não encontrada'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar solicitação', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar detalhes da reserva'
            ], 500);
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

                return response()->json([
                    'success' => false,
                    'message' => 'A solicitação já foi processada'
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
            Mail::to($rentalRequest->user->email)
                ->send(new RentalRequestConfirmed($rentalRequest));

            Log::info('Solicitação de aluguel confirmada', [
                'rental_request_id' => $rentalRequest->id,
                'admin_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reserva confirmada com sucesso!',
                'rental_request' => $rentalRequest
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Solicitação de aluguel não encontrada', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Solicitação não encontrada'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erro ao confirmar solicitação de aluguel', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao confirmar solicitação'
            ], 500);
        }
    }

    // Listar solicitações para admin
    public function adminIndex(Request $request)
    {
        try {
            // Verificar se o usuário tem permissão de admin
            if ($request->user()->role !== 'admin') {
                Log::warning('Tentativa de acesso não autorizado ao adminIndex', [
                    'user_id' => $request->user()->id,
                    'user_role' => $request->user()->role
                ]);

                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json(['error' => 'Acesso negado. Apenas administradores podem acessar esta funcionalidade.'], 403);
                }

                return back()->with('error', 'Acesso negado. Apenas administradores podem acessar esta funcionalidade.');
            }

            $rentalRequests = RentalRequest::with(['user', 'car'])
                                         ->latest()
                                         ->get();

            Log::info('Admin visualizou solicitações com sucesso', [
                'user_id' => $request->user()->id,
                'count' => $rentalRequests->count()
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'data' => $rentalRequests,
                    'count' => $rentalRequests->count()
                ], 200);
            }

            return view('admin.rental_requests.index', [
                'rentalRequests' => $rentalRequests
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao listar solicitações para admin', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao listar solicitações. Tente novamente mais tarde.',
                    'message' => 'Erro interno do servidor'
                ], 500);
            }

            return back()->with('error', 'Erro ao listar solicitações. Tente novamente mais tarde.');
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::findOrFail($id);

            // Verifica se o usuário tem permissão
            if ($rentalRequest->user_id != $request->user()->id) {
                throw new \Exception('Acesso não autorizado');
            }

            $rentalRequest->update([
                'status' => 'cancelado'
            ]);

            // Se o carro estava marcado como alugado para esta reserva, volta para disponível
            if ($rentalRequest->car->status === 'alugado') {
                $rentalRequest->car->update(['status' => 'disponivel']);
            }

            Log::info('Solicitação de aluguel cancelada', [
                'rental_request_id' => $rentalRequest->id,
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Solicitação cancelada com sucesso']);
            }

            return redirect()->route('rental-requests.index')
                           ->with('success', 'Solicitação cancelada com sucesso');

        } catch (\Exception $e) {
            Log::error('Erro ao cancelar solicitação de aluguel', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            if ($request->wantsJson()) {
                return response()->json(['error' => 'Erro ao cancelar solicitação'], 500);
            }

            return back()->with('error', 'Erro ao cancelar solicitação');
        }
    }

    public function finish(Request $request, $id)
    {
        try {
            $rentalRequest = RentalRequest::findOrFail($id);

            if ($rentalRequest->status !== 'confirmado') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas aluguéis confirmados podem ser finalizados'
                ], 422);
            }

            // Atualiza o status da reserva
            $rentalRequest->update([
                'status' => 'finalizado'
            ]);

            // Atualiza o status do carro para disponível
            $rentalRequest->car->update(['status' => 'disponivel']);

            Log::info('Aluguel finalizado com sucesso', [
                'rental_request_id' => $rentalRequest->id,
                'admin_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Aluguel finalizado com sucesso!',
                'rental_request' => $rentalRequest
            ]);

        } catch (ModelNotFoundException $e) {
            Log::error('Reserva não encontrada', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Reserva não encontrada'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erro ao finalizar aluguel', [
                'rental_request_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar aluguel'
            ], 500);
        }
    }

    /**
     * Rejeitar solicitação de aluguel (admin)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:255',
        ]);
        try {
            $rentalRequest = RentalRequest::findOrFail($id);
            if ($rentalRequest->status !== 'pendente') {
                return response()->json([
                    'success' => false,
                    'message' => 'A solicitação já foi processada.'
                ], 422);
            }
            $rentalRequest->update([
                'status' => 'rejeitado',
                'reject_reason' => $request->reject_reason,
            ]);
            // Enviar e-mail para o cliente (opcional: criar Mailable RentalRequestRejected)
            // Mail::to($rentalRequest->user->email)->send(new RentalRequestRejected($rentalRequest));
            \Log::info('Solicitação de aluguel rejeitada', [
                'rental_request_id' => $rentalRequest->id,
                'admin_id' => $request->user()->id,
                'reason' => $request->reject_reason
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Reserva rejeitada com sucesso!',
                'rental_request' => $rentalRequest
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitação não encontrada.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao rejeitar solicitação.'
            ], 500);
        }
    }
}