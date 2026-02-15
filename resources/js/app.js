import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Registra plugin de collapse para FAQ
Alpine.plugin(collapse);

window.Alpine = Alpine;
Alpine.start();

// Scroll Reveal Animation
document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);

    // Adiciona animação de reveal aos elementos que precisam
    document.querySelectorAll('.scroll-reveal').forEach(element => {
        observer.observe(element);
    });

    // Smooth scroll para âncoras
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offset = 80; // Offset para header fixo
                    const targetPosition = target.offsetTop - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Adiciona efeito de parallax suave no hero
    let lastScrollY = window.scrollY;
    let ticking = false;

    const updateParallax = () => {
        const heroElements = document.querySelectorAll('[data-parallax]');
        heroElements.forEach(element => {
            const speed = element.dataset.parallax || 0.5;
            const yPos = -(lastScrollY * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        lastScrollY = window.scrollY;
        if (!ticking) {
            window.requestAnimationFrame(updateParallax);
            ticking = true;
        }
    });

    // Counter animation para números
    const animateCounter = (element) => {
        const target = parseInt(element.dataset.target);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };

        updateCounter();
    };

    // Observa elementos com números para animar
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-counter]').forEach(element => {
        counterObserver.observe(element);
    });

    // Navbar sticky effect - adiciona sombra ao rolar
    const navbar = document.querySelector('nav.sticky');
    if (navbar) {
        let lastScroll = 0;
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.scrollY;
            
            if (currentScroll > 50) {
                navbar.classList.add('shadow-lg');
                navbar.classList.remove('shadow-sm');
            } else {
                navbar.classList.add('shadow-sm');
                navbar.classList.remove('shadow-lg');
            }
            
            lastScroll = currentScroll;
        });
    }

    // Marca link ativo na navbar baseado na seção visível
    const navLinks = document.querySelectorAll('nav a[href^="/#"]');
    const sections = document.querySelectorAll('section[id]');
    
    if (navLinks.length > 0 && sections.length > 0) {
        const observerOptions = {
            threshold: 0.3,
            rootMargin: '-100px 0px -50% 0px'
        };
        
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        const href = link.getAttribute('href');
                        if (href === `/#${sectionId}`) {
                            link.classList.add('text-[var(--cor-verde-serra)]');
                            link.classList.remove('text-[var(--cor-texto-secundario)]');
                        } else if (href.startsWith('/#')) {
                            link.classList.remove('text-[var(--cor-verde-serra)]');
                            link.classList.add('text-[var(--cor-texto-secundario)]');
                        }
                    });
                }
            });
        }, observerOptions);
        
        sections.forEach(section => {
            sectionObserver.observe(section);
        });
    }
});
