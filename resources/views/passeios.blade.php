@extends('layouts.app')

@section('title', 'Passeios')

@section('content')

 <!-- Hero com formulário -->
 <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20 px-6" style="background-image: url('https://images.pexels.com/photos/247599/pexels-photo-247599.jpeg?auto=compress&cs=tinysrgb&w=1260'); background-size: cover;">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-4xl md:text-5xl font-bold mb-6">Descubra os Melhores Passeios com a Fórmula Sul</h1>
      <p class="text-lg mb-8">Escolha seu tipo de passeio, data e número de pessoas para encontrar as opções ideais.</p>

      <form class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-xl shadow-lg text-gray-900">
        <div>
          <label class="block text-sm font-medium mb-1">Tipo de Passeio</label>
          <select class="w-full border border-gray-300 rounded-lg p-2">
            <option value="">Todos</option>
            <option value="praia">Praia</option>
            <option value="historia">História</option>
            <option value="aventura">Aventura</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Data</label>
          <input type="date" class="w-full border border-gray-300 rounded-lg p-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Pessoas</label>
          <input type="number" class="w-full border border-gray-300 rounded-lg p-2" min="1" />
        </div>
        <div class="flex items-end">
          <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg">Pesquisar</button>
        </div>
      </form>
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

  <!-- Galeria com depoimento -->
  <section class="py-16 px-6 bg-white">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-3xl font-bold mb-12 text-center">Momentos Inesquecíveis</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <img src="https://images.pexels.com/photos/21014/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=800" class="rounded-xl shadow-md" alt="Travel Africa" />
        <img src="https://images.pexels.com/photos/346885/pexels-photo-346885.jpeg?auto=compress&cs=tinysrgb&w=800" class="rounded-xl shadow-md" alt="Boat Tourism" />
        <img src="https://images.pexels.com/photos/358483/pexels-photo-358483.jpeg?auto=compress&cs=tinysrgb&w=800" class="rounded-xl shadow-md" alt="Sunset Trip" />
      </div>

      <div class="bg-indigo-50 p-6 rounded-xl shadow-md text-center">
        <p class="italic text-lg text-indigo-800 mb-4">"A Fórmula Sul tornou a nossa viagem ao Namibe uma experiência mágica! Tudo foi perfeito: do atendimento ao passeio." </p>
        <p class="font-semibold text-indigo-900">— Ana Paula, Cliente Satisfeita</p>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="py-16 px-6 bg-gray-100">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-3xl font-bold mb-10 text-center">Perguntas Frequentes</h2>
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