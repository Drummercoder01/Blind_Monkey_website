// Newsletter Handler - UPDATED WITH DATABASE INTEGRATION
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');
    const emailInput = document.getElementById('newsletter-email');
    const submitBtn = document.querySelector('.newsletter-btn');
    const messageDiv = document.getElementById('newsletter-message');
    const footerLinks = document.querySelectorAll('.footer-link');
    
    console.log('📧 Newsletter handler initialized');

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
            
            setLoadingState(true);
            sendNewsletterData(email);
        });
    }

    // Send to database via API
    function sendNewsletterData(email) {
        fetch('/api/newsletter-subscribe.php', {  // ← Ruta absoluta (sin ../)
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
                showMessage(data.message || 'Thanks for subscribing! 🎸', 'success');
                emailInput.value = '';
                
                // Optional: Track conversion
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'newsletter_subscription', {
                        'event_category': 'engagement',
                        'event_label': 'footer'
                    });
                }
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

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = `newsletter-message ${type}`;
        messageDiv.style.display = 'block';
        
        if (type === 'success') {
            setTimeout(() => hideMessage(), 5000);
        }
    }

    function hideMessage() {
        messageDiv.style.display = 'none';
        messageDiv.className = 'newsletter-message';
    }

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

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            if (messageDiv.classList.contains('error')) {
                hideMessage();
            }
        });

        emailInput.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        emailInput.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    }

    footerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const targetSection = document.querySelector(href);
                if (targetSection) {
                    const navbarHeight = 80;
                    const offsetTop = targetSection.offsetTop - navbarHeight;
                    
                    window.scrollTo({
                        top: Math.max(0, offsetTop),
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    console.log('✅ Newsletter handler ready');
});
