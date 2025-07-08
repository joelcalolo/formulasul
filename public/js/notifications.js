/**
 * Sistema de Notificações - Formula Sul
 * Funcionalidades: Toast notifications, Modais de confirmação, Alertas
 */

class NotificationSystem {
    constructor() {
        this.toastContainer = document.getElementById('toast-container');
        this.confirmationModal = null;
        this.toastQueue = [];
        this.isProcessing = false;
    }

    showToast(type, title, message, duration = 5000) {
        const toast = this.createToast(type, title, message);
        this.toastQueue.push({ toast, duration });
        if (!this.isProcessing) {
            this.processQueue();
        }
    }

    createToast(type, title, message) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="${icons[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress"></div>
        `;

        return toast;
    }

    processQueue() {
        if (this.toastQueue.length === 0) {
            this.isProcessing = false;
            return;
        }

        this.isProcessing = true;
        const { toast, duration } = this.toastQueue.shift();
        this.toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        const progress = toast.querySelector('.toast-progress');
        if (progress) {
            progress.style.width = '100%';
            progress.style.transition = `width ${duration}ms linear`;
        }

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
                this.processQueue();
            }, 300);
        }, duration);
    }

    showConfirmation(options) {
        return new Promise((resolve) => {
            this.createConfirmationModal(options, resolve);
        });
    }

    createConfirmationModal(options, resolve) {
        const {
            title = 'Confirmar Ação',
            message = 'Tem certeza que deseja realizar esta ação?',
            icon = 'fas fa-question-circle',
            confirmText = 'Confirmar',
            cancelText = 'Cancelar',
            type = 'danger',
            showCancel = true
        } = options;

        if (this.confirmationModal) {
            this.confirmationModal.remove();
        }

        this.confirmationModal = document.createElement('div');
        this.confirmationModal.className = 'confirmation-modal';
        
        const iconColors = {
            danger: 'text-red-500',
            success: 'text-green-500',
            warning: 'text-yellow-500'
        };

        const buttonClasses = {
            danger: 'confirmation-btn confirm',
            success: 'confirmation-btn success',
            warning: 'confirmation-btn confirm'
        };

        this.confirmationModal.innerHTML = `
            <div class="confirmation-content">
                <div class="confirmation-icon ${iconColors[type]}">
                    <i class="${icon}"></i>
                </div>
                <div class="confirmation-title">${title}</div>
                <div class="confirmation-message">${message}</div>
                <div class="confirmation-buttons">
                    ${showCancel ? `<button class="confirmation-btn cancel" onclick="this.closest('.confirmation-modal').remove(); window.notificationSystem.resolveConfirmation(false);">${cancelText}</button>` : ''}
                    <button class="${buttonClasses[type]}" onclick="this.closest('.confirmation-modal').remove(); window.notificationSystem.resolveConfirmation(true);">${confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(this.confirmationModal);

        setTimeout(() => {
            this.confirmationModal.classList.add('show');
        }, 100);

        this.confirmationResolver = resolve;
    }

    resolveConfirmation(result) {
        if (this.confirmationResolver) {
            this.confirmationResolver(result);
            this.confirmationResolver = null;
        }
    }

    showLoading(message = 'Carregando...') {
        const loading = document.createElement('div');
        loading.className = 'confirmation-modal show';
        loading.id = 'loading-modal';
        
        loading.innerHTML = `
            <div class="confirmation-content">
                <div class="confirmation-icon text-blue-500">
                    <div class="loading-spinner"></div>
                </div>
                <div class="confirmation-title">${message}</div>
            </div>
        `;

        document.body.appendChild(loading);
    }

    hideLoading() {
        const loading = document.getElementById('loading-modal');
        if (loading) {
            loading.remove();
        }
    }

    success(title, message, duration = 5000) {
        this.showToast('success', title, message, duration);
    }

    error(title, message, duration = 7000) {
        this.showToast('error', title, message, duration);
    }

    warning(title, message, duration = 6000) {
        this.showToast('warning', title, message, duration);
    }

    info(title, message, duration = 5000) {
        this.showToast('info', title, message, duration);
    }

    confirmDelete(itemName) {
        return this.showConfirmation({
            title: 'Confirmar Exclusão',
            message: `Tem certeza que deseja excluir "${itemName}"? Esta ação não pode ser desfeita.`,
            icon: 'fas fa-trash-alt',
            confirmText: 'Excluir',
            cancelText: 'Cancelar',
            type: 'danger'
        });
    }

    confirmAction(title, message, type = 'danger') {
        return this.showConfirmation({
            title,
            message,
            type
        });
    }
}

// Inicializar sistema de notificações
window.notificationSystem = new NotificationSystem();

// Funções globais
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `px-4 py-3 rounded shadow mb-2 text-white font-semibold animate-fade-in-up ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    toast.innerText = message;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3500);
};

window.showSuccess = (title, message, duration) => {
    window.notificationSystem.success(title, message, duration);
};

window.showError = (title, message, duration) => {
    window.notificationSystem.error(title, message, duration);
};

window.showWarning = (title, message, duration) => {
    window.notificationSystem.warning(title, message, duration);
};

window.showInfo = (title, message, duration) => {
    window.notificationSystem.info(title, message, duration);
};

window.showConfirmation = (options) => {
    return window.notificationSystem.showConfirmation(options);
};

window.confirmDelete = (itemName) => {
    return window.notificationSystem.confirmDelete(itemName);
};

window.showLoading = (message) => {
    window.notificationSystem.showLoading(message);
};

window.hideLoading = () => {
    window.notificationSystem.hideLoading();
};
