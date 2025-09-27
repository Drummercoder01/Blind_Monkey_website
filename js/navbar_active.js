// JavaScript para manejar fallback del logo en navbar
document.addEventListener('DOMContentLoaded', function() {
    const navbarLogo = document.getElementById('navbar-logo');
    const navbarBrand = document.getElementById('mobile-brand');
    
    if (navbarLogo && navbarBrand) {
        // Manejar error de carga del logo
        navbarLogo.addEventListener('error', function() {
            console.log('⚠️ Logo del navbar no se pudo cargar, mostrando texto fallback');
            navbarBrand.classList.add('logo-failed');
        });
        
        // Verificar si el logo se cargó correctamente
        navbarLogo.addEventListener('load', function() {
            console.log('✅ Logo del navbar cargado correctamente');
            navbarBrand.classList.remove('logo-failed');
        });
        
        // Verificar si la imagen ya está cargada (cache)
        if (navbarLogo.complete) {
            if (navbarLogo.naturalWidth === 0) {
                // La imagen falló al cargar
                navbarBrand.classList.add('logo-failed');
            } else {
                // La imagen se cargó correctamente
                navbarBrand.classList.remove('logo-failed');
            }
        }
        
        // Hacer que el logo sea clickeable para ir a home
        navbarBrand.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Buscar la sección home
            const homeSection = document.querySelector('#home');
            if (homeSection) {
                // Cerrar menú móvil si está abierto
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
                
                // Scroll suave a home
                window.scrollTo({ 
                    top: 0, 
                    behavior: 'smooth' 
                });
                
                // Actualizar enlace activo
                const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
                navLinks.forEach(link => link.classList.remove('active'));
                
                const homeLink = document.querySelector('a[href="#home"]');
                if (homeLink) {
                    homeLink.classList.add('active');
                }
            }
        });
    }
    
    console.log('🎨 Navbar logo handler inicializado');
});