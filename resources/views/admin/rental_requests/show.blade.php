@extends('layouts.app')

@section('title', 'Detalhes do Aluguel')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Detalhes do Pedido de Aluguel</h1>
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Dados do Cliente</h2>
        <p><strong>Nome:</strong> {{ $rentalRequest->user->name ?? '-' }}</p>
        <p><strong>E-mail:</strong> {{ $rentalRequest->user->email ?? '-' }}</p>
        <p><strong>Telefone:</strong> {{ $rentalRequest->user->phone ?? '-' }}</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Dados do Aluguel</h2>
        <p><strong>Carro Principal:</strong> {{ $rentalRequest->car ? $rentalRequest->car->marca . ' ' . $rentalRequest->car->modelo : '-' }}</p>
        <p><strong>Data de Início:</strong> {{ $rentalRequest->data_inicio ? \Carbon\Carbon::parse($rentalRequest->data_inicio)->format('d/m/Y') : '-' }}</p>
        <p><strong>Data de Fim:</strong> {{ $rentalRequest->data_fim ? \Carbon\Carbon::parse($rentalRequest->data_fim)->format('d/m/Y') : '-' }}</p>
        <p><strong>Local de Entrega:</strong> {{ $rentalRequest->local_entrega }}</p>
        <p><strong>Status:</strong> <span class="font-semibold">{{ ucfirst($rentalRequest->status) }}</span></p>
        <p><strong>Observações:</strong> {{ $rentalRequest->observacoes ?? 'Nenhuma' }}</p>
        <p><strong>E-mail Enviado:</strong> {{ $rentalRequest->email_enviado ? 'Sim' : 'Não' }}</p>
    </div>
    <div class="flex gap-4 mt-6">
        @if($rentalRequest->status === 'pendente')
            <button type="button" onclick="openConfirmModal({{ $rentalRequest->id }})" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-all duration-200 hover:scale-105">Confirmar</button>
            <button type="button" onclick="openRejectModal({{ $rentalRequest->id }})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-all duration-200 hover:scale-105">Rejeitar</button>
        @endif
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.rental-requests.index') : route('rental-requests.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition-all duration-200 hover:scale-105">Voltar</a>
    </div>

    <!-- Modal de Rejeição com Animações -->
    <div id="reject-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
        <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative transform transition-all duration-300 scale-95 opacity-0">
            <button onclick="closeRejectModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold mb-4 text-gray-800">Justificar Rejeição</h2>
                <label class="block mb-2 font-medium text-gray-700">Motivo:</label>
                <select id="reject_reason" class="w-full border border-gray-300 rounded-lg p-3 mb-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                    <option value="">Selecione um motivo...</option>
                    <option value="Carro indisponível">Carro indisponível</option>
                    <option value="Dados inconsistentes">Dados inconsistentes</option>
                    <option value="Solicitação duplicada">Solicitação duplicada</option>
                    <option value="Período indisponível">Período indisponível</option>
                    <option value="Documentação incompleta">Documentação incompleta</option>
                    <option value="Outro">Outro</option>
                </select>
                <div class="flex justify-center space-x-3">
                    <button onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all duration-200 hover:scale-105">
                        Cancelar
                    </button>
                    <button id="reject-btn" onclick="submitReject()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 hover:scale-105 flex items-center">
                        <span id="reject-btn-text">Rejeitar Reserva</span>
                        <div id="reject-loading" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação com Animações -->
    <div id="confirm-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
        <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative transform transition-all duration-300 scale-95 opacity-0">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200" onclick="closeConfirmModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="mb-5 text-lg font-normal text-gray-800">
                    Confirmar esta reserva?
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Esta ação enviará um e-mail de confirmação ao cliente.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all duration-200 hover:scale-105">
                        Cancelar
                    </button>
                    <button id="confirm-btn" type="button" onclick="confirmReservation()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 hover:scale-105 flex items-center">
                        <span id="confirm-btn-text">Confirmar</span>
                        <div id="confirm-loading" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
</div>
@endsection

@push('scripts')
<script>
    let currentRejectId = {{ $rentalRequest->id }};
    let currentConfirmId = {{ $rentalRequest->id }};
    
    // Função para mostrar toast notifications
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
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
    
    function openRejectModal(id) {
        const modal = document.getElementById('reject-modal');
        const content = modal.querySelector('.modal-content');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }
    
    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        const content = modal.querySelector('.modal-content');
        
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    function submitReject() {
        const reason = document.getElementById('reject_reason').value;
        if (!reason) {
            showToast('Selecione uma justificativa.', 'warning');
            return;
        }

        const btn = document.getElementById('reject-btn');
        const btnText = document.getElementById('reject-btn-text');
        const loading = document.getElementById('reject-loading');

        // Mostrar loading
        btn.disabled = true;
        btnText.textContent = 'Rejeitando...';
        loading.classList.remove('hidden');

        fetch(`/admin/rental-requests/${currentRejectId}/reject`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reject_reason: reason })
        })
        .then(res => res.json())
        .then(data => {
            closeRejectModal();
            if (data.success) {
                showToast('Reserva rejeitada com sucesso!', 'success');
                setTimeout(() => window.location.href = '{{ route('admin.rental-requests.index') }}', 1500);
            } else {
                showToast(data.message || 'Erro ao rejeitar reserva.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao rejeitar reserva:', error);
            showToast('Erro ao rejeitar reserva. Tente novamente.', 'error');
        })
        .finally(() => {
            // Restaurar botão
            btn.disabled = false;
            btnText.textContent = 'Rejeitar Reserva';
            loading.classList.add('hidden');
        });
    }

    // Funções para o modal de confirmação
    function openConfirmModal(id) {
        const modal = document.getElementById('confirm-modal');
        const content = modal.querySelector('.modal-content');
        
        currentConfirmId = id;
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirm-modal');
        const content = modal.querySelector('.modal-content');
        
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function confirmReservation() {
        if (!currentConfirmId) return;

        const btn = document.getElementById('confirm-btn');
        const btnText = document.getElementById('confirm-btn-text');
        const loading = document.getElementById('confirm-loading');

        // Mostrar loading
        btn.disabled = true;
        btnText.textContent = 'Confirmando...';
        loading.classList.remove('hidden');

        fetch(`/admin/rental-requests/${currentConfirmId}/confirm`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            closeConfirmModal();
            if (data.success) {
                showToast('Reserva confirmada com sucesso!', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(data.message || 'Erro ao confirmar reserva', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao confirmar reserva:', error);
            showToast('Erro ao confirmar reserva. Tente novamente.', 'error');
        })
        .finally(() => {
            // Restaurar botão
            btn.disabled = false;
            btnText.textContent = 'Confirmar';
            loading.classList.add('hidden');
        });
    }

    // Fechar modais quando clicar fora deles
    window.onclick = function(event) {
        const rejectModal = document.getElementById('reject-modal');
        const confirmModal = document.getElementById('confirm-modal');
        
        if (event.target === rejectModal) {
            closeRejectModal();
        }
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
    }
</script>
@endpush 