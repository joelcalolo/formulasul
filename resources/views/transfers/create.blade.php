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
        <div id="flight-fields" style="display: {{ old('tipo', 'transfer') === 'transfer' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="flight_number" class="form-label">Número do Voo</label>
                <input type="text" name="flight_number" id="flight_number" class="form-control @error('flight_number') is-invalid @enderror" value="{{ old('flight_number') }}">
                @error('flight_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="flight_date" class="form-label">Data do Voo</label>
                <input type="date" name="flight_date" id="flight_date" class="form-control @error('flight_date') is-invalid @enderror" value="{{ old('flight_date') }}">
                @error('flight_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="flight_time" class="form-label">Horário do Voo</label>
                <input type="time" name="flight_time" id="flight_time" class="form-control @error('flight_time') is-invalid @enderror" value="{{ old('flight_time') }}">
                @error('flight_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="airline" class="form-label">Companhia Aérea</label>
                <input type="text" name="airline" id="airline" class="form-control @error('airline') is-invalid @enderror" value="{{ old('airline') }}">
                @error('airline')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="special_requests" class="form-label">Pedidos Especiais</label>
                <textarea name="special_requests" id="special_requests" class="form-control @error('special_requests') is-invalid @enderror">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoSelect = document.getElementById('tipo');
            const flightFields = document.getElementById('flight-fields');
            tipoSelect.addEventListener('change', function() {
                if (this.value === 'transfer') {
                    flightFields.style.display = 'block';
                } else {
                    flightFields.style.display = 'none';
                }
            });
        });
        </script>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endsection