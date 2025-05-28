@extends('layouts.app')

@section('title', 'Início')

@section('content')

<!-- Hero principal da página inicial -->
<section class="relative bg-cover bg-center text-white py-24 px-6" style="background-image: url('https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=1600');">
  <div class="absolute inset-0 bg-black/50"></div>

  <div class="relative max-w-7xl mx-auto z-10">
    <div class="text-center max-w-3xl mx-auto">
      <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">Mobilidade Inteligente com a Fórmula Sul</h1>
      <p class="mt-4 text-lg md:text-xl text-white/90">
        Alugue um carro, agende seu transfer ou descubra passeios inesquecíveis. Tudo em um só lugar.
      </p>
      <a href="#servicos" class="mt-6 inline-block bg-[var(--secondary)] text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-[var(--secondary)]/90 transition">
        Conheça Nossos Serviços
      </a>
    </div>

    <!-- Formulário hero simplificado -->
    <form class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-6 rounded-xl shadow-xl max-w-4xl mx-auto text-gray-900">
      <div>
        <label class="block text-sm font-medium mb-1">Serviço</label>
        <select class="w-full border border-gray-300 rounded-lg p-2">
          <option value="">Selecione</option>
          <option value="aluguel">Aluguel de Carro</option>
          <option value="transfer">Transfer</option>
          <option value="passeio">Passeio</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Data</label>
        <input type="date" class="w-full border border-gray-300 rounded-lg p-2" />
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg">
          Pesquisar
        </button>
      </div>
    </form>
  </div>
</section>


  <!-- Frota -->
<section id="frota" class="py-16 bg-gray-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-gray-900">Conheça a Nossa Frota</h2>
        <p class="mt-4 text-lg text-gray-600 text-center max-w-2xl mx-auto">
            Explore nossa frota diversificada e encontre o veículo ideal para sua viagem.
        </p>

        <div class="relative mt-10">
            <!-- Botão de scroll à esquerda -->
            <button onclick="scrollFleet(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white p-2 shadow-md rounded-full">
                &#8592;
            </button>

            <!-- Lista horizontal de veículos -->
            <div id="fleetContainer" class="flex gap-6 overflow-x-auto scroll-smooth px-10 py-4">
                @foreach($cars as $car)
                    <div class="min-w-[300px] bg-white rounded-lg shadow-sm overflow-hidden shrink-0">
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}"
                                alt="{{ $car->modelo }}" class="w-full h-48 object-cover" />
                        @else
                            <img src="https://via.placeholder.com/300x200?text=Sem+Imagem"
                                alt="Sem Imagem" class="w-full h-48 object-cover" />
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $car->modelo }}</h3>
                            <p class="mt-2 text-gray-600">Categoria: {{ ucfirst($car->categoria) }}</p>
                            <p class="mt-2 text-[var(--primary)] font-bold">A partir de Akz {{ number_format($car->price, 2, ',', '.') }}/dia</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botão de scroll à direita -->
            <button onclick="scrollFleet(1)" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white p-2 shadow-md rounded-full">
                &#8594;
            </button>
        </div>
    </div>
</section>


<!-- Nossos Serviços -->
<section id="servicos" class="py-20 bg-[var(--background)]">
  <div class="container mx-auto px-6 max-w-7xl">
    <h2 class="text-4xl font-bold text-center text-gray-900 mb-4">Nossos Serviços</h2>
    <p class="text-lg text-gray-600 text-center max-w-2xl mx-auto mb-16">
      Soluções de mobilidade para todas as suas necessidades.
    </p>

    <!-- Serviço 1 -->
    <div class="flex flex-col md:flex-row items-center mb-16 gap-8">
      <div class="md:w-1/2">
        <img src="https://images.pexels.com/photos/210182/pexels-photo-210182.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Aluguel de Carros" class="rounded-2xl shadow-lg w-full object-cover h-64 md:h-96">
      </div>
      <div class="md:w-1/2">
        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Aluguel de Carros</h3>
        <p class="text-gray-600 mb-6">
          Nossa frota moderna e diversificada atende desde necessidades pessoais até demandas empresariais. Alugue o carro ideal com conforto e agilidade.
        </p>
        <a href="#frota" class="inline-block bg-[var(--secondary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--secondary)]/90 transition">Ver Frota</a>
      </div>
    </div>

    <!-- Serviço 2 -->
    <div class="flex flex-col md:flex-row-reverse items-center mb-16 gap-8">
      <div class="md:w-1/2">
        <img src="https://images.pexels.com/photos/3860253/pexels-photo-3860253.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Transfer Aeroporto" class="rounded-2xl shadow-lg w-full object-cover h-64 md:h-96">
      </div>
      <div class="md:w-1/2">
        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Transfer no Aeroporto</h3>
        <p class="text-gray-600 mb-6">
          Ao chegar ao aeroporto, você já tem transporte garantido com conforto e pontualidade. Ideal para turistas, executivos e famílias.
        </p>
        <a href="#me-pegue" class="inline-block bg-[var(--secondary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--secondary)]/90 transition">Solicitar</a>
      </div>
    </div>

    <!-- Serviço 3 -->
    <div class="flex flex-col md:flex-row items-center gap-8">
      <div class="md:w-1/2">
        <img src="https://images.pexels.com/photos/3184394/pexels-photo-3184394.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Pacotes Corporativos" class="rounded-2xl shadow-lg w-full object-cover h-64 md:h-96">
      </div>
      <div class="md:w-1/2">
        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Passeios turisticos</h3>
        <p class="text-gray-600 mb-6">
          Oferecemos planos personalizados para empresas que buscam mobilidade eficiente e soluções em transporte para colaboradores e executivos.
        </p>
        <a href="#contacto" class="inline-block bg-[var(--secondary)] text-white px-6 py-3 rounded-lg shadow-md hover:bg-[var(--secondary)]/90 transition">Contato</a>
      </div>
    </div>
  </div>
