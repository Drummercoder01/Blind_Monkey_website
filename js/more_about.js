// more_about.js - Versión con scroll al inicio al contraer
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-button');
    if (!toggleButton) return;
    
    const shortText = document.getElementById('about-text-short');
    const fullText = document.getElementById('about-text-full');
    const aboutSection = document.getElementById('about');
    
    let isExpanded = false;

    function toggleAboutText() {
        isExpanded = !isExpanded;
        
        // Usar clases CSS en lugar de style.display
        if (isExpanded) {
            shortText.classList.add('collapsed');
            fullText.classList.add('expanded');
            
            // Scroll suave al inicio de la sección About cuando se expande
            setTimeout(() => {
                if (aboutSection) {
                    aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        } else {
            shortText.classList.remove('collapsed');
            fullText.classList.remove('expanded');
            
            // Scroll suave al inicio de la sección About cuando se contrae
            setTimeout(() => {
                if (aboutSection) {
                    aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
        
        updateButtonState();
    }
    
    function updateButtonState() {
        const buttonText = toggleButton.querySelector('.button-text');
        const buttonIcon = toggleButton.querySelector('i');
        
        if (buttonText) {
            buttonText.textContent = isExpanded ? 'Read less' : 'Read more';
        }
        
        if (buttonIcon) {
            buttonIcon.className = isExpanded ? 'bi bi-arrow-up-circle me-2' : 'bi bi-arrow-down-circle me-2';
        }
        
        toggleButton.setAttribute('aria-expanded', isExpanded);
    }
    
    toggleButton.addEventListener('click', toggleAboutText);
    
    // Inicializar estado
    updateButtonState();
});