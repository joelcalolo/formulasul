// Sistema de Autenticação Modal

/* Sistema de Autenticação Modal - Formula Sul */

class AuthModal {
    constructor() {
        this.currentTab = 'login';
        this.isSubmitting = false;
        this.phoneFormats = {
            'AO': { // Angola
                format: '+244 999 999 999',
                regex: /^\+244\s?\d{3}\s?\d{3}\s?\d{3}$/,
                placeholder: '+244 999 999 999',
                mask: '+244 000 000 000'
            },
            'PT': { // Portugal
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            },
            'ZA': { // África do Sul
                format: '(999) 999-9999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{4})$/,
                placeholder: '(999) 999-9999',
                mask: '000 000-0000'
            },
            'NA': { // Namíbia
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            },
            'BR': { // Brasil
                format: '(99) 99999-9999',
                regex: /^(\d{2})\s?(\d{4,5})-?(\d{4})$/,
                placeholder: '(99) 99999-9999',
                mask: '00 00000-0000'
            },
            'US': { // Estados Unidos
                format: '(999) 999-9999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{4})$/,
                placeholder: '(999) 999-9999',
                mask: '000 000-0000'
            },
            'CA': { // Canadá
                format: '(999) 999-9999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{4})$/,
                placeholder: '(999) 999-9999',
                mask: '000 000-0000'
            },
            'MZ': { // Moçambique
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            },
            'CV': { // Cabo Verde
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            },
            'ST': { // São Tomé e Príncipe
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            },
            'GW': { // Guiné-Bissau
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            },
            'GQ': { // Guiné Equatorial
                format: '(999) 999-999',
                regex: /^(\d{3})\s?(\d{3})-?(\d{3})$/,
                placeholder: '(999) 999-999',
                mask: '000 000-000'
            }
        };
        this.currentCountry = 'AO'; // Padrão Angola
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupFormValidation();
        this.updatePhoneFormat(); // Inicializar com o formato padrão
    }

