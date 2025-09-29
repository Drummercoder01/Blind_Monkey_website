document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
    const navbar = document.querySelector(".navbar");
    const body = document.body;
    
    console.log("🚀 Navbar con transición inicializado");
    console.log("Secciones:", sections.length, "Enlaces:", navLinks.length);

    // Variable para controlar el estado del navbar
    let isNavbarTop = false;
    
    // Función para mover navbar arriba/abajo según scroll
    function handleNavbarPosition() {
        const scrollPosition = window.scrollY;
        const triggerPoint = 100; // Pixel donde se activa la transición
        
        if (scrollPosition > triggerPoint && !isNavbarTop) {
            // Mover navbar arriba
            navbar.classList.add('navbar-top');
            body.classList.add('navbar-is-top');
            isNavbarTop = true;
            console.log("📍 Navbar movido arriba");
            
        } else if (scrollPosition <= triggerPoint && isNavbarTop) {
            // Mover navbar abajo
            navbar.classList.remove('navbar-top');
            body.classList.remove('navbar-is-top');
            isNavbarTop = false;
            console.log("📍 Navbar movido abajo");
        }
    }

        // Función para actualizar enlace activo
    function updateActiveLink() {
        let currentSection = "";
        const scrollPosition = window.scrollY + 100;
    
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

    

    // Función combinada para scroll
    function handleScroll() {
        handleNavbarPosition(); // Mover navbar
        updateActiveLink();     // Actualizar enlace activo
    }

    // Event listener para scroll
    window.addEventListener("scroll", handleScroll);
    
    // Ejecutar una vez al cargar
    setTimeout(() => {
        handleScroll();
    }, 100);

    // NAVEGACIÓN POR CLICKS
    navLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            console.log("🔗 CLICK en:", href);
            
            if (href && href.startsWith("#")) {
                e.preventDefault();
                
                const targetSection = document.querySelector(href);
                if (targetSection) {
                    // Activar enlace
                    navLinks.forEach((l) => l.classList.remove("active"));
                    this.classList.add("active");
                    
                    // Cerrar menú móvil si está abierto
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                    }
                    
                    // Calcular posición de scroll
                    let navbarHeight = 70; // Altura base del navbar
                    
                    // Si vamos a hacer scroll significativo, el navbar se moverá arriba
                    const targetOffsetTop = targetSection.offsetTop;
                    const currentScroll = window.scrollY;
                    
                    if (targetOffsetTop > 100) {
                        // El navbar se moverá arriba, considerar altura
                        navbarHeight = 80;
                    } else {
                        // El navbar permanecerá abajo
                        navbarHeight = 0; // No necesita offset si navbar está abajo
                    }
                    
                    const offsetTop = targetOffsetTop - navbarHeight;
                    
                    console.log("📍 Scrolling a:", Math.max(0, offsetTop));
                    
                    // Scroll suave
                    window.scrollTo({ 
                        top: Math.max(0, offsetTop), 
                        behavior: 'smooth' 
                    });
                    
                    // Actualizar estado después del scroll
                    setTimeout(() => {
                        handleScroll();
                    }, 500);
                }
            }
        });
    });
    
    // Función para debugging (opcional)
    window.debugNavbar = function() {
        console.log("🐛 Debug Navbar:");
        console.log("- Posición scroll:", window.scrollY);
        console.log("- Navbar en top:", isNavbarTop);
        console.log("- Clases navbar:", navbar.className);
        console.log("- Clases body:", body.className);
    };
    
    console.log("✅ Navbar con transición completamente inicializado");
    console.log("💡 Tip: Usa debugNavbar() en consola para debugging");
});