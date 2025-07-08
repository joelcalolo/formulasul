@extends('layouts.app')

@section('title', 'Passeios')

@section('content')

<!-- Hero com formulário -->
<section class="bg-[#D0D0FB] text-[#232323] py-20 px-6 bg-cover bg-center relative" style="background-image: url('{{ asset('images/turistas.png') }}');">
  <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
  <div class="max-w-6xl mx-auto relative z-10">
    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white">Descubra os Melhores Passeios com a Fórmula Sul</h1>
    <p class="text-lg mb-8 text-white">Escolha seu tipo de passeio, data e número de pessoas para encontrar as opções ideais.</p>

    <form method="POST" action="{{ route('contact.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg text-gray-900">
      @csrf
      <input type="hidden" name="tipo_contato" value="passeio">
      <div>
        <label class="block text-sm font-medium mb-1">Nome</label>
        <input type="text" name="name" class="w-full border border-gray-300 rounded-lg p-2" value="{{ auth()->user()->name ?? '' }}" required readonly>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg p-2" value="{{ auth()->user()->email ?? '' }}" required readonly>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Destino do Passeio</label>
        <input type="text" name="destino_passeio" class="w-full border border-gray-300 rounded-lg p-2" placeholder="Ex: Praia do Lubito" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Data</label>
        <input type="date" name="data_passeio" class="w-full border border-gray-300 rounded-lg p-2" min="{{ date('Y-m-d') }}" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Pessoas</label>
        <input type="number" name="pessoas" class="w-full border border-gray-300 rounded-lg p-2" min="1" max="20" required>
      </div>
      <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Mensagem</label>
        <textarea name="message" class="w-full border border-gray-300 rounded-lg p-2" rows="2" placeholder="Deixe sua mensagem ou dúvida..."></textarea>
      </div>
      <div class="md:col-span-4 flex items-end">
        <button type="submit" class="w-full bg-[#232323] hover:bg-[#1a1a1a] text-white py-2 px-4 rounded-lg">Enviar Solicitação</button>
      </div>
    </form>
  </div>
</section>

<!-- Passeios Populares -->
<section class="py-16 px-6 bg-[#F4F4F4]">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-8 text-center text-[#232323]">Passeios Populares</h2>
    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
        <img src="https://img.freepik.com/free-photo/praia-dos-tres-irmaos_181624-26756.jpg" class="w-full h-48 object-cover" alt="Praia do Lubito" />
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-2 text-[#232323]">Praia do Lubito</h3>
          <p class="text-sm text-gray-600">Relaxe nas águas quentes e areia branca com vista deslumbrante.</p>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
        <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=60" class="w-full h-48 object-cover" alt="Serra da Leba" />
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-2 text-[#232323]">Serra da Leba</h3>
          <p class="text-sm text-gray-600">Explore curvas e paisagens inesquecíveis na serra mais icônica de Angola.</p>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
        <img src="https://img.freepik.com/free-photo/beautiful-view-tranquil-desert-clear-sky-captured-morocco_181624-8496.jpg" class="w-full h-48 object-cover" alt="Passeio no Deserto" />
        <div class="p-4">
          <h3 class="font-semibold text-lg mb-2 text-[#232323]">Passeio no Deserto</h3>
          <p class="text-sm text-gray-600">Aventure-se em dunas, vistas amplas e silêncio absoluto do deserto do Namibe.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Galeria com depoimento -->
<section class="py-16 px-6 bg-white">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-12 text-center text-[#232323]">Momentos Inesquecíveis</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
      <img src="https://images.pexels.com/photos/21014/pexels-photo.jpg" class="rounded-xl shadow-md" alt="Travel Africa" />
      <img src="https://images.pexels.com/photos/346885/pexels-photo-346885.jpeg" class="rounded-xl shadow-md" alt="Boat Tourism" />
      <img src="https://images.pexels.com/photos/358483/pexels-photo-358483.jpeg" class="rounded-xl shadow-md" alt="Sunset Trip" />
    </div>
    <div class="bg-[#D0D0FB] p-6 rounded-xl shadow-md text-center">
      <p class="italic text-lg text-[#232323] mb-4">"A Fórmula Sul tornou a nossa viagem ao Namibe uma experiência mágica! Tudo foi perfeito: do atendimento ao passeio."</p>
      <p class="font-semibold text-[#232323]">— Ana Paula, Cliente Satisfeita</p>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="py-16 px-6 bg-[#F4F4F4]">
  <div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold mb-10 text-center text-[#232323]">Perguntas Frequentes</h2>
    <div class="space-y-6">
      <div>
        <button onclick="toggleFAQ(0)" class="w-full text-left font-medium text-lg bg-white px-4 py-3 rounded-md shadow">
          Quais são os horários disponíveis para os passeios?
        </button>
        <div class="faq-answer px-4 pt-2 text-gray-700">
          Os passeios geralmente começam às 8h e retornam até as 18h, mas oferecemos opções personalizadas mediante reserva.
        </div>
      </div>
      <div>
        <button onclick="toggleFAQ(1)" class="w-full text-left font-medium text-lg bg-white px-4 py-3 rounded-md shadow">
          Como posso fazer uma reserva?
        </button>
        <div class="faq-answer px-4 pt-2 text-gray-700">
          Você pode reservar diretamente no site, via WhatsApp ou entrando em contato com nossa equipe de atendimento.
        </div>
      </div>
      <div>
        <button onclick="toggleFAQ(2)" class="w-full text-left font-medium text-lg bg-white px-4 py-3 rounded-md shadow">
          É possível cancelar ou remarcar um passeio?
        </button>
        <div class="faq-answer px-4 pt-2 text-gray-700">
          Sim. Cancelamentos até 48h antes têm reembolso total. Remarcações são gratuitas com até 24h de antecedência.
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
