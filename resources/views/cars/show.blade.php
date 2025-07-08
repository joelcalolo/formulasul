@extends('layouts.app')

@section('title', $car->marca . ' ' . $car->modelo)

@section('content')
<!-- Hero Section -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                {{ $car->marca }} {{ $car->modelo }}
            </h1>
            <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
                Descubra todos os detalhes deste veículo e faça sua reserva.
            </p>
        </div>
    </div>
</section>

<!-- Car Details Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Galeria de Imagens -->
            <div class="space-y-6">
                @if($car->hasGallery())
                    <!-- Carrossel Principal -->
                    <div class="carousel-container">
                        <div class="carousel-track">
                            <!-- Imagem de Capa -->
                            @if($car->image_cover)
                                <div class="carousel-slide">
                                    <img src="{{ asset('storage/' . $car->image_cover) }}" 
                                         alt="{{ $car->modelo }}" 
                                         class="carousel-image zoom-image"
                                         onclick="openLightboxFromImage(this)">
                                </div>
                            @endif

                            <!-- Imagens Adicionais -->
                            @for($i = 1; $i <= 3; $i++)
                                @php $imageField = "image_{$i}"; @endphp
                                @if($car->$imageField)
                                    <div class="carousel-slide">
                                        <img src="{{ asset('storage/' . $car->$imageField) }}" 
                                             alt="{{ $car->modelo }} - Imagem {{ $i }}" 
                                             class="carousel-image zoom-image"
                                             onclick="openLightboxFromImage(this)">
                                    </div>
                                @endif
                            @endfor
                        </div>

                        <!-- Navegação do Carrossel -->
                        @if($car->image_count > 1)
                            <button class="carousel-nav carousel-prev" aria-label="Imagem anterior">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-nav carousel-next" aria-label="Próxima imagem">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                            <!-- Indicadores -->
                            <div class="carousel-indicators">
                                @for($i = 0; $i < $car->image_count; $i++)
                                    <div class="carousel-indicator {{ $i === 0 ? 'active' : '' }}" 
                                         onclick="goToSlide({{ $i }})"></div>
                                @endfor
                            </div>
                        @endif
                    </div>

                    <!-- Miniaturas -->
                    <div class="thumbnails-container">
                        <!-- Miniatura da Imagem de Capa -->
                        @if($car->image_cover)
                            <div class="thumbnail active" onclick="selectThumbnail(this, 0)">
                                <img src="{{ asset('storage/' . $car->image_cover) }}" 
                                     alt="{{ $car->modelo }} - Miniatura">
                            </div>
                        @endif

                        <!-- Miniaturas das Imagens Adicionais -->
                        @for($i = 1; $i <= 3; $i++)
                            @php $imageField = "image_{$i}"; @endphp
                            @if($car->$imageField)
                                <div class="thumbnail" onclick="selectThumbnail(this, {{ $i }})">
                                    <img src="{{ asset('storage/' . $car->$imageField) }}" 
                                         alt="{{ $car->modelo }} - Miniatura {{ $i }}">
                                </div>
                            @endif
                        @endfor
                    </div>

                    <!-- Informações da Galeria -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-images text-[var(--primary)]"></i>
                                <span>{{ $car->image_count }} {{ $car->image_count === 1 ? 'imagem' : 'imagens' }} disponível{{ $car->image_count === 1 ? '' : 'is' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="downloadAllImages()" class="text-[var(--primary)] hover:text-[var(--primary)]/80 flex items-center gap-1">
                                    <i class="fas fa-download"></i>
                                    <span>Baixar</span>
                                </button>
                                <button onclick="shareGallery()" class="text-[var(--primary)] hover:text-[var(--primary)]/80 flex items-center gap-1">
                                    <i class="fas fa-share"></i>
                                    <span>Compartilhar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Imagem única -->
                    <div class="relative">
                        @if($car->image_cover)
                            <img src="{{ asset('storage/' . $car->image_cover) }}" 
                                 alt="{{ $car->modelo }}" 
                                 class="w-full h-96 object-cover rounded-2xl shadow-lg cursor-pointer transition-transform hover:scale-105"
                                 onclick="openLightboxFromImage(this)">
                        @else
                            <img src="https://via.placeholder.com/600x400?text=Sem+Imagem" 
                                 alt="Sem Imagem" 
                                 class="w-full h-96 object-cover rounded-2xl shadow-lg">
                        @endif
                    </div>
                @endif
            </div>

            <!-- Informações do Carro -->
            <div class="space-y-8">
                <!-- Preço e Status -->
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">{{ $car->marca }} {{ $car->modelo }}</h2>
                            <p class="text-gray-600">{{ ucfirst($car->status) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-[var(--primary)]">
                                Akz {{ number_format($car->price, 2, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500">por dia</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-700">Status:</span>
                            <span class="ml-2 px-3 py-1 rounded-full text-xs font-semibold
                                {{ $car->status === 'disponivel' ? 'bg-green-100 text-green-800' : 
                                   ($car->status === 'alugado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($car->status) }}
                            </span>
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center">
                            <span class="text-[var(--primary)] font-semibold mr-2">4.8</span>
                            <div class="flex text-yellow-400">★★★★★</div>
                        </div>
                    </div>

                    <!-- Botão de Reserva -->
                    <button onclick="openReservationModal()" 
                            class="w-full bg-[var(--primary)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition-colors">
                        Reservar Agora
                    </button>
                </div>

                <!-- Especificações -->
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Especificações</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-cog text-[var(--primary)] mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Transmissão</div>
                                <div class="font-medium">{{ $car->caixa ?? 'Automático' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-gas-pump text-[var(--primary)] mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Combustível</div>
                                <div class="font-medium">{{ $car->combustivel ?? 'Gasolina' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users text-[var(--primary)] mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Lugares</div>
                                <div class="font-medium">{{ $car->lugares }} pessoas</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-car text-[var(--primary)] mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Tração</div>
                                <div class="font-medium">{{ $car->tracao ?? '2WD' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Preços -->
                @if($car->priceTable)
                <div class="bg-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tabela de Preços</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dentro da cidade (com motorista)</span>
                            <span class="font-semibold">
                                @if($car->priceTable->preco_dentro_com_motorista > 0)
                                    Akz {{ number_format($car->priceTable->preco_dentro_com_motorista, 2, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dentro da cidade (sem motorista)</span>
                            <span class="font-semibold">
                                @if($car->priceTable->preco_dentro_sem_motorista > 0)
                                    Akz {{ number_format($car->priceTable->preco_dentro_sem_motorista, 2, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fora da cidade (com motorista)</span>
                            <span class="font-semibold">
                                @if($car->priceTable->preco_fora_com_motorista > 0)
                                    Akz {{ number_format($car->priceTable->preco_fora_com_motorista, 2, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fora da cidade (sem motorista)</span>
                            <span class="font-semibold">
                                @if($car->priceTable->preco_fora_sem_motorista > 0)
                                    Akz {{ number_format($car->priceTable->preco_fora_sem_motorista, 2, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <hr class="my-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Taxa de entrega/recolha</span>
                            <span class="font-semibold">
                                @if($car->priceTable->taxa_entrega_recolha > 0)
                                    Akz {{ number_format($car->priceTable->taxa_entrega_recolha, 2, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Caução</span>
                            <span class="font-semibold">
                                @if($car->priceTable->caucao > 0)
                                    Akz {{ number_format($car->priceTable->caucao, 2, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Modal de Reserva -->
<div id="reservation-modal" class="modal fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm z-40 hidden flex items-center justify-center transition-all duration-300">
    <div class="modal-content bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Reservar {{ $car->marca }} {{ $car->modelo }}</h3>
            <button onclick="closeReservationModal()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('rental-requests.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="carro_principal_id" value="{{ $car->id }}">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Serviço</label>
                <select name="tipo_servico" id="tipo_servico" required 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
                    <option value="">Selecione o tipo de serviço</option>
                    @if($car->priceTable->preco_dentro_com_motorista > 0)
                        <option value="dentro_com_motorista">Dentro da Cidade (Com Motorista)</option>
                    @endif
                    @if($car->priceTable->preco_dentro_sem_motorista > 0)
                        <option value="dentro_sem_motorista">Dentro da Cidade (Sem Motorista)</option>
                    @endif
                    @if($car->priceTable->preco_fora_com_motorista > 0)
                        <option value="fora_com_motorista">Fora da Cidade (Com Motorista)</option>
                    @endif
                    @if($car->priceTable->preco_fora_sem_motorista > 0)
                        <option value="fora_sem_motorista">Fora da Cidade (Sem Motorista)</option>
                    @endif
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Local de Entrega</label>
                <input type="text" name="local_entrega" required placeholder="Digite o local de entrega"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações (opcional)</label>
                <textarea name="observacoes" rows="3" placeholder="Informações adicionais..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)]"></textarea>
            </div>
            
            <!-- Preço estimado em tempo real -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Preço Estimado:</span>
                    <span id="preco-estimado" class="text-lg font-bold text-[var(--primary)]">Kz 0,00</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    <span id="detalhes-preco">Selecione as datas e tipo de serviço</span>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[var(--primary)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--primary)]/90 transition-all duration-200 hover:scale-105 flex items-center justify-center">
                <span class="submit-text">Confirmar Reserva</span>
                <span class="loading-text hidden flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                    Processando...
                </span>
            </button>
        </form>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
function selectThumbnail(thumbnail, index) {
    // Remover classe active de todas as miniaturas
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    
    // Adicionar classe active na miniatura selecionada
    thumbnail.classList.add('active');
    
    // Atualizar carrossel
    const track = document.querySelector('.carousel-track');
    track.style.transform = `translateX(-${index * 100}%)`;
    
    // Atualizar indicadores
    document.querySelectorAll('.carousel-indicator').forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
    });
}

function goToSlide(index) {
    const track = document.querySelector('.carousel-track');
    track.style.transform = `translateX(-${index * 100}%)`;
    
    // Atualizar indicadores
    document.querySelectorAll('.carousel-indicator').forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
    });
    
    // Atualizar miniaturas
    document.querySelectorAll('.thumbnail').forEach((thumbnail, i) => {
        thumbnail.classList.toggle('active', i === index);
    });
}

function openLightboxFromImage(imageElement) {
    const images = Array.from(document.querySelectorAll('.carousel-image'));
    const startIndex = images.indexOf(imageElement);
    
    if (window.carGallery) {
        window.carGallery.openLightbox(images, startIndex);
    }
}

function downloadAllImages() {
    const images = [
        @if($car->image_cover)
            '{{ asset('storage/' . $car->image_cover) }}',
        @endif
        @for($i = 1; $i <= 3; $i++)
            @php $imageField = "image_{$i}"; @endphp
            @if($car->$imageField)
                '{{ asset('storage/' . $car->$imageField) }}',
            @endif
        @endfor
    ];
    
    images.forEach((src, index) => {
        setTimeout(() => {
            if (window.carGallery) {
                window.carGallery.downloadImage(src, `{{ $car->marca }}_{{ $car->modelo }}_${index + 1}.jpg`);
            }
        }, index * 500);
    });
}

function shareGallery() {
    const title = '{{ $car->marca }} {{ $car->modelo }} - Formula Sul';
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        });
    } else {
        // Fallback para copiar URL
        navigator.clipboard.writeText(url).then(() => {
            if (window.carGallery) {
                window.carGallery.showNotification('URL copiada para a área de transferência!', 'success');
            }
        });
    }
}

function openReservationModal() {
    const modal = document.getElementById('reservation-modal');
    const content = modal.querySelector('.modal-content');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeReservationModal() {
    const modal = document.getElementById('reservation-modal');
    const content = modal.querySelector('.modal-content');
    
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

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

// Interceptar submit do formulário de reserva
document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.querySelector('#reservation-modal form');
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const submitText = btn.querySelector('.submit-text');
            const loadingText = btn.querySelector('.loading-text');
            
            // Mostrar loading
            btn.disabled = true;
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            
            // Fazer a requisição
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                closeReservationModal();
                if (data.success) {
                    showToast('Reserva solicitada com sucesso!', 'success');
                    this.reset();
                } else {
                    showToast(data.message || 'Erro ao solicitar reserva', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao solicitar reserva:', error);
                showToast('Erro ao solicitar reserva. Tente novamente.', 'error');
            })
            .finally(() => {
                // Restaurar botão
                btn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');
            });
        });
    }
});

// Inicializar funcionalidades quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Auto-play do carrossel
    const carousel = document.querySelector('.carousel-container');
    if (carousel && {{ $car->image_count }} > 1) {
        let currentSlide = 0;
        const totalSlides = {{ $car->image_count }};
        
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            goToSlide(currentSlide);
        }, 5000);
        
        // Pausar no hover
        carousel.addEventListener('mouseenter', () => {
            clearInterval(window.carouselInterval);
        });
        
        carousel.addEventListener('mouseleave', () => {
            window.carouselInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                goToSlide(currentSlide);
            }, 5000);
        });
    }

    // Cálculo do preço estimado em tempo real
    const dataInicio = document.getElementById('data_inicio');
    const dataFim = document.getElementById('data_fim');
    const tipoServico = document.getElementById('tipo_servico');
    const precoEstimado = document.getElementById('preco-estimado');
    const detalhesPreco = document.getElementById('detalhes-preco');
    
    // Preços da tabela de preços do carro
    const precos = {
        dentro_com_motorista: {{ $car->priceTable->preco_dentro_com_motorista ?? 0 }},
        dentro_sem_motorista: {{ $car->priceTable->preco_dentro_sem_motorista ?? 0 }},
        fora_com_motorista: {{ $car->priceTable->preco_fora_com_motorista ?? 0 }},
        fora_sem_motorista: {{ $car->priceTable->preco_fora_sem_motorista ?? 0 }}
    };
    
    // Caução do carro
    const caucao = {{ $car->priceTable->caucao ?? 0 }};
    
    function calcularPrecoEstimado() {
        console.log('Calculando preço estimado...');
        console.log('Data início:', dataInicio?.value);
        console.log('Data fim:', dataFim?.value);
        console.log('Tipo serviço:', tipoServico?.value);
        
        if (dataInicio && dataFim && tipoServico && 
            dataInicio.value && dataFim.value && tipoServico.value) {
            
            const inicio = new Date(dataInicio.value);
            const fim = new Date(dataFim.value);
            const dias = Math.ceil((fim - inicio) / (1000 * 60 * 60 * 24));
            
            console.log('Dias calculados:', dias);
            
            if (dias > 0) {
                const precoPorDia = precos[tipoServico.value] || 0;
                const precoAluguel = precoPorDia * dias;
                const precoTotal = precoAluguel + caucao; // Incluir caução no total
                
                console.log('Preço por dia:', precoPorDia);
                console.log('Preço aluguel:', precoAluguel);
                console.log('Caução:', caucao);
                console.log('Preço total:', precoTotal);
                
                let tipoServicoText = '';
                switch(tipoServico.value) {
                    case 'dentro_com_motorista':
                        tipoServicoText = 'Dentro da Cidade (Com Motorista)';
                        break;
                    case 'dentro_sem_motorista':
                        tipoServicoText = 'Dentro da Cidade (Sem Motorista)';
                        break;
                    case 'fora_com_motorista':
                        tipoServicoText = 'Fora da Cidade (Com Motorista)';
                        break;
                    case 'fora_sem_motorista':
                        tipoServicoText = 'Fora da Cidade (Sem Motorista)';
                        break;
                }
                
                if (precoPorDia > 0) {
                    precoEstimado.textContent = `Kz ${precoTotal.toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    detalhesPreco.innerHTML = `
                        ${dias} dia(s) × Kz ${precoPorDia.toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}/dia (${tipoServicoText})<br>
                        + Caução: Kz ${caucao.toLocaleString('pt-AO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    `;
                } else {
                    precoEstimado.textContent = 'N/A';
                    detalhesPreco.textContent = 'Serviço não disponível';
                }
            } else {
                precoEstimado.textContent = 'Kz 0,00';
                detalhesPreco.textContent = 'Data de fim deve ser posterior à data de início';
            }
        } else {
            precoEstimado.textContent = 'Kz 0,00';
            detalhesPreco.textContent = 'Selecione as datas e tipo de serviço';
        }
    }

    // Event listeners para cálculo em tempo real
    if (dataInicio) {
        dataInicio.addEventListener('change', calcularPrecoEstimado);
        // Definir data mínima para hoje
        const hoje = new Date().toISOString().split('T')[0];
        dataInicio.min = hoje;
        console.log('Event listener adicionado para data início');
    }
    
    if (dataFim) {
        dataFim.addEventListener('change', calcularPrecoEstimado);
        // Atualizar data mínima do fim quando início for selecionado
        if (dataInicio) {
            dataInicio.addEventListener('change', function() {
                dataFim.min = this.value;
                console.log('Data mínima do fim atualizada para:', this.value);
            });
        }
        console.log('Event listener adicionado para data fim');
    }
    
    if (tipoServico) {
        tipoServico.addEventListener('change', calcularPrecoEstimado);
        console.log('Event listener adicionado para tipo serviço');
    }

    // Calcular preço inicial se todos os campos já estiverem preenchidos
    setTimeout(() => {
        if (dataInicio && dataFim && tipoServico && 
            dataInicio.value && dataFim.value && tipoServico.value) {
            calcularPrecoEstimado();
        }
    }, 100);

    // Validação e envio do formulário
    const form = document.querySelector('#reservation-modal form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar campos obrigatórios
            const campos = {
                data_inicio: dataInicio.value,
                data_fim: dataFim.value,
                tipo_servico: tipoServico.value,
                local_entrega: form.querySelector('input[name="local_entrega"]').value
            };
            
            let erros = [];
            
            if (!campos.data_inicio) {
                erros.push('Data de início é obrigatória');
            }
            if (!campos.data_fim) {
                erros.push('Data de fim é obrigatória');
            }
            if (!campos.tipo_servico) {
                erros.push('Tipo de serviço é obrigatório');
            }
            if (!campos.local_entrega.trim()) {
                erros.push('Local de entrega é obrigatório');
            }
            
            // Validar datas
            if (campos.data_inicio && campos.data_fim) {
                const inicio = new Date(campos.data_inicio);
                const fim = new Date(campos.data_fim);
                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0);
                
                if (inicio < hoje) {
                    erros.push('Data de início não pode ser no passado');
                }
                if (fim <= inicio) {
                    erros.push('Data de fim deve ser posterior à data de início');
                }
            }
            
            // Validar se o serviço selecionado tem preço
            if (campos.tipo_servico && precos[campos.tipo_servico] <= 0) {
                erros.push('Serviço selecionado não está disponível');
            }
            
                            if (erros.length > 0) {
                    showToast('Erros de validação:\n' + erros.join('\n'), 'error');
                    return;
                }
            
            // Se tudo estiver ok, enviar o formulário
            const formData = new FormData(form);
            
            // Mostrar loading
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Enviando...';
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Reserva enviada com sucesso! Entraremos em contato em breve.', 'success');
                    closeReservationModal();
                    form.reset();
                } else {
                    let mensagem = 'Erro ao enviar reserva';
                    if (data.message) {
                        mensagem += ': ' + data.message;
                    }
                    if (data.errors) {
                        mensagem += '\n\nDetalhes:\n' + Object.values(data.errors).flat().join('\n');
                    }
                    showToast(mensagem, 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao enviar reserva. Tente novamente.', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Fechar modal de reserva ao clicar fora
document.getElementById('reservation-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReservationModal();
    }
});

// Navegação com teclado
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReservationModal();
    }
});
</script>
@endsection 