// ========== VARIABLES GLOBALES ==========
let allVideos = [];
let videosToShowInitial = 4;
let videosExpanded = false;

// ========== FUNCIONES PRINCIPALES ==========

/**
 * Inicializar la sección de videos
 */
function initializeVideos() {
    loadVideosFromDB();
}

/**
 * Cargar videos desde la base de datos
 */
function loadVideosFromDB() {
    fetch('../scripts/get_videos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            hideLoadingState();
            
            if (data.status === 'success' && data.videos && data.videos.length > 0) {
                allVideos = data.videos;
                renderVideos();
                setupToggleButton();
            } else {
                showNoVideosState();
            }
        })
        .catch(error => {
            console.error('Error loading videos:', error);
            hideLoadingState();
            showErrorState();
        });
}

/**
 * Extraer YouTube ID del iframe
 */
function extractYouTubeId(iframe) {
    const srcMatch = iframe.match(/src="([^"]*?)"/);
    if (!srcMatch) return null;
    
    const src = srcMatch[1];
    const idMatch = src.match(/embed\/([a-zA-Z0-9_-]+)/);
    return idMatch ? idMatch[1] : null;
}

/**
 * Renderizar videos en el DOM
 */
function renderVideos() {
    const videosGrid = document.getElementById('videos-grid');
    const videosAdditional = document.getElementById('videos-additional');
    
    if (!videosGrid || !videosAdditional) return;
    
    videosGrid.innerHTML = '';
    videosAdditional.innerHTML = '';
    
    const initialVideos = allVideos.slice(0, videosToShowInitial);
    const additionalVideos = allVideos.slice(videosToShowInitial);
    
    initialVideos.forEach((video, index) => {
        const videoElement = createVideoElement(video, index);
        videosGrid.appendChild(videoElement);
    });
    
    additionalVideos.forEach((video, index) => {
        const videoElement = createVideoElement(video, index + videosToShowInitial);
        videosAdditional.appendChild(videoElement);
    });
    
    videosGrid.style.display = 'flex';
    applyStaggeredAnimations();
}

/**
 * Crear elemento de video individual - MUESTRA VIDEO DIRECTO
 */
function createVideoElement(video, index) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col video-item';
    colDiv.style.animationDelay = `${index * 0.1}s`;
    
    // Procesar el iframe para que no se reproduzca automáticamente
    let processedIframe = video.iframe
        .replace(/width=["']\d+["']/g, '')
        .replace(/height=["']\d+["']/g, '')
        .replace(/autoplay=1/g, 'autoplay=0')
        .replace(/<iframe/g, '<iframe width="100%" height="100%" style="border: none; border-radius: 12px;"');
    
    // Si no tiene autoplay=0, agregarlo
    if (!processedIframe.includes('autoplay=')) {
        processedIframe = processedIframe.replace(/src="([^"]+)"/, 'src="$1?autoplay=0"');
    }
    
    colDiv.innerHTML = `
        <div class="card h-100 bg-black border-white video-card" style="border-width: 1.5px !important;">
            <div class="video-wrapper">
                <div class="video-embed-container">
                    ${processedIframe}
                </div>
            </div>
        </div>
    `;
    
    return colDiv;
}

// Funciones de thumbnail eliminadas - ya no son necesarias

/**
 * Configurar el botón de toggle
 */
function setupToggleButton() {
    const buttonContainer = document.getElementById('videos-button-container');
    const toggleButton = document.getElementById('toggleVideos');
    
    if (!buttonContainer || !toggleButton) return;
    
    if (allVideos.length > videosToShowInitial) {
        buttonContainer.style.display = 'block';
        toggleButton.addEventListener('click', toggleVideoVisibility);
    }
}

/**
 * Toggle para mostrar/ocultar videos adicionales
 */
function toggleVideoVisibility() {
    const videosAdditional = document.getElementById('videos-additional');
    const toggleButton = document.getElementById('toggleVideos');
    const buttonText = toggleButton.querySelector('.button-text');
    const buttonIcon = toggleButton.querySelector('i');
    
    if (!videosExpanded) {
        videosAdditional.style.display = 'flex';
        
        const additionalItems = videosAdditional.querySelectorAll('.video-item');
        additionalItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('animate-in');
            }, index * 100);
        });
        
        buttonText.textContent = 'Show fewer videos';
        buttonIcon.className = 'bi bi-eye-slash me-2';
        videosExpanded = true;
        
    } else {
        videosAdditional.style.display = 'none';
        buttonText.textContent = 'Watch more videos';
        buttonIcon.className = 'bi bi-play-circle me-2';
        videosExpanded = false;
        
        document.getElementById('videos').scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Aplicar animaciones escalonadas a los videos
 */
function applyStaggeredAnimations() {
    const videoItems = document.querySelectorAll('.video-item');
    
    videoItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate-in');
        }, index * 150);
    });
}

/**
 * Ocultar estado de carga
 */
function hideLoadingState() {
    const loadingState = document.getElementById('videos-loading');
    if (loadingState) {
        loadingState.style.display = 'none';
    }
}

/**
 * Mostrar estado sin videos
 */
function showNoVideosState() {
    const noVideosState = document.getElementById('no-videos-state');
    if (noVideosState) {
        noVideosState.style.display = 'block';
    }
}

/**
 * Mostrar estado de error
 */
function showErrorState() {
    const videosContainer = document.querySelector('.videos-container');
    if (videosContainer) {
        videosContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <p class="text-white mt-3 fs-5">Unable to load videos at the moment</p>
                <button class="btn mt-2" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                </button>
            </div>
        `;
    }
}

// ========== INICIALIZACIÓN ==========

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en una página que tiene la sección de videos
    if (document.getElementById('videos')) {
        initializeVideos();
    }
});

// Reinicializar si se navega de vuelta a la sección (para SPAs)
if (typeof window !== 'undefined') {
    window.initializeVideos = initializeVideos;
}