@extends('layouts.app')

@section('title', 'Nova Solicitação')

@section('content')
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center">Nova Solicitação de Aluguel</h1>
    </div>
</div>

<section class="py-12">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('rental-requests.store') }}">
                @csrf
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="carro_principal_id">Carro Principal*</label>
                        <select name="carro_principal_id" id="carro_principal_id" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                            <option value="">Selecione um carro</option>
                            @foreach($availableCars as $car)
                                <option value="{{ $car->id }}">{{ $car->modelo }} - {{ $car->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="carro_secundario_id">Carro Secundário (Opcional)</label>
                        <select name="carro_secundario_id" id="carro_secundario_id" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                            <option value="">Nenhum</option>
                            @foreach($availableCars as $car)
                                <option value="{{ $car->id }}">{{ $car->modelo }} - {{ $car->categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="data_inicio">Data de Início*</label>
                        <input type="date" name="data_inicio" id="data_inicio" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="data_fim">Data de Fim*</label>
                        <input type="date" name="data_fim" id="data_fim" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="local_entrega">Local de Entrega*</label>
                    <input type="text" name="local_entrega" id="local_entrega" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                </div>
                
                <div class="mt-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="observacoes">Observações</label>
                    <textarea name="observacoes" id="observacoes" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]"></textarea>
                </div>
                
                <div class="mt-8">
                    <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-md font-semibold hover:bg-[var(--primary)]/90 transition">
                        Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('data_inicio').min = today;
    
    document.getElementById('data_inicio').addEventListener('change', function() {
        document.getElementById('data_fim').min = this.value;
    });
});
</script>
@endsection