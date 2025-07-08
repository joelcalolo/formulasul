@extends('layouts.app')

@section('title', 'Gerenciar Transfers')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex-shrink-0 hidden md:block">
        <div class="h-full flex flex-col">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-blue-700">Admin Menu</h2>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.transfers.index') }}" class="block px-4 py-2 rounded {{ request()->is('admin/transfers*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-shuttle-van mr-2"></i> Transfers
                </a>
                <a href="{{ route('admin.rental-requests.index') }}" class="block px-4 py-2 rounded {{ request()->is('admin/rental-requests*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-car mr-2"></i> Reservas de Aluguel
                </a>
            </nav>
        </div>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-6">Transfers</h1>
        <div class="bg-white shadow rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transfers as $transfer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transfer->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transfer->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transfer->origem }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transfer->destino }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transfer->data_hora ? \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transfer->status === 'confirmado' ? 'bg-green-100 text-green-800' : ($transfer->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($transfer->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.transfers.show', $transfer->id) }}" class="text-blue-600 hover:underline mr-2">Detalhes</a>
                            @if($transfer->status === 'pendente')
                            <form method="POST" action="{{ route('admin.transfers.confirm', $transfer->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:underline">Confirmar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Nenhum transfer encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection 