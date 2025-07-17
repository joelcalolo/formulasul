@extends('layouts.app')

@section('title', 'Início')

@section('content')

    <!-- Hero Section -->
    <section class="bg-slate-900 py-16 px-6 bg-cover bg-center relative" style="background-image: url('{{ asset('images/frota.png') }}');">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <!-- Imagem do Hero -->
            <!-- Coluna 2: Formulário -->
                <!-- Formulário Rápido no Hero -->
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6 mt-8 w-full lg:min-w-[520px]">
                <!-- Abas -->
            <div class="flex justify-center mb-6 space-x-2 lg:space-x-4">
            <button type="button" class="tab-btn px-3 py-2 lg:px-4 lg:py-2 rounded font-semibold bg-[var(--primary)] text-white text-sm lg:text-base" onclick="showTab('aluguel', event)">Aluguel</button>
            <button type="button" class="tab-btn px-3 py-2 lg:px-4 lg:py-2 rounded font-semibold bg-gray-200 text-gray-700 text-sm lg:text-base" onclick="showTab('transfer', event)">Transfer</button>
        </div>

        <!-- Formulário Aluguel -->
        <form id="form-aluguel" class="tab-form" method="POST" action="{{ route('rental-requests.store') }}" novalidate>
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Carro</label>
                <select name="carro_principal_id" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" required>
                    <option value="">Selecione um carro</option>
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}">{{ $car->marca }} {{ $car->modelo }}</option>
                    @endforeach
                </select>
                <div class="error-message hidden text-red-500 text-xs mt-1"></div>
            </div>
            <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                    <input type="date" name="data_inicio" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                    <input type="date" name="data_fim" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Local de Entrega</label>
                <input type="text" name="local_entrega" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" placeholder="Digite o local de entrega" required>
                <div class="error-message hidden text-red-500 text-xs mt-1"></div>
            </div>
            <button type="submit" id="btn-solicitar-aluguel" class="w-full bg-[var(--primary)] text-white py-2 px-3 text-base rounded-md sm:py-3 sm:px-6 sm:text-lg sm:rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-[var(--primary)] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                <span class="submit-text" style="display: inline;">Solicitar Aluguel</span>
                <span class="loading-text" style="display: none; align-items: center;">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                    Processando...
                </span>
            </button>
            <div class="flex items-center justify-center gap-2 mt-3">
                <img src="{{ asset('images/europcar-logo.png') }}" alt="Europcar" class="h-5" style="height: 20px; width: auto;"/>
            </div>
        </form>

        <!-- Formulário Transfer -->
        <form id="form-transfer" class="tab-form hidden" method="POST" action="{{ route('transfers.store') }}" novalidate>
            @csrf
            <input type="hidden" name="tipo" value="transfer">
            
            <!-- Primeira linha: Origem e Destino -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Origem</label>
                    <input type="text" name="origem" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" value="Aeroporto do Lubango" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                    <input type="text" name="destino" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" placeholder="Ex: Hotel em Lubango" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
            </div>

            <!-- Segunda linha: Data/Hora e Número do Voo -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data e Hora de Chegada</label>
                    <input type="datetime-local" name="data_hora" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número do Voo</label>
                    <input type="text" name="flight_number" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" placeholder="Ex: TAAG 123" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
            </div>

            <!-- Terceira linha: Número de Pessoas e Observações -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Pessoas</label>
                    <input type="number" name="num_pessoas" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" min="1" max="20" placeholder="Ex: 4" required>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações (opcional)</label>
                    <textarea name="observacoes" class="form-input w-full border border-gray-300 rounded-md px-2 py-2 text-base sm:rounded-lg sm:px-3 sm:py-2 sm:text-lg focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-colors" rows="1" placeholder="Informações adicionais..."></textarea>
                    <div class="error-message hidden text-red-500 text-xs mt-1"></div>
                </div>
            </div>

            <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition-all duration-200 hover:scale-105 focus:ring-2 focus:ring-[var(--primary)] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                <span class="submit-text" style="display: inline;">Solicitar Transfer</span>
                <span class="loading-text" style="display: none; align-items: center;">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                    Processando...
                </span>
            </button>
            <div class="flex items-center justify-center gap-2 mt-3">
                <img src="{{ asset('images/europcar-logo.png') }}" alt="Europcar" class="h-5" style="height: 20px; width: auto;"/>
            </div>
        </form>

            </div>

            <!-- Texto do Hero -->
            <div>
                <span class="bg-[var(--primary)]/10 text-[var(--primary)] text-xs font-bold uppercase px-2 py-1 rounded flex items-center gap-2">
                    <img src="{{ asset('images/europcar-logo.png') }}" alt="Europcar" class="h-7" style="height: 28px; width: auto;"/>
                    Mobilidade Premium
                </span>

                <h1 class="mt-4 text-4xl font-extrabold leading-tight text-white">
                    Sua jornada começa aqui com a melhor experiência em aluguel de veículos
                </h1>

                <p class="mt-4 text-lg text-gray-200">
                    Oferecemos uma frota diversificada de veículos para atender todas as suas necessidades de mobilidade. Desde carros econômicos até SUVs de luxo, temos a solução perfeita para você.
                </p>

                <div class="mt-8 flex gap-4">
                    <a href="{{ route('cars.index') }}" class="px-6 py-3 bg-[var(--primary)] text-white rounded hover:bg-[var(--primary)]/90 transition">
                        Alugar Agora
                    </a>
                    <a href="#frota" class="px-6 py-3 border border-[var(--primary)] text-[var(--primary)] rounded hover:bg-[var(--primary)]/10 transition">
                        Ver Frota
                    </a>
                </div>
            </div>
        </div>
    </section>




        <!-- Seção de Serviços com Imagens -->
        <section class="bg-gray-50 py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto px-4 grid md:grid-cols-3 gap-8">
            <!-- Coluna 1 -->
            <div class="flex flex-col items-start">
                <img src="{{ asset('images/mostrando.png') }}" alt="Aluguel de Carros" class="mb-6 rounded-lg shadow-md w-full h-64 object-cover">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aluguel de Carros</h3>
                <p class="text-gray-600 text-base">
                    Oferecemos uma frota moderna e diversificada para atender todas as suas necessidades de mobilidade, desde carros econômicos até SUVs de luxo.
                </p>
            </div>

            <!-- Coluna 2 -->
            <div class="flex flex-col items-start">
                <img src="{{ asset('images/transfer.png') }}" alt="Transfer Aeroporto" class="mb-6 rounded-lg shadow-md w-full h-64 object-cover">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Transfer no Aeroporto</h3>
                <p class="text-gray-600 text-base">
                    Serviço de transfer exclusivo do aeroporto para seu destino, com conforto, pontualidade e segurança garantidos.
                </p>
            </div>

            <!-- Coluna 3-->
            <div class="flex flex-col items-start">
                <img src="{{ asset('images/passeios.png') }}" alt="Passeios" class="mb-6 rounded-lg shadow-md w-full h-64 object-cover">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Passeios Turisticos</h3>
                <p class="text-gray-600 text-base">
                    Serviço de transfer exclusivo do aeroporto para seu destino, com conforto, pontualidade e segurança garantidos.
                </p>
            </div>
        </div>
    </section>


    <!-- Seção de Carros em Destaque -->
    <section class="bg-white-50 py-16 w-full">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-[var(--secondary)]">Carros em Destaque</h2>
                <a href="{{ route('cars.index') }}" class="text-[var(--primary)] hover:text-[var(--primary)]/80 font-medium flex items-center gap-2">
                    Ver todos os carros
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <!-- Cards com Scroll Horizontal -->
            <div class="relative">
                <div class="overflow-x-auto pb-4 hide-scrollbar">
                    <div class="flex gap-6 min-w-max">
                @foreach($cars as $car)
                            <div class="w-80 rounded-2xl shadow-[var(--shadow)] bg-white hover:shadow-lg transition-all duration-300">
                                <div class="relative">
                        @if($car->image_cover)
                            <img src="{{ asset('storage/' . $car->image_cover) }}"
                                                alt="{{ $car->modelo }}" class="rounded-t-2xl h-56 w-full object-cover cursor-pointer hover:scale-105 transition-transform duration-300" 
                                                onclick="openCarDetails({{ $car->id }})" />
                        @else
                            <img src="https://via.placeholder.com/300x200?text=Sem+Imagem"
                                                alt="Sem Imagem" class="rounded-t-2xl h-56 w-full object-cover" />
                        @endif
                                    <div class="absolute top-4 right-4 bg-[var(--primary)] text-[var(--secondary)] text-sm font-semibold rounded-full px-4 py-1">
                                        {{ strtoupper($car->status) }}
                                    </div>
                                    @if($car->hasGallery())
                                        <div class="absolute bottom-4 right-4 bg-black/50 text-white text-xs rounded-full px-2 py-1">
                                            <i class="fas fa-images mr-1"></i>{{ $car->image_count }} fotos
                                        </div>
                                    @endif
                                    <!-- Botão de visualizar galeria -->
                                    @if($car->hasGallery())
                                        <div class="absolute inset-0 bg-black/0 hover:bg-black/20 transition-colors duration-300 rounded-t-2xl flex items-center justify-center opacity-0 hover:opacity-100">
                                            <button onclick="openCarGallery({{ $car->id }})" class="bg-white/90 text-gray-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                                                <i class="fas fa-images"></i>
                                                Ver Galeria
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h2 class="text-xl font-bold mb-2 text-[var(--secondary)]">{{ $car->modelo }}</h2>
                                    <p class="text-gray-600 mb-4">{{ ucfirst($car->status) }}</p>
                                    <div class="flex items-center mb-4">
                                        <span class="text-[var(--primary)] font-semibold mr-2">4.8</span>
                                        <div class="flex text-yellow-400">★★★★★</div>
                                    </div>
                                    <div class="text-2xl font-semibold mb-4 text-[var(--secondary)]">Akz {{ number_format($car->price, 2, ',', '.') }}/dia</div>
                                    <div class="flex justify-between text-gray-600 text-sm mb-4">
                                        <div class="flex items-center gap-1">🚗 {{ $car->transmissao ?? 'Automático' }}</div>
                                        <div class="flex items-center gap-1">⛽ {{ $car->combustivel ?? 'Gasolina' }}</div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="openCarDetails({{ $car->id }})" class="flex-1 bg-[var(--primary)] text-white py-2 rounded-lg font-medium hover:bg-[var(--primary)]/90 transition-colors">
                                            Ver Detalhes
                                        </button>
                                        @if($car->hasGallery())
                                            <button onclick="openCarGallery({{ $car->id }})" class="px-3 py-2 border border-[var(--primary)] text-[var(--primary)] rounded-lg hover:bg-[var(--primary)]/10 transition-colors">
                                                <i class="fas fa-images"></i>
                                            </button>
                                        @endif
                                    </div>
                        </div>
                    </div>
                @endforeach
            </div>
                </div>
            </div>
    </div>
</section>

    <style>
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;  /* Chrome, Safari and Opera */
        }
    </style>




        <!-- Sobre nos -->
    <section class="bg-white py-16 "> 
        <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Imagem do Hero -->
            <div>
                <img src="{{ asset('images/jornada.png') }}" alt="Fórmula Sul - Aluguel de Carros" class="w-full rounded-lg shadow-md">
            </div>

            <!-- Texto do Hero -->
            <div>
                <span class="bg-[var(--primary)]/10 text-[var(--primary)] text-xs font-bold uppercase px-2 py-1 rounded">Mobilidade Premium</span>

                <h1 class="mt-4 text-4xl font-extrabold leading-tight text-gray-900">
                    Sua jornada começa aqui com a melhor experiência em aluguel de veículos
                </h1>

                <p class="mt-4 text-lg text-gray-600">
                    Oferecemos uma frota diversificada de veículos para atender todas as suas necessidades de mobilidade. Desde carros econômicos até SUVs de luxo, temos a solução perfeita para você.
                </p>

                <div class="mt-6 text-sm text-gray-500">
                    Por <span class="font-semibold text-gray-700">Fórmula Sul</span> · Desde 2020
                </div>
          </div>
        </div>
    </section>

<!-- Passeios Populares -->
<section class="py-20 px-6 bg-gradient-to-b from-white to-gray-100">
  <div class="max-w-7xl mx-auto text-center">
    <h2 class="text-4xl font-extrabold text-gray-800 mb-12">Passeios Populares</h2>

    <div class="grid gap-10 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      <!-- Card 1 -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
        <img src="https://img.freepik.com/free-photo/praia-dos-tres-irmaos_181624-26756.jpg?t=st=1747035615~exp=1747039215~hmac=ab489390d6ed15f5de2ce9898cb14019b0be88db6abb7536b33e77a69ff7ff71&w=1060" class="w-full h-56 object-cover" alt="Praia do Lubito" />
        <div class="p-6 text-left">
          <h3 class="text-xl font-bold mb-2 text-gray-800">Praia do Lubito</h3>
          <p class="text-gray-600 text-sm mb-4">Relaxe nas águas quentes e areia branca com vista deslumbrante.</p>
          <a href="#" class="inline-block text-[var(--primary)] hover:underline font-medium">Ver detalhes →</a>
          </div>
        </div>

      <!-- Card 2 -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=60" class="w-full h-56 object-cover" alt="Serra da Leba" />
        <div class="p-6 text-left">
          <h3 class="text-xl font-bold mb-2 text-gray-800">Serra da Leba</h3>
          <p class="text-gray-600 text-sm mb-4">Explore curvas e paisagens inesquecíveis na serra mais icônica de Angola.</p>
          <a href="#" class="inline-block text-[var(--primary)] hover:underline font-medium">Ver detalhes →</a>
          </div>
        </div>

      <!-- Card 3 -->
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
        <img src="https://img.freepik.com/free-photo/beautiful-view-tranquil-desert-clear-sky-captured-morocco_181624-8496.jpg?t=st=1747035673~exp=1747039273~hmac=cb7097f46224d76ce92953dab36291f48af98722b2bed3f92018c2b77fc7a467&w=1380" class="w-full h-56 object-cover" alt="Deserto do Namibe" />
        <div class="p-6 text-left">
          <h3 class="text-xl font-bold mb-2 text-gray-800">Passeio no Deserto</h3>
          <p class="text-gray-600 text-sm mb-4">Aventure-se em dunas, vistas amplas e silêncio absoluto do deserto do Namibe.</p>
          <a href="#" class="inline-block text-[var(--primary)] hover:underline font-medium">Ver detalhes →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Contacto -->
