@extends('layouts.app')

@section('title', 'Aluguel de Viaturas')

@push('styles')
<!-- car-gallery.css já está incluído no layout principal -->
@endpush

@section('content')

<!-- Hero com formulário -->
<section class="bg-[#D0D0FB] text-[#232323] py-20 px-6 bg-cover bg-center relative" style="background-image: url('{{ asset('images/frota.png') }}');">
  <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
  <div class="max-w-6xl mx-auto relative z-10">
    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white">Conheça toda a fronta da Fórmula Sul</h1>
    <p class="text-lg mb-8 text-white">Escolha seu tipo de carro que precisas, seu estilo e suas necessidades.</p>

    <!-- Filtros -->
    <form id="filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-gray-900">
      <div class="flex flex-col">
        <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Buscar</label>
        <input type="text" name="search" value="{{ request('search') }}" 
          placeholder="Marca ou modelo" 
          class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
      </div>
      
      <div class="flex flex-col">
        <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Marca</label>
        <select name="marca" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
          <option value="">Todas as marcas</option>
          @foreach($marcas as $marca)
            <option value="{{ $marca }}" {{ request('marca') == $marca ? 'selected' : '' }}>
              {{ $marca }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex flex-col">
        <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Tipo de Serviço</label>
        <select name="tipo_servico" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
          <option value="">Todos os tipos</option>
          <option value="dentro_com_motorista" {{ request('tipo_servico') == 'dentro_com_motorista' ? 'selected' : '' }}>
            Dentro da Cidade (Com Motorista)
          </option>
          <option value="dentro_sem_motorista" {{ request('tipo_servico') == 'dentro_sem_motorista' ? 'selected' : '' }}>
            Dentro da Cidade (Sem Motorista)
          </option>
          <option value="fora_com_motorista" {{ request('tipo_servico') == 'fora_com_motorista' ? 'selected' : '' }}>
            Fora da Cidade (Com Motorista)
          </option>
          <option value="fora_sem_motorista" {{ request('tipo_servico') == 'fora_sem_motorista' ? 'selected' : '' }}>
            Fora da Cidade (Sem Motorista)
          </option>
        </select>
    </div>

      <div class="flex flex-col">
        <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Ordenar por</label>
        <select name="ordenar" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
          <option value="recentes" {{ request('ordenar') == 'recentes' ? 'selected' : '' }}>Mais recentes</option>
          <option value="preco_asc" {{ request('ordenar') == 'preco_asc' ? 'selected' : '' }}>Menor preço</option>
          <option value="preco_desc" {{ request('ordenar') == 'preco_desc' ? 'selected' : '' }}>Maior preço</option>
          <option value="disponibilidade" {{ request('ordenar') == 'disponibilidade' ? 'selected' : '' }}>Disponibilidade</option>
        </select>
</div>

      <div class="md:col-span-4 flex justify-end space-x-4 mt-2">
        <button type="reset" onclick="limparFiltros()" class="px-4 py-2 text-[var(--secondary)] hover:text-[var(--primary)]">
          Limpar Filtros
        </button>
        <button type="submit" class="bg-[var(--primary)] text-[var(--secondary)] px-6 py-2 rounded-md hover:bg-[var(--accent)] transition">
          Aplicar Filtros
        </button>
      </div>
    </form>
  </div>
</section>




<!-- Catalog Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-[var(--secondary)] mb-6 text-center">Catálogo de Viaturas</h2>
        <p class="text-gray-600 text-center max-w-2xl mx-auto mb-12">Escolha o veículo ideal para sua viagem.</p>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($cars as $car)
                <div class="rounded-2xl shadow-[var(--shadow)] bg-white hover:shadow-lg transition-all duration-300 cursor-pointer" data-car-id="{{ $car->id }}" onclick="openCarDetails({{ $car->id }})">
                    <div class="relative">
                        @if($car->image_cover)
                            <img src="{{ asset('storage/' . $car->image_cover) }}" 
                                 alt="{{ $car->marca }} {{ $car->modelo }}" 
                                 class="rounded-t-2xl h-56 w-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300"
                                 onclick="event.stopPropagation(); openCarGallery({{ $car->id }})">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=Sem+Imagem" 
                                 alt="Sem Imagem" 
                                 class="rounded-t-2xl h-56 w-full object-cover">
                        @endif
                        
                        <div class="absolute top-4 right-4 bg-[var(--primary)] text-[var(--secondary)] text-sm font-semibold rounded-full px-4 py-1">
                            {{ strtoupper($car->status) }}
                        </div>
                        
                        @if($car->hasGallery())
                            <div class="absolute bottom-4 right-4 bg-black/50 text-white text-xs rounded-full px-2 py-1">
                                <i class="fas fa-images mr-1"></i>{{ $car->image_count }} fotos
                            </div>
                            
                            <!-- Botão de visualizar galeria -->
                            <div class="absolute inset-0 bg-black/0 hover:bg-black/20 transition-colors duration-300 rounded-t-2xl flex items-center justify-center opacity-0 hover:opacity-100">
                                <button onclick="event.stopPropagation(); openCarGallery({{ $car->id }})" class="bg-white/90 text-gray-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                                    <i class="fas fa-images"></i>
                                    Ver Galeria
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2 text-[var(--secondary)]">{{ $car->marca }} {{ $car->modelo }}</h2>
                        <p class="text-gray-600 mb-4">{{ ucfirst($car->status) }}</p>
                        <div class="flex items-center mb-4">
                            <span class="text-[var(--primary)] font-semibold mr-2">4.8</span>
                            <div class="flex text-yellow-400">★★★★★</div>
                        </div>
                        <div class="text-2xl font-semibold mb-4 text-[var(--secondary)]">Akz {{ number_format($car->price, 2, ',', '.') }}/dia</div>
                        <div class="flex justify-between text-gray-600 text-sm mb-4">
                            <div class="flex items-center gap-1">🚗 {{ $car->caixa ?? 'Automático' }}</div>
                            <div class="flex items-center gap-1">⛽ {{ $car->combustivel ?? 'Gasolina' }}</div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="event.stopPropagation(); openCarDetails({{ $car->id }})" class="flex-1 bg-[var(--primary)] text-[var(--secondary)] px-4 py-2 rounded-md hover:bg-[var(--accent)] transition">
                                Ver Detalhes
                            </button>
                            @if($car->hasGallery())
                                <button onclick="event.stopPropagation(); openCarGallery({{ $car->id }})" class="px-3 py-2 border border-[var(--primary)] text-[var(--primary)] rounded-md hover:bg-[var(--primary)]/10 transition">
                                    <i class="fas fa-images"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600">Nenhum carro encontrado.</p>
                </div>
            @endforelse
        </div>
        <div class="mt-8">
        {{ $cars->links() }}
        </div>
    </div>
</section>

<!-- How to Rent Section -->
<section id="como-alugar" class="pt-12 bg-[var(--background)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
    <div class="modal-content bg-white rounded-lg w-full max-w-lg mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeCarModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 id="car-model" class="text-2xl font-bold text-[var(--primary)] mb-4">Modelo do Carro</h2>
        <img id="car-image" src="" alt="Imagem do Carro" class="w-full h-48 object-cover rounded mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p id="car-category" class="text-gray-600">Categoria: <span class="font-semibold"></span></p>
            <p id="car-price" class="text-gray-600">Preço: <span class="font-semibold"></span></p>
            <p id="car-status" class="text-gray-600">Status: <span class="font-semibold"></span></p>
            <p id="car-seats" class="text-gray-600">Lugares: <span class="font-semibold"></span></p>
            <p id="car-fuel" class="text-gray-600">Combustível: <span class="font-semibold"></span></p>
            <p id="car-traction" class="text-gray-600">Tração: <span class="font-semibold"></span></p>
            <p id="car-transmission" class="text-gray-600">Caixa: <span class="font-semibold"></span></p>
        </div>
        <button class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90 mt-6" onclick="closeCarModal()">Fechar</button>
    </div>
</div>

<!-- Modal de Reserva -->
<div id="reservation-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center z-50">
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
            <div class="mb-4">
                <label for="tipo_servico" class="block text-sm font-medium text-gray-700">Tipo de Serviço</label>
                <select name="tipo_servico" id="tipo_servico" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                    <option value="">Selecione o tipo de serviço</option>
                    @if($car->priceTable && $car->priceTable->preco_dentro_com_motorista > 0)
                        <option value="dentro_com_motorista">Dentro da Cidade (Com Motorista)</option>
                    @endif
                    @if($car->priceTable && $car->priceTable->preco_dentro_sem_motorista > 0)
                        <option value="dentro_sem_motorista">Dentro da Cidade (Sem Motorista)</option>
                    @endif
                    @if($car->priceTable && $car->priceTable->preco_fora_com_motorista > 0)
                        <option value="fora_com_motorista">Fora da Cidade (Com Motorista)</option>
                    @endif
                    @if($car->priceTable && $car->priceTable->preco_fora_sem_motorista > 0)
                        <option value="fora_sem_motorista">Fora da Cidade (Sem Motorista)</option>
                    @endif
                </select>
            </div>

            <p id="preco-estimado" class="text-sm text-green-600 mt-2">Preço estimado: Kz 0</p>
            <p id="detalhes-preco" class="text-xs text-gray-500 mt-1"></p>
            <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-md font-semibold hover:bg-[var(--primary)]/90 transition">Enviar Solicitação</button>
        </form>
    </div>
</div>

<!-- Modal de Transfer -->
<div id="transfer-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeTransferModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-[var(--primary)] mb-4">Solicitar Transfer</h2>
        <form method="POST" action="{{ route('transfers.store') }}">
            @csrf
            <input type="hidden" name="car_id" id="transfer_car_id">
            <div class="mb-4">
                <label for="transfer-origin" class="block text-sm font-medium text-gray-700">Origem</label>
                <input type="text" name="origin" id="transfer-origin" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="transfer-destination" class="block text-sm font-medium text-gray-700">Destino</label>
                <input type="text" name="destination" id="transfer-destination" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="transfer-date" class="block text-sm font-medium text-gray-700">Data</label>
                <input type="datetime-local" name="date" id="transfer-date" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="transfer-passengers" class="block text-sm font-medium text-gray-700">Número de Passageiros</label>
                <input type="number" name="passengers" id="transfer-passengers" min="1" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
            </div>
            <div class="mb-4">
                <label for="transfer-notes" class="block text-sm font-medium text-gray-700">Observações</label>
                <textarea name="notes" id="transfer-notes" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]"></textarea>
            </div>
            <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-md font-semibold hover:bg-[var(--primary)]/90 transition">Solicitar Transfer</button>
        </form>
    </div>
