<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <title>@yield('title', 'Formula Sul')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('public/images/favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/form-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/car-gallery.css') }}">
    <style>
        :root {
            --primary:rgb(148, 148, 234);
            --secondary: #232323;
            --accent: #E8E8FF;
            --background: #F9FAFB;
            --text: #232323;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .accordion-content {
            display: none;
        }
        .accordion-content.active {
            display: block;
        }
        html {
            scroll-behavior: smooth;
        }
        .hero {
            height: 150px;
            background: linear-gradient(rgba(35, 35, 35, 0.7), rgba(35, 35, 35, 0.7)), url('https://images.unsplash.com/photo-1504215680853-026ed2a45def?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
<!-- Navbar moderna com login e idioma -->
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Fórmula Sul" class="h-8 w-auto">
            </a>

            <!-- Menu principal -->
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-[var(--primary)] font-medium">Home</a>
                <a href="{{ route('cars.index') }}" class="text-gray-700 hover:text-[var(--primary)] font-medium">Catálogo</a>
                <a href="{{ route('passeios.index') }}" class="text-gray-700 hover:text-[var(--primary)] font-medium">Passeios</a>
                <a href="{{ route('suporte') }}" class="text-gray-700 hover:text-[var(--primary)] font-medium">Suporte</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-[var(--primary)] font-medium">Contacto</a>
            </nav>

            <!-- Ações: Login + Idioma -->
            <div class="flex items-center space-x-4">


            <!-- Social Media Icons -->
    <div class="hidden md:flex items-center space-x-3 ml-4">
        <a href="https://facebook.com/formulasul" target="_blank" class="text-blue-600 hover:text-blue-800 text-lg">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com/formulasul" target="_blank" class="text-pink-500 hover:text-pink-700 text-lg">
            <i class="fab fa-instagram"></i>
        </a>
    </div>

                <!-- Seletor de idioma -->
                <select class="text-sm bg-transparent border border-gray-300 rounded p-1 focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" onchange="switchLanguage(this.value)">
                    <option value="pt">🇧🇷 PT</option>
                    <option value="en">🇺🇸 EN</option>
                </select>
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-[var(--primary)]">
                        <div class="w-8 h-8 rounded-full bg-[var(--primary)] flex items-center justify-center text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i> Meu Perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <button onclick="openModal()" class="px-4 py-1 bg-[var(--primary)] text-white rounded hover:bg-[var(--primary)]/90 text-sm">Login</button>
            @endauth
            </div>
        </div>
    </div>
</header>

@if(session('success'))
    <div class="max-w-2xl mx-auto mt-6">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="max-w-2xl mx-auto mt-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
@endif

    <!-- Auth Modal -->
    <div id="auth-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur flex items-center justify-center z-50">
        <div class="modal-content bg-white rounded-lg w-full max-w-md mx-4 p-6 relative">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="flex border-b">
                <button id="login-tab" class="flex-1 py-4 font-medium text-[var(--primary)] border-b-2 border-[var(--primary)]" onclick="switchTab('login')">Login</button>
                <button id="register-tab" class="flex-1 py-4 font-medium text-gray-500" onclick="switchTab('register')">Cadastro</button>
            </div>
            
            <!-- Container para mensagens de erro/sucesso -->
            <div id="auth-messages" class="p-4 hidden">
                <!-- Mensagens serão inseridas aqui via JavaScript -->
            </div>
            
            <div id="login-form" class="p-6">
                <h2 class="text-2xl font-bold text-[var(--primary)] mb-6">Login</h2>
                <form id="login-form-element" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-email">E-mail</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="login-email" type="email" name="email" placeholder="Seu E-mail" required>
                        <div id="login-email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-password">Senha</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="login-password" type="password" name="password" placeholder="Sua Senha" required>
                        <div id="login-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        <div class="mt-2 text-right">
                            <a href="{{ route('password.request') }}" class="text-sm text-[var(--primary)] hover:text-[var(--primary)]/90">
                                Esqueceu sua senha?
                            </a>
                        </div>
                    </div>
                    <button id="login-submit" class="w-full bg-[var(--primary)] text-white py-2 rounded hover:bg-[var(--primary)]/90 flex items-center justify-center" type="submit">
                        <span id="login-submit-text">Entrar</span>
                        <div id="login-loading" class="loading-spinner ml-2 hidden"></div>
                    </button>
                    <p class="mt-4 text-center text-sm text-gray-600">Não tem conta? <a href="#" class="text-[var(--primary)] font-medium" onclick="switchTab('register')">Cadastre-se</a></p>
                </form>
            </div>
            
            <div id="register-form" class="hidden p-6">
                <h2 class="text-2xl font-bold text-[var(--primary)] mb-6">Criar Conta</h2>
                <form id="register-form-element" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-name">Nome Completo</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="register-name" type="text" name="name" placeholder="Seu Nome" required>
                        <div id="register-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-email">E-mail</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="register-email" type="email" name="email" placeholder="Seu E-mail" required>
                        <div id="register-email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-country">País</label>
                        <select class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                                id="register-country" name="country" required>
                            <option value="">Selecione um país</option>
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
                        <div id="register-country-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-phone">Telefone</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="register-phone" type="tel" name="phone" placeholder="Seu telefone" required>
                        <div id="register-phone-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        <div class="mt-1 text-xs text-gray-500" id="phone-format-hint">
                            Formato: (999) 999-999
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-password">Senha</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="register-password" type="password" name="password" placeholder="Crie uma Senha" required>
                        <div id="register-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        <div class="mt-1 text-xs text-gray-500">
                            A senha deve conter pelo menos 8 caracteres, incluindo maiúsculas, minúsculas, números e símbolos.
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-confirm">Confirme a Senha</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" 
                               id="register-confirm" type="password" name="password_confirmation" placeholder="Confirme sua Senha" required>
                        <div id="register-confirm-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <button id="register-submit" class="w-full bg-[var(--primary)] text-white py-2 rounded hover:bg-[var(--primary)]/90 flex items-center justify-center" type="submit">
                        <span id="register-submit-text">Cadastrar</span>
                        <div id="register-loading" class="loading-spinner ml-2 hidden"></div>
                    </button>
                    <p class="mt-4 text-center text-sm text-gray-600">Já tem conta? <a href="#" class="text-[var(--primary)] font-medium" onclick="switchTab('login')">Faça login</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
    @yield('content')
    </main>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/+244953429189" target="_blank" class="fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>


    <!-- Footer -->
    <footer class="bg-white border-t py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Formula Sul</h3>
                    <img src="{{ asset('images/logo.png') }}" alt="Fórmula Sul" class="h-12 w-auto mt-2">
                    <p class="mt-2 text-gray-600">A sua escolha para mobilidade com qualidade.</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Links Rápidos</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-[var(--primary)]">Home</a></li>
                        <li><a href="{{ route('cars.index') }}" class="text-gray-600 hover:text-[var(--primary)]">Catálogo</a></li>
                        <li><a href="{{ route('passeios.index') }}" class="text-gray-600 hover:text-[var(--primary)]">Passeios</a></li>
                        <li><a href="{{ route('suporte') }}" class="text-gray-600 hover:text-[var(--primary)]">Suporte</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-600 hover:text-[var(--primary)]">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Contacto</h3>
                    <p class="mt-2 text-gray-600">Email: <a href="mailto:contato@formulasul.com" class="text-[var(--primary)]">contato@formulasul.com</a></p>
                    <p class="mt-2 text-gray-600">Telefone: +244 953 42 9189</p>
                    <p class="mt-2 text-gray-600">WhatsApp: <a href="https://wa.me/+244953429189" class="text-[var(--primary)]">+244 953 42 9189</a></p>
                </div>
            </div>
            <p class="mt-8 text-center text-sm text-gray-600">&copy; {{ date('Y') }} Formula Sul. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/auth-modal.js') }}"></script>
    <script src="{{ asset('js/car-gallery.js') }}"></script>
    <script src="{{ asset('js/form-validation.js') }}"></script>
    @stack('scripts')
    <script>
        // Auth Modal Functions
        function openModal() {
            document.getElementById('auth-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('auth-modal').classList.add('hidden');
        }

        function switchTab(tab) {
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

        // Language Switch
        function switchLanguage(lang) {
            console.log('Switching to:', lang);
        }
    </script>
</body>
</html>