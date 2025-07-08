@extends('layouts.app')

@section('title', 'Transfers')

@section('content')
<!-- Hero -->
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold">Seus Transfers</h1>
        <p class="mt-4 text-lg">Gerencie suas solicitações de transfer com facilidade.</p>
        <button onclick="openCreateModal()" class="mt-4 inline-block bg-[var(--primary)] text-white px-6 py-2 rounded-md hover:bg-[var(--primary)]/90">Nova Solicitação</button>
    </div>
</div>

<!-- Transfers Content -->
<section class="py-12 bg-[var(--background)]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Lista de Transfers</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origem</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº do Voo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transfers as $transfer)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($transfer->tipo) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transfer->origem }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transfer->destino }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transfer->data_hora ? \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') : 'Data não definida' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($transfer->tipo === 'transfer')
                                        <div><strong>Voo:</strong> {{ $transfer->flight_number ?? 'N/A' }}</div>
                                        <div><strong>Data:</strong> {{ $transfer->flight_date ? \Carbon\Carbon::parse($transfer->flight_date)->format('d/m/Y') : 'N/A' }}</div>
                                        <div><strong>Horário:</strong> {{ $transfer->flight_time ?? 'N/A' }}</div>
                                        <div><strong>Pessoas:</strong> {{ $transfer->num_pessoas ?? 'N/A' }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transfer->status === 'confirmado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($transfer->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('transfers.show', $transfer) }}" class="text-[var(--primary)] hover:underline">Ver</a>
                                    @if (auth()->user()->role === 'admin' && $transfer->status === 'pendente')
                                        <button onclick="handleTransferAction({{ $transfer->id }}, 'confirm')" class="ml-4 text-[var(--secondary)] hover:underline">Confirmar</button>
                                        <button onclick="handleTransferAction({{ $transfer->id }}, 'reject')" class="ml-4 text-red-600 hover:underline">Rejeitar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-600">Nenhum transfer encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Create Transfer Modal -->
<div id="create-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeCreateModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Nova Solicitação de Transfer</h2>
        <form id="create-form" method="POST" action="{{ route('transfers.store') }}">
            @csrf
            <div class="mb-4">
                <label for="origem" class="block text-sm font-medium text-gray-700">Origem</label>
                <input type="text" name="origem" id="origem" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>
                @error('origem')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="destino" class="block text-sm font-medium text-gray-700">Destino</label>
                <input type="text" name="destino" id="destino" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>
                @error('destino')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="data_hora" class="block text-sm font-medium text-gray-700">Data e Hora</label>
                <input type="datetime-local" name="data_hora" id="data_hora" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>
                @error('data_hora')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="tipo" id="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]" required>
                    <option value="aeroporto">Aeroporto</option>
                    <option value="cidade">Cidade</option>
                    <option value="intermunicipal">Intermunicipal</option>
                </select>
                @error('tipo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                <textarea name="observacoes" id="observacoes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]"></textarea>
                @error('observacoes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" onclick="closeCreateModal()">Cancelar</button>
                <button type="submit" class="bg-[var(--secondary)] text-white px-4 py-2 rounded-md hover:bg-[var(--secondary)]/90">Solicitar</button>
            </div>
        </form>
    </div>
</div>

<!-- Confirm Transfer Modal (Admin) -->
<div id="action-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeActionModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 id="action-modal-title" class="text-xl font-bold text-[var(--primary)] mb-4">Confirmar Transfer</h2>
        <p id="action-modal-message" class="text-gray-600 mb-6">Deseja confirmar este transfer?</p>
            <div class="flex justify-end space-x-4">
            <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" onclick="closeActionModal()">Cancelar</button>
            <button id="action-confirm-button" onclick="submitTransferAction()" class="bg-[var(--secondary)] text-white px-4 py-2 rounded-md hover:bg-[var(--secondary)]/90">Confirmar</button>
            </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection

@section('scripts')
<script>
    // Função para mostrar toast notifications
    function showToast(message, type = 'info') {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        
        toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0 flex items-center space-x-2`;
        toast.innerHTML = `
            <span class="font-bold">${icons[type]}</span>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        // Remover após 4 segundos
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (container.contains(toast)) {
                    container.removeChild(toast);
                }
            }, 300);
        }, 4000);
    }
</script>
<script>
    let currentTransferId = null;
    let currentAction = null;

    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }

    function handleTransferAction(transferId, action) {
        currentTransferId = transferId;
        currentAction = action;
        
        const modal = document.getElementById('action-modal');
        const title = document.getElementById('action-modal-title');
        const message = document.getElementById('action-modal-message');
        const button = document.getElementById('action-confirm-button');
        
        if (action === 'confirm') {
            title.textContent = 'Confirmar Transfer';
            message.textContent = 'Deseja confirmar este transfer?';
            button.textContent = 'Confirmar';
            button.className = 'bg-[var(--secondary)] text-white px-4 py-2 rounded-md hover:bg-[var(--secondary)]/90';
        } else {
            title.textContent = 'Rejeitar Transfer';
            message.textContent = 'Deseja rejeitar este transfer?';
            button.textContent = 'Rejeitar';
            button.className = 'bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700';
        }
        
        modal.classList.remove('hidden');
    }

    function closeActionModal() {
        document.getElementById('action-modal').classList.add('hidden');
        currentTransferId = null;
        currentAction = null;
    }

    async function submitTransferAction() {
        if (!currentTransferId || !currentAction) return;

        try {
            const response = await fetch(`/transfers/${currentTransferId}/${currentAction}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Recarrega a página para mostrar as mudanças
                window.location.reload();
            } else {
                showToast(data.message || 'Ocorreu um erro ao processar sua solicitação.', 'error');
            }
        } catch (error) {
            console.error('Erro:', error);
            showToast('Ocorreu um erro ao processar sua solicitação.', 'error');
        } finally {
            closeActionModal();
        }
    }
</script>
@endsection