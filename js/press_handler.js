// press_handler.js - Frontend Press Handler with CSS Grid
(function() {
    'use strict';
    
    console.log('%c[PRESS HANDLER] Script loaded', 'color: #26e3ff; font-weight: bold');
    
    // ========== VARIABLES LOCALES ==========
    let allPressData = [];
    let pressToShowInitial = 3;
    let pressExpanded = false;
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('press')) {
            console.log('%c[PRESS HANDLER] Press section not found - exiting', 'color: #ff0000');
            return;
        }
        
        console.log('%c[PRESS HANDLER] Press section found - initializing...', 'color: #4CAF50');
        initializePress();
    });
    
    // ========== FUNCIONES PRINCIPALES ==========
    
    function initializePress() {
        console.log('[PRESS HANDLER] initializePress() called');
        loadPressFromDB();
    }
    
    function loadPressFromDB() {
        console.log('[PRESS HANDLER] Loading press items from database...');
        
        // Ocultar estados iniciales
        hideElement('no-press-state');
        hideElement('press-button-container');
        hideElement('press-grid');
        hideElement('press-additional');
        
        fetch('../scripts/get_press.php')
            .then(response => {
                console.log('[PRESS HANDLER] Fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[PRESS HANDLER] Data received:', data);
                
                if (data.status === 'success' && data.press_items && data.press_items.length > 0) {
                    // Ordenar por fecha (más reciente primero)
                    allPressData = data.press_items.sort((a, b) => {
                        return new Date(b.press_time) - new Date(a.press_time);
                    });
                    
                    console.log('%c[PRESS HANDLER] Successfully loaded ' + allPressData.length + ' press items', 'color: #4CAF50; font-weight: bold');
                    renderPress();
                    setupPressToggleButton();
                } else {
                    console.log('%c[PRESS HANDLER] No press items found', 'color: #ff9800');
                    showNoPressState();
                }
            })
            .catch(error => {
                console.error('%c[PRESS HANDLER] Error loading press:', 'color: #f44336; font-weight: bold', error);
                showErrorState();
            });
    }
    
    function renderPress() {
        console.log('[PRESS HANDLER] renderPress() called');
        
        const pressGrid = document.getElementById('press-grid');
        const pressAdditional = document.getElementById('press-additional');
        
        if (!pressGrid || !pressAdditional) {
            console.error('[PRESS HANDLER] Grid containers not found');
            return;
        }
        
        // Limpiar containers
        pressGrid.innerHTML = '';
        pressAdditional.innerHTML = '';
        
        // Separar items
        const initialPress = allPressData.slice(0, pressToShowInitial);
        const additionalPress = allPressData.slice(pressToShowInitial);
        
        console.log('[PRESS HANDLER] Initial press:', initialPress.length);
        console.log('[PRESS HANDLER] Additional press:', additionalPress.length);
        
        // Renderizar items iniciales
        initialPress.forEach((pressItem, index) => {
            const pressElement = createPressElement(pressItem, index);
            pressGrid.appendChild(pressElement);
        });
        
        // Renderizar items adicionales
        additionalPress.forEach((pressItem, index) => {
            const pressElement = createPressElement(pressItem, index + pressToShowInitial);
            pressAdditional.appendChild(pressElement);
        });
        
        // Mostrar el grid inicial
        showElement('press-grid');
        
        // Animar entrada
        setTimeout(() => {
            applyPressStaggeredAnimations('press-grid');
        }, 100);
    }
    
    function createPressElement(pressItem, index) {
        // CRITICAL: Crear solo el div item, sin clases de Bootstrap
        const pressItemDiv = document.createElement('div');
        pressItemDiv.className = 'press-item';
        pressItemDiv.setAttribute('data-press-id', pressItem.id || index);
        
        // Formatear fecha
        const pressDate = new Date(pressItem.press_time);
        const formattedDate = pressDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Estructura profesional
        pressItemDiv.innerHTML = `
            <div class="press-card">
                <!-- Source Badge (opcional) -->
                ${pressItem.press_source ? `
                    <div class="press-source">${escapeHtml(pressItem.press_source)}</div>
                ` : ''}
                
                <!-- Press Title (si existe) -->
                ${pressItem.press_title ? `
                    <h5>${escapeHtml(pressItem.press_title)}</h5>
                ` : ''}
                
                <!-- Press Excerpt/Quote -->
                <div class="press-excerpt">
                    "${escapeHtml(pressItem.press_text)}"
                </div>
                
                <!-- Meta Information -->
                <div class="press-meta">
                    <div class="press-author">
                        <i class="bi bi-person-fill"></i>
                        <span class="press-name">${escapeHtml(pressItem.press_author)}</span>
                        ${pressItem.press_comment ? ', ' + escapeHtml(pressItem.press_comment) : ''}
                    </div>
                    
                    <div class="press-time">
                        <i class="bi bi-clock"></i>
                        ${formattedDate}
                    </div>
                </div>
                
                <!-- Read More Link (si existe) -->
                ${pressItem.press_link ? `
                    <a href="${escapeHtml(pressItem.press_link)}" target="_blank" rel="noopener noreferrer" class="press-link">
                        Read Full Article
                        <i class="bi bi-arrow-right"></i>
                    </a>
                ` : ''}
            </div>
        `;
        
        return pressItemDiv;
    }
    
    function setupPressToggleButton() {
        console.log('[PRESS HANDLER] setupPressToggleButton() called');
        
        const buttonContainer = document.getElementById('press-button-container');
        const toggleButton = document.getElementById('togglePress');
        
        if (!buttonContainer || !toggleButton) {
            console.error('[PRESS HANDLER] Button elements not found');
            return;
        }
        
        console.log('[PRESS HANDLER] Total press items:', allPressData.length);
        console.log('[PRESS HANDLER] Should show button:', allPressData.length > pressToShowInitial);
        
        if (allPressData.length > pressToShowInitial) {
            console.log('%c[PRESS HANDLER] Showing toggle button', 'color: #4CAF50; font-weight: bold');
            showElement('press-button-container');
            
            // Clonar botón para limpiar listeners
            const newToggleButton = toggleButton.cloneNode(true);
            toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
            
            // Agregar listener
            newToggleButton.addEventListener('click', function(e) {
                console.log('%c[PRESS HANDLER] Toggle button clicked!', 'color: #FF5722; font-weight: bold');
                e.preventDefault();
                togglePressVisibility();
            });
            
            console.log('[PRESS HANDLER] Button listener attached');
        } else {
            console.log('[PRESS HANDLER] NOT showing toggle button');
            hideElement('press-button-container');
        }
    }
    
    function togglePressVisibility() {
        console.log('%c[PRESS HANDLER] togglePressVisibility() called', 'color: #FF5722; font-weight: bold');
        console.log('[PRESS HANDLER] Current state - pressExpanded:', pressExpanded);
        
        const pressAdditional = document.getElementById('press-additional');
        const toggleButton = document.getElementById('togglePress');
        const buttonText = toggleButton.querySelector('.button-text');
        const chevronIcon = toggleButton.querySelector('.chevron-icon');
        
        if (!pressExpanded) {
            console.log('%c[PRESS HANDLER] EXPANDING press', 'color: #4CAF50; font-weight: bold');
            
            // Mostrar adicionales
            showElement('press-additional');
            
            // Animar
            setTimeout(() => {
                applyPressStaggeredAnimations('press-additional');
            }, 50);
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Show Less';
            if (toggleButton) toggleButton.classList.add('active');
            pressExpanded = true;
            
            console.log('[PRESS HANDLER] State updated - pressExpanded:', pressExpanded);
            
        } else {
            console.log('%c[PRESS HANDLER] COLLAPSING press', 'color: #FF9800; font-weight: bold');
            
            // Ocultar adicionales
            hideElement('press-additional');
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Load More Articles';
            if (toggleButton) toggleButton.classList.remove('active');
            pressExpanded = false;
            
            console.log('[PRESS HANDLER] State updated - pressExpanded:', pressExpanded);
            
            // Scroll
            setTimeout(() => {
                const pressSection = document.getElementById('press');
                if (pressSection) {
                    pressSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    }
    
    function applyPressStaggeredAnimations(containerId) {
        console.log('[PRESS HANDLER] Applying animations to:', containerId);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('[PRESS HANDLER] Animation container not found:', containerId);
            return;
        }
        
        const items = container.querySelectorAll('.press-item');
        console.log('[PRESS HANDLER] Animating', items.length, 'items');
        
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    function showNoPressState() {
        console.log('[PRESS HANDLER] Showing no press state');
        showElement('no-press-state');
        hideElement('press-grid');
        hideElement('press-additional');
    }
    
    function showErrorState() {
        console.log('[PRESS HANDLER] Showing error state');
        const pressContainer = document.querySelector('.press-container');
        if (pressContainer) {
            pressContainer.innerHTML = `
                <div class="no-press-state" style="display: flex;">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <h3 class="empty-title">Unable to Load Press</h3>
                        <p class="empty-text">Please try again later</p>
                        <button class="btn-press-toggle" onclick="location.reload()" style="margin-top: 20px;">
                            <span class="btn-content">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <span>Try Again</span>
                            </span>
                        </button>
                    </div>
                </div>
            `;
        }
    }
    
    // ========== FUNCIONES AUXILIARES ==========
    
    function showElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) {
            console.error('[PRESS HANDLER] showElement - Element not found:', elementId);
            return;
        }
        
        console.log('[PRESS HANDLER] Showing element:', elementId);
        
        // CRITICAL: Limpiar style inline para que CSS tome control
        element.style.display = '';
    }
    
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        console.log('[PRESS HANDLER] Hiding element:', elementId);
        element.style.display = 'none';
    }
    
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
    
    // ========== FUNCIONES GLOBALES ==========
    
    window.refreshFrontendPress = function() {
        console.log('%c[PRESS HANDLER] Refresh requested', 'color: #00BCD4; font-weight: bold');
        pressExpanded = false;
        initializePress();
    };
    
    window.debugFrontendPress = function() {
        console.log('%c=== FRONTEND PRESS DEBUG ===', 'color: #00BCD4; font-weight: bold');
        console.log('Total press items:', allPressData.length);
        console.log('Press expanded:', pressExpanded);
        console.log('Initial to show:', pressToShowInitial);
        console.log('press-grid display:', document.getElementById('press-grid')?.style.display);
        console.log('press-additional display:', document.getElementById('press-additional')?.style.display);
        console.log('%c============================', 'color: #00BCD4; font-weight: bold');
    };
    
    console.log('%c[PRESS HANDLER] Script initialization complete', 'color: #26e3ff; font-weight: bold');
    
})();