<section class="py-20 bg-gray-100" id="contacto">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-gray-800 text-center mb-4">Nossa Localização & Contato</h2>
    <p class="text-lg text-gray-600 text-center max-w-2xl mx-auto mb-12">Visite-nos ou entre em contato para mais informações.</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Mapa -->
      <div class="rounded-2xl overflow-hidden shadow-lg">
              <iframe 
                src="https://www.google.com/maps?q=3FHR+FG5+Lubango&output=embed"
                referrerpolicy="no-referrer-when-downgrade" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
          loading="lazy"
          class="w-full h-full">
              </iframe>
            </div>
      
            <!-- Formulário -->
      <div class="bg-white rounded-2xl shadow-lg p-8">
              <form>
          <h3 class="text-2xl font-bold mb-6 text-gray-800">Entre em Contato</h3>

          <div class="mb-5">
            <label for="contact-name" class="block text-gray-700 font-medium mb-2">Nome</label>
            <input type="text" id="contact-name" placeholder="Seu Nome"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--primary)] focus:outline-none text-gray-800" />
          </div>

          <div class="mb-5">
            <label for="contact-email" class="block text-gray-700 font-medium mb-2">E-mail</label>
            <input type="email" id="contact-email" placeholder="Seu E-mail"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--primary)] focus:outline-none text-gray-800" />
        </div>

          <div class="mb-6">
            <label for="contact-message" class="block text-gray-700 font-medium mb-2">Mensagem</label>
            <textarea id="contact-message" rows="5" placeholder="Sua Mensagem"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--primary)] focus:outline-none text-gray-800"></textarea>
        </div>

          <button type="submit"
            class="w-full bg-[var(--primary)] text-white py-3 px-6 rounded-full font-semibold hover:bg-[var(--primary)]/90 transition-all">
            Enviar Mensagem
          </button>
        </form>
            </div>
        </div>
    </div>