</section>



     <!-- Passeios Populares -->
 <section class="py-16 px-6 bg-gray-100">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-3xl font-bold mb-8 text-center">Passeios Populares</h2>
      <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
          <img src="https://img.freepik.com/free-photo/praia-dos-tres-irmaos_181624-26756.jpg?t=st=1747035615~exp=1747039215~hmac=ab489390d6ed15f5de2ce9898cb14019b0be88db6abb7536b33e77a69ff7ff71&w=1060" class="w-full h-48 object-cover" alt="Deserto do Namibe" />
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-2">Praia do Lubito</h3>
            <p class="text-sm text-gray-600">Relaxe nas águas quentes e areia branca com vista deslumbrante.</p>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
          <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=60" class="w-full h-48 object-cover" alt="Deserto do Namibe" />
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-2">Serra da Leba</h3>
            <p class="text-sm text-gray-600">Explore curvas e paisagens inesquecíveis na serra mais icônica de Angola.</p>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
          <img src="https://img.freepik.com/free-photo/beautiful-view-tranquil-desert-clear-sky-captured-morocco_181624-8496.jpg?t=st=1747035673~exp=1747039273~hmac=cb7097f46224d76ce92953dab36291f48af98722b2bed3f92018c2b77fc7a467&w=1380" class="w-full h-48 object-cover" alt="Deserto do Namibe" />
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-2">Passeio no Deserto</h3>
            <p class="text-sm text-gray-600">Aventure-se em dunas, vistas amplas e silêncio absoluto do deserto do Namibe.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

<!-- Contacto -->
<section class="py-12">
        <div id="contacto" class="container mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Nossa Localização & Contato</h2>
          <p class="mt-4 text-lg text-gray-600 text-center max-w-2xl mx-auto">Visite-nos ou entre em contato para mais informações.</p>
      
          <div class="mt-10 flex flex-col lg:flex-row gap-8">
            <!-- Mapa -->
            <div class="w-full lg:w-1/2">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153167!3d-37.81720997975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0x5045675218ce6e0!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sus!4v1611811234567!5m2!1sen!2sus" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
              </iframe>
            </div>
      
            <!-- Formulário -->
            <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow-md">
              <form>
                <h3 class="text-2xl font-semibold mb-4 text-gray-800">Entre em Contato</h3>
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="contact-name">Nome</label>
                  <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="contact-name" type="text" placeholder="Seu Nome">
                </div>
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="contact-email">E-mail</label>
                  <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="contact-email" type="email" placeholder="Seu E-mail">
                </div>
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="contact-message">Mensagem</label>
                  <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="contact-message" rows="5" placeholder="Sua Mensagem"></textarea>
                </div>
                <button class="bg-[var(--primary)] text-white px-6 py-3 rounded-full font-semibold hover:bg-[var(--primary)]/90 w-full" type="submit">Enviar Mensagem</button>
              </form>
            </div>
          </div>
        </div>
      </section>

<!-- Sobre Nós -->
<section id="sobre" class="py-20 bg-[var(--background)]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-gray-900">Sobre a Fórmula Sul</h2>
        <p class="mt-4 text-lg text-gray-600 text-center max-w-2xl mx-auto">
            Soluções de mobilidade com qualidade, confiabilidade e preços acessíveis.
        </p>

        <!-- Bloco 1 -->
        <div class="mt-16 grid md:grid-cols-2 gap-12 items-center">
            <div class="md:order-1 order-2">
                <h3 class="text-2xl font-semibold text-gray-900">Qualidade Garantida</h3>
                <p class="mt-4 text-gray-600">
                    Nossa frota é composta por veículos modernos e bem mantidos para garantir conforto, segurança e desempenho em cada viagem.
                </p>
            </div>
            <div class="md:order-2 order-1">
                <img src="https://images.pexels.com/photos/248539/pexels-photo-248539.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Qualidade Garantida" class="rounded-2xl shadow-lg w-full object-cover h-64 md:h-96">
            </div>
        </div>

        <!-- Bloco 2 -->
        <div class="mt-16 grid md:grid-cols-2 gap-12 items-center">
            <div class="md:order-1">
                <img src="https://images.pexels.com/photos/3727469/pexels-photo-3727469.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Atendimento 24/7" class="rounded-2xl shadow-lg w-full object-cover h-64 md:h-96">
            </div>
            <div class="md:order-2">
                <h3 class="text-2xl font-semibold text-gray-900">Atendimento 24/7</h3>
                <p class="mt-4 text-gray-600">
                    Conte com uma equipa sempre disponível, pronta para ajudar a qualquer hora do dia ou da noite. Atendimento rápido e eficaz.
                </p>
            </div>
        </div>

        <!-- Bloco 3 -->
        <div class="mt-16 grid md:grid-cols-2 gap-12 items-center">
            <div class="md:order-1 order-2">
                <h3 class="text-2xl font-semibold text-gray-900">Frota Diversificada</h3>
                <p class="mt-4 text-gray-600">
                    Desde compactos econômicos até SUVs premium – temos o veículo ideal para cada cliente e ocasião.
                </p>
            </div>
            <div class="md:order-2 order-1">
                <img src="https://images.pexels.com/photos/70912/pexels-photo-70912.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Frota Diversificada" class="rounded-2xl shadow-lg w-full object-cover h-64 md:h-96">
            </div>
        </div>
    </div>
</section>




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
</script>
@endsection