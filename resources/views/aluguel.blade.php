<!-- resources/views/aluguel.blade.php -->
@extends('layouts.app')

@section('title', 'Aluguel de Viaturas')

@section('content')
<!-- Hero -->
<section class="bg-gray py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <!-- Imagem do Hero -->
        <div>
            <img src="/images/aluguel-hero.jpg" alt="Fórmula Sul - Aluguel de Carros" class="w-full rounded-lg shadow-[var(--shadow)]">
        </div>

        <!-- Texto do Hero -->
        <div>
            <span class="bg-[var(--primary)]/10 text-[var(--primary)] text-xs font-bold uppercase px-2 py-1 rounded">Aluguel Premium</span>

            <h1 class="mt-4 text-4xl font-extrabold leading-tight text-[var(--secondary)]">
                Encontre o veículo ideal para sua viagem com conforto e praticidade
            </h1>

            <p class="mt-4 text-lg text-gray-600">
                Oferecemos uma frota diversificada de veículos para atender todas as suas necessidades de mobilidade. Desde carros econômicos até SUVs de luxo, temos a solução perfeita para você.
            </p>

            <form id="hero-filter" class="mt-8 bg-white p-6 rounded-xl shadow-[var(--shadow)] grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
                    <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Tipo</label>
                    <select class="w-full rounded-md border border-gray-300 px-3 py-2 text-[var(--secondary)] focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]" name="vehicle_type">
          <option value="">Todos</option>
          <option value="compacto">Compacto</option>
          <option value="suv">SUV</option>
          <option value="luxo">Luxo</option>
        </select>
      </div>
      <div>
                    <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Local</label>
                    <input type="text" class="w-full rounded-md border border-gray-300 px-3 py-2 text-[var(--secondary)] focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]" name="location" placeholder="Cidade ou aeroporto">
      </div>
      <div>
                    <label class="block text-sm font-semibold text-[var(--secondary)] mb-1">Início</label>
                    <input type="date" class="w-full rounded-md border border-gray-300 px-3 py-2 text-[var(--secondary)] focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]" name="start_date">
      </div>
      <div class="flex items-end">
                    <button type="submit" class="w-full bg-[var(--primary)] text-[var(--secondary)] font-semibold py-2 px-4 rounded-md hover:bg-[var(--accent)] transition">Buscar</button>
      </div>
    </form>
        </div>
  </div>
</section>

<!-- Catálogo -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-[var(--secondary)] mb-6 text-center">Catálogo de Viaturas</h2>
        <p class="text-gray-600 text-center max-w-2xl mx-auto mb-12">Escolha o veículo ideal para sua viagem.</p>

        <!-- Filtros -->
        <div class="mb-6 flex flex-wrap justify-center gap-4">
            <button class="bg-[var(--primary)] text-[var(--secondary)] px-4 py-2 rounded-full text-sm font-medium hover:bg-[var(--accent)] transition shadow-[var(--shadow)]">Todos</button>
            <button class="bg-white text-[var(--secondary)] px-4 py-2 rounded-full text-sm font-medium hover:bg-[var(--accent)] transition shadow-[var(--shadow)]">Compacto</button>
            <button class="bg-white text-[var(--secondary)] px-4 py-2 rounded-full text-sm font-medium hover:bg-[var(--accent)] transition shadow-[var(--shadow)]">SUV</button>
            <button class="bg-white text-[var(--secondary)] px-4 py-2 rounded-full text-sm font-medium hover:bg-[var(--accent)] transition shadow-[var(--shadow)]">Luxo</button>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($vehicles as $vehicle)
                <div class="rounded-2xl shadow-[var(--shadow)] bg-white hover:shadow-lg transition-all duration-300">
                    <div class="relative">
                        <img src="{{ $vehicle->image }}" alt="{{ $vehicle->name }}" class="rounded-t-2xl h-56 w-full object-cover">
                        <div class="absolute top-4 right-4 bg-[var(--primary)] text-[var(--secondary)] text-sm font-semibold rounded-full px-4 py-1">
                            {{ strtoupper($vehicle->type) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h2 class="text-xl font-bold mb-2 text-[var(--secondary)]">{{ $vehicle->name }}</h2>
                        <p class="text-gray-600 mb-4">{{ $vehicle->description }}</p>
                        <div class="flex items-center mb-4">
                            <span class="text-[var(--primary)] font-semibold mr-2">4.8</span>
                            <div class="flex text-yellow-400">★★★★★</div>
                        </div>
                        <div class="text-2xl font-semibold mb-4 text-[var(--secondary)]">Akz {{ number_format($vehicle->price, 2, ',', '.') }}/dia</div>
                        <div class="flex justify-between text-gray-600 text-sm">
                            <div class="flex items-center gap-1">🚗 {{ $vehicle->transmission ?? 'Automático' }}</div>
                            <div class="flex items-center gap-1">⛽ {{ $vehicle->fuel ?? 'Gasolina' }}</div>
                        </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@include('components.como-alugar')
@include('components.form-reserva')

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

@section('scripts')
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

    document.getElementById('rental-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = {
            vehicle_type: formData.get('vehicle_type'),
            location: formData.get('location'),
            start_date: formData.get('start_date'),
            end_date: formData.get('end_date'),
            name: formData.get('name'),
            email: formData.get('email')
        };

        try {
            const response = await fetch('/api/rental-requests', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer {{ Auth::user() ? Auth::user()->createToken('api')->plainTextToken : '' }}'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.error || `Erro ${response.status}`);
            }
            showToast('Reserva solicitada com sucesso!', 'success');
            form.reset();
        } catch (error) {
            console.error('Erro ao enviar reserva:', error);
            showToast(`Erro: ${error.message}`, 'error');
        }
    });
</script>
@endsection
