@extends('layouts.app')

@section('title', 'Minhas Transferências')

@section('content')
    <h1>Minhas Transferências/Passeios</h1>
    <a href="{{ route('transfers.create') }}" class="btn btn-primary mb-3">Nova Transferência</a>
    @if ($transfers->isEmpty())
        <p>Nenhuma transferência encontrada.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Data/Hora</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->origem }}</td>
                        <td>{{ $transfer->destino }}</td>
                        <td>{{ \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($transfer->tipo) }}</td>
                        <td>{{ ucfirst($transfer->status) }}</td>
                        <td>
                            <a href="{{ route('transfers.show', $transfer) }}" class="btn btn-sm btn-info">Detalhes</a>
                            @if ($transfer->status === 'pendente')
                                <form action="{{ route('transfers.destroy', $transfer) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja cancelar?')">Cancelar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $transfers->links() }}
    @endif
@endsection