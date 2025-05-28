@extends('layouts.app')

@section('title', 'Nova Transferência')

@section('content')
    <h1>Nova Transferência/Passeio</h1>
    <form method="POST" action="{{ route('transfers.store') }}">
        @csrf
        <div class="mb-3">
            <label for="origem" class="form-label">Origem</label>
            <input type="text" name="origem" id="origem" class="form-control @error('origem') is-invalid @enderror" value="{{ old('origem') }}">
            @error('origem')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="destino" class="form-label">Destino</label>
            <input type="text" name="destino" id="destino" class="form-control @error('destino') is-invalid @enderror" value="{{ old('destino') }}">
            @error('destino')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="data_hora" class="form-label">Data e Hora</label>
            <input type="datetime-local" name="data_hora" id="data_hora" class="form-control @error('data_hora') is-invalid @enderror" value="{{ old('data_hora') }}">
            @error('data_hora')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror">
                <option value="transfer" {{ old('tipo') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="passeio" {{ old('tipo') === 'passeio' ? 'selected' : '' }}>Passeio</option>
            </select>
            @error('tipo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="observacoes" class="form-label">Observações</label>
            <textarea name="observacoes" id="observacoes" class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes') }}</textarea>
            @error('observacoes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endsection