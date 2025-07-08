/**
 * Galeria de Carros - Formula Sul
 * Funcionalidades: Lightbox, Carrossel, Zoom, Navegação por Teclado
 */

class CarGallery {
    constructor() {
        this.currentImageIndex = 0;
        this.images = [];
        this.lightbox = null;
        this.carousel = null;
        this.autoPlayInterval = null;
        this.isAutoPlaying = false;
        
        this.init();
    }

    init() {
        this.createLightbox();
        this.bindEvents();
        this.initCarousels();
        this.initThumbnails();
        this.initKeyboardNavigation();
    }

    createLightbox() {
        // Criar estrutura do lightbox
        const lightboxHTML = `
            <div class="lightbox" id="carLightbox">
                <div class="lightbox-content">
                    <button class="lightbox-close" aria-label="Fechar galeria">
                        <i class="fas fa-times"></i>
                    </button>
                    <img class="lightbox-image" src="" alt="">
                    <button class="lightbox-nav lightbox-prev" aria-label="Imagem anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="lightbox-nav lightbox-next" aria-label="Próxima imagem">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="lightbox-counter">
                        <span id="currentImage">1</span> / <span id="totalImages">1</span>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);
        this.lightbox = document.getElementById('carLightbox');
    }

    bindEvents() {
        // Eventos do lightbox
        this.lightbox.querySelector('.lightbox-close').addEventListener('click', () => {
            this.closeLightbox();
        });

        this.lightbox.querySelector('.lightbox-prev').addEventListener('click', () => {
            this.previousImage();
        });

        this.lightbox.querySelector('.lightbox-next').addEventListener('click', () => {
            this.nextImage();
        });

        // Fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.lightbox.classList.contains('active')) {
                this.closeLightbox();
            }
        });

        // Navegação por teclado
        document.addEventListener('keydown', (e) => {
            if (!this.lightbox.classList.contains('active')) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    this.previousImage();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.nextImage();
                    break;
            }
        });

        // Fechar ao clicar fora da imagem
        this.lightbox.addEventListener('click', (e) => {
            if (e.target === this.lightbox) {
                this.closeLightbox();
            }
        });
    }

    initCarousels() {
        const carousels = document.querySelectorAll('.carousel-container');
        
        carousels.forEach(carousel => {
            this.initCarousel(carousel);
        });
    }

    initCarousel(carousel) {
        // Verificar se o carousel existe
        if (!carousel) {
            console.warn('Carousel não fornecido para initCarousel');
            return;
        }
        
        const track = carousel.querySelector('.carousel-track');
        const slides = carousel.querySelectorAll('.carousel-slide');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');
        const indicators = carousel.querySelectorAll('.carousel-indicator');
        
        // Verificar se o track e slides existem
        if (!track || slides.length === 0) {
            console.warn('Track ou slides não encontrados no carousel');
            return;
        }
        
        let currentSlide = 0;
        const totalSlides = slides.length;

        if (totalSlides <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            return;
        }

        // Função para atualizar carrossel
        const updateCarousel = (index) => {
            currentSlide = index;
            if (track) {
                track.style.transform = `translateX(-${index * 100}%)`;
            }
            
            // Atualizar indicadores
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
        };

        // Eventos dos botões
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                const newIndex = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
                updateCarousel(newIndex);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const newIndex = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
                updateCarousel(newIndex);
            });
        }

        // Eventos dos indicadores
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                updateCarousel(index);
            });
        });

        // Auto-play
        if (totalSlides > 1) {
            let autoPlayInterval = setInterval(() => {
                const newIndex = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
                updateCarousel(newIndex);
            }, 5000);

            // Pausar no hover
            carousel.addEventListener('mouseenter', () => {
                clearInterval(autoPlayInterval);
            });

            carousel.addEventListener('mouseleave', () => {
                autoPlayInterval = setInterval(() => {
                    const newIndex = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
                    updateCarousel(newIndex);
                }, 5000);
            });
        }

        // Swipe para dispositivos móveis
        let startX = 0;
        let endX = 0;

        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });

        carousel.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            const diff = startX - endX;

            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentSlide < totalSlides - 1) {
                    updateCarousel(currentSlide + 1);
                } else if (diff < 0 && currentSlide > 0) {
                    updateCarousel(currentSlide - 1);
                }
            }
        });
    }

    initThumbnails() {
        const thumbnailContainers = document.querySelectorAll('.thumbnails-container');
        
        thumbnailContainers.forEach(container => {
            const thumbnails = container.querySelectorAll('.thumbnail');
            const carGallery = container.closest('.car-gallery');
            
            // Verificar se existe um car-gallery pai
            if (!carGallery) {
                console.warn('Thumbnail container não está dentro de um .car-gallery');
                return;
            }
            
            const carousel = carGallery.querySelector('.carousel-container');
            
            // Verificar se existe o carousel
            if (!carousel) {
                console.warn('Carousel container não encontrado para thumbnails');
                return;
            }
            
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', () => {
                    this.selectThumbnail(thumbnail, thumbnails, carousel, index);
                });
            });
        });
    }

    selectThumbnail(selectedThumbnail, allThumbnails, carousel, index) {
        // Verificar se todos os parâmetros necessários existem
        if (!selectedThumbnail || !allThumbnails || !carousel) {
            console.warn('Parâmetros inválidos para selectThumbnail');
            return;
        }
        
        // Remover classe active de todas as miniaturas
        allThumbnails.forEach(thumb => thumb.classList.remove('active'));
        
        // Adicionar classe active na miniatura selecionada
        selectedThumbnail.classList.add('active');
        
        // Atualizar carrossel
        const track = carousel.querySelector('.carousel-track');
        if (track) {
            track.style.transform = `translateX(-${index * 100}%)`;
        }
        
        // Atualizar indicadores
        const indicators = carousel.querySelectorAll('.carousel-indicator');
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }

    initKeyboardNavigation() {
        // Navegação por teclado para carrosséis
        document.addEventListener('keydown', (e) => {
            const activeCarousel = document.querySelector('.carousel-container:focus-within');
            if (!activeCarousel) return;
            
            const track = activeCarousel.querySelector('.carousel-track');
            const slides = activeCarousel.querySelectorAll('.carousel-slide');
            
            // Verificar se track e slides existem
            if (!track || slides.length === 0) return;
            
            const currentTransform = track.style.transform;
            const currentIndex = Math.abs(parseInt(currentTransform.replace('translateX(-', '').replace('%)', '')) / 100);
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        track.style.transform = `translateX(-${(currentIndex - 1) * 100}%)`;
                    }
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    if (currentIndex < slides.length - 1) {
                        track.style.transform = `translateX(-${(currentIndex + 1) * 100}%)`;
                    }
                    break;
            }
        });
    }

    openLightbox(images, startIndex = 0) {
        // Verificar se o lightbox existe
        if (!this.lightbox) {
            console.warn('Lightbox não encontrado');
            return;
        }
        
        this.images = images;
        this.currentImageIndex = startIndex;
        
        this.updateLightboxImage();
        this.updateLightboxCounter();
        this.lightbox.classList.add('active');
        
        // Prevenir scroll do body
        document.body.style.overflow = 'hidden';
        
        // Focar no lightbox para navegação por teclado
        this.lightbox.focus();
    }

    closeLightbox() {
        this.lightbox.classList.remove('active');
        document.body.style.overflow = '';
        
        // Parar auto-play se estiver ativo
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
            this.isAutoPlaying = false;
        }
    }

    updateLightboxImage() {
        if (!this.lightbox) return;
        
        const image = this.lightbox.querySelector('.lightbox-image');
        const currentImage = this.images[this.currentImageIndex];
        
        if (currentImage && image) {
            image.src = currentImage.src;
            image.alt = currentImage.alt || `Imagem ${this.currentImageIndex + 1}`;
        }
    }

    updateLightboxCounter() {
        if (!this.lightbox) return;
        
        const currentSpan = this.lightbox.querySelector('#currentImage');
        const totalSpan = this.lightbox.querySelector('#totalImages');
        
        if (currentSpan) currentSpan.textContent = this.currentImageIndex + 1;
        if (totalSpan) totalSpan.textContent = this.images.length;
    }

    previousImage() {
        this.currentImageIndex = this.currentImageIndex > 0 
            ? this.currentImageIndex - 1 
            : this.images.length - 1;
        
        this.updateLightboxImage();
        this.updateLightboxCounter();
    }

    nextImage() {
        this.currentImageIndex = this.currentImageIndex < this.images.length - 1 
            ? this.currentImageIndex + 1 
            : 0;
        
        this.updateLightboxImage();
        this.updateLightboxCounter();
    }

    // Função para abrir lightbox a partir de uma imagem
    openFromImage(imageElement) {
        const gallery = imageElement.closest('.car-gallery');
        if (!gallery) return;
        
        const images = Array.from(gallery.querySelectorAll('.carousel-image, .gallery-item img'));
        const startIndex = images.indexOf(imageElement);
        
        this.openLightbox(images, startIndex);
    }

    // Função para zoom nas imagens
    initZoom() {
        const zoomImages = document.querySelectorAll('.zoom-image');
        
        zoomImages.forEach(img => {
            img.addEventListener('mouseenter', (e) => {
                const rect = img.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const xPercent = (x / rect.width) * 100;
                const yPercent = (y / rect.height) * 100;
                
                img.style.transformOrigin = `${xPercent}% ${yPercent}%`;
            });
        });
    }

    // Função para lazy loading das imagens
    initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Função para preload das imagens
    preloadImages(images) {
        images.forEach(src => {
            const img = new Image();
            img.src = src;
        });
    }

    // Função para download de imagem
    downloadImage(imageSrc, filename) {
        const link = document.createElement('a');
        link.href = imageSrc;
        link.download = filename || 'carro-formula-sul.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Função para compartilhamento
    shareImage(imageSrc, title = 'Carro Formula Sul') {
        if (navigator.share) {
            navigator.share({
                title: title,
                url: imageSrc
            });
        } else {
            // Fallback para copiar URL
            navigator.clipboard.writeText(imageSrc).then(() => {
                this.showNotification('URL da imagem copiada!');
            });
        }
    }

    // Função para mostrar notificações
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Estilos da notificação
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 20px',
            borderRadius: '8px',
            color: 'white',
            zIndex: '10000',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease'
        });
        
        // Cores baseadas no tipo
        const colors = {
            info: '#3b82f6',
            success: '#10b981',
            warning: '#f59e0b',
            error: '#ef4444'
        };
        
        notification.style.backgroundColor = colors[type] || colors.info;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remover após 3 segundos
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Inicializar galeria quando o DOM estiver pronto
// (protegido para evitar múltiplas instâncias)
document.addEventListener('DOMContentLoaded', () => {
    if (!window.carGallery) {
        window.carGallery = new CarGallery();
        window.carGallery.initZoom();
        window.carGallery.initLazyLoading();
        document.addEventListener('click', (e) => {
            if (e.target.matches('.carousel-image, .gallery-item img')) {
                e.preventDefault();
                window.carGallery.openFromImage(e.target);
            }
        });
    }
});

// Função global para abrir lightbox
window.openCarGallery = (images, startIndex = 0) => {
    if (window.carGallery) {
        window.carGallery.openLightbox(images, startIndex);
    }
};

// Função global para download
window.downloadCarImage = (imageSrc, filename) => {
    if (window.carGallery) {
        window.carGallery.downloadImage(imageSrc, filename);
    }
};

// Função global para compartilhamento
window.shareCarImage = (imageSrc, title) => {
    if (window.carGallery) {
        window.carGallery.shareImage(imageSrc, title);
    }
}; 