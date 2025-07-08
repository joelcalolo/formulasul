@extends('layouts.app')

@section('title', 'Nossos Serviços')

@section('content')
<!-- Hero Section -->
<section class="bg-slate-900 py-16 px-6 bg-cover bg-center relative" style="background-image: url('https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg?auto=compress&cs=tinysrgb&w=1600');">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Nossos Serviços</h1>
            <p class="text-lg text-gray-200 max-w-2xl mx-auto">
                Oferecemos soluções completas em mobilidade para atender todas as suas necessidades, desde transfers até passeios personalizados.
            </p>
        </div>
    </div>
</section>

<!-- Aluguel de Viaturas -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <img src="https://images.pexels.com/photos/3802510/pexels-photo-3802510.jpeg" alt="Aluguel de Carros" class="rounded-lg shadow-lg w-full h-[400px] object-cover">
            </div>
            <div>
                <h2 class="text-3xl font-bold text-[var(--secondary)] mb-4">Aluguel de Viaturas</h2>
                <p class="text-gray-600 mb-6">
                    Oferecemos uma frota diversificada de veículos para atender todas as suas necessidades de mobilidade. Desde carros econômicos até SUVs de luxo, temos a solução perfeita para você.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Frota moderna e bem mantida</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Opções com e sem motorista</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Preços competitivos e transparentes</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Suporte 24/7</span>
                    </li>
                </ul>
                <a href="{{ route('cars.index') }}" class="inline-block bg-[var(--primary)] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition">
                    Ver Frota
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Transfer -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <h2 class="text-3xl font-bold text-[var(--secondary)] mb-4">Transfer</h2>
                <p class="text-gray-600 mb-6">
                    Serviço de transfer exclusivo para garantir sua comodidade e segurança em qualquer trajeto. Ideal para viagens de negócios, turismo ou eventos especiais.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Motoristas profissionais e experientes</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Veículos confortáveis e modernos</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Pontualidade garantida</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Serviço personalizado</span>
                    </li>
                </ul>
                <a href="{{ route('transfers.index') }}" class="inline-block bg-[var(--primary)] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition">
                    Solicitar Transfer
                </a>
            </div>
            <div class="order-1 lg:order-2">
                <img src="https://images.pexels.com/photos/3860253/pexels-photo-3860253.jpeg" alt="Transfer" class="rounded-lg shadow-lg w-full h-[400px] object-cover">
            </div>
        </div>
    </div>
</section>

<!-- Passeios -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <img src="https://images.pexels.com/photos/3860253/pexels-photo-3860253.jpeg" alt="Passeios" class="rounded-lg shadow-lg w-full h-[400px] object-cover">
            </div>
            <div>
                <h2 class="text-3xl font-bold text-[var(--secondary)] mb-4">Passeios</h2>
                <p class="text-gray-600 mb-6">
                    Descubra os melhores destinos de Angola com nossos passeios personalizados. Oferecemos roteiros exclusivos para você explorar as belezas naturais e culturais do país.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Roteiros personalizados</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Guias especializados</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Transporte confortável</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-[var(--primary)] mr-2"></i>
                        <span>Experiências únicas</span>
                    </li>
                </ul>
                <a href="{{ route('passeios.index') }}" class="inline-block bg-[var(--primary)] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition">
                    Ver Passeios
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Por que nos escolher -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-[var(--secondary)] mb-12">Por que nos escolher?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-[var(--primary)]/10 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-car text-[var(--primary)] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Frota Moderna</h3>
                <p class="text-gray-600">Veículos novos e bem mantidos para sua segurança e conforto.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-[var(--primary)]/10 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clock text-[var(--primary)] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Pontualidade</h3>
                <p class="text-gray-600">Compromisso com horários e prazos estabelecidos.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-[var(--primary)]/10 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-headset text-[var(--primary)] text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Suporte 24/7</h3>
                <p class="text-gray-600">Atendimento personalizado a qualquer momento.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-[var(--primary)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Pronto para começar sua jornada?</h2>
        <p class="text-white/90 mb-8 max-w-2xl mx-auto">
            Entre em contato conosco para mais informações ou faça sua reserva agora mesmo.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('cars.index') }}" class="bg-white text-[var(--primary)] px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Fazer Reserva
            </a>
            <a href="#contacto" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
                Contato
            </a>
        </div>
    </div>
</section>
@endsection 