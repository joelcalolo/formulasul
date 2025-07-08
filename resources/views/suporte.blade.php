@extends('layouts.app')

@section('title', 'Suporte')

@section('content')
<!-- Hero -->
<!-- Hero com formulário -->
<section class="bg-[#D0D0FB] text-[#232323] py-20 px-6 bg-cover bg-center relative" style="background-image: url('https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=1600');">
  <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
  <div class="max-w-6xl mx-auto relative z-10">
    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white">Descubra os Melhores Passeios com a Fórmula Sul</h1>
    <p class="text-lg mb-8 text-white">Escolha seu tipo de passeio, data e número de pessoas para encontrar as opções ideais.</p>

    <!-- Pesquisa -->
    <form onsubmit="event.preventDefault();" class="flex flex-col sm:flex-row items-center justify-center gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-gray-900">
                    <input type="text" placeholder="Pesquise aqui sua dúvida..." class="w-full sm:w-2/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                    <button type="submit" class="bg-[var(--primary)] text-white px-6 py-2 rounded-md hover:bg-[var(--primary)]/90 transition">Pesquisar</button>
    </form>
   
  </div>
</section>


    

<!-- About Us Section -->
<section id="sobre-nos" class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Sobre Nós</h2>
        <p class="mt-6 text-lg text-gray-600 max-w-3xl mx-auto text-center">
            A Formula Sul é uma empresa dedicada a oferecer soluções de mobilidade com qualidade e confiança. Nossa missão é proporcionar aluguel de carros, transfers e passeios turísticos com disponibilidade instantânea, atendendo às necessidades dos nossos clientes com excelência. Valorizamos a segurança, a pontualidade e a satisfação do cliente em cada serviço prestado.
        </p>
    </div>
</section>

<!-- FAQs Section -->
<section id="faq" class="py-12">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Perguntas Frequentes</h2>
        <div class="space-y-4">
            @forelse ($faqs as $faq)
                <div>
                    <button class="accordion-toggle w-full text-left font-semibold text-[var(--primary)] flex justify-between items-center py-3 px-4 bg-white shadow rounded">
                        {{ $faq->question }}
                        <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="accordion-content bg-white px-4 py-2 text-gray-600 rounded shadow">
                        {{ $faq->answer }}
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600">Nenhuma pergunta frequente disponível.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Regulations Section -->
<section id="regulation" class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Regulamento</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            @forelse ($regulations as $regulation)
                <li>{{ $regulation->rule }}</li>
            @empty
                <li>Nenhum regulamento disponível.</li>
            @endforelse
        </ul>
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
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153167!3d-37.81720997975171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0x5045675218ce6e0!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sus!4v1611811234567!5m2!1sen!2sus" 
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

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.accordion-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const isActive = content.classList.contains('active');
            document.querySelectorAll('.accordion-content').forEach(item => {
                item.classList.remove('active');
                item.previousElementSibling.querySelector('svg').classList.remove('rotate-180');
            });
            if (!isActive) {
                content.classList.add('active');
                button.querySelector('svg').classList.add('rotate-180');
            }
        });
    });
</script>
@endsection