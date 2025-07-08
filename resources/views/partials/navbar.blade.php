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
        <div class="flex items-center space-x-4 relative">
            <button class="bg-[var(--primary)] text-white px-4 py-2 rounded-md font-semibold hover:bg-[var(--primary)]/90" onclick="openModal()">Login</button>
            <select class="p-2 rounded-none bg-transparent focus:outline-none focus:ring-0" onchange="switchLanguage(this.value)">
                <option value="pt" data-flag="🇧🇷">🇧🇷 Português</option>
                <option value="en" data-flag="🇺🇸">🇺🇸 English</option>
            </select>
            <!-- Menu Hamburguer (apenas mobile) -->
            <button id="menu-toggle" class="block md:hidden text-gray-700 focus:outline-none p-2" type="button">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            <!-- Menu Mobile - cobre a tela toda -->
            <div id="mobile-menu" class="hidden fixed inset-0 bg-white z-50 flex flex-col items-center justify-center md:hidden">
                <a href="#inicio" class="block px-4 py-4 text-gray-700 text-xl font-bold w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Início</a>
                <a href="#sobre" class="block px-4 py-4 text-gray-700 text-xl font-bold w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Sobre Nós</a>
                <a href="#frota" class="block px-4 py-4 text-gray-700 text-xl font-bold w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Frota</a>
                <a href="#servicos" class="block px-4 py-4 text-gray-700 text-xl font-bold w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Serviços</a>
                <a href="#contacto" class="block px-4 py-4 text-gray-700 text-xl font-bold w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Contacto</a>
                <hr class="my-2 border-t border-gray-200 w-3/4">
                <a href="aluguer.html" class="block px-4 py-4 text-gray-700 w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Aluguel</a>
                <a href="transfer.html" class="block px-4 py-4 text-gray-700 w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Transfer</a>
                <a href="passeios.html" class="block px-4 py-4 text-gray-700 w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Passeios Turísticos</a>
                <a href="suporte.html" target="_blank" class="block px-4 py-4 text-gray-700 w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Suporte</a>
                <a href="conta.html" class="block px-4 py-4 text-gray-700 w-full text-center hover:bg-[var(--background)]" onclick="closeMobileMenu()">Conta</a>
            </div>
        </div>
    </div>
</nav>
<script>
    function closeMobileMenu() {
        document.getElementById('mobile-menu').classList.add('hidden');
    }
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        if (toggle && menu) {
            toggle.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>