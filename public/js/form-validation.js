// Formulário de Validação para Formula Sul
class FormValidator {
    constructor() {
        // Aguardar o DOM estar carregado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        this.initializeValidation();
        this.setupDateValidation();
        this.setupRealTimeValidation();
    }

    initializeValidation() {
        // Configurar validação para todos os formulários
        const forms = document.querySelectorAll('form[novalidate]');
        forms.forEach(form => {
            if (form) {
                form.addEventListener('submit', (e) => this.handleSubmit(e));
            }
        });
    }

    setupDateValidation() {
        // Configurar data mínima para hoje
        const today = new Date().toISOString().split('T')[0];
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            if (input) {
                input.min = today;
            }
        });

        // Validação específica para datas de aluguel
        const dataInicio = document.querySelector('input[name="data_inicio"]');
        const dataFim = document.querySelector('input[name="data_fim"]');

        if (dataInicio && dataFim) {
            dataInicio.addEventListener('change', () => this.validateDateRange(dataInicio, dataFim));
            dataFim.addEventListener('change', () => this.validateDateRange(dataInicio, dataFim));
        }
    }

    setupRealTimeValidation() {
        // Validação em tempo real para todos os campos
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldError(input));
            }
        });
    }

    validateField(field) {
        if (!field || !field.value) return true;
        
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';

        // Validações específicas por campo
        switch (fieldName) {
            case 'carro_principal_id':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, selecione um carro';
                }
                break;

            case 'data_inicio':
            case 'data_fim':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, selecione uma data';
                } else if (new Date(value) < new Date().setHours(0, 0, 0, 0)) {
                    isValid = false;
                    errorMessage = 'A data não pode ser no passado';
                }
                break;

            case 'local_entrega':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe o local de entrega';
                } else if (value.length < 3) {
                    isValid = false;
                    errorMessage = 'O local deve ter pelo menos 3 caracteres';
                }
                break;

            case 'origem':
            case 'destino':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe a origem/destino';
                } else if (value.length < 3) {
                    isValid = false;
                    errorMessage = 'Deve ter pelo menos 3 caracteres';
                }
                break;

            case 'data_hora':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, selecione data e hora';
                } else if (new Date(value) < new Date()) {
                    isValid = false;
                    errorMessage = 'A data e hora não pode ser no passado';
                }
                break;

            case 'tipo':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, selecione o tipo de transfer';
                }
                break;

            case 'destino_passeio':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe o destino do passeio';
                }
                break;

            case 'data_passeio':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, selecione uma data';
                } else if (new Date(value) < new Date().setHours(0, 0, 0, 0)) {
                    isValid = false;
                    errorMessage = 'A data não pode ser no passado';
                }
                break;

            case 'pessoas':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe o número de pessoas';
                } else if (value < 1 || value > 20) {
                    isValid = false;
                    errorMessage = 'O número de pessoas deve ser entre 1 e 20';
                }
                break;

            case 'name':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe seu nome';
                } else if (value.length < 2) {
                    isValid = false;
                    errorMessage = 'O nome deve ter pelo menos 2 caracteres';
                }
                break;

            case 'email':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe seu email';
                } else if (!this.isValidEmail(value)) {
                    isValid = false;
                    errorMessage = 'Por favor, informe um email válido';
                }
                break;

            case 'phone':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Por favor, informe seu telefone';
                } else if (!this.isValidPhone(value)) {
                    isValid = false;
                    errorMessage = 'Por favor, informe um telefone válido';
                }
                break;
        }

        this.showFieldValidation(field, isValid, errorMessage);
        return isValid;
    }

    validateDateRange(dataInicio, dataFim) {
        if (!dataInicio || !dataFim) return true;
        
        const inicio = new Date(dataInicio.value);
        const fim = new Date(dataFim.value);
        let isValid = true;
        let errorMessage = '';

        if (dataInicio.value && dataFim.value) {
            if (fim <= inicio) {
                isValid = false;
                errorMessage = 'A data de fim deve ser posterior à data de início';
                this.showFieldValidation(dataFim, false, errorMessage);
            } else {
                this.showFieldValidation(dataFim, true, '');
            }
        }

        return isValid;
    }

    isValidEmail(email) {
        if (!email) return false;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        if (!phone) return false;
        // Aceita formatos: +244 953 429 189, 953429189, 953 429 189
        const phoneRegex = /^(\+244\s?)?(\d{3}\s?\d{3}\s?\d{3}|\d{9})$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }

    showFieldValidation(field, isValid, message) {
        if (!field || !field.classList) return;
        
        const errorElement = field.parentNode ? field.parentNode.querySelector('.error-message') : null;
        
        if (isValid) {
            field.classList.remove('border-red-500');
            field.classList.add('border-green-500');
            if (errorElement && errorElement.classList) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
        } else {
            field.classList.remove('border-green-500');
            field.classList.add('border-red-500');
            if (errorElement && errorElement.classList) {
                errorElement.classList.remove('hidden');
                errorElement.textContent = message;
            }
        }
    }

    clearFieldError(field) {
        if (!field || !field.classList) return;
        
        field.classList.remove('border-red-500', 'border-green-500');
        const errorElement = field.parentNode ? field.parentNode.querySelector('.error-message') : null;
        if (errorElement && errorElement.classList) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    }

    validateForm(form) {
        if (!form) return false;
        
        const fields = form.querySelectorAll('.form-input[required]');
        let isValid = true;

        fields.forEach(field => {
            if (field && !this.validateField(field)) {
                isValid = false;
            }
        });

        // Validação específica para datas de aluguel
        if (form.id === 'form-aluguel') {
            const dataInicio = form.querySelector('input[name="data_inicio"]');
            const dataFim = form.querySelector('input[name="data_fim"]');
            if (dataInicio && dataFim && !this.validateDateRange(dataInicio, dataFim)) {
                isValid = false;
            }
        }

        return isValid;
    }

    async handleSubmit(e) {
        e.preventDefault();
        const form = e.target;
        
        if (!form) return false;
        
        if (!this.validateForm(form)) {
            this.showToast('Por favor, corrija os erros no formulário', 'error');
            return false;
        }

        // Mostrar loading
        this.showLoading(form, true);

        try {
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const headers = {};
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: headers
            });

            const result = await response.json();

            if (response.ok) {
                this.showToast(result.message || 'Solicitação enviada com sucesso!', 'success');
                form.reset();
                this.clearAllErrors(form);
            } else {
                this.showToast(result.message || 'Erro ao enviar solicitação', 'error');
                if (result.errors) {
                    this.showServerErrors(form, result.errors);
                }
            }
        } catch (error) {
            console.error('Erro:', error);
            this.showToast('Erro de conexão. Tente novamente.', 'error');
        } finally {
            this.showLoading(form, false);
        }
    }

    showServerErrors(form, errors) {
        if (!form) return;
        
        Object.keys(errors).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                this.showFieldValidation(field, false, errors[fieldName][0]);
            }
        });
    }

    clearAllErrors(form) {
        if (!form) return;
        
        const fields = form.querySelectorAll('.form-input');
        fields.forEach(field => {
            if (field) {
                this.clearFieldError(field);
            }
        });
    }

    showLoading(form, isLoading) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;
        
        const submitText = submitBtn.querySelector('.submit-text');
        const loadingText = submitBtn.querySelector('.loading-text');
        
        if (!submitText || !loadingText) return;

        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            
            // Adicionar spinner se não existir
            if (!submitBtn.querySelector('.loading-spinner')) {
                const spinner = document.createElement('div');
                spinner.className = 'loading-spinner animate-spin rounded-full h-4 w-4 border-b-2 border-white ml-2';
                submitBtn.appendChild(spinner);
            }
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            submitText.classList.remove('hidden');
            loadingText.classList.add('hidden');
            
            // Remover spinner
            const spinner = submitBtn.querySelector('.loading-spinner');
            if (spinner) {
                spinner.remove();
            }
        }
    }

    showToast(message, type = 'info') {
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
}

// Função para alternar entre abas
function showTab(tab, event) {
    // Verificar se estamos na página correta (página inicial com formulários)
    const tabForms = document.querySelectorAll('.tab-form');
    const tabButtons = document.querySelectorAll('.tab-btn');
    
    // Se não há formulários de aba OU botões de aba, não executar esta função
    if (tabForms.length === 0 || tabButtons.length === 0) {
        // Se não há formulários de aba, não executar esta função
        return;
    }
    
    // Esconder todos os formulários
    tabForms.forEach(f => {
        if (f && f.classList) {
            f.classList.add('hidden');
        }
    });
    
    // Mostrar o formulário selecionado
    const selectedForm = document.getElementById('form-' + tab);
    if (selectedForm && selectedForm.classList) {
        selectedForm.classList.remove('hidden');
    }
    
    // Atualizar botões das abas
    tabButtons.forEach(b => {
        if (b && b.classList) {
            b.classList.remove('bg-[var(--primary)]', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700');
        }
    });
    
    // Ativar o botão clicado (se o evento foi fornecido)
    if (event && event.target && event.target.classList) {
        event.target.classList.add('bg-[var(--primary)]', 'text-white');
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
    }
} 