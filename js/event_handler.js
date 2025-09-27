// event_handler.js - Versión con diseño de dos columnas como Music
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en una página que tiene la sección de eventos
    if (!document.getElementById('events')) return;
    
    initializeEvents();
});

// ========== VARIABLES GLOBALES ==========
let allEvents = [];
let eventsToShowInitial = 4; // Número de eventos a mostrar inicialmente
let eventsExpanded = false;

// ========== FUNCIONES PRINCIPALES ==========

/**
 * Inicializar la sección de eventos
 */
function initializeEvents() {
    loadEventsFromDB();
}

/**
 * Cargar eventos desde la base de datos
 */
function loadEventsFromDB() {
    // Ocultar otros estados
    hideNoEventsState();
    hideEventsButton();
    showElement('events-grid', false);
    showElement('events-additional', false);
    
    fetch('../scripts/get_events.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success' && data.events && data.events.length > 0) {
                // Ordenar eventos por fecha (más reciente primero)
                allEvents = data.events.sort((a, b) => {
                    return new Date(b.event_date) - new Date(a.event_date);
                });
                renderEvents();
                setupEventsToggleButton();
            } else {
                showNoEventsState();
            }
        })
        .catch(error => {
            console.error('Error loading events:', error);
            showErrorState();
        });
}

/**
 * Renderizar eventos en el DOM
 */
function renderEvents() {
    const eventsGrid = document.getElementById('events-grid');
    const eventsAdditional = document.getElementById('events-additional');
    
    if (!eventsGrid || !eventsAdditional) return;
    
    // Limpiar containers
    eventsGrid.innerHTML = '';
    eventsAdditional.innerHTML = '';
    
    // Separar eventos iniciales y adicionales
    const initialEvents = allEvents.slice(0, eventsToShowInitial);
    const additionalEvents = allEvents.slice(eventsToShowInitial);
    
    // Renderizar eventos iniciales
    initialEvents.forEach((event, index) => {
        const eventElement = createEventElement(event, index);
        eventsGrid.appendChild(eventElement);
    });
    
    // Renderizar eventos adicionales
    additionalEvents.forEach((event, index) => {
        const eventElement = createEventElement(event, index + eventsToShowInitial);
        eventsAdditional.appendChild(eventElement);
    });
    
    // Mostrar el grid
    showElement('events-grid', true);
    
    // Aplicar animaciones escalonadas
    applyEventsStaggeredAnimations();
}

/**
 * Crear elemento de evento individual
 */
function createEventElement(event, index) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col event-item';
    colDiv.style.opacity = '0';
    colDiv.style.transform = 'translateY(20px)';
    colDiv.style.transition = 'all 0.5s ease';
    
    // Formatear fecha
    const eventDate = new Date(event.event_date);
    const formattedDate = eventDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Formatear hora si existe
    let timeHTML = '';
    if (event.event_time) {
        const eventTime = new Date('1970-01-01T' + event.event_time);
        const formattedTime = eventTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        timeHTML = `<p class='event-time text-white-50 mb-2'><i class='bi bi-clock me-1'></i>${formattedTime}</p>`;
    }

    // Crear estructura del evento
    colDiv.innerHTML = `
        <div class="card h-100 bg-dark border-light event-card">
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="event-date text-primary mb-0">${formattedDate}</h5>
                    <span class="badge bg-warning text-dark">Event</span>
                </div>
                ${timeHTML}
                <h4 class="event-name text-white mb-3">${event.event_name}</h4>
                <p class="event-location text-white-50">
                    <i class="bi bi-geo-alt me-1"></i>${event.event_location}
                </p>
                ${event.event_description ? `<p class="event-description text-white mt-auto">${event.event_description}</p>` : ''}
            </div>
        </div>
    `;
    
    return colDiv;
}

/**
 * Configurar el botón de toggle
 */
function setupEventsToggleButton() {
    const buttonContainer = document.getElementById('events-button-container');
    const toggleButton = document.getElementById('toggleEvents');
    
    if (!buttonContainer || !toggleButton) return;
    
    // Solo mostrar botón si hay más eventos de los iniciales
    if (allEvents.length > eventsToShowInitial) {
        showElement('events-button-container', true);
        
        // Clonar y reemplazar el botón para evitar múltiples event listeners
        const newToggleButton = toggleButton.cloneNode(true);
        toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
        newToggleButton.addEventListener('click', toggleEventsVisibility);
    }
}

/**
 * Toggle para mostrar/ocultar eventos adicionales
 */
function toggleEventsVisibility() {
    const eventsAdditional = document.getElementById('events-additional');
    const toggleButton = document.getElementById('toggleEvents');
    const buttonText = toggleButton.querySelector('.button-text');
    const buttonIcon = toggleButton.querySelector('i');
    
    if (!eventsExpanded) {
        // Expandir - mostrar eventos adicionales
        showElement('events-additional', true);
        
        // Animar entrada
        eventsAdditional.querySelectorAll('.event-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        buttonText.textContent = 'Show less events';
        buttonIcon.className = 'bi bi-eye-slash me-2';
        eventsExpanded = true;
        
    } else {
        // Contraer - ocultar eventos adicionales
        showElement('events-additional', false);
        buttonText.textContent = 'View more events';
        buttonIcon.className = 'bi bi-calendar-event me-2';
        eventsExpanded = false;
        
        // Scroll suave hacia la sección de eventos
        document.getElementById('events').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Aplicar animaciones escalonadas a los eventos
 */
function applyEventsStaggeredAnimations() {
    document.querySelectorAll('.event-item').forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 150);
    });
}

/**
 * Mostrar estado sin eventos
 */
function showNoEventsState() {
    showElement('no-events-state', true);
}

/**
 * Ocultar estado sin eventos
 */
function hideNoEventsState() {
    showElement('no-events-state', false);
}

/**
 * Ocultar botón de eventos
 */
function hideEventsButton() {
    showElement('events-button-container', false);
}

/**
 * Mostrar estado de error
 */
function showErrorState() {
    const eventsContainer = document.querySelector('.events-container');
    if (eventsContainer) {
        eventsContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <p class="text-white mt-3 fs-5">Unable to load events at the moment</p>
                <button class="btn btn-outline-light mt-2" onclick="initializeEvents()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                </button>
            </div>
        `;
    }
}

// Función auxiliar para mostrar/ocultar elementos
function showElement(elementId, show) {
    const element = document.getElementById(elementId);
    if (!element) return;
    element.style.display = show ? '' : 'none';
    if ((elementId === 'events-grid' || elementId === 'events-additional') && show) {
        element.style.display = 'flex';
    }
}

// ========== FUNCIÓN GLOBAL PARA RECARGAR ==========
window.refreshEvents = function() {
    initializeEvents();
};