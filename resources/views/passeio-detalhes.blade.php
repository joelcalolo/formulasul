@extends('layouts.app')

@section('title', $passeio->nome ?? 'Detalhes do Passeio')

@section('content')
<!-- Hero Section -->
<section class="relative h-[60vh] bg-cover bg-center" style="background-image: url('{{ asset('images/turistas.png') }}');">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $passeio->nome ?? 'Nome do Passeio' }}</h1>
            <p class="text-xl md:text-2xl mb-6">{{ $passeio->subtitulo ?? 'Subtítulo do Passeio' }}</p>
            <div class="flex items-center gap-4">
                <span class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $passeio->localizacao ?? 'Localização' }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    {{ $passeio->duracao ?? 'Duração' }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-star mr-2"></i>
                    {{ $passeio->avaliacao ?? '4.8' }} ({{ $passeio->total_avaliacoes ?? '120' }} avaliações)
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Conteúdo Principal -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2">
                <!-- Descrição -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-[var(--secondary)] mb-4">Sobre o Passeio</h2>
                    <div class="prose max-w-none">
                        {!! $passeio->descricao ?? 'Descrição detalhada do passeio...' !!}
                    </div>
                </div>

                <!-- Galeria de Imagens -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[var(--secondary)] mb-6">Galeria de Fotos</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if(isset($passeio->galeria) && count($passeio->galeria) > 0)
                            @foreach($passeio->galeria as $imagem)
                                <div class="relative group cursor-pointer">
                                    <img src="{{ $imagem }}" alt="Imagem do passeio" class="w-full h-48 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white text-2xl"></i>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Imagens de exemplo -->
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/460672/pexels-photo-460672.jpeg" alt="Imagem 1" class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/3802510/pexels-photo-3802510.jpeg" alt="Imagem 2" class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="relative group cursor-pointer">
                                <img src="https://images.pexels.com/photos/3860253/pexels-photo-3860253.jpeg" alt="Imagem 3" class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white text-2xl"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Card de Reserva -->
                <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-24">
                    <h3 class="text-xl font-bold text-[var(--secondary)] mb-4">Reservar Passeio</h3>
                    <div class="mb-6">
                        <div class="text-3xl font-bold text-[var(--primary)] mb-2">
                            Akz {{ number_format($passeio->preco ?? 0, 2, ',', '.') }}
                        </div>
                        <p class="text-gray-600">por pessoa</p>
                    </div>

                    <form action="{{ route('passeios.reservar', $passeio->id ?? 1) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                            <input type="date" name="data" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--primary)]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de Pessoas</label>
                            <select name="pessoas" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[var(--primary)]" required>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'pessoa' : 'pessoas' }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition">
                            Reservar Agora
                        </button>
                    </form>

                    <!-- Informações Adicionais -->
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-[var(--primary)] mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold">Duração</h4>
                                <p class="text-gray-600">{{ $passeio->duracao ?? 'Duração do passeio' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-users text-[var(--primary)] mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold">Grupo</h4>
                                <p class="text-gray-600">{{ $passeio->tamanho_grupo ?? 'Tamanho do grupo' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-language text-[var(--primary)] mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold">Idioma</h4>
                                <p class="text-gray-600">{{ $passeio->idioma ?? 'Idioma do passeio' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal da Galeria -->
<div id="gallery-modal" class="fixed inset-0 bg-black/90 hidden z-50">
    <div class="absolute top-4 right-4">
        <button onclick="closeGalleryModal()" class="text-white hover:text-gray-300">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    <div class="h-full flex items-center justify-center">
        <img id="modal-image" src="" alt="Imagem ampliada" class="max-h-[90vh] max-w-[90vw] object-contain">
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Função para abrir o modal da galeria
    function openGalleryModal(imageSrc) {
        const modal = document.getElementById('gallery-modal');
        const modalImage = document.getElementById('modal-image');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Função para fechar o modal da galeria
    function closeGalleryModal() {
        const modal = document.getElementById('gallery-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Adicionar event listeners para as imagens da galeria
    document.addEventListener('DOMContentLoaded', function() {
        const galleryImages = document.querySelectorAll('.group.cursor-pointer img');
        galleryImages.forEach(img => {
            img.parentElement.addEventListener('click', () => {
                openGalleryModal(img.src);
            });
        });

        // Fechar modal ao clicar fora da imagem
        document.getElementById('gallery-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGalleryModal();
            }
        });
    });
</script>
@endsection 