// event_handler.js - Frontend Events Handler with Enhanced Debugging
(function() {
    'use strict';
    
    console.log('%c[EVENT HANDLER] Script loaded', 'color: #26e3ff; font-weight: bold');
    
    // ========== VARIABLES LOCALES ==========
    let allEventsData = [];
    let eventsToShowInitial = 4;
    let eventsExpanded = false;
    
    // ========== VERIFICACIÓN DE ELEMENTOS ==========
    function checkElements() {
        const elements = {
            'events section': document.getElementById('events'),
            'events-grid': document.getElementById('events-grid'),
            'events-additional': document.getElementById('events-additional'),
            'events-button-container': document.getElementById('events-button-container'),
            'toggleEvents': document.getElementById('toggleEvents'),
            'no-events-state': document.getElementById('no-events-state')
        };
        
        console.log('%c[EVENT HANDLER] Element check:', 'color: #ffa500; font-weight: bold');
        for (const [name, element] of Object.entries(elements)) {
            console.log(`  ${name}:`, element ? '✓ Found' : '✗ NOT FOUND');
            if (element) {
                console.log(`    Display:`, window.getComputedStyle(element).display);
            }
        }
    }
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        console.log('%c[EVENT HANDLER] DOMContentLoaded fired', 'color: #4CAF50; font-weight: bold');
        
        // Verificar si estamos en una página que tiene la sección de eventos
        if (!document.getElementById('events')) {
            console.log('%c[EVENT HANDLER] Events section not found - exiting', 'color: #ff0000');
            return;
        }
        
        console.log('%c[EVENT HANDLER] Events section found - initializing...', 'color: #4CAF50');
        checkElements();
        
        // Pequeño delay para asegurar que todo el DOM está listo
        setTimeout(() => {
            initializeEvents();
        }, 100);
    });
    
    // ========== FUNCIONES PRINCIPALES ==========
    
    function initializeEvents() {
        console.log('%c[EVENT HANDLER] initializeEvents() called', 'color: #2196F3; font-weight: bold');
        loadEventsFromDB();
    }
    
    function loadEventsFromDB() {
        console.log('[EVENT HANDLER] Loading events from database...');
        
        // Ocultar estados iniciales
        hideNoEventsState();
        hideEventsButton();
        hideElement('events-grid');
        hideElement('events-additional');
        
        fetch('../scripts/get_events.php')
            .then(response => {
                console.log('[EVENT HANDLER] Fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[EVENT HANDLER] Data received:', data);
                
                if (data.status === 'success' && data.events && data.events.length > 0) {
                    // Ordenar eventos de manera inteligente:
                    // 1. Eventos futuros primero (ascendente - próximo evento primero)
                    // 2. Eventos pasados después (descendente - más reciente primero)
                    const now = new Date();
                    now.setHours(0, 0, 0, 0); // Reset time to start of day
                    
                    allEventsData = data.events.sort((a, b) => {
                        const dateA = new Date(a.event_date + 'T00:00:00');
                        const dateB = new Date(b.event_date + 'T00:00:00');
                        
                        const isAFuture = dateA >= now;
                        const isBFuture = dateB >= now;
                        
                        // Si ambos son futuros o ambos son pasados
                        if (isAFuture === isBFuture) {
                            // Futuros: ascendente (próximo primero)
                            if (isAFuture) {
                                return dateA - dateB;
                            }
                            // Pasados: descendente (más reciente primero)
                            return dateB - dateA;
                        }
                        
                        // Futuros antes que pasados
                        return isAFuture ? -1 : 1;
                    });
                    
                    console.log('%c[EVENT HANDLER] Successfully loaded ' + allEventsData.length + ' events', 'color: #4CAF50; font-weight: bold');
                    console.log('[EVENT HANDLER] Sorted events (upcoming first, then past):', allEventsData.map(e => ({
                        name: e.event_name,
                        date: e.event_date,
                        isPast: new Date(e.event_date + 'T00:00:00') < now
                    })));
                    
                    renderEvents();
                    setupEventsToggleButton();
                } else {
                    console.log('%c[EVENT HANDLER] No events found in response', 'color: #ff9800');
                    showNoEventsState();
                }
            })
            .catch(error => {
                console.error('%c[EVENT HANDLER] Error loading events:', 'color: #f44336; font-weight: bold', error);
                showErrorState();
            });
    }
    
    function renderEvents() {
        console.log('%c[EVENT HANDLER] renderEvents() called', 'color: #2196F3; font-weight: bold');
        
        const eventsGrid = document.getElementById('events-grid');
        const eventsAdditional = document.getElementById('events-additional');
        
        if (!eventsGrid || !eventsAdditional) {
            console.error('%c[EVENT HANDLER] ERROR: Grid containers not found!', 'color: #f44336; font-weight: bold');
            checkElements();
            return;
        }
        
        console.log('[EVENT HANDLER] Grid containers found, clearing...');
        
        // Limpiar containers
        eventsGrid.innerHTML = '';
        eventsAdditional.innerHTML = '';
        
        // Separar eventos
        const initialEvents = allEventsData.slice(0, eventsToShowInitial);
        const additionalEvents = allEventsData.slice(eventsToShowInitial);
        
        console.log('%c[EVENT HANDLER] Splitting events:', 'color: #9C27B0; font-weight: bold');
        console.log('  Initial events:', initialEvents.length, initialEvents);
        console.log('  Additional events:', additionalEvents.length, additionalEvents);
        
        // Renderizar eventos iniciales
        console.log('[EVENT HANDLER] Rendering initial events...');
        initialEvents.forEach((event, index) => {
            const eventElement = createEventElement(event, index);
            eventsGrid.appendChild(eventElement);
        });
        console.log('[EVENT HANDLER] ✓ Initial events rendered');
        
        // Renderizar eventos adicionales
        if (additionalEvents.length > 0) {
            console.log('[EVENT HANDLER] Rendering additional events...');
            additionalEvents.forEach((event, index) => {
                const eventElement = createEventElement(event, index + eventsToShowInitial);
                eventsAdditional.appendChild(eventElement);
            });
            console.log('[EVENT HANDLER] ✓ Additional events rendered');
        } else {
            console.log('[EVENT HANDLER] No additional events to render');
        }
        
        // Verificar estado antes de mostrar
        console.log('[EVENT HANDLER] Before showing grid:');
        console.log('  events-grid children:', eventsGrid.children.length);
        console.log('  events-additional children:', eventsAdditional.children.length);
        console.log('  events-grid display:', eventsGrid.style.display);
        console.log('  events-additional display:', eventsAdditional.style.display);
        
        // Mostrar solo el grid inicial
        showElement('events-grid');
        
        // Verificar estado después de mostrar
        setTimeout(() => {
            console.log('[EVENT HANDLER] After showing grid:');
            console.log('  events-grid display:', eventsGrid.style.display);
            console.log('  events-grid computed display:', window.getComputedStyle(eventsGrid).display);
            console.log('  events-additional display:', eventsAdditional.style.display);
            console.log('  events-additional computed display:', window.getComputedStyle(eventsAdditional).display);
        }, 50);
        
        // Animar entrada
        setTimeout(() => {
            applyEventsStaggeredAnimations('events-grid');
        }, 100);
    }
    
    function createEventElement(event, index) {
        const eventItem = document.createElement('div');
        eventItem.className = 'event-item';
        eventItem.setAttribute('data-event-id', event.id || index);
        
        // Formatear fecha
        const eventDate = new Date(event.event_date + 'T00:00:00');
        const month = eventDate.toLocaleDateString('en-US', { month: 'short' }).toUpperCase();
        const day = eventDate.getDate();
        const year = eventDate.getFullYear();
        
        // Formatear hora sin segundos
        let timeHTML = '';
        if (event.event_time) {
            // Eliminar segundos del formato HH:MM:SS → HH:MM
            const timeWithoutSeconds = event.event_time.substring(0, 5);
            
            timeHTML = `
                <div class="event-time">
                    <i class="bi bi-clock-fill"></i>
                    ${timeWithoutSeconds}
                </div>
            `;
        }
    
        eventItem.innerHTML = `
            <div class="event-card">
                <div class="event-date-box">
                    <div class="event-month">${month}</div>
                    <div class="event-day">${day}</div>
                    <div class="event-year">${year}</div>
                </div>
                <h3 class="event-name">${escapeHtml(event.event_name || 'Untitled Event')}</h3>
                <div class="event-location">
                    <i class="bi bi-geo-alt-fill"></i>
                    ${escapeHtml(event.event_location || 'Location TBA')}
                </div>
                ${timeHTML}
                ${event.event_url ? `
                    <a href="${escapeHtml(event.event_url)}" target="_blank" rel="noopener noreferrer" class="event-ticket-btn">
                        <i class="bi bi-ticket-perforated"></i>
                        Get Tickets
                    </a>
                ` : ''}
            </div>
        `;
        
        return eventItem;
    }
    
    function setupEventsToggleButton() {
        console.log('%c[EVENT HANDLER] setupEventsToggleButton() called', 'color: #2196F3; font-weight: bold');
        
        const buttonContainer = document.getElementById('events-button-container');
        const toggleButton = document.getElementById('toggleEvents');
        
        if (!buttonContainer || !toggleButton) {
            console.error('[EVENT HANDLER] Button elements not found!');
            return;
        }
        
        console.log('[EVENT HANDLER] Total events:', allEventsData.length);
        console.log('[EVENT HANDLER] Events to show initial:', eventsToShowInitial);
        console.log('[EVENT HANDLER] Should show button:', allEventsData.length > eventsToShowInitial);
        
        if (allEventsData.length > eventsToShowInitial) {
            console.log('%c[EVENT HANDLER] Showing toggle button', 'color: #4CAF50; font-weight: bold');
            showElement('events-button-container');
            
            // Clonar botón para limpiar listeners
            const newToggleButton = toggleButton.cloneNode(true);
            toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
            
            // Agregar listener
            newToggleButton.addEventListener('click', function(e) {
                console.log('%c[EVENT HANDLER] Toggle button clicked!', 'color: #FF5722; font-weight: bold');
                e.preventDefault();
                toggleEventsVisibility();
            });
            
            console.log('[EVENT HANDLER] Button listener attached');
        } else {
            console.log('[EVENT HANDLER] NOT showing toggle button (not enough events)');
            hideEventsButton();
        }
    }
    
    function toggleEventsVisibility() {
        console.log('%c[EVENT HANDLER] toggleEventsVisibility() called', 'color: #FF5722; font-weight: bold');
        console.log('[EVENT HANDLER] Current state - eventsExpanded:', eventsExpanded);
        
        const eventsAdditional = document.getElementById('events-additional');
        const toggleButton = document.getElementById('toggleEvents');
        const buttonText = toggleButton.querySelector('.button-text');
        const chevronIcon = toggleButton.querySelector('.chevron-icon');
        
        if (!eventsExpanded) {
            console.log('%c[EVENT HANDLER] EXPANDING events', 'color: #4CAF50; font-weight: bold');
            
            // Mostrar adicionales
            showElement('events-additional');
            
            console.log('[EVENT HANDLER] After show - display:', eventsAdditional.style.display);
            console.log('[EVENT HANDLER] After show - computed:', window.getComputedStyle(eventsAdditional).display);
            
            // Animar
            setTimeout(() => {
                applyEventsStaggeredAnimations('events-additional');
            }, 50);
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Show Less';
            if (toggleButton) toggleButton.classList.add('active');
            eventsExpanded = true;
            
            console.log('[EVENT HANDLER] State updated - eventsExpanded:', eventsExpanded);
            
        } else {
            console.log('%c[EVENT HANDLER] COLLAPSING events', 'color: #FF9800; font-weight: bold');
            
            // Ocultar adicionales
            hideElement('events-additional');
            
            console.log('[EVENT HANDLER] After hide - display:', eventsAdditional.style.display);
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'View All Events';
            if (toggleButton) toggleButton.classList.remove('active');
            eventsExpanded = false;
            
            console.log('[EVENT HANDLER] State updated - eventsExpanded:', eventsExpanded);
            
            // Scroll
            setTimeout(() => {
                const eventsSection = document.getElementById('events');
                if (eventsSection) {
                    eventsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    }
    
    function applyEventsStaggeredAnimations(containerId) {
        console.log('[EVENT HANDLER] Applying animations to:', containerId);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('[EVENT HANDLER] Animation container not found:', containerId);
            return;
        }
        
        const items = container.querySelectorAll('.event-item');
        console.log('[EVENT HANDLER] Animating', items.length, 'items');
        
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
    
    function showNoEventsState() {
        console.log('[EVENT HANDLER] Showing no events state');
        showElement('no-events-state');
        hideElement('events-grid');
        hideElement('events-additional');
        hideEventsButton();
    }
    
    function hideNoEventsState() {
        hideElement('no-events-state');
    }
    
    function hideEventsButton() {
        hideElement('events-button-container');
    }
    
    function showErrorState() {
        console.log('[EVENT HANDLER] Showing error state');
        const eventsContainer = document.querySelector('.events-container');
        if (eventsContainer) {
            eventsContainer.innerHTML = `
                <div class="no-events-state" style="display: flex;">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h3 class="empty-title">Unable to Load Events</h3>
                        <p class="empty-text">Please try again later</p>
                        <button class="btn-events-toggle" onclick="location.reload()" style="margin-top: 20px;">
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
            console.error('[EVENT HANDLER] showElement - Element not found:', elementId);
            return;
        }
        
        console.log('[EVENT HANDLER] Showing element:', elementId);
        console.log('  Before - style.display:', element.style.display);
        
        // CRITICAL: Limpiar style inline
        element.style.display = '';
        
        console.log('  After - style.display:', element.style.display);
        console.log('  Computed display:', window.getComputedStyle(element).display);
    }
    
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        console.log('[EVENT HANDLER] Hiding element:', elementId);
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
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    
    // ========== FUNCIONES GLOBALES ==========
    
    window.refreshFrontendEvents = function() {
        console.log('%c[EVENT HANDLER] Refresh requested', 'color: #00BCD4; font-weight: bold');
        eventsExpanded = false;
        initializeEvents();
    };
    
    window.debugFrontendEvents = function() {
        console.log('%c=== FRONTEND EVENTS DEBUG ===', 'color: #00BCD4; font-weight: bold');
        console.log('Total events:', allEventsData.length);
        console.log('Events expanded:', eventsExpanded);
        console.log('Initial to show:', eventsToShowInitial);
        
        // Mostrar orden de eventos
        if (allEventsData.length > 0) {
            const now = new Date();
            now.setHours(0, 0, 0, 0);
            
            console.log('\n📅 Event Order:');
            allEventsData.forEach((event, index) => {
                const eventDate = new Date(event.event_date + 'T00:00:00');
                const isPast = eventDate < now;
                const status = isPast ? '⏮️ PAST' : '⏭️ UPCOMING';
                const color = isPast ? 'color: #ff9800' : 'color: #4CAF50';
                
                console.log(`%c${index + 1}. ${status} - ${event.event_date} - ${event.event_name}`, color);
            });
            console.log('\n');
        }
        
        checkElements();
        console.log('%c============================', 'color: #00BCD4; font-weight: bold');
    };
    
    console.log('%c[EVENT HANDLER] Script initialization complete', 'color: #26e3ff; font-weight: bold');
    
})();
