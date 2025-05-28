<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formula Sul - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #1986FF;
            --secondary: #38A169;
            --accent: #FFC107;
            --background: #F9FAFB;
            --text: #333333;
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
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1504215680853-026ed2a45def?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-100">
<!-- Navbar -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center">
            <!-- Logo -->
            <img src="/images/logo.png" alt="Fórmula Sul" class="h-8 mr-2">
            <!-- (Opcional) Nome ao lado do logo -->
            <span class="text-2xl font-bold text-[var(--primary)]">Fórmula Sul</span>
          </a>
        <ul class="hidden md:flex space-x-6">
            <li><a href="#inicio" class="text-gray-700 hover:text-[var(--primary)]">Início</a></li>
            <li><a href="#sobre" class="text-gray-700 hover:text-[var(--primary)]">Sobre Nós</a></li>
            <li><a href="#frota" class="text-gray-700 hover:text-[var(--primary)]">Frota</a></li>
            <li><a href="#servicos" class="text-gray-700 hover:text-[var(--primary)]">Serviços</a></li>
            <li><a href="#contacto" class="text-gray-700 hover:text-[var(--primary)]">Contacto</a></li>
        </ul>
        
        <!-- Botões Login, Idioma e Menu Hamburguer -->
        <div class="flex items-center space-x-4 relative"> <!-- Adicionei relative aqui -->
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-[var(--primary)] font-semibold">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">Logout</button>
                    </form>
                @else
                    <button class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90" onclick="openModal()">Login</button>
                @endauth
            <select class="p-2 rounded-none bg-transparent focus:outline-none focus:ring-0" onchange="switchLanguage(this.value)">
                <option value="pt" data-flag="🇧🇷">🇧🇷 Português</option>
                <option value="en" data-flag="🇺🇸">🇺🇸 English</option>
            </select>
            <!-- Menu Hamburguer (PC e Mobile) -->
            <button class="text-gray-700 focus:outline-none" onclick="toggleMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            
            <!-- Menu Mobile - Agora como dropdown -->
            <div id="mobile-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white shadow-lg rounded-md py-1 z-50">
                <a href="{{ route('cars.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-[var(--background)] font-bold">
                    <svg class="w-5 h-5 mr-2 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2l1-5h13l1 5h2m-3 0v6a2 2 0 01-2 2h-4a2 2 0 01-2-2v-6m-4 0v6a2 2 0 002 2h4a2 2 0 002-2v-6"></path>
                    </svg>
                    Aluguel
                </a>
                <a href="{{ route('transfers.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-[var(--background)] font-bold">
                    <svg class="w-5 h-5 mr-2 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6h-6V8zm-6 0H7a2 2 0 00-2 2v6h6V8zm-6 8v2a1 1 0 001 1h12a1 1 0 001-1v-2"></path>
                    </svg>
                    Transfer
                </a>
                <a href="{{ route('passeios') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-[var(--background)] font-bold">
                    <svg class="w-5 h-5 mr-2 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12A9 9 0 113 12a9 9 0 0118 0zm-6-2l-4 4m0 0l-4-4m4 4V8"></path>
                    </svg>
                    Passeios Turísticos
                </a>
            
                <hr class="my-2 border-t border-gray-200">
            
                <a href="{{ route('suporte') }}" target="_blank" class="block px-4 py-2 text-gray-700 hover:bg-[var(--background)]">Suporte</a>
                <a href="conta.html" class="block px-4 py-2 text-gray-700 hover:bg-[var(--background)]">Conta</a>
            </div>
            
        </div>
    </div>
