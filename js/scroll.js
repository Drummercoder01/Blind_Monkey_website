document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
    const navbar = document.querySelector(".navbar");
    const body = document.body;
    
    console.log("🚀 Navbar con transición optimizado inicializado");
    console.log("Secciones:", sections.length, "Enlaces:", navLinks.length);

    // Variable para controlar el estado del navbar
    let isNavbarTop = false;
    let isTransitioning = false;
    
    // Función para mover navbar arriba/abajo según scroll
    function handleNavbarPosition() {
        const scrollPosition = window.scrollY;
        const triggerPoint = 80; // Pixel donde se activa la transición (reducido para activar antes)
        
        if (scrollPosition > triggerPoint && !isNavbarTop) {
            // Mover navbar arriba
            isTransitioning = true;
            
            // CRITICAL: Guardar scroll position antes del cambio
            const currentScroll = window.scrollY;
            
            navbar.classList.add('navbar-top');
            body.classList.add('navbar-is-top');
            isNavbarTop = true;
            
            // CRITICAL: Restaurar scroll position para evitar jump
            window.scrollTo(0, currentScroll);
            
            setTimeout(() => {
                isTransitioning = false;
            }, 500);
            
            console.log("📍 Navbar movido arriba");
            
        } else if (scrollPosition <= triggerPoint && isNavbarTop) {
            // Mover navbar abajo
            isTransitioning = true;
            
            // CRITICAL: Guardar scroll position antes del cambio
            const currentScroll = window.scrollY;
            
            navbar.classList.remove('navbar-top');
            body.classList.remove('navbar-is-top');
            isNavbarTop = false;
            
            // CRITICAL: Restaurar scroll position para evitar jump
            window.scrollTo(0, currentScroll);
            
            setTimeout(() => {
                isTransitioning = false;
            }, 500);
            
            console.log("📍 Navbar movido abajo");
        }
    }

    // Función para actualizar enlace activo
    function updateActiveLink() {
        // No actualizar si estamos en transición
        if (isTransitioning) return;
        
        let currentSection = "";
        const scrollPosition = window.scrollY + 120;
    
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.offsetHeight;
            const sectionId = section.getAttribute("id");
        
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                currentSection = sectionId;
            }
        });
    
        navLinks.forEach((link) => {
            link.classList.remove("active");
            const href = link.getAttribute("href");
            
            if (href && href.startsWith("#")) {
                const targetId = href.substring(1);
                if (targetId === currentSection) {
                    link.classList.add("active");
                }
            }
        });
    
        // Activar home por defecto al inicio
        if (!currentSection && scrollPosition < 200) {
            const homeLink = document.querySelector('a[href="#home"]');
            if (homeLink) homeLink.classList.add("active");
        }
    }

    // Función combinada para scroll (con throttle)
    let scrollTimeout;
    function handleScroll() {
        // Clear timeout anterior
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        
        // Ejecutar navbar position inmediatamente
        handleNavbarPosition();
        
        // Throttle para updateActiveLink (mejor performance)
        scrollTimeout = setTimeout(() => {
            updateActiveLink();
        }, 50);
    }

    // Event listener para scroll con passive para mejor performance
    window.addEventListener("scroll", handleScroll, { passive: true });
    
    // Ejecutar una vez al cargar
    setTimeout(() => {
        handleScroll();
    }, 100);

    // NAVEGACIÓN POR CLICKS
    navLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            console.log("🔗 CLICK en:", href);
            
            // Si es link externo (Webshop), permitir comportamiento normal
            if (!href || !href.startsWith("#")) {
                return; // Dejar que el navegador maneje el link externo
            }
            
            e.preventDefault();
            
            const targetSection = document.querySelector(href);
            if (targetSection) {
                // Activar enlace inmediatamente
                navLinks.forEach((l) => l.classList.remove("active"));
                this.classList.add("active");
                
                // Cerrar menú móvil si está abierto
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
                
                // Calcular posición de scroll considerando navbar
                const targetOffsetTop = targetSection.offsetTop;
                let navbarHeight = 85; // Altura del navbar cuando está arriba
                
                // Si vamos a home (scroll 0), no necesitamos offset
                if (href === '#home') {
                    window.scrollTo({ 
                        top: 0, 
                        behavior: 'smooth' 
                    });
                    
                    console.log("🏠 Scrolling a Home (top: 0)");
                } else {
                    // Para otras secciones, considerar altura del navbar
                    const offsetTop = Math.max(0, targetOffsetTop - navbarHeight);
                    
                    console.log("📍 Scrolling a:", offsetTop, "| Section top:", targetOffsetTop);
                    
                    window.scrollTo({ 
                        top: offsetTop, 
                        behavior: 'smooth' 
                    });
                }
                
                // Actualizar estado después del scroll
                setTimeout(() => {
                    handleScroll();
                }, 600);
            }
        });
    });
    
    // CRITICAL: Prevenir scroll jump en resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        if (resizeTimeout) {
            clearTimeout(resizeTimeout);
        }
        
        resizeTimeout = setTimeout(() => {
            const currentScroll = window.scrollY;
            // Forzar recalculo sin jump
            window.scrollTo(0, currentScroll);
        }, 100);
    }, { passive: true });
    
    // Función para debugging
    window.debugNavbar = function() {
        console.log("🛠️ Debug Navbar:");
        console.log("- Posición scroll:", window.scrollY);
        console.log("- Navbar en top:", isNavbarTop);
        console.log("- En transición:", isTransitioning);
        console.log("- Clases navbar:", navbar.className);
        console.log("- Clases body:", body.className);
        console.log("- Body padding-top:", window.getComputedStyle(body).paddingTop);
        console.log("- Body padding-bottom:", window.getComputedStyle(body).paddingBottom);
    };
    
    console.log("✅ Navbar con transición optimizado completamente inicializado");
    console.log("💡 Tip: Usa debugNavbar() en consola para debugging");
});
