@extends('layouts.app')

@section('title', 'Suporte')

@section('content')
<!-- Hero -->
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center">Formula Sul Suporte</h1>
        <div class="bg-white py-8 px-4 shadow-md mt-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Está com alguma dúvida?</h2>
                <form onsubmit="event.preventDefault();" class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <input type="text" placeholder="Pesquise aqui sua dúvida..." class="w-full sm:w-2/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary)]">
                    <button type="submit" class="bg-[var(--primary)] text-white px-6 py-2 rounded-md hover:bg-[var(--primary)]/90 transition">Pesquisar</button>
                </form>
            </div>
        </div>
    </div>
</div>

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

<!-- Contact Section -->
<section id="contacto" class="py-12">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Fale Conosco</h2>
        <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <input type="text" name="name" placeholder="Seu nome" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <input type="email" name="email" placeholder="Seu email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <textarea name="message" placeholder="Sua mensagem..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" rows="5" required></textarea>
                    @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="bg-[var(--primary)] text-white px-6 py-2 rounded-md hover:bg-[var(--primary)]/90">Enviar Mensagem</button>
        </form>
        <div class="mt-6 text-center text-gray-600">
            <p>Ou entre em contato diretamente:</p>
            <p>Email: <a href="mailto:contato@formulasul.com" class="text-[var(--primary)]">contato@formulasul.com</a></p>
            <p>Telefone: (XX) XXXX-XXXX</p>
            <p>WhatsApp: <a href="https://wa.me/1234567890" target="_blank" class="text-[var(--primary)]">(XX) XXXX-XXXX</a></p>
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