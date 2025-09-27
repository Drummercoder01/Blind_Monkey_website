// Newsletter Handler - Manejo del formulario de suscripción
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');
    const emailInput = document.getElementById('newsletter-email');
    const submitBtn = document.querySelector('.newsletter-btn');
    const messageDiv = document.getElementById('newsletter-message');
    const footerLinks = document.querySelectorAll('.footer-link');
    
    console.log('📧 Newsletter handler initialized');

    // Manejar envío del formulario
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = emailInput.value.trim();
            
            if (!email) {
                showMessage('Please enter your email address', 'error');
                return;
            }
            
            if (!isValidEmail(email)) {
                showMessage('Please enter a valid email address', 'error');
                return;
            }
            
            // Mostrar estado de carga
            setLoadingState(true);
            
            // Simular envío (aquí puedes integrar con tu backend)
            handleNewsletterSubmission(email);
        });
    }

    // Función para manejar la suscripción
    function handleNewsletterSubmission(email) {
        // Opción 1: Mailto directo (funciona pero abre cliente de email)
        // createMailtoLink(email);
        
        // Opción 2: Simulación de envío exitoso (para demo)
        setTimeout(() => {
            setLoadingState(false);
            showMessage('Thanks for subscribing! Welcome to The 5 AM family! 🎸', 'success');
            emailInput.value = '';
            
            // Opcional: Aquí puedes enviar a tu backend
            // sendToBackend(email);
        }, 2000);
        
        // Opción 3: Integración real con backend (descomenta cuando esté listo)
        // sendNewsletterData(email);
    }

    // Crear enlace mailto con información pre-llenada
    function createMailtoLink(email) {
        const subject = 'New Newsletter Subscription - The 5 AM';
        const body = `
New newsletter subscription from: ${email}

Time: ${new Date().toLocaleString()}
Source: Official Website

Please add this email to The 5 AM newsletter list.

---
This email was generated automatically from the website.
        `.trim();
        
        const mailtoLink = `mailto:info@the5am.be?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        
        // Abrir enlace mailto
        window.location.href = mailtoLink;
        
        setLoadingState(false);
        showMessage('Email client opened! Please send the email to complete your subscription.', 'success');
        emailInput.value = '';
    }

    // Función para envío real a backend (implementar cuando esté listo)
    function sendNewsletterData(email) {
        fetch('/api/newsletter-subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                timestamp: new Date().toISOString(),
                source: 'website_footer'
            })
        })
        .then(response => response.json())
        .then(data => {
            setLoadingState(false);
            if (data.success) {
                showMessage('Thanks for subscribing! Check your email for confirmation.', 'success');
                emailInput.value = '';
            } else {
                showMessage(data.message || 'Something went wrong. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Newsletter subscription error:', error);
            setLoadingState(false);
            showMessage('Network error. Please check your connection and try again.', 'error');
        });
    }

    // Validar formato de email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Mostrar mensaje de estado
    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = `newsletter-message ${type}`;
        messageDiv.style.display = 'block';
        
        // Auto-hide después de 5 segundos para mensajes de éxito
        if (type === 'success') {
            setTimeout(() => {
                hideMessage();
            }, 5000);
        }
    }

    // Ocultar mensaje
    function hideMessage() {
        messageDiv.style.display = 'none';
        messageDiv.className = 'newsletter-message';
    }

    // Estado de carga del botón
    function setLoadingState(loading) {
        if (loading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="newsletter-btn-text">Sending...</span>';
            submitBtn.style.opacity = '0.7';
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i><span class="newsletter-btn-text">Subscribe</span>';
            submitBtn.style.opacity = '1';
        }
    }

    // Mejorar UX del input
    if (emailInput) {
        // Limpiar mensaje de error cuando el usuario escribe
        emailInput.addEventListener('input', function() {
            if (messageDiv.classList.contains('error')) {
                hideMessage();
            }
        });

        // Efecto focus mejorado
        emailInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        emailInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });

        // Permitir envío con Enter
        emailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                newsletterForm.dispatchEvent(new Event('submit'));
            }
        });
    }

    // Manejar navegación de footer links (smooth scroll)
    footerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href && href.startsWith('#')) {
                e.preventDefault();
                
                const targetSection = document.querySelector(href);
                if (targetSection) {
                    // Calcular posición considerando navbar
                    const navbarHeight = 80;
                    const offsetTop = targetSection.offsetTop - navbarHeight;
                    
                    window.scrollTo({
                        top: Math.max(0, offsetTop),
                        behavior: 'smooth'
                    });
                    
                    // Actualizar navegación activa
                    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                    
                    const correspondingNavLink = document.querySelector(`a[href="${href}"]`);
                    if (correspondingNavLink) {
                        correspondingNavLink.classList.add('active');
                    }
                }
            }
        });
    });

    // Animaciones adicionales para elementos del footer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observar elementos del footer para animaciones
    const footerElements = document.querySelectorAll('.footer-brand, .footer-newsletter, .footer-social, .footer-links');
    footerElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = `all 0.6s ease ${index * 0.1}s`;
        footerObserver.observe(element);
    });

    // Debug function
    window.debugNewsletter = function() {
        console.log('📧 Newsletter Debug Info:');
        console.log('- Form element:', newsletterForm);
        console.log('- Email input:', emailInput);
        console.log('- Submit button:', submitBtn);
        console.log('- Message div:', messageDiv);
    };

    console.log('✅ Newsletter handler completely initialized');
    console.log('💡 Tip: Use debugNewsletter() for debugging');
});