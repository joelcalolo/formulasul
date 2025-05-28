
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

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('login-tab').addEventListener('click', () => switchTab('login'));
        document.getElementById('register-tab').addEventListener('click', () => switchTab('register'));
    });

    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }

    function switchLanguage(lang) {
        console.log(`Idioma alterado para: ${lang}`);
        const select = document.querySelector('select');
        const flag = select.options[select.selectedIndex].getAttribute('data-flag');
        select.style.backgroundImage = `url('data:image/svg+xml;utf8,${encodeURIComponent(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="16">${flag}</text></svg>`)}')`;
    }

    window.addEventListener('scroll', function() {
        const navBg = document.getElementById('nav-bg');
        const scrollPosition = window.scrollY;
        const navElements = document.querySelectorAll('.nav-logo, .nav-select, .nav-button, .nav-hamburger');
        
        if (scrollPosition > 50) {
            navBg.classList.remove('opacity-0');
            navBg.classList.add('opacity-100');
            navElements.forEach(el => {
                el.classList.remove('text-white');
                el.classList.add('text-[var(--primary)]');
            });
            document.querySelector('.nav-hamburger svg').classList.add('stroke-[var(--primary)]');
            document.querySelector('.nav-hamburger svg').classList.remove('stroke-white');
        } else {
            navBg.classList.add('opacity-0');
            navBg.classList.remove('opacity-100');
            navElements.forEach(el => {
                el.classList.add('text-white');
                el.classList.remove('text-[var(--primary)]');
            });
            document.querySelector('.nav-hamburger svg').classList.add('stroke-white');
            document.querySelector('.nav-hamburger svg').classList.remove('stroke-[var(--primary)]');
        }
    });

   // Função para o accordion das FAQs
   function toggleFAQ(index) {
      const answers = document.querySelectorAll('.faq-answer');
      answers.forEach((ans, i) => {
        if (i === index) {
          ans.classList.toggle('active');
        } else {
          ans.classList.remove('active');
        }
      });
    }
