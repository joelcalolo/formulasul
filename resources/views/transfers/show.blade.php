@extends('layouts.app')

@section('title', 'Detalhes da Transferência')

@section('content')
    <h1>Detalhes da Transferência/Passeio</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Origem:</strong> {{ $transfer->origem }}</p>
            <p><strong>Destino:</strong> {{ $transfer->destino }}</p>
            <p><strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') }}</p>
            <p><strong>Tipo:</strong> {{ ucfirst($transfer->tipo) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($transfer->status) }}</p>
            <p><strong>Observações:</strong> {{ $transfer->observacoes ?? 'Nenhuma' }}</p>
            <p><strong>E-mail Enviado:</strong> {{ $transfer->email_enviado ? 'Sim' : 'Não' }}</p>
        </div>
    </div>
    <a href="{{ route('transfers.index') }}" class="btn btn-secondary mt-3">Voltar</a>
@endsection