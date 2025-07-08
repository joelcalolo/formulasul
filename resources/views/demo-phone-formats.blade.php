@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-center text-[var(--primary)] mb-8">
                Demonstração - Formatos de Telefone por País
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Formulário de Teste -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4">Teste de Formato</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="test-country">País</label>
                            <select class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                                    id="test-country" name="country">
                                <option value="AO" selected>🇦🇴 Angola</option>
                                <option value="PT">🇵🇹 Portugal</option>
                                <option value="ZA">🇿🇦 África do Sul</option>
                                <option value="NA">🇳🇦 Namíbia</option>
                                <option value="BR">🇧🇷 Brasil</option>
                                <option value="US">🇺🇸 Estados Unidos</option>
                                <option value="CA">🇨🇦 Canadá</option>
                                <option value="MZ">🇲🇿 Moçambique</option>
                                <option value="CV">🇨🇻 Cabo Verde</option>
                                <option value="ST">🇸🇹 São Tomé e Príncipe</option>
                                <option value="GW">🇬🇼 Guiné-Bissau</option>
                                <option value="GQ">🇬🇶 Guiné Equatorial</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="test-phone">Telefone</label>
                            <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                                   id="test-phone" type="tel" name="phone" placeholder="Digite o telefone">
                            <div class="mt-1 text-xs text-gray-500" id="test-format-hint">
                                Formato: (999) 999-999
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Resultado da Validação</label>
                            <div id="validation-result" class="p-3 rounded text-sm">
                                Digite um telefone para ver a validação
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informações dos Formatos -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4">Formatos Suportados</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇦🇴 Angola</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">+244 999 999 999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇵🇹 Portugal</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇿🇦 África do Sul</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-9999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇳🇦 Namíbia</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇧🇷 Brasil</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(99) 99999-9999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇺🇸 Estados Unidos</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-9999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇨🇦 Canadá</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-9999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇲🇿 Moçambique</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇨🇻 Cabo Verde</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇸🇹 São Tomé</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇬🇼 Guiné-Bissau</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-white rounded">
                            <span>🇬🇶 Guiné Equatorial</span>
                            <code class="bg-gray-200 px-2 py-1 rounded">(999) 999-999</code>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="bg-[var(--primary)] text-white px-6 py-2 rounded hover:bg-[var(--primary)]/90">
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Script para demonstração dos formatos de telefone
class PhoneFormatDemo {
    constructor() {
        this.phoneFormats = {
            'AO': { format: '+244 999 999 999', placeholder: '+244 999 999 999' },
            'PT': { format: '(999) 999-999', placeholder: '(999) 999-999' },
            'ZA': { format: '(999) 999-9999', placeholder: '(999) 999-9999' },
            'NA': { format: '(999) 999-999', placeholder: '(999) 999-999' },
            'BR': { format: '(99) 99999-9999', placeholder: '(99) 99999-9999' },
            'US': { format: '(999) 999-9999', placeholder: '(999) 999-9999' },
            'CA': { format: '(999) 999-9999', placeholder: '(999) 999-9999' },
            'MZ': { format: '(999) 999-999', placeholder: '(999) 999-999' },
            'CV': { format: '(999) 999-999', placeholder: '(999) 999-999' },
            'ST': { format: '(999) 999-999', placeholder: '(999) 999-999' },
            'GW': { format: '(999) 999-999', placeholder: '(999) 999-999' },
            'GQ': { format: '(999) 999-999', placeholder: '(999) 999-999' }
        };
        
        this.currentCountry = 'AO';
        this.init();
    }
    
