// about_handler.js - Frontend About Handler (consistent with events/music)
(function() {
    'use strict';
    
    console.log('%c[ABOUT HANDLER] Script loaded', 'color: #26e3ff; font-weight: bold');
    
    // ========== VARIABLES LOCALES ==========
    let isExpanded = false;
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggle-button');
        
        if (!toggleButton) {
            console.log('%c[ABOUT HANDLER] Toggle button not found - exiting', 'color: #ff0000');
            return;
        }
        
        console.log('%c[ABOUT HANDLER] Toggle button found - initializing...', 'color: #4CAF50');
        
        const shortText = document.getElementById('about-text-short');
        const fullText = document.getElementById('about-text-full');
        const aboutSection = document.getElementById('about');
        
        if (!shortText || !fullText) {
            console.error('[ABOUT HANDLER] Text containers not found');
            return;
        }
        
        // Event listener para el botón
        toggleButton.addEventListener('click', function(e) {
            console.log('%c[ABOUT HANDLER] Toggle button clicked!', 'color: #FF5722; font-weight: bold');
            e.preventDefault();
            toggleAboutText(shortText, fullText, aboutSection, toggleButton);
        });
        
        // Inicializar estado
        updateButtonState(toggleButton);
        
        console.log('[ABOUT HANDLER] Initialization complete');
    });
    
    // ========== FUNCIONES PRINCIPALES ==========
    
    function toggleAboutText(shortText, fullText, aboutSection, toggleButton) {
        console.log('[ABOUT HANDLER] Current state - isExpanded:', isExpanded);
        
        isExpanded = !isExpanded;
        
        if (isExpanded) {
            console.log('%c[ABOUT HANDLER] EXPANDING about text', 'color: #4CAF50; font-weight: bold');
            
            // Contraer texto corto
            shortText.classList.add('collapsed');
            
            // Expandir texto completo
            fullText.classList.add('expanded');
            
            // Scroll al inicio de About
            setTimeout(() => {
                if (aboutSection) {
                    aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
            
        } else {
            console.log('%c[ABOUT HANDLER] COLLAPSING about text', 'color: #FF9800; font-weight: bold');
            
            // Mostrar texto corto
            shortText.classList.remove('collapsed');
            
            // Ocultar texto completo
            fullText.classList.remove('expanded');
            
            // Scroll al inicio de About
            setTimeout(() => {
                if (aboutSection) {
                    aboutSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
        
        updateButtonState(toggleButton);
        console.log('[ABOUT HANDLER] State updated - isExpanded:', isExpanded);
    }
    
    function updateButtonState(toggleButton) {
        const buttonText = toggleButton.querySelector('.button-text');
        const chevronIcon = toggleButton.querySelector('.chevron-icon');
        
        if (buttonText) {
            buttonText.textContent = isExpanded ? 'Show Less' : 'Read Full Story';
        }
        
        if (chevronIcon) {
            // Rotar el chevron en lugar de cambiar el icono
            if (isExpanded) {
                toggleButton.classList.add('active');
            } else {
                toggleButton.classList.remove('active');
            }
        }
        
        toggleButton.setAttribute('aria-expanded', isExpanded);
        
        console.log('[ABOUT HANDLER] Button state updated:', isExpanded ? 'Show Less' : 'Read Full Story');
    }
    
    // ========== FUNCIONES GLOBALES ==========
    
    window.refreshFrontendAbout = function() {
        console.log('%c[ABOUT HANDLER] Refresh requested', 'color: #00BCD4; font-weight: bold');
        isExpanded = false;
        const toggleButton = document.getElementById('toggle-button');
        if (toggleButton) {
            updateButtonState(toggleButton);
        }
    };
    
    window.debugFrontendAbout = function() {
        console.log('%c=== FRONTEND ABOUT DEBUG ===', 'color: #00BCD4; font-weight: bold');
        console.log('Is expanded:', isExpanded);
        console.log('Short text element:', document.getElementById('about-text-short'));
        console.log('Full text element:', document.getElementById('about-text-full'));
        console.log('Toggle button:', document.getElementById('toggle-button'));
        
        const shortText = document.getElementById('about-text-short');
        const fullText = document.getElementById('about-text-full');
        
        if (shortText) {
            console.log('Short text classes:', shortText.className);
            console.log('Short text max-height:', window.getComputedStyle(shortText).maxHeight);
        }
        
        if (fullText) {
            console.log('Full text classes:', fullText.className);
            console.log('Full text max-height:', window.getComputedStyle(fullText).maxHeight);
        }
        
        console.log('%c============================', 'color: #00BCD4; font-weight: bold');
    };
    
    console.log('%c[ABOUT HANDLER] Script initialization complete', 'color: #26e3ff; font-weight: bold');
    
})();