</section>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
    
    function scrollFleet(direction) {
        const container = document.getElementById('fleetContainer');
        const scrollAmount = 320; // Ajuste conforme o tamanho do card
        container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }

    // Função showTab agora está no arquivo form-validation.js
    // Esta função é mantida apenas para compatibilidade
    function showTab(tab, event) {
        // Esconder todos os formulários
        document.querySelectorAll('.tab-form').forEach(f => f.classList.add('hidden'));
        
        // Mostrar o formulário selecionado
        document.getElementById('form-' + tab).classList.remove('hidden');
        
        // Atualizar botões das abas
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-[var(--primary)]', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700');
        });
        
        // Ativar o botão clicado
        event.target.classList.add('bg-[var(--primary)]', 'text-white');
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
    }

    // Melhorar responsividade do formulário
    function adjustFormLayout() {
        const formContainer = document.querySelector('.max-w-2xl');
        if (window.innerWidth < 768) {
            formContainer.classList.remove('min-w-[520px]');
            formContainer.classList.add('w-full', 'px-4');
        } else {
            formContainer.classList.add('min-w-[520px]');
            formContainer.classList.remove('w-full', 'px-4');
        }
    }

    // Executar no carregamento e no redimensionamento
    document.addEventListener('DOMContentLoaded', function() {
        adjustFormLayout();
        window.addEventListener('resize', adjustFormLayout);
    });

    function openCarDetails(carId) {
        window.location.href = `/cars/${carId}`;
    }

    function openCarGallery(carId) {
        // Mostrar modal
        document.getElementById('carGalleryModal').classList.remove('hidden');
        
        // Carregar galeria via AJAX
        fetch(`/cars/${carId}/gallery`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('carGalleryContent').innerHTML = html;
                // Inicializar galeria
                if (window.carGallery) {
                    window.carGallery.initCarousels();
                    window.carGallery.initThumbnails();
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

    document.addEventListener('DOMContentLoaded', function() {
        // Definir data mínima para hoje no formulário de aluguel rápido
        const dataInicio = document.querySelector('#form-aluguel input[name="data_inicio"]');
        const dataFim = document.querySelector('#form-aluguel input[name="data_fim"]');
        if (dataInicio) {
            const hoje = new Date().toISOString().split('T')[0];
            dataInicio.min = hoje;
            dataInicio.addEventListener('change', function() {
                if (dataFim) dataFim.min = this.value;
            });
        }
    });
</script>

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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Para ambos os formulários com abas
    var forms = document.querySelectorAll('.tab-form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var btn = form.querySelector('button[type="submit"]');
            var submitText = btn.querySelector('.submit-text');
            var loadingText = btn.querySelector('.loading-text');
            if (btn && submitText && loadingText) {
                submitText.style.display = 'none';
                loadingText.style.display = 'flex';
                btn.disabled = true;
            }
        });
    });

    // Função para verificar se o usuário está autenticado (Laravel injeta variável auth)
    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

    function handleProtectedForm(e) {
        if (!isAuthenticated) {
            e.preventDefault();
            if (typeof openModal === 'function') openModal();
            // Garante que o toast-container está no body
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer || toastContainer.parentElement !== document.body) {
                if (toastContainer) toastContainer.remove();
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'fixed top-4 right-4 z-[9999] space-y-2';
                document.body.appendChild(toastContainer);
            }
            if (window.showError) {
                window.showError('Atenção', 'Você precisa estar logado para fazer uma solicitação. Por favor, crie uma conta ou faça login se já possui uma.');
            } else if (window.showToast) {
                window.showToast('Você precisa estar logado para fazer uma solicitação. Por favor, crie uma conta ou faça login se já possui uma.', 'error');
            }
            return false;
        }
        return true;
    }

    const formAluguel = document.getElementById('form-aluguel');
    if (formAluguel) {
        formAluguel.addEventListener('submit', handleProtectedForm);
    }
    const formTransfer = document.getElementById('form-transfer');
    if (formTransfer) {
        formTransfer.addEventListener('submit', handleProtectedForm);
    }
});
</script>
@endpush