</nav>

    <!-- Auth Modal -->
    <div id="auth-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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
            <div id="login-form" class="p-6">
                <h2 class="text-2xl font-bold text-[var(--primary)] mb-6">Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-email">E-mail</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="login-email" type="email" name="email" placeholder="Seu E-mail" required>
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-password">Senha</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="login-password" type="password" name="password" placeholder="Sua Senha" required>
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <button class="w-full bg-[var(--primary)] text-white py-2 rounded-md hover:bg-[var(--primary)]/90" type="submit">Entrar</button>
                    <p class="mt-4 text-center text-sm text-gray-600">Não tem conta? <a href="#" class="text-[var(--primary)] font-medium" onclick="switchTab('register')">Cadastre-se</a></p>
                </form>
            </div>
            <div id="register-form" class="hidden p-6">
                <h2 class="text-2xl font-bold text-[var(--primary)] mb-6">Criar Conta</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-name">Nome Completo</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="register-name" type="text" name="name" placeholder="Seu Nome" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-email">E-mail</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="register-email" type="email" name="email" placeholder="Seu E-mail" required>
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-phone">Telefone</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="register-phone" type="tel" name="phone" placeholder="Seu Telefone">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-password">Senha</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="register-password" type="password" name="password" placeholder="Crie uma Senha" required>
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-confirm">Confirme a Senha</label>
                        <input class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[var(--primary)]" id="register-confirm" type="password" name="password_confirmation" placeholder="Confirme sua Senha" required>
                    </div>
                    <button class="w-full bg-[var(--primary)] text-white py-2 rounded-md hover:bg-[var(--primary)]/90" type="submit">Cadastrar</button>
                    <p class="mt-4 text-center text-sm text-gray-600">Já tem conta? <a href="#" class="text-[var(--primary)] font-medium" onclick="switchTab('login')">Faça login</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- WhatsApp Button -->
    <a href="https://wa.me/1234567890" target="_blank" class="fixed bottom-4 right-4 bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.096.548 4.069 1.5 5.794L0 24l6.314-1.5A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22.071c-1.752 0-3.437-.448-4.932-1.291l-.345 1.31 1.31-.345A11.932 11.932 0 011.929 12C1.929 6.546 6.546 1.929 12 1.929s10.071 4.617 10.071 10.071-4.617 10.071-10.071 10.071zm4.828-7.485c-.234-.117-1.38-.682-1.595-.758-.215-.077-.372-.117-.528.117-.156.234-.608.758-.745.915-.137.156-.274.176-.508.059-.234-.117-.99-.366-1.884-1.17-.694-.624-1.164-1.396-1.3-1.63-.137-.234-.015-.36.103-.477.105-.105.234-.273.352-.41.117-.137.156-.234.234-.39.078-.156.039-.293-.02-.41-.058-.117-.528-1.288-.724-1.762-.195-.47-.39-.41-.528-.416-.137-.006-.293-.006-.45-.006-.156 0-.41.058-.625.293-.215.234-.82.8-.82 1.95 0 1.15.82 2.26.936 2.417.117.156 1.617 2.47 3.917 3.464.55.237 1.02.38 1.37.485.575.17 1.1.146 1.513.09.46-.063.89-.325 1.14-.64.25-.315.25-.585.215-.64-.035-.055-.117-.088-.234-.146z"/>
        </svg>
    </a>

    <!-- Footer -->
    <footer class="bg-white shadow mt-12 py-6">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Formula Sul</h3>
                    <p class="mt-2 text-gray-600">A sua escolha para mobilidade com qualidade.</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Links Rápidos</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="{{ route('cars.index') }}" class="text-gray-600 hover:text-gray-900">Aluguel</a></li>
                        <li><a href="{{ route('transfers.index') }}" class="text-gray-600 hover:text-gray-900">Transfer</a></li>
                        <li><a href="{{ route('passeios') }}" class="text-gray-600 hover:text-gray-900">Passeios</a></li>
                        <li><a href="{{ route('suporte') }}" class="text-gray-600 hover:text-gray-900">Suporte</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Contacto</h3>
                    <p class="mt-2 text-gray-600">Email: <a href="mailto:contato@formulasul.com" class="text-[var(--primary)]">contato@formulasul.com</a></p>
                    <p class="mt-2 text-gray-600">Telefone: (XX) XXXX-XXXX</p>
                    <p class="mt-2 text-gray-600">WhatsApp: <a href="https://wa.me/1234567890" class="text-[var(--primary)]">(XX) XXXX-XXXX</a></p>
                </div>
            </div>
            <p class="mt-8 text-center text-sm text-gray-600">&copy; 2025 Formula Sul. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        function openModal() {
            document.getElementById('auth-modal').classList.remove('hidden');
            switchTab('login');
        }

        function closeModal() {
            document.getElementById('auth-modal').classList.add('hidden');
        }

        function switchTab(tab) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginTab.classList.add('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
                loginTab.classList.remove('text-gray-500');
                registerTab.classList.remove('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
                registerTab.classList.add('text-gray-500');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                registerTab.classList.add('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
                registerTab.classList.remove('text-gray-500');
                loginTab.classList.remove('text-[var(--primary)]', 'border-b-2', 'border-[var(--primary)]');
                loginTab.classList.add('text-gray-500');
            }
        }

        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function switchLanguage(lang) {
            console.log(`Idioma alterado para: ${lang}`);
            // Implement language switch logic (e.g., reload page with lang parameter)
        }
    </script>
    @yield('scripts')
</body>
</html>