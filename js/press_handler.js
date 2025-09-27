// press_handler.js - sin spinner
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('press')) return;
    
    initializePress();
});

// ========== VARIABLES GLOBALES ==========
let allPressItems = [];
let pressToShowInitial = 3;
let pressExpanded = false;

// ========== FUNCIONES PRINCIPALES ==========

function initializePress() {
    loadPressFromDB();
}

function loadPressFromDB() {
    // Ocultar elementos al inicio
    showElement('no-press-state', false);
    showElement('press-button-container', false);
    showElement('press-grid', false);
    showElement('press-additional', false);
    
    fetch('../scripts/get_press.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success' && data.press_items && data.press_items.length > 0) {
                allPressItems = data.press_items;
                renderPress();
                setupPressToggleButton();
            } else {
                showNoPressState();
            }
        })
        .catch(error => {
            console.error('Error loading press:', error);
            showErrorState();
        });
}

function renderPress() {
    const pressGrid = document.getElementById('press-grid');
    const pressAdditional = document.getElementById('press-additional');
    
    if (!pressGrid || !pressAdditional) return;
    
    // Limpiar containers
    pressGrid.innerHTML = '';
    pressAdditional.innerHTML = '';
    
    // Separar items iniciales (3) y adicionales
    const initialPress = allPressItems.slice(0, pressToShowInitial);
    const additionalPress = allPressItems.slice(pressToShowInitial);
    
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
    
    // Mostrar el grid principal
    showElement('press-grid', true);
    
    // Aplicar animaciones escalonadas
    applyPressStaggeredAnimations();
}

function createPressElement(pressItem, index) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col press-item';
    colDiv.style.animationDelay = `${index * 0.1}s`;
    
    // Formatear fecha
    const pressDate = new Date(pressItem.press_time);
    const formattedDate = pressDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    colDiv.innerHTML = `
        <div class="card h-100 bg-black border-white press-card" style="border-width: 1.5px !important;">
            <div class="card-body">
                <div class="press-excerpt text-white fs-5 fst-italic">
                    "${pressItem.press_text}"
                </div>
                <div class="press-author text-gray-custom fw-bold py-3">
                    — <span class="press-name">${pressItem.press_author}</span>
                    ${pressItem.press_comment ? ', ' + pressItem.press_comment : ''}
                </div>
                <div class="press-link">
                    <a href="${pressItem.press_link}" target="_blank" class="text-decoration-none">
                        <div class="press-time text-gray-custom">${formattedDate}</div>
                    </a>
                </div>
            </div>
        </div>
    `;
    
    return colDiv;
}

function setupPressToggleButton() {
    const buttonContainer = document.getElementById('press-button-container');
    const toggleButton = document.getElementById('togglePress');
    
    if (!buttonContainer || !toggleButton) return;
    
    // Solo mostrar botón si hay más items de los iniciales (3)
    if (allPressItems.length > pressToShowInitial) {
        showElement('press-button-container', true);
        
        toggleButton.addEventListener('click', togglePressVisibility);
    }
}

function togglePressVisibility() {
    const pressAdditional = document.getElementById('press-additional');
    const toggleButton = document.getElementById('togglePress');
    const buttonText = toggleButton.querySelector('.button-text');
    const buttonIcon = toggleButton.querySelector('i');
    
    if (!pressExpanded) {
        // Expandir - mostrar items adicionales
        showElement('press-additional', true);
        
        // Animar entrada
        const additionalItems = pressAdditional.querySelectorAll('.press-item');
        additionalItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('animate-in');
            }, index * 100);
        });
        
        buttonText.textContent = 'Show less press';
        buttonIcon.className = 'bi bi-eye-slash me-2';
        pressExpanded = true;
        
    } else {
        // Contraer - ocultar items adicionales
        showElement('press-additional', false);
        buttonText.textContent = 'Read more press';
        buttonIcon.className = 'bi bi-newspaper me-2';
        pressExpanded = false;
        
        // Scroll suave hacia la sección de prensa
        document.getElementById('press').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

function applyPressStaggeredAnimations() {
    const pressItems = document.querySelectorAll('.press-item');
    
    pressItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate-in');
        }, index * 150);
    });
}

function showNoPressState() {
    showElement('no-press-state', true);
}

function showErrorState() {
    const pressContainer = document.querySelector('.press-container');
    if (pressContainer) {
        pressContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <p class="text-white mt-3 fs-5">Unable to load press at the moment</p>
                <button class="btn mt-2" onclick="initializePress()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                </button>
            </div>
        `;
    }
}

// Función auxiliar para mostrar/ocultar elementos
function showElement(elementId, show) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = show ? 'block' : 'none';
    }
}

window.refreshPress = function() {
    initializePress();
};