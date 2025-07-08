@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .form-input:invalid {
        border-color: red;
    }

    .error-message {
        display: none;
    }

    .image-preview-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .image-preview {
        position: relative;
        width: 100px;
        height: 100px;
        overflow: hidden;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 14px;
        cursor: pointer;
    }

    .tabs .tab.active {
        border-bottom: 2px solid #2563eb;
        color: #2563eb;
    }
</style>
@endpush

@section('content')
<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="flex h-screen overflow-hidden bg-gray-100">
    <!-- Mobile menu button -->
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="mobile-menu-button" class="bg-white p-2 rounded-md shadow-lg">
            <i class="fas fa-bars text-gray-600"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white shadow-lg flex-shrink-0 hidden md:block fixed md:relative inset-y-0 left-0 z-40 transform transition-transform duration-300 ease-in-out">
        <div class="h-full flex flex-col">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-blue-700">
                        @if (auth()->user()->role === 'admin')
                            Admin Menu
                        @else
                            Menu do Cliente
                        @endif
                    </h2>
                    <button id="close-sidebar" class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                
                @if (auth()->user()->role === 'admin')
                    <!-- Menu Admin -->
                    <a href="{{ route('admin.transfers.index') }}" class="block px-4 py-2 rounded {{ request()->is('admin/transfers*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-shuttle-van mr-2"></i> Transfers
                    </a>
                    <a href="{{ route('admin.rental-requests.index') }}" class="block px-4 py-2 rounded {{ request()->is('admin/rental-requests*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-car mr-2"></i> Reservas de Aluguel
                    </a>
                    <a href="{{ route('admin.cars.index') }}" class="block px-4 py-2 rounded {{ request()->is('admin/cars*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cog mr-2"></i> Gerenciar Carros
                    </a>
                @else
                    <!-- Menu Cliente -->
                    <a href="{{ route('transfers.index') }}" class="block px-4 py-2 rounded {{ request()->is('transfers*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-shuttle-van mr-2"></i> Meus Transfers
                    </a>
                    <a href="{{ route('rental-requests.index') }}" class="block px-4 py-2 rounded {{ request()->is('rental-requests*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-car mr-2"></i> Minhas Reservas
                    </a>
                    <a href="{{ route('cars.index') }}" class="block px-4 py-2 rounded {{ request()->is('cars*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-search mr-2"></i> Explorar Carros
                    </a>
                @endif
            </nav>
        </div>
    </aside>
    
    <!-- Overlay for mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden hidden"></div>
    
    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto md:ml-0">
        <!-- Header -->
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                @if (auth()->user()->role === 'admin')
                    Dashboard Administrativo
                @else
                    Meu Dashboard
                @endif
            </h1>
        </header>

        <!-- Hero -->
        <div class="bg-blue-600 text-white rounded-lg p-8 mb-6">
            <div class="text-center">
                <h1 class="text-2xl md:text-3xl font-bold">Bem-vindo, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-md">
                    @if (auth()->user()->role === 'admin')
                        Gerencie reservas, transfers e veículos do sistema.
                    @else
                        Acompanhe suas reservas, transfers e explore nossos veículos.
                    @endif
                </p>
                @if (auth()->user()->role === 'cliente')
                    <a href="{{ route('rental-requests.create') }}" class="mt-4 inline-block bg-white text-blue-600 px-6 py-2 rounded-md hover:bg-gray-100 font-medium">Nova Reserva</a>
                @endif
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            @if (auth()->user()->role === 'admin')
                <!-- Cards para Admin -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Reservas Pendentes</h2>
                            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $rentalRequests->where('status', 'pendente')->count() }}</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.rental-requests.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Ver Todas <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Transfers Pendentes</h2>
                            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $transfers->where('status', 'pendente')->count() }}</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-shuttle-van text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.transfers.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Ver Todos <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Total de Veículos</h2>
                            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $cars->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-car text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.cars.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Gerenciar <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Veículos Disponíveis</h2>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $cars->where('status', 'disponivel')->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.cars.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Ver Todos <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @else
                <!-- Cards para Cliente -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Minhas Reservas</h2>
                            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $rentalRequests->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-car text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('rental-requests.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Ver Todas <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Meus Transfers</h2>
                            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $transfers->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-shuttle-van text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('transfers.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Ver Todos <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Carros Disponíveis</h2>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $cars->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-key text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('cars.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Explorar <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-gray-600 text-sm font-medium">Reservas Ativas</h2>
                            <p class="text-2xl font-bold text-green-600 mt-2">{{ $rentalRequests->where('status', 'confirmado')->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <a href="{{ route('rental-requests.index') }}" class="mt-4 text-sm text-blue-600 hover:underline inline-flex items-center">
                        Ver Ativas <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Role-Based Sections -->
        @if (auth()->user()->role === 'cliente')
            <!-- Cliente: Recent Activity -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Minhas Atividades Recentes</h2>
                    <a href="{{ route('rental-requests.index') }}" class="text-sm text-blue-600 hover:underline">Ver Todas</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="fas fa-car mr-2 text-blue-600"></i>
                                            Reserva de Aluguel
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $request->car->marca ?? 'N/A' }} {{ $request->car->modelo ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : 'Data não definida' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $request->status === 'confirmado' ? 'bg-green-100 text-green-800' : 
                                               ($request->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="openReservationDetailsModal({{ $request->id }})" class="text-blue-600 hover:underline">Ver Detalhes</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-600">Nenhuma reserva recente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cliente: Transfers Recentes -->
            @if($transfers->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Meus Transfers Recentes</h2>
                    <a href="{{ route('transfers.index') }}" class="text-sm text-blue-600 hover:underline">Ver Todos</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transfers->take(5) as $transfer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        <div class="font-medium">{{ $transfer->destino }}</div>
                                        <div class="text-sm text-gray-500">{{ $transfer->origem }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        {{ $transfer->data_hora ? \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') : 'Data não definida' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $transfer->status === 'confirmado' ? 'bg-green-100 text-green-800' : 
                                               ($transfer->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('transfers.show', $transfer->id) }}" class="text-blue-600 hover:underline">Ver Detalhes</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-600">Nenhum transfer recente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Cliente: Ações Rápidas -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('rental-requests.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-plus-circle text-blue-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-medium text-blue-900">Nova Reserva</div>
                            <div class="text-sm text-blue-700">Alugar um veículo</div>
                        </div>
                    </a>
                    <a href="{{ route('transfers.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-shuttle-van text-green-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-medium text-green-900">Novo Transfer</div>
                            <div class="text-sm text-green-700">Solicitar transporte</div>
                        </div>
                    </a>
                    <a href="{{ route('cars.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-search text-purple-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-medium text-purple-900">Explorar Carros</div>
                            <div class="text-sm text-purple-700">Ver disponibilidade</div>
                        </div>
                    </a>
                </div>
            </div>
        @else
            <!-- Admin: Management -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Pending Rental Requests -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Reservas Pendentes</h3>
                        <a href="{{ route('admin.rental-requests.index') }}" class="text-sm text-blue-600 hover:underline">Ver Todas</a>
                    </div>
                    <div class="space-y-4">
                        @forelse ($rentalRequests->where('status', 'pendente')->take(3) as $request)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $request->car->marca ?? 'N/A' }} {{ $request->car->modelo ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600">{{ $request->user->name ?? 'N/A' }} - {{ $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') : 'Data não definida' }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.rental-requests.show', $request->id) }}" class="bg-gray-200 text-blue-700 px-3 py-1 rounded hover:bg-gray-300 text-sm">Detalhes</a>
                                    <button onclick="openConfirmModal({{ $request->id }})" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-sm">Confirmar</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">Nenhuma reserva pendente.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Transfers -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Transfers Pendentes</h3>
                        <a href="{{ route('admin.transfers.index') }}" class="text-sm text-blue-600 hover:underline">Ver Todos</a>
                    </div>
                    <div class="space-y-4">
                        @forelse ($transfers->where('status', 'pendente')->take(3) as $transfer)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transfer->destino }}</p>
                                    <p class="text-xs text-gray-600">{{ $transfer->user->name ?? 'N/A' }} - {{ $transfer->data_hora ? \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') : 'Data não definida' }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.transfers.show', $transfer->id) }}" class="bg-gray-200 text-blue-700 px-3 py-1 rounded hover:bg-gray-300 text-sm">Detalhes</a>
                                    <button onclick="openTransferConfirmModal({{ $transfer->id }})" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-sm">Confirmar</button>
                                    <button onclick="openTransferRejectModal({{ $transfer->id }})" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm">Rejeitar</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">Nenhum transfer pendente.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Gerenciamento de Carros -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Gerenciar Carros</h3>
                    <button onclick="openCarModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                        <i class="fas fa-plus mr-2"></i>Novo Carro
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca/Modelo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especificações</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço Base</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($cars as $car)
                                <tr data-car-id="{{ $car->id }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        <div>
                                            <div class="font-medium">{{ $car->marca }} {{ $car->modelo }}</div>
                                            @if($car->cor)
                                                <div class="text-sm text-gray-500">{{ $car->cor }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        <div class="text-sm">
                                            <div>{{ $car->caixa }} • {{ $car->tracao }}</div>
                                            <div>{{ $car->lugares }} lugares • {{ $car->combustivel }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $car->status === 'disponivel' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($car->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                        @if($car->price)
                                            Kz {{ number_format($car->price, 2, ',', '.') }}
                                        @else
                                            <span class="text-gray-400">Não definido</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="editCar({{ $car->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $car->id }}, '{{ $car->marca }} {{ $car->modelo }}')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-600">Nenhum carro cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>
</div>

@include('admin.components.modals.car-modal')
@include('admin.components.modals.edit-car-modal')
@include('admin.components.modals.delete-car-modal')
@include('admin.components.modals.confirm-reservation-modal')
@include('admin.components.modals.transfer-modals')
@include('admin.components.modals.reservation-details-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
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

    // Tratamento global de erros AJAX
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('fetch')) {
            showToast('Erro de conexão. Verifique sua internet e tente novamente.', 'error');
        }
    });

    // Interceptar erros de fetch
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response;
            })
            .catch(error => {
                console.error('Erro na requisição fetch:', error);
                if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                    showToast('Erro de conexão. Verifique sua internet e tente novamente.', 'error');
                } else if (error.message.includes('401')) {
                    showToast('Sessão expirada. Faça login novamente.', 'error');
                    setTimeout(() => window.location.href = '/login', 2000);
                } else if (error.message.includes('403')) {
                    showToast('Acesso negado. Você não tem permissão para esta ação.', 'error');
                } else if (error.message.includes('404')) {
                    showToast('Recurso não encontrado.', 'error');
                } else if (error.message.includes('500')) {
                    showToast('Erro interno do servidor. Tente novamente mais tarde.', 'error');
                } else {
                    showToast('Erro inesperado. Tente novamente.', 'error');
                }
                throw error;
            });
    };

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const closeSidebarButton = document.getElementById('close-sidebar');

    function openSidebar() {
        sidebar.classList.remove('hidden');
        sidebar.classList.add('block');
        sidebarOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('hidden');
        sidebar.classList.remove('block');
        sidebarOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    mobileMenuButton.addEventListener('click', openSidebar);
    closeSidebarButton.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar when clicking on a link (mobile)
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                closeSidebar();
            }
        });
    });

    // Close sidebar on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            closeSidebar();
        }
    });
});
</script>
@endpush