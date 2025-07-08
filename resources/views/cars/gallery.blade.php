<div class="car-gallery">
    <!-- Debug info -->
    <div style="background: #f0f0f0; padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 12px;">
        <p>Debug: Carro ID: {{ $car->id }}</p>
        <p>Debug: Marca: {{ $car->marca }}</p>
        <p>Debug: Modelo: {{ $car->modelo }}</p>
        <p>Debug: Image Cover: {{ $car->image_cover ? 'Sim' : 'Não' }}</p>
        <p>Debug: Image 1: {{ $car->image_1 ? 'Sim' : 'Não' }}</p>
        <p>Debug: Image 2: {{ $car->image_2 ? 'Sim' : 'Não' }}</p>
        <p>Debug: Image 3: {{ $car->image_3 ? 'Sim' : 'Não' }}</p>
        <p>Debug: Has Gallery: {{ $car->hasGallery() ? 'Sim' : 'Não' }}</p>
        <p>Debug: Image Count: {{ $car->image_count }}</p>
    </div>

    <div class="mb-4">
        <h4 class="text-lg font-semibold text-gray-800">{{ $car->marca }} {{ $car->modelo }}</h4>
        <p class="text-sm text-gray-600">{{ ucfirst($car->status) }}</p>
    </div>

    @if($car->hasGallery())
        <!-- Carrossel Principal -->
        <div class="carousel-container mb-4">
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
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
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
        <!-- Estado sem galeria -->
        <div class="text-center py-8">
            <div class="gallery-error">
                <i class="fas fa-images"></i>
                <span>Nenhuma imagem adicional disponível</span>
            </div>
            <p class="text-gray-500 mt-2">Este carro possui apenas a imagem de capa.</p>
        </div>
    @endif
</div>

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
});

window.selectThumbnail = selectThumbnail;
window.goToSlide = goToSlide;
window.openLightboxFromImage = openLightboxFromImage;
window.downloadAllImages = downloadAllImages;
window.shareGallery = shareGallery;
</script> 