</div>

<!-- Modal de Galeria de Carro -->
<div id="carGalleryModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800" id="carGalleryTitle">Galeria de Carro</h3>
            <button onclick="closeCarGallery()" class="text-gray-500 hover:text-gray-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <div id="carGalleryContent" class="car-gallery">
                <!-- Conteúdo da galeria será carregado aqui -->
            </div>
        </div>
    </div>
</div>

<script>
    // Funções globais que devem estar disponíveis imediatamente
    function openCarDetails(carId) {
        window.location.href = `/cars/${carId}`;
    }

    function openCarGallery(carId) {
        console.log('Abrindo galeria do carro:', carId);
        // Mostrar modal
        const modal = document.getElementById('carGalleryModal');
        modal.classList.remove('hidden');
        console.log('Modal exibido:', !modal.classList.contains('hidden'));
        
        // Carregar galeria via AJAX
        fetch(`/cars/${carId}/gallery`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(html => {
                console.log('HTML recebido:', html.substring(0, 200) + '...');
                document.getElementById('carGalleryContent').innerHTML = html;
                // Inicializar galeria
                if (window.carGallery) {
                    console.log('Inicializando galeria...');
                    window.carGallery.initCarousels();
                    window.carGallery.initThumbnails();
                } else {
                    console.log('window.carGallery não encontrado');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar galeria:', error);
                document.getElementById('carGalleryContent').innerHTML = '<p class="text-red-500">Erro ao carregar galeria</p>';
            });
    }

    function closeCarGallery() {
        document.getElementById('carGalleryModal').classList.add('hidden');
        document.getElementById('carGalleryContent').innerHTML = '';
    }

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
            document.getElementById('car-model').textContent = `${car.marca} ${car.modelo}`;
            if (car.image) {
                document.getElementById('car-image').src = `/storage/${car.image}`;
            } else {
                document.getElementById('car-image').src = 'data:image/svg+xml;base64,' + btoa(`
                    <svg class="w-full h-full text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                `);
            }
            document.getElementById('car-price').innerHTML = `Preço: <span class="font-semibold">Kz ${parseFloat(car.price).toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}/dia</span>`;
            document.getElementById('car-status').innerHTML = `Status: <span class="font-semibold">${car.status.charAt(0).toUpperCase() + car.status.slice(1)}</span>`;
            document.getElementById('car-seats').innerHTML = `Lugares: <span class="font-semibold">${car.lugares}</span>`;
            document.getElementById('car-fuel').innerHTML = `Combustível: <span class="font-semibold">${car.combustivel.charAt(0).toUpperCase() + car.combustivel.slice(1)}</span>`;
            document.getElementById('car-traction').innerHTML = `Tração: <span class="font-semibold">${car.tracao ? car.tracao.charAt(0).toUpperCase() + car.tracao.slice(1) : 'N/A'}</span>`;
            document.getElementById('car-transmission').innerHTML = `Caixa: <span class="font-semibold">${car.caixa.charAt(0).toUpperCase() + car.caixa.slice(1)}</span>`;
            
            // Exibir o modal
            document.getElementById('car-details-modal').classList.remove('hidden');
        }
    }

    function closeCarModal() {
        document.getElementById('car-details-modal').classList.add('hidden');
    }

    function openReservationModal(carId) {
        const cars = @json($cars->items());
        const car = cars.find(c => c.id === carId);
        
        if (car) {
            document.getElementById('carro_principal_id').value = car.id;
            document.getElementById('car-model-display').value = `${car.marca} ${car.modelo}`;
            document.getElementById('reservation-modal').classList.remove('hidden');
        }
    }

    function closeReservationModal() {
        document.getElementById('reservation-modal').classList.add('hidden');
    }

    function limparFiltros() {
        document.getElementById('search').value = '';
        document.getElementById('marca').value = '';
        document.getElementById('tipo_servico').value = '';
        document.getElementById('ordenar').value = 'recentes';
        document.getElementById('filter-form').submit();
    }

    function openTransferModal(carId) {
        @auth
            document.getElementById('transfer_car_id').value = carId;
            document.getElementById('transfer-modal').classList.remove('hidden');
        @else
            openModal();
        @endauth
    }

    function closeTransferModal() {
        document.getElementById('transfer-modal').classList.add('hidden');
    }

    // Adicionar event listeners para os modais
    document.addEventListener('DOMContentLoaded', function() {
        // Event listener para clicar fora do modal e fechar
        window.addEventListener('click', function(event) {
            const carModal = document.getElementById('car-details-modal');
            const reservationModal = document.getElementById('reservation-modal');
            const transferModal = document.getElementById('transfer-modal');
            
            if (event.target === carModal) {
                closeCarModal();
            }
            if (event.target === reservationModal) {
                closeReservationModal();
            }
            if (event.target === transferModal) {
                closeTransferModal();
            }
        });

        // Event listeners para datas de aluguel
        const dataInicio = document.getElementById('data_inicio');
        const dataFim = document.getElementById('data_fim');
        const tipoServico = document.getElementById('tipo_servico');
        const precoEstimado = document.getElementById('preco-estimado');
        const detalhesPreco = document.getElementById('detalhes-preco');

        function calcularPrecoEstimado() {
            console.log('Calculando preço estimado...');
            if (dataInicio.value && dataFim.value && tipoServico.value) {
                const inicio = new Date(dataInicio.value);
                const fim = new Date(dataFim.value);
                const dias = Math.ceil((fim - inicio) / (1000 * 60 * 60 * 24));
                
                console.log('Dias calculados:', dias);
                
                if (dias > 0) {
                    const carId = document.getElementById('carro_principal_id').value;
                    const car = @json($cars->items()).find(c => c.id === parseInt(carId));
                    
                    if (car && car.price_table) {
                        let precoPorDia = 0;
                        
                        // Usar preços da tabela de preços
                        switch(tipoServico.value) {
                            case 'dentro_com_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_dentro_com_motorista) || 0;
                                break;
                            case 'dentro_sem_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_dentro_sem_motorista) || 0;
                                break;
                            case 'fora_com_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_fora_com_motorista) || 0;
                                break;
                            case 'fora_sem_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_fora_sem_motorista) || 0;
                                break;
                        }

                        const caucao = parseFloat(car.price_table.caucao) || 0;
                        const precoAluguel = precoPorDia * dias;
                        const precoTotal = precoAluguel + caucao; // Incluir caução no total

                        console.log('Preço por dia:', precoPorDia);
                        console.log('Preço aluguel:', precoAluguel);
                        console.log('Caução:', caucao);
                        console.log('Preço total:', precoTotal);

                        if (precoPorDia > 0) {
                            precoEstimado.textContent = `Preço estimado: Kz ${precoTotal.toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            detalhesPreco.innerHTML = `
                                ${dias} dia(s) × Kz ${precoPorDia.toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}/dia<br>
                                + Caução: Kz ${caucao.toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                            `;
                        } else {
                            precoEstimado.textContent = 'Serviço não disponível';
                            detalhesPreco.textContent = '';
                        }
                    } else {
                        precoEstimado.textContent = 'Preço não disponível';
                        detalhesPreco.textContent = '';
                    }
                } else {
                    precoEstimado.textContent = 'Data de fim deve ser posterior à data de início';
                    detalhesPreco.textContent = '';
                }
            } else {
                precoEstimado.textContent = 'Selecione as datas e tipo de serviço';
                detalhesPreco.textContent = '';
            }
        }

        if (dataInicio) {
            dataInicio.addEventListener('change', calcularPrecoEstimado);
            console.log('Event listener adicionado para data início');
        }
        if (dataFim) {
            dataFim.addEventListener('change', calcularPrecoEstimado);
            console.log('Event listener adicionado para data fim');
        }
        if (tipoServico) {
            tipoServico.addEventListener('change', calcularPrecoEstimado);
            console.log('Event listener adicionado para tipo serviço');
        }

        // Definir data mínima para hoje
        if (dataInicio) {
            const hoje = new Date().toISOString().split('T')[0];
            dataInicio.min = hoje;
            dataInicio.addEventListener('change', function() {
                if (dataFim) dataFim.min = this.value;
                console.log('Data mínima do fim atualizada para:', this.value);
            });
        }

        // Calcular preço inicial se todos os campos já estiverem preenchidos
        setTimeout(() => {
            if (dataInicio && dataFim && tipoServico && 
                dataInicio.value && dataFim.value && tipoServico.value) {
                calcularPrecoEstimado();
            }
        }, 100);

        // Validação e envio do formulário de reserva
        const reservationForm = document.querySelector('#reservation-modal form');
        if (reservationForm) {
            reservationForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar campos obrigatórios
                const campos = {
                    data_inicio: dataInicio.value,
                    data_fim: dataFim.value,
                    tipo_servico: tipoServico.value,
                    local_entrega: reservationForm.querySelector('input[name="local_entrega"]').value
                };
                
                let erros = [];
                
                if (!campos.data_inicio) {
                    erros.push('Data de início é obrigatória');
                }
                if (!campos.data_fim) {
                    erros.push('Data de fim é obrigatória');
                }
                if (!campos.tipo_servico) {
                    erros.push('Tipo de serviço é obrigatório');
                }
                if (!campos.local_entrega.trim()) {
                    erros.push('Local de entrega é obrigatório');
                }
                
                // Validar datas
                if (campos.data_inicio && campos.data_fim) {
                    const inicio = new Date(campos.data_inicio);
                    const fim = new Date(campos.data_fim);
                    const hoje = new Date();
                    hoje.setHours(0, 0, 0, 0);
                    
                    if (inicio < hoje) {
                        erros.push('Data de início não pode ser no passado');
                    }
                    if (fim <= inicio) {
                        erros.push('Data de fim deve ser posterior à data de início');
                    }
                }
                
                // Validar se o serviço selecionado tem preço
                if (campos.tipo_servico) {
                    const carId = document.getElementById('carro_principal_id').value;
                    const car = @json($cars->items()).find(c => c.id === parseInt(carId));
                    
                    if (car && car.price_table) {
                        let precoPorDia = 0;
                        switch(campos.tipo_servico) {
                            case 'dentro_com_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_dentro_com_motorista) || 0;
                                break;
                            case 'dentro_sem_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_dentro_sem_motorista) || 0;
                                break;
                            case 'fora_com_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_fora_com_motorista) || 0;
                                break;
                            case 'fora_sem_motorista':
                                precoPorDia = parseFloat(car.price_table.preco_fora_sem_motorista) || 0;
                                break;
                        }
                        
                        if (precoPorDia <= 0) {
                            erros.push('Serviço selecionado não está disponível');
                        }
                    }
                }
                
                if (erros.length > 0) {
                    showToast('Erros de validação:\n' + erros.join('\n'), 'error');
                    return;
                }
                
                // Se tudo estiver ok, enviar o formulário
                const formData = new FormData(reservationForm);
                
                // Mostrar loading
                const submitBtn = reservationForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Enviando...';
                submitBtn.disabled = true;
                
                fetch(reservationForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Reserva enviada com sucesso! Entraremos em contato em breve.', 'success');
                        closeReservationModal();
                        reservationForm.reset();
                    } else {
                        let mensagem = 'Erro ao enviar reserva';
                        if (data.message) {
                            mensagem += ': ' + data.message;
                        }
                        if (data.errors) {
                            mensagem += '\n\nDetalhes:\n' + Object.values(data.errors).flat().join('\n');
                        }
                        showToast(mensagem, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showToast('Erro ao enviar reserva. Tente novamente.', 'error');
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@push('scripts')
<!-- car-gallery.js já está incluído no layout principal -->
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
</script>
@endpush
@endsection