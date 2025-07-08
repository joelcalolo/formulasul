@extends('layouts.app')

@section('title', 'Detalhes do Transfer')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Detalhes do Pedido de Transfer</h1>
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Dados do Cliente</h2>
        <p><strong>Nome:</strong> {{ $transfer->user->name ?? '-' }}</p>
        <p><strong>E-mail:</strong> {{ $transfer->user->email ?? '-' }}</p>
        <p><strong>Telefone:</strong> {{ $transfer->user->phone ?? '-' }}</p>
    </div>
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Dados do Transfer</h2>
        <p><strong>Origem:</strong> {{ $transfer->origem }}</p>
        <p><strong>Destino:</strong> {{ $transfer->destino }}</p>
        <p><strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') }}</p>
        <p><strong>Tipo:</strong> {{ ucfirst($transfer->tipo) }}</p>
        <p><strong>Status:</strong> <span class="font-semibold">{{ ucfirst($transfer->status) }}</span></p>
        <p><strong>Observações:</strong> {{ $transfer->observacoes ?? 'Nenhuma' }}</p>
        <p><strong>E-mail Enviado:</strong> {{ $transfer->email_enviado ? 'Sim' : 'Não' }}</p>
        @if($transfer->tipo === 'transfer')
            <hr>
            <p><strong>Número do Voo:</strong> {{ $transfer->flight_number ?? 'Não informado' }}</p>
            <p><strong>Data do Voo:</strong> {{ $transfer->flight_date ? \Carbon\Carbon::parse($transfer->flight_date)->format('d/m/Y') : 'Não informado' }}</p>
            <p><strong>Horário do Voo:</strong> {{ $transfer->flight_time ?? 'Não informado' }}</p>
            <p><strong>Companhia Aérea:</strong> {{ $transfer->airline ?? 'Não informado' }}</p>
            <p><strong>Número de Pessoas:</strong> {{ $transfer->num_pessoas ?? 'Não informado' }}</p>
            <p><strong>Pedidos Especiais:</strong> {{ $transfer->special_requests ?? 'Nenhum' }}</p>
        @endif
    </div>
    <div class="flex gap-4 mt-6">
        @if($transfer->status === 'pendente')
            <form method="POST" action="{{ route('admin.transfers.confirm', $transfer->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Confirmar</button>
            </form>
        @endif
        <a href="{{ route('admin.transfers.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Voltar</a>
    </div>
</div>
@endsection 