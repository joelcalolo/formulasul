@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Hero -->
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold">Bem-vindo, {{ auth()->user()->name }}</h1>
        <p class="mt-4 text-lg">Gerencie suas reservas, transfers e muito mais.</p>
        @if (auth()->user()->role === 'cliente')
            <a href="{{ route('rental-requests.create') }}" class="mt-4 inline-block bg-[var(--primary)] text-white px-6 py-2 rounded-md hover:bg-[var(--primary)]/90">Nova Reserva</a>
        @endif
    </div>
</div>

<!-- Dashboard Content -->
<section class="py-12 bg-[var(--background)]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900">Reservas Ativas</h3>
                <p class="mt-2 text-3xl font-bold text-[var(--primary)]">{{ $rentalRequests->where('status', 'confirmado')->count() }}</p>
                <a href="{{ route('rental-requests.index') }}" class="mt-4 inline-block text-[var(--primary)] hover:underline">Ver Todas</a>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900">Transfers Pendentes</h3>
                <p class="mt-2 text-3xl font-bold text-[var(--primary)]">{{ $transfers->where('status', 'pendente')->count() }}</p>
                <a href="{{ route('transfers.index') }}" class="mt-4 inline-block text-[var(--primary)] hover:underline">Ver Todos</a>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900">Veículos Disponíveis</h3>
                <p class="mt-2 text-3xl font-bold text-[var(--primary)]">{{ $cars->where('status', 'disponivel')->count() }}</p>
                <a href="{{ route('cars.index') }}" class="mt-4 inline-block text-[var(--primary)] hover:underline">Explorar</a>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-12">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Tendências de Reservas</h2>
            <canvas id="rentalChart" class="w-full h-64"></canvas>
        </div>

        <!-- Role-Based Sections -->
        @if (auth()->user()->role === 'cliente')
            <!-- Cliente: Recent Activity -->
            <div class="mb-12">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Atividade Recente</h2>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalhes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($rentalRequests->take(5) as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Reserva</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : 'Data não definida' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $request->status === 'confirmado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('rental-requests.show', $request) }}" class="text-[var(--primary)] hover:underline">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-600">Nenhuma reserva recente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Admin: Management -->
            <div class="mb-12">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Gerenciamento</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Pending Rental Requests -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reservas Pendentes</h3>
                        @forelse ($rentalRequests->where('status', 'pendente')->take(3) as $request)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $request->car->modelo ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600">{{ $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : 'Data não definida' }}</p>
                                </div>
                                <button onclick="openConfirmModal({{ $request->id }})" class="bg-[var(--secondary)] text-white px-4 py-1 rounded-md hover:bg-[var(--secondary)]/90">Confirmar</button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">Nenhuma reserva pendente.</p>
                        @endforelse
                        <a href="{{ route('admin.rental-requests.index') }}" class="mt-4 inline-block text-[var(--primary)] hover:underline">Ver Todas</a>
                    </div>
                    <!-- Pending Transfers -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transfers Pendentes</h3>
                        @forelse ($transfers->where('status', 'pendente')->take(3) as $transfer)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $transfer->destination }}</p>
                                    <p class="text-xs text-gray-600">{{ $transfer->user->name }} - {{ $transfer->date ? $transfer->date->format('d/m/Y') : 'Data não definida' }}</p>
                                </div>
                                <button onclick="openTransferConfirmModal({{ $transfer->id }})" class="bg-[var(--secondary)] text-white px-4 py-1 rounded-md hover:bg-[var(--secondary)]/90">Confirmar</button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">Nenhum transfer pendente.</p>
                        @endforelse
                        <a href="{{ route('admin.transfers.index') }}" class="mt-4 inline-block text-[var(--primary)] hover:underline">Ver Todos</a>
                    </div>
                </div>
            </div>

            <!-- Gerenciamento de Carros -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gerenciar Carros</h3>
                <button onclick="openCarModal()" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90 mb-4">Cadastrar Novo Carro</button>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço por dia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($cars as $car)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $car->modelo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $car->categoria }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $car->status === 'disponivel' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($car->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Akz {{ number_format($car->price, 2, ',', '.') }}</td>
                            
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="editCar({{ $car->id }})" class="text-[var(--primary)] hover:underline">Editar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-600">Nenhum carro cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Confirmation Modal (Admin) -->
<div id="confirm-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeConfirmModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Confirmar Reserva</h2>
        <p class="text-gray-600 mb-6">Deseja confirmar esta reserva?</p>
        <form id="confirm-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" onclick="closeConfirmModal()">Cancelar</button>
                <button type="submit" class="bg-[var(--secondary)] text-white px-4 py-2 rounded-md hover:bg-[var(--secondary)]/90">Confirmar</button>
            </div>
        </form>
    </div>
</div>

<!-- Transfer Confirmation Modal (Admin) -->
<div id="transfer-confirm-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeTransferConfirmModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Confirmar Transfer</h2>
        <p class="text-gray-600 mb-6">Deseja confirmar este transfer?</p>
        <form id="transfer-confirm-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" onclick="closeTransferConfirmModal()">Cancelar</button>
                <button type="submit" class="bg-[var(--secondary)] text-white px-4 py-2 rounded-md hover:bg-[var(--secondary)]/90">Confirmar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Cadastro de Carros -->
<div id="car-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeCarModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Cadastrar Novo Carro</h2>
        <form id="car-form" method="POST" action="{{ route('cars.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" name="modelo" id="modelo" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
                <input type="text" name="categoria" id="categoria" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="w-full border rounded px-3 py-2">
                    <option value="disponivel">Disponível</option>
                    <option value="alugado">Alugado</option>
                    <option value="manutencao">Manutenção</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Imagem</label>
                <input type="file" name="image" id="image" class="w-full border rounded px-3 py-2" accept="image/*">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Preço (por dia)</label>
                <input type="number" name="price" id="price" class="w-full border rounded px-3 py-2" step="0.01" required>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" onclick="closeCarModal()">Cancelar</button>
                <button type="submit" class="bg-[var(--secondary)] text-white px-4 py-2 rounded-md hover:bg-[var(--secondary)]/90">Salvar</button>
            </div>
        </form>
    </div>
</div>


@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js for Rental Trends
    const ctx = document.getElementById('rentalChart').getContext('2d');
    const rentalChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Reservas',
                data: [{{ $rentalRequests->where('created_at', '>=', now()->subMonths(5)->startOfMonth())->where('created_at', '<', now()->subMonths(4)->startOfMonth())->count() }},
                       {{ $rentalRequests->where('created_at', '>=', now()->subMonths(4)->startOfMonth())->where('created_at', '<', now()->subMonths(3)->startOfMonth())->count() }},
                       {{ $rentalRequests->where('created_at', '>=', now()->subMonths(3)->startOfMonth())->where('created_at', '<', now()->subMonths(2)->startOfMonth())->count() }},
                       {{ $rentalRequests->where('created_at', '>=', now()->subMonths(2)->startOfMonth())->where('created_at', '<', now()->subMonths(1)->startOfMonth())->count() }},
                       {{ $rentalRequests->where('created_at', '>=', now()->subMonths(1)->startOfMonth())->where('created_at', '<', now()->startOfMonth())->count() }},
                       {{ $rentalRequests->where('created_at', '>=', now()->startOfMonth())->count() }}],
                backgroundColor: 'rgba(25, 134, 255, 0.6)',
                borderColor: 'rgba(25, 134, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Modal Functions for Admin
    function openConfirmModal(requestId) {
        const form = document.getElementById('confirm-form');
        form.action = '{{ url("/rental-requests") }}/' + requestId + '/confirm';
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
    }

    function openTransferConfirmModal(transferId) {
        const form = document.getElementById('transfer-confirm-form');
        form.action = '{{ url("/transfers") }}/' + transferId + '/confirm';
        document.getElementById('transfer-confirm-modal').classList.remove('hidden');
    }

    function closeTransferConfirmModal() {
        document.getElementById('transfer-confirm-modal').classList.add('hidden');
    }

    function openCarModal() {
        document.getElementById('car-modal').classList.remove('hidden');
    }

    function closeCarModal() {
        document.getElementById('car-modal').classList.add('hidden');
    }
    function openCarModal() {
        document.getElementById('car-modal').classList.remove('hidden');
    }

    function closeCarModal() {
        document.getElementById('car-modal').classList.add('hidden');
    }
</script>
@endsection