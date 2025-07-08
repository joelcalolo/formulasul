@extends('layouts.app')

@section('title', 'Página não encontrada')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-600 mb-8">Página não encontrada</h2>
        <p class="text-gray-500 mb-8">A página que você está procurando não existe ou foi movida.</p>
        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
            Voltar para a página inicial
        </a>
    </div>
</div>
@endsection 