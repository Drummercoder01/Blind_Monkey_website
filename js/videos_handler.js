// videos_handler.js - Frontend Videos Handler with CSS Grid
(function() {
    'use strict';
    
    console.log('%c[VIDEOS HANDLER] Script loaded', 'color: #26e3ff; font-weight: bold');
    
    // ========== VARIABLES LOCALES ==========
    let allVideosData = [];
    let videosToShowInitial = 4;
    let videosExpanded = false;
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('videos')) {
            console.log('%c[VIDEOS HANDLER] Videos section not found - exiting', 'color: #ff0000');
            return;
        }
        
        console.log('%c[VIDEOS HANDLER] Videos section found - initializing...', 'color: #4CAF50');
        initializeVideos();
    });
    
    // ========== FUNCIONES PRINCIPALES ==========
    
    function initializeVideos() {
        console.log('[VIDEOS HANDLER] initializeVideos() called');
        loadVideosFromDB();
    }
    
    function loadVideosFromDB() {
        console.log('[VIDEOS HANDLER] Loading videos from database...');
        
        // Mostrar loading state
        showElement('videos-loading');
        hideElement('no-videos-state');
        hideElement('videos-button-container');
        hideElement('videos-grid');
        hideElement('videos-additional');
        
        fetch('../scripts/get_videos.php')
            .then(response => {
                console.log('[VIDEOS HANDLER] Fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[VIDEOS HANDLER] Data received:', data);
                hideLoadingState();
                
                if (data.status === 'success' && data.videos && data.videos.length > 0) {
                    // Ordenar por video_order (ascendente - el orden configurado en admin)
                    allVideosData = data.videos.sort((a, b) => {
                        return (a.video_order || 999) - (b.video_order || 999);
                    });
                    
                    console.log('%c[VIDEOS HANDLER] Successfully loaded ' + allVideosData.length + ' videos', 'color: #4CAF50; font-weight: bold');
                    renderVideos();
                    setupVideosToggleButton();
                } else {
                    console.log('%c[VIDEOS HANDLER] No videos found', 'color: #ff9800');
                    showNoVideosState();
                }
            })
            .catch(error => {
                console.error('%c[VIDEOS HANDLER] Error loading videos:', 'color: #f44336; font-weight: bold', error);
                hideLoadingState();
                showErrorState();
            });
    }
    
    function renderVideos() {
        console.log('[VIDEOS HANDLER] renderVideos() called');
        
        const videosGrid = document.getElementById('videos-grid');
        const videosAdditional = document.getElementById('videos-additional');
        
        if (!videosGrid || !videosAdditional) {
            console.error('[VIDEOS HANDLER] Grid containers not found');
            return;
        }
        
        // Limpiar containers
        videosGrid.innerHTML = '';
        videosAdditional.innerHTML = '';
        
        // Separar videos
        const initialVideos = allVideosData.slice(0, videosToShowInitial);
        const additionalVideos = allVideosData.slice(videosToShowInitial);
        
        console.log('[VIDEOS HANDLER] Initial videos:', initialVideos.length);
        console.log('[VIDEOS HANDLER] Additional videos:', additionalVideos.length);
        
        // Renderizar videos iniciales
        initialVideos.forEach((video, index) => {
            const videoElement = createVideoElement(video, index);
            videosGrid.appendChild(videoElement);
        });
        
        // Renderizar videos adicionales
        additionalVideos.forEach((video, index) => {
            const videoElement = createVideoElement(video, index + videosToShowInitial);
            videosAdditional.appendChild(videoElement);
        });
        
        // Mostrar el grid inicial
        showElement('videos-grid');
        
        // Animar entrada
        setTimeout(() => {
            applyVideosStaggeredAnimations('videos-grid');
        }, 100);
    }
    
    function createVideoElement(video, index) {
        // CRITICAL: Crear solo el div item, sin clases de Bootstrap
        const videoItem = document.createElement('div');
        videoItem.className = 'video-item';
        videoItem.setAttribute('data-video-id', video.id || index);
        
        // Procesar iframe para que sea responsivo y no autoplay
        let processedIframe = video.iframe
            .replace(/width=["']\d+["']/g, '')
            .replace(/height=["']\d+["']/g, '')
            .replace(/autoplay=1/g, 'autoplay=0');
        
        // Si no tiene autoplay=0, agregarlo
        if (!processedIframe.includes('autoplay=')) {
            processedIframe = processedIframe.replace(/src="([^"]+)"/, 'src="$1?autoplay=0"');
        }
        
        // Agregar estilos responsivos
        processedIframe = processedIframe.replace(
            /<iframe/g, 
            '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; border-radius: 12px;"'
        );
        
        // Estructura profesional
        videoItem.innerHTML = `
            <div class="video-card">
                <div class="video-embed-container">
                    ${processedIframe}
                </div>
            </div>
        `;
        
        return videoItem;
    }
    
    function setupVideosToggleButton() {
        console.log('[VIDEOS HANDLER] setupVideosToggleButton() called');
        
        const buttonContainer = document.getElementById('videos-button-container');
        const toggleButton = document.getElementById('toggleVideos');
        
        if (!buttonContainer || !toggleButton) {
            console.error('[VIDEOS HANDLER] Button elements not found');
            return;
        }
        
        console.log('[VIDEOS HANDLER] Total videos:', allVideosData.length);
        console.log('[VIDEOS HANDLER] Should show button:', allVideosData.length > videosToShowInitial);
        
        if (allVideosData.length > videosToShowInitial) {
            console.log('%c[VIDEOS HANDLER] Showing toggle button', 'color: #4CAF50; font-weight: bold');
            showElement('videos-button-container');
            
            // Clonar botón para limpiar listeners
            const newToggleButton = toggleButton.cloneNode(true);
            toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
            
            // Agregar listener
            newToggleButton.addEventListener('click', function(e) {
                console.log('%c[VIDEOS HANDLER] Toggle button clicked!', 'color: #FF5722; font-weight: bold');
                e.preventDefault();
                toggleVideoVisibility();
            });
            
            console.log('[VIDEOS HANDLER] Button listener attached');
        } else {
            console.log('[VIDEOS HANDLER] NOT showing toggle button');
            hideElement('videos-button-container');
        }
    }
    
    function toggleVideoVisibility() {
        console.log('%c[VIDEOS HANDLER] toggleVideoVisibility() called', 'color: #FF5722; font-weight: bold');
        console.log('[VIDEOS HANDLER] Current state - videosExpanded:', videosExpanded);
        
        const videosAdditional = document.getElementById('videos-additional');
        const toggleButton = document.getElementById('toggleVideos');
        const buttonText = toggleButton.querySelector('.button-text');
        const chevronIcon = toggleButton.querySelector('.chevron-icon');
        
        if (!videosExpanded) {
            console.log('%c[VIDEOS HANDLER] EXPANDING videos', 'color: #4CAF50; font-weight: bold');
            
            // Mostrar adicionales
            showElement('videos-additional');
            
            // Animar
            setTimeout(() => {
                applyVideosStaggeredAnimations('videos-additional');
            }, 50);
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Show Less';
            if (toggleButton) toggleButton.classList.add('active');
            videosExpanded = true;
            
            console.log('[VIDEOS HANDLER] State updated - videosExpanded:', videosExpanded);
            
        } else {
            console.log('%c[VIDEOS HANDLER] COLLAPSING videos', 'color: #FF9800; font-weight: bold');
            
            // Ocultar adicionales
            hideElement('videos-additional');
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Watch More Videos';
            if (toggleButton) toggleButton.classList.remove('active');
            videosExpanded = false;
            
            console.log('[VIDEOS HANDLER] State updated - videosExpanded:', videosExpanded);
            
            // Scroll
            setTimeout(() => {
                const videosSection = document.getElementById('videos');
                if (videosSection) {
                    videosSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    }
    
    function applyVideosStaggeredAnimations(containerId) {
        console.log('[VIDEOS HANDLER] Applying animations to:', containerId);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('[VIDEOS HANDLER] Animation container not found:', containerId);
            return;
        }
        
        const items = container.querySelectorAll('.video-item');
        console.log('[VIDEOS HANDLER] Animating', items.length, 'items');
        
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
    
    function hideLoadingState() {
        console.log('[VIDEOS HANDLER] Hiding loading state');
        hideElement('videos-loading');
    }
    
    function showNoVideosState() {
        console.log('[VIDEOS HANDLER] Showing no videos state');
        showElement('no-videos-state');
        hideElement('videos-grid');
        hideElement('videos-additional');
    }
    
    function showErrorState() {
        console.log('[VIDEOS HANDLER] Showing error state');
        const videosContainer = document.querySelector('.videos-container');
        if (videosContainer) {
            videosContainer.innerHTML = `
                <div class="no-videos-state" style="display: flex;">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <h3 class="empty-title">Unable to Load Videos</h3>
                        <p class="empty-text">Please try again later</p>
                        <button class="btn-videos-toggle" onclick="location.reload()" style="margin-top: 20px;">
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
            console.error('[VIDEOS HANDLER] showElement - Element not found:', elementId);
            return;
        }
        
        console.log('[VIDEOS HANDLER] Showing element:', elementId);
        
        // CRITICAL: Limpiar style inline para que CSS tome control
        element.style.display = '';
    }
    
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        console.log('[VIDEOS HANDLER] Hiding element:', elementId);
        element.style.display = 'none';
    }
    
    // ========== FUNCIONES GLOBALES ==========
    
    window.refreshFrontendVideos = function() {
        console.log('%c[VIDEOS HANDLER] Refresh requested', 'color: #00BCD4; font-weight: bold');
        videosExpanded = false;
        initializeVideos();
    };
    
    window.debugFrontendVideos = function() {
        console.log('%c=== FRONTEND VIDEOS DEBUG ===', 'color: #00BCD4; font-weight: bold');
        console.log('Total videos:', allVideosData.length);
        console.log('Videos expanded:', videosExpanded);
        console.log('Initial to show:', videosToShowInitial);
        console.log('videos-grid display:', document.getElementById('videos-grid')?.style.display);
        console.log('videos-additional display:', document.getElementById('videos-additional')?.style.display);
        console.log('%c============================', 'color: #00BCD4; font-weight: bold');
    };
    
    console.log('%c[VIDEOS HANDLER] Script initialization complete', 'color: #26e3ff; font-weight: bold');
    
})();