    init() {
        const countrySelect = document.getElementById('test-country');
        const phoneInput = document.getElementById('test-phone');
        
        if (countrySelect) {
            countrySelect.addEventListener('change', (e) => {
                this.currentCountry = e.target.value;
                this.updateFormat();
            });
        }
        
        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => {
                this.formatPhone(e.target);
                this.validatePhone(e.target.value);
            });
        }
        
        this.updateFormat();
    }
    
    updateFormat() {
        const phoneInput = document.getElementById('test-phone');
        const formatHint = document.getElementById('test-format-hint');
        
        if (!phoneInput || !formatHint) return;
        
        const countryFormat = this.phoneFormats[this.currentCountry];
        if (countryFormat) {
            phoneInput.placeholder = countryFormat.placeholder;
            formatHint.textContent = `Formato: ${countryFormat.format}`;
        }
    }
    
    formatPhone(input) {
        let value = input.value.replace(/\D/g, '');
        const countryFormat = this.phoneFormats[this.currentCountry];
        
        if (!countryFormat) return;
        
        switch (this.currentCountry) {
            case 'AO': // Angola: +244 999 999 999
                if (value.length >= 9) {
                    // Se não tem +244, adicionar
                    if (!input.value.startsWith('+244')) {
                        input.value = `+244 ${value.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3')}`;
                    } else {
                        // Se já tem +244, apenas formatar
                        input.value = input.value.replace(/^\+244\s?(\d{3})(\d{3})(\d{3})$/, '+244 $1 $2 $3');
                    }
                } else {
                    input.value = value;
                }
                break;
            case 'BR':
                if (value.length > 11) value = value.slice(0, 11);
                if (value.length >= 10) {
                    input.value = value.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
                } else {
                    input.value = value;
                }
                break;
            case 'ZA':
            case 'US':
            case 'CA':
                if (value.length > 10) value = value.slice(0, 10);
                if (value.length >= 10) {
                    input.value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                } else {
                    input.value = value;
                }
                break;
            default:
                if (value.length > 9) value = value.slice(0, 9);
                if (value.length >= 9) {
                    input.value = value.replace(/(\d{3})(\d{3})(\d{3})/, '($1) $2-$3');
                } else {
                    input.value = value;
                }
                break;
        }
    }
    
    validatePhone(phone) {
        const resultDiv = document.getElementById('validation-result');
        if (!resultDiv) return;
        
        if (!phone) {
            resultDiv.textContent = 'Digite um telefone para ver a validação';
            resultDiv.className = 'p-3 rounded text-sm bg-gray-100';
            return;
        }
        
        const cleanPhone = phone.replace(/\D/g, '');
        const countryFormat = this.phoneFormats[this.currentCountry];
        
        if (!countryFormat) {
            resultDiv.textContent = 'País não suportado';
            resultDiv.className = 'p-3 rounded text-sm bg-red-100 text-red-700';
            return;
        }
        
        let isValid = false;
        let message = '';
        
        switch (this.currentCountry) {
            case 'AO': // Angola: +244 999 999 999
                isValid = /^\+244\s?\d{3}\s?\d{3}\s?\d{3}$/.test(phone);
                message = isValid ? '✅ Telefone válido!' : '❌ Formato inválido. Use: +244 999 999 999';
                break;
            case 'BR':
                isValid = /^\(\d{2}\)\s?\d{4,5}-\d{4}$/.test(phone);
                message = isValid ? '✅ Telefone válido!' : '❌ Formato inválido. Use: (99) 99999-9999';
                break;
            case 'ZA':
            case 'US':
            case 'CA':
                isValid = /^\(\d{3}\)\s?\d{3}-\d{4}$/.test(phone);
                message = isValid ? '✅ Telefone válido!' : '❌ Formato inválido. Use: (999) 999-9999';
                break;
            default:
                isValid = /^\(\d{3}\)\s?\d{3}-\d{3}$/.test(phone);
                message = isValid ? '✅ Telefone válido!' : '❌ Formato inválido. Use: (999) 999-999';
                break;
        }
        
        resultDiv.textContent = message;
        resultDiv.className = `p-3 rounded text-sm ${isValid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new PhoneFormatDemo();
});
</script>
@endsection 