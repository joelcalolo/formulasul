@extends('layouts.app')

@section('title', 'Minhas Reservas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Minhas Reservas</h1>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Início</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Fim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rentalRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($request->car && $request->car->image)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $request->car->image) }}" alt="{{ $request->car->modelo }}">
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $request->car ? $request->car->marca . ' ' . $request->car->modelo : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : '-' }}</div>
                                <div class="text-sm text-gray-500">até {{ $request->data_fim ? \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $request->status === 'confirmado' ? 'bg-green-100 text-green-800' : 
                                       ($request->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openReservationDetailsModal({{ $request->id }})" 
                                        class="text-[var(--primary)] hover:text-[var(--primary)]/80 mr-3">
                                    Ver Detalhes
                                </button>
                                @if($request->status === 'pendente')
                                    @if(auth()->user()->role === 'admin')
                                        <button onclick="openConfirmModal({{ $request->id }})"
                                                class="text-green-600 hover:text-green-900 mr-3">
                                            Confirmar
                                        </button>
                                    @endif
                                    <button onclick="confirmCancelReservation({{ $request->id }})"
                                            class="text-red-600 hover:text-red-900">
                                        Cancelar
                                    </button>
                                @endif
                                @if($request->status === 'confirmado' && auth()->user()->role === 'admin')
                                    <button onclick="openFinishModal({{ $request->id }})"
                                            class="text-blue-600 hover:text-blue-900">
                                        Finalizar Aluguel
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Nenhuma reserva encontrada
                                </td>
                            </tr>
                    @endforelse
                        </tbody>
                    </table>
                </div>
        <div class="px-6 py-4 border-t border-gray-200">
                    {{ $rentalRequests->links() }}
                </div>
    </div>
</div>

<!-- Modal de Detalhes da Reserva -->
<div id="reservation-details-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-lg mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeReservationDetailsModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Detalhes da Reserva</h2>
        <div id="reservation-details-content" class="text-gray-600">
            <!-- Os detalhes da reserva serão preenchidos dinamicamente -->
        </div>
        <button class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90 mt-6" onclick="closeReservationDetailsModal()">Fechar</button>
    </div>
</div>

<!-- Modal de Confirmação de Cancelamento -->
<div id="cancel-confirmation-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeCancelConfirmationModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="text-center">
            <svg class="mx-auto mb-4 w-14 h-14 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-800">
                Tem certeza que deseja cancelar esta reserva?
            </h3>
            <p class="mb-5 text-sm text-gray-600">
                Esta ação não pode ser desfeita.
            </p>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="closeCancelConfirmationModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Não, manter
                </button>
                <button type="button" onclick="cancelReservation()" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                    Sim, cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Reserva (Admin) -->
<div id="confirm-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeConfirmModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="text-center">
            <svg class="mx-auto mb-4 w-14 h-14 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-800">
                Confirmar esta reserva?
            </h3>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="closeConfirmModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="button" onclick="confirmReservation()" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Finalização de Aluguel -->
<div id="finish-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeFinishModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="text-center">
            <svg class="mx-auto mb-4 w-14 h-14 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-800">
                Finalizar este aluguel?
            </h3>
            <p class="mb-5 text-sm text-gray-600">
                O carro será marcado como disponível novamente.
            </p>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="closeFinishModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="button" onclick="finishRental()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Finalizar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection

@push('scripts')
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
    let reservationToCancel = null;
    let reservationToConfirm = null;
    let reservationToFinish = null;

    function openReservationDetailsModal(reservationId) {
        fetch(`/rental-requests/${reservationId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                const content = `
                    <div class="space-y-4">
                        <div>
                            <p class="font-semibold">Carro:</p>
                            <p>${data.car ? data.car.marca + ' ' + data.car.modelo : 'N/A'}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Período:</p>
                            <p>De ${new Date(data.data_inicio).toLocaleDateString()} até ${new Date(data.data_fim).toLocaleDateString()}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Local de Entrega:</p>
                            <p>${data.local_entrega || 'Não especificado'}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Status:</p>
                            <p>${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</p>
                        </div>
                        ${data.observacoes ? `
                        <div>
                            <p class="font-semibold">Observações:</p>
                            <p>${data.observacoes}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                document.getElementById('reservation-details-content').innerHTML = content;
                document.getElementById('reservation-details-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erro ao carregar detalhes da reserva:', error);
                showToast('Erro ao carregar detalhes da reserva. Por favor, tente novamente.', 'error');
            });
    }

    function closeReservationDetailsModal() {
        document.getElementById('reservation-details-modal').classList.add('hidden');
    }

    function confirmCancelReservation(id) {
        reservationToCancel = id;
        document.getElementById('cancel-confirmation-modal').classList.remove('hidden');
    }

    function closeCancelConfirmationModal() {
        document.getElementById('cancel-confirmation-modal').classList.add('hidden');
        reservationToCancel = null;
    }

    function cancelReservation() {
        if (!reservationToCancel) return;

        fetch(`/rental-requests/${reservationToCancel}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeCancelConfirmationModal();
            if (data.message) {
                // Recarregar a página após o cancelamento
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Erro ao cancelar reserva:', error);
            showToast('Erro ao cancelar reserva. Por favor, tente novamente.', 'error');
        });
    }

    function openConfirmModal(id) {
        reservationToConfirm = id;
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
        reservationToConfirm = null;
    }

    function confirmReservation() {
        if (!reservationToConfirm) return;

        fetch(`/rental-requests/${reservationToConfirm}/confirm`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeConfirmModal();
            if (data.success) {
                // Recarregar a página após a confirmação
                window.location.reload();
            } else {
                showToast(data.message || 'Erro ao confirmar reserva', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao confirmar reserva:', error);
            showToast('Erro ao confirmar reserva. Por favor, tente novamente.', 'error');
        });
    }

    function openFinishModal(id) {
        reservationToFinish = id;
        document.getElementById('finish-modal').classList.remove('hidden');
    }

    function closeFinishModal() {
        document.getElementById('finish-modal').classList.add('hidden');
        reservationToFinish = null;
    }

    function finishRental() {
        if (!reservationToFinish) return;

        fetch(`/rental-requests/${reservationToFinish}/finish`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeFinishModal();
            if (data.success) {
                window.location.reload();
            } else {
                showToast(data.message || 'Erro ao finalizar aluguel', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao finalizar aluguel:', error);
            showToast('Erro ao finalizar aluguel. Por favor, tente novamente.', 'error');
        });
    }

    // Fechar modais quando clicar fora deles
    window.onclick = function(event) {
        const reservationModal = document.getElementById('reservation-details-modal');
        const cancelModal = document.getElementById('cancel-confirmation-modal');
        const confirmModal = document.getElementById('confirm-modal');
        const finishModal = document.getElementById('finish-modal');
        
        if (event.target === reservationModal) {
            closeReservationDetailsModal();
        }
        if (event.target === cancelModal) {
            closeCancelConfirmationModal();
        }
        if (event.target === confirmModal) {
            closeConfirmModal();
        }
        if (event.target === finishModal) {
            closeFinishModal();
        }
    }
</script>
@endpush