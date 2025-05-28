@extends('layouts.app')

@section('title', 'Aluguel de Viaturas')

@section('content')
<!-- Hero -->
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center">Aluguel de Viaturas</h1>
        <p class="mt-4 text-center">Escolha o veículo ideal para sua viagem.</p>
    </div>
</div>

<!-- Catalog Section -->
<section class="my-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Catálogo de Viaturas</h2>
        <p class="mt-4 text-lg text-gray-600 text-center max-w-2xl mx-auto">
            Escolha o veículo ideal para sua viagem.
        </p>
        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($cars as $car)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ $car->image ?? 'https://via.placeholder.com/300x200' }}" alt="{{ $car->modelo }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $car->modelo }}</h3>
                        <p class="mt-2 text-gray-600">{{ $car->categoria }}</p>
                        <p class="mt-2 text-[var(--primary)] font-bold">A partir de Kz{{ $car->price ?? 'Sob consulta' }}/dia</p>
                        <div class="mt-4 flex space-x-4">
                            <button onclick="openReservationModal({{ $car->id }}, '{{ $car->modelo }}')" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">Reservar</button>
                            <button onclick="openCarModal({{ $car->id }})" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Ver Detalhes</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600 col-span-full">Nenhum veículo disponível no momento.</p>
            @endforelse
        </div>
        {{ $cars->links() }}
    </div>
</section>

<!-- How to Rent Section -->
<section id="como-alugar" class="py-12 bg-[var(--background)]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Como Alugar uma Viatura</h2>
        <div class="mt-10 max-w-3xl mx-auto space-y-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[var(--primary)] text-white">1</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Escolha seu Veículo</h3>
                    <p class="mt-2 text-gray-600">Navegue pelo catálogo e selecione o veículo que melhor atende às suas necessidades.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[var(--primary)] text-white">2</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Preencha o Formulário</h3>
                    <p class="mt-2 text-gray-600">Forneça seus dados pessoais e as datas de aluguel no formulário de reserva.</p>
                </div>
            </div>
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[var(--primary)] text-white">3</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirme e Retire</h3>
                    <p class="mt-2 text-gray-600">Após a confirmação, retire o veículo no local e horário combinados.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Detalhes do Carro -->
<div id="car-details-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeCarModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 id="car-model" class="text-xl font-bold text-[var(--primary)] mb-4">Modelo do Carro</h2>
        <img id="car-image" src="" alt="Imagem do Carro" class="w-full h-48 object-cover rounded mb-4">
        <p id="car-category" class="text-gray-600 mb-2">Categoria: </p>
        <p id="car-price" class="text-gray-600 mb-2">Preço: </p>
        <p id="car-status" class="text-gray-600 mb-2">Status: </p>
        <button class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90 mt-4" onclick="closeCarModal()">Fechar</button>
    </div>
</div>

<!-- Modal de Reserva -->
<div id="reservation-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeReservationModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Reservar Carro</h2>
        <form method="POST" action="{{ route('rental-requests.store') }}">
            @csrf
            <input type="hidden" name="carro_principal_id" id="carro_principal_id">
            <div class="mb-4">
                <label for="car-model-display" class="block text-sm font-medium text-gray-700">Carro</label>
                <input type="text" id="car-model-display" class="w-full px-3 py-2 border rounded bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
            </div>
            <div class="mb-4">
                <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data de Início</label>
                <input type="date" name="data_inicio" id="data_inicio" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="data_fim" class="block text-sm font-medium text-gray-700">Data de Fim</label>
                <input type="date" name="data_fim" id="data_fim" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="local_entrega" class="block text-sm font-medium text-gray-700">Local de Entrega</label>
                <input type="text" name="local_entrega" id="local_entrega" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                <textarea name="observacoes" id="observacoes" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]"></textarea>
            </div>
            <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-md font-semibold hover:bg-[var(--primary)]/90 transition">Enviar Solicitação</button>
        </form>
    </div>
</div>

<script>
    function openCarModal(carId) {
        // Obter os dados dos carros (acessando a propriedade 'data' se for paginado)
        const cars = @json($cars->items());

        // Verificar se 'cars' é um array
        if (!Array.isArray(cars)) {
            console.error('Os dados dos carros não são um array:', cars);
            return;
        }

        // Encontrar o carro pelo ID
        const car = cars.find(c => c.id === carId);
        if (car) {
            document.getElementById('car-model').textContent = car.modelo;
            document.getElementById('car-image').src = car.image ?? 'https://via.placeholder.com/300x200';
            document.getElementById('car-category').textContent = `Categoria: ${car.categoria}`;
            document.getElementById('car-price').textContent = `Preço: Kz${car.price ?? 'Sob consulta'}`;
            document.getElementById('car-status').textContent = `Status: ${car.status}`;

            // Remover a classe 'hidden' para exibir o modal
            document.getElementById('car-details-modal').classList.remove('hidden');
        } else {
            console.error('Carro não encontrado com o ID:', carId);
        }
    }

    function closeCarModal() {
        // Adicionar a classe 'hidden' para fechar o modal
        document.getElementById('car-details-modal').classList.add('hidden');
    }

    function openReservationModal(carId, carModel) {
        // Preencher os campos do modal com os dados do carro selecionado
        document.getElementById('carro_principal_id').value = carId;
        document.getElementById('car-model-display').value = carModel;

        // Exibir o modal
        document.getElementById('reservation-modal').classList.remove('hidden');
    }

    function closeReservationModal() {
        // Fechar o modal
        document.getElementById('reservation-modal').classList.add('hidden');
    }

    // Configurar datas mínimas para os campos de data
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('data_inicio').min = today;

        document.getElementById('data_inicio').addEventListener('change', function() {
            document.getElementById('data_fim').min = this.value;
        });
    });
</script>
@endsection