    setupEventListeners() {
        const loginForm = document.getElementById('login-form-element');
        const registerForm = document.getElementById('register-form-element');
        const countrySelect = document.getElementById('register-country');

        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }

        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegister(e));
        }

        // Adicionar listener para mudança de país
        if (countrySelect) {
            countrySelect.addEventListener('change', (e) => {
                this.currentCountry = e.target.value;
                this.updatePhoneFormat();
            });
        }

        this.setupRealTimeValidation();
    }

    setupFormValidation() {
        const passwordInput = document.getElementById('register-password');
        const confirmInput = document.getElementById('register-confirm');
        if (passwordInput && confirmInput) confirmInput.addEventListener('input', () => this.validatePasswordMatch());
    }

    setupRealTimeValidation() {
        const fields = ['register-name', 'register-email', 'register-phone', 'register-password'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => this.validateField(fieldId));
                
                // Adicionar formatação em tempo real para o telefone
                if (fieldId === 'register-phone') {
                    field.addEventListener('input', (e) => {
                        const formatted = this.formatPhone(e.target.value);
                        if (formatted !== e.target.value) {
                            e.target.value = formatted;
                        }
                    });
                }
            }
        });
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`);
        if (!field || !errorElement) return;
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        switch (fieldId) {
            case 'register-name':
                if (value.length < 3) { isValid = false; errorMessage = 'O nome deve ter pelo menos 3 caracteres.'; }
                break;
            case 'register-email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) { isValid = false; errorMessage = 'Digite um e-mail válido.'; }
                break;
            case 'register-phone':
                errorMessage = this.validatePhone(value);
                isValid = !errorMessage;
                break;
            case 'register-password':
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!passwordRegex.test(value)) { isValid = false; errorMessage = 'A senha deve conter pelo menos 8 caracteres, incluindo maiúsculas, minúsculas, números e símbolos.'; }
                break;
        }
        this.showFieldError(fieldId, isValid ? '' : errorMessage);
        return isValid;
    }

    validatePasswordMatch() {
        const password = document.getElementById('register-password');
        const confirm = document.getElementById('register-confirm');
        const errorElement = document.getElementById('register-confirm-error');
        if (!password || !confirm || !errorElement) return;
        const isValid = password.value === confirm.value;
        const errorMessage = isValid ? '' : 'As senhas não coincidem.';
        this.showFieldError('register-confirm', errorMessage);
        return isValid;
    }

    showFieldError(fieldId, message) {
        const errorElement = document.getElementById(`${fieldId}-error`);
        const field = document.getElementById(fieldId);
        if (!errorElement || !field) return;
        if (message) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            field.classList.add('border-red-500');
            field.classList.remove('border-gray-300');
        } else {
            errorElement.classList.add('hidden');
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        }
    }

    clearAllErrors() {
        const errorElements = document.querySelectorAll('[id$="-error"]');
        errorElements.forEach(element => { element.classList.add('hidden'); element.textContent = ''; });
        const inputs = document.querySelectorAll('#auth-modal input');
        inputs.forEach(input => { input.classList.remove('border-red-500'); input.classList.add('border-gray-300'); });
    }

    showMessage(type, message) {
        const messagesContainer = document.getElementById('auth-messages');
        if (!messagesContainer) return;
        messagesContainer.innerHTML = `<div class="p-3 rounded-md ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 'bg-red-100 text-red-700 border border-red-400'}"><div class="flex items-center"><i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i><span>${message}</span></div></div>`;
        messagesContainer.classList.remove('hidden');
    }

    hideMessage() {
        const messagesContainer = document.getElementById('auth-messages');
        if (messagesContainer) messagesContainer.classList.add('hidden');
    }

    setLoading(formType, loading) {
        const submitButton = document.getElementById(`${formType}-submit`);
        const submitText = document.getElementById(`${formType}-submit-text`);
        const loadingSpinner = document.getElementById(`${formType}-loading`);
        if (!submitButton || !submitText || !loadingSpinner) return;
        if (loading) {
            submitButton.disabled = true;
            submitText.textContent = formType === 'login' ? 'Entrando...' : 'Cadastrando...';
            loadingSpinner.classList.remove('hidden');
        } else {
            submitButton.disabled = false;
            submitText.textContent = formType === 'login' ? 'Entrar' : 'Cadastrar';
            loadingSpinner.classList.add('hidden');
        }
    }

    async handleLogin(e) {
        e.preventDefault();
        if (this.isSubmitting) return;
        this.isSubmitting = true;
        this.clearAllErrors();
        this.hideMessage();
        this.setLoading('login', true);
        const formData = new FormData(e.target);
        try {
            const response = await fetch(e.target.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (response.ok && data.success) {
                this.showMessage('success', 'Login realizado com sucesso!');
                setTimeout(() => { window.location.href = data.redirect || '/dashboard'; }, 1500);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const fieldId = `login-${field}`;
                        this.showFieldError(fieldId, data.errors[field][0]);
                    });
                } else if (data.message) {
                    this.showMessage('error', data.message);
                } else {
                    this.showMessage('error', 'Erro ao fazer login. Tente novamente.');
                }
            }
        } catch (error) {
            console.error('Erro no login:', error);
            this.showMessage('error', 'Erro de conexão. Tente novamente.');
        } finally {
            this.isSubmitting = false;
            this.setLoading('login', false);
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        if (this.isSubmitting) return;
        const fields = ['register-name', 'register-email', 'register-phone', 'register-password'];
        let isValid = true;
        fields.forEach(fieldId => { if (!this.validateField(fieldId)) isValid = false; });
        if (!this.validatePasswordMatch()) isValid = false;
        if (!isValid) { this.showMessage('error', 'Por favor, corrija os erros antes de continuar.'); return; }
        this.isSubmitting = true;
        this.clearAllErrors();
        this.hideMessage();
        this.setLoading('register', true);
        const formData = new FormData(e.target);
        try {
            const response = await fetch(e.target.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (response.ok && data.success) {
                this.showMessage('success', 'Conta criada com sucesso!');
                setTimeout(() => { window.location.href = data.redirect || '/dashboard'; }, 1500);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const fieldId = `register-${field}`;
                        this.showFieldError(fieldId, data.errors[field][0]);
                    });
                } else if (data.message) {
                    this.showMessage('error', data.message);
                } else {
                    this.showMessage('error', 'Erro ao criar conta. Tente novamente.');
                }
            }
        } catch (error) {
            console.error('Erro no registro:', error);
            this.showMessage('error', 'Erro de conexão. Tente novamente.');
        } finally {
            this.isSubmitting = false;
            this.setLoading('register', false);
        }
    }

    switchTab(tab) {
        this.currentTab = tab;
        this.clearAllErrors();
        this.hideMessage();
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        if (tab === 'login') {
            loginTab.classList.add('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
            registerTab.classList.remove('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        } else {
            registerTab.classList.add('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
            loginTab.classList.remove('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }
    }

    open() {
        const modal = document.getElementById('auth-modal');
        if (modal) {
            modal.classList.remove('hidden');
            this.clearAllErrors();
            this.hideMessage();
            this.switchTab('login');
        }
    }

    close() {
        const modal = document.getElementById('auth-modal');
        if (modal) {
            modal.classList.add('hidden');
            this.clearAllErrors();
            this.hideMessage();
        }
    }

    updatePhoneFormat() {
        const phoneInput = document.getElementById('register-phone');
        const formatHint = document.getElementById('phone-format-hint');
        
        if (!phoneInput || !formatHint) return;

        const countryFormat = this.phoneFormats[this.currentCountry];
        if (countryFormat) {
            phoneInput.placeholder = countryFormat.placeholder;
            formatHint.textContent = `Formato: ${countryFormat.format}`;
            
            // Limpar o campo se o formato mudou
            if (phoneInput.value) {
                phoneInput.value = '';
            }
        }
    }

    validatePhone(phone) {
        if (!phone) return 'O telefone é obrigatório';
        
        // Remover todos os caracteres não numéricos
        const cleanPhone = phone.replace(/\D/g, '');
        
        // Obter o formato do país atual
        const countryFormat = this.phoneFormats[this.currentCountry];
        if (!countryFormat) return 'País não suportado';
        
        // Validar comprimento mínimo (geralmente 9-10 dígitos)
        if (cleanPhone.length < 9) return 'Telefone muito curto';
        if (cleanPhone.length > 15) return 'Telefone muito longo';
        
        // Validar com regex específico do país
        if (!countryFormat.regex.test(phone)) {
            return `Formato inválido. Use: ${countryFormat.format}`;
        }
        
        return null;
    }

    formatPhone(phone) {
        if (!phone) return '';
        
        // Remover todos os caracteres não numéricos
        const cleanPhone = phone.replace(/\D/g, '');
        
        // Obter o formato do país atual
        const countryFormat = this.phoneFormats[this.currentCountry];
        if (!countryFormat) return phone;
        
        // Aplicar formatação específica do país
        switch (this.currentCountry) {
            case 'AO': // Angola: +244 999 999 999
                if (cleanPhone.length >= 9) {
                    // Se não tem +244, adicionar
                    if (!phone.startsWith('+244')) {
                        return `+244 ${cleanPhone.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3')}`;
                    }
                    // Se já tem +244, apenas formatar
                    return phone.replace(/^\+244\s?(\d{3})(\d{3})(\d{3})$/, '+244 $1 $2 $3');
                }
                break;
            case 'BR': // Brasil: (99) 99999-9999
                if (cleanPhone.length >= 10) {
                    return cleanPhone.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
                }
                break;
            case 'ZA': // África do Sul: (999) 999-9999
            case 'US': // Estados Unidos: (999) 999-9999
            case 'CA': // Canadá: (999) 999-9999
                if (cleanPhone.length >= 10) {
                    return cleanPhone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                }
                break;
            default: // Outros países: (999) 999-999
                if (cleanPhone.length >= 9) {
                    return cleanPhone.replace(/(\d{3})(\d{3})(\d{3})/, '($1) $2-$3');
                }
                break;
        }
        
        // Se não conseguir formatar, retornar os números limpos
        return cleanPhone;
    }
}

window.authModal = new AuthModal();
window.openModal = () => window.authModal.open();
window.closeModal = () => window.authModal.close();
window.switchTab = (tab) => window.authModal.switchTab(tab);
