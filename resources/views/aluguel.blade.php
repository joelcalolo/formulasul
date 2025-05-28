<!-- resources/views/aluguel.blade.php -->
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

<!-- Seção Catálogo -->
<section class="my-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Catálogo de Viaturas</h2>
        <p class="mt-4 text-lg text-gray-600 text-center max-w-2xl mx-auto">
            Escolha o veículo ideal para sua viagem.
        </p>
        <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($vehicles as $vehicle)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ $vehicle->image }}" alt="{{ $vehicle->name }}" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $vehicle->name }}</h3>
                        <p class="mt-2 text-gray-600">{{ $vehicle->description }}</p>
                        <p class="mt-2 text-[var(--primary)] font-bold">A partir de Kz {{ $vehicle->price }}/dia</p>
                        <a href="#reserva" class="mt-4 inline-block bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">Reservar</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Seção Como Alugar -->
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

<!-- Seção Reserva -->
<section id="reserva" class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Faça sua Reserva</h2>
        <p class="mt-4 text-lg text-gray-600 text-center max-w-2xl mx-auto">
            Preencha o formulário abaixo para reservar sua viatura.
        </p>
        <form id="rental-form" class="max-w-lg mx-auto mt-8 bg-white p-6 rounded-lg shadow-sm">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="vehicle-type">Tipo de Veículo</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="vehicle-type" name="vehicle_type" required>
                    <option value="compacto">Compacto Econômico</option>
                    <option value="suv">SUV Familiar</option>
                    <option value="luxo">Luxo</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Local de Retirada</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="location" type="text" name="location" placeholder="Cidade ou Aeroporto" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start-date">Data de Início</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="start-date" type="date" name="start_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="end-date">Data de Fim</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="end-date" type="date" name="end_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome Completo</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="name" type="text" name="name" placeholder="Seu Nome" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">E-mail</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="email" type="email" name="email" placeholder="Seu E-mail" required>
            </div>
            <button class="bg-[var(--primary)] text-white px-6 py-3 rounded-md font-semibold hover:bg-[var(--primary)]/90 w-full" type="submit">Reservar Agora</button>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
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
            alert('Reserva solicitada com sucesso!');
            form.reset();
        } catch (error) {
            console.error('Erro ao enviar reserva:', error);
            alert(`Erro: ${error.message}`);
        }
    });
</script>
@endsection