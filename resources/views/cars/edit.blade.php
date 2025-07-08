@extends('layouts.app')

@section('title', 'Editar Carro')

@section('content')
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center">Editar Carro</h1>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('cars.update', $car->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Abas -->
                    <div class="tabs flex border-b mb-4">
                        <button type="button" class="tab active px-4 py-2 text-[var(--primary)] font-semibold border-b-2 border-[var(--primary)]" onclick="showTab('car-details')">Dados do Carro</button>
                        <button type="button" class="tab px-4 py-2 text-gray-500 hover:text-[var(--primary)]" onclick="showTab('price-table')">Tabela de Preços</button>
                    </div>

                    <!-- Aba: Dados do Carro -->
                    <div id="car-details" class="tab-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                                <input type="text" name="marca" id="marca" value="{{ old('marca', $car->marca) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                            </div>
                            <div class="mb-4">
                                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                                <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $car->modelo) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                            </div>
                            <div class="mb-4">
                                <label for="caixa" class="block text-sm font-medium text-gray-700">Caixa</label>
                                <select name="caixa" id="caixa" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                                    <option value="manual" {{ $car->caixa === 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatica" {{ $car->caixa === 'automatica' ? 'selected' : '' }}>Automática</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="tracao" class="block text-sm font-medium text-gray-700">Tração</label>
                                <select name="tracao" id="tracao" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                                    <option value="Simples" {{ $car->tracao === 'Simples' ? 'selected' : '' }}>Simples</option>
                                    <option value="4X4" {{ $car->tracao === '4X4' ? 'selected' : '' }}>4X4</option>
                                 </select>
                            </div>
                            <div class="mb-4">
                                <label for="lugares" class="block text-sm font-medium text-gray-700">Lugares</label>
                                <input type="number" name="lugares" id="lugares" value="{{ old('lugares', $car->lugares) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" min="1" required>
                            </div>
                            <div class="mb-4">
                                <label for="combustivel" class="block text-sm font-medium text-gray-700">Combustível</label>
                                <select name="combustivel" id="combustivel" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                                    <option value="gasolina" {{ $car->combustivel === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                                    <option value="diesel" {{ $car->combustivel === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="eletrico" {{ $car->combustivel === 'eletrico' ? 'selected' : '' }}>Elétrico</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                                    <option value="disponivel" {{ $car->status === 'disponivel' ? 'selected' : '' }}>Disponível</option>
                                    <option value="alugado" {{ $car->status === 'alugado' ? 'selected' : '' }}>Alugado</option>
                                    <option value="manutencao" {{ $car->status === 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="image" class="block text-sm font-medium text-gray-700">Imagem do Carro</label>
                                @if($car->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $car->image) }}" alt="Imagem atual do carro" class="w-32 h-32 object-cover rounded">
                                        <p class="text-sm text-gray-500 mt-1">Imagem atual</p>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                                <p class="mt-1 text-sm text-gray-500">Formatos aceitos: JPG, JPEG, PNG. Tamanho máximo: 5MB. Deixe em branco para manter a imagem atual.</p>
                            </div>
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Preço por Dia</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $car->price) }}" step="0.01" min="0" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                            </div>
                        </div>
                    </div>

                    <!-- Aba: Tabela de Preços -->
                    <div id="price-table" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="preco_dentro_com_motorista" class="block text-sm font-medium text-gray-700">Dentro da Cidade (Com Motorista)</label>
                                <input type="number" name="preco_dentro_com_motorista" id="preco_dentro_com_motorista" value="{{ old('preco_dentro_com_motorista', $car->priceTable->preco_dentro_com_motorista) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="preco_dentro_sem_motorista" class="block text-sm font-medium text-gray-700">Dentro da Cidade (Sem Motorista)</label>
                                <input type="number" name="preco_dentro_sem_motorista" id="preco_dentro_sem_motorista" value="{{ old('preco_dentro_sem_motorista', $car->priceTable->preco_dentro_sem_motorista) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="preco_fora_com_motorista" class="block text-sm font-medium text-gray-700">Fora da Cidade (Com Motorista)</label>
                                <input type="number" name="preco_fora_com_motorista" id="preco_fora_com_motorista" value="{{ old('preco_fora_com_motorista', $car->priceTable->preco_fora_com_motorista) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="preco_fora_sem_motorista" class="block text-sm font-medium text-gray-700">Fora da Cidade (Sem Motorista)</label>
                                <input type="number" name="preco_fora_sem_motorista" id="preco_fora_sem_motorista" value="{{ old('preco_fora_sem_motorista', $car->priceTable->preco_fora_sem_motorista) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="taxa_entrega_recolha" class="block text-sm font-medium text-gray-700">Taxa de Entrega/Recolha</label>
                                <input type="number" name="taxa_entrega_recolha" id="taxa_entrega_recolha" value="{{ old('taxa_entrega_recolha', $car->priceTable->taxa_entrega_recolha) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="plafond_km_dia" class="block text-sm font-medium text-gray-700">Plafond de KM por Dia</label>
                                <input type="number" name="plafond_km_dia" id="plafond_km_dia" value="{{ old('plafond_km_dia', $car->priceTable->plafond_km_dia) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                            </div>
                            <div class="mb-4">
                                <label for="preco_km_extra" class="block text-sm font-medium text-gray-700">Preço por KM Extra</label>
                                <input type="number" name="preco_km_extra" id="preco_km_extra" value="{{ old('preco_km_extra', $car->priceTable->preco_km_extra) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                            <div class="mb-4">
                                <label for="caucao" class="block text-sm font-medium text-gray-700">Caução</label>
                                <input type="number" name="caucao" id="caucao" value="{{ old('caucao', $car->priceTable->caucao) }}" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('cars.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancelar</a>
                        <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabId) {
        // Localizar elementos apenas no contexto desta página
        const form = document.querySelector('form');
        
        // Esconder todas as abas de conteúdo
        form.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
        
        // Mostrar a aba selecionada
        const selectedTab = form.querySelector(`#${tabId}`);
        if (selectedTab) {
            selectedTab.classList.remove('hidden');
        }

        // Atualizar estado visual das abas
        form.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active', 'border-[var(--primary)]', 'text-[var(--primary)]');
            tab.classList.add('text-gray-500');
        });
        
        // Ativar a aba clicada
        const activeTab = form.querySelector(`[onclick="showTab('${tabId}')"]`);
        if (activeTab) {
            activeTab.classList.add('active', 'border-[var(--primary)]', 'text-[var(--primary)]');
            activeTab.classList.remove('text-gray-500');
        }
    }
</script>
@endsection 