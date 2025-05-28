<!-- Navbar -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.html" class="flex items-center">
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
            <button class="bg-[var(--primary)] text-white px-4 py-2 rounded-md font-semibold hover:bg-[var(--primary)]/90" onclick="openModal()">Login</button>
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
                <a href="aluguer.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-[var(--background)] font-bold">
                    <svg class="w-5 h-5 mr-2 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2l1-5h13l1 5h2m-3 0v6a2 2 0 01-2 2h-4a2 2 0 01-2-2v-6m-4 0v6a2 2 0 002 2h4a2 2 0 002-2v-6"></path>
                    </svg>
                    Aluguel
                </a>
                <a href="transfer.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-[var(--background)] font-bold">
                    <svg class="w-5 h-5 mr-2 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6h-6V8zm-6 0H7a2 2 0 00-2 2v6h6V8zm-6 8v2a1 1 0 001 1h12a1 1 0 001-1v-2"></path>
                    </svg>
                    Transfer
                </a>
                <a href="passeios.html" class="flex items-center px-4 py-2 text-gray-700 hover:bg-[var(--background)] font-bold">
                    <svg class="w-5 h-5 mr-2 text-[var(--primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12A9 9 0 113 12a9 9 0 0118 0zm-6-2l-4 4m0 0l-4-4m4 4V8"></path>
                    </svg>
                    Passeios Turísticos
                </a>
            
                <hr class="my-2 border-t border-gray-200">
            
                <a href="suporte.html" target="_blank" class="block px-4 py-2 text-gray-700 hover:bg-[var(--background)]">Suporte</a>
                <a href="conta.html" class="block px-4 py-2 text-gray-700 hover:bg-[var(--background)]">Conta</a>
            </div>
            
        </div>
    </div>
</nav>