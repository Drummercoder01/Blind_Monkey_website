// photos_handler.js - Frontend Photos Handler with CSS Grid
(function() {
    'use strict';
    
    console.log('%c[PHOTOS HANDLER] Script loaded', 'color: #26e3ff; font-weight: bold');
    
    // ========== VARIABLES LOCALES ==========
    let allPhotosData = [];
    let photosToShowInitial = 8;
    let photosExpanded = false;
    let currentLightboxIndex = 0;
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('photos-1')) {
            console.log('%c[PHOTOS HANDLER] Photos section not found - exiting', 'color: #ff0000');
            return;
        }
        
        console.log('%c[PHOTOS HANDLER] Photos section found - initializing...', 'color: #4CAF50');
        initializePhotos();
        initializeLightbox();
    });
    
    // ========== FUNCIONES PRINCIPALES ==========
    
    function initializePhotos() {
        console.log('[PHOTOS HANDLER] initializePhotos() called');
        loadPhotosFromDB();
    }
    
    function loadPhotosFromDB() {
        console.log('[PHOTOS HANDLER] Loading photos from database...');
        
        // Mostrar loading
        showElement('photos-loading');
        hideElement('no-photos-state');
        hideElement('photos-button-container');
        
        fetch('../scripts/get_photos.php')
            .then(response => {
                console.log('[PHOTOS HANDLER] Fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[PHOTOS HANDLER] Data received:', data);
                hideLoadingState();
                
                if (data.status === 'success' && data.photos && data.photos.length > 0) {
                    // Ordenar por img_order (configurado en admin)
                    allPhotosData = data.photos.sort((a, b) => {
                        return (a.img_order || 999) - (b.img_order || 999);
                    });
                    
                    console.log('%c[PHOTOS HANDLER] Successfully loaded ' + allPhotosData.length + ' photos', 'color: #4CAF50; font-weight: bold');
                    renderPhotos();
                    setupPhotosToggleButton();
                } else {
                    console.log('%c[PHOTOS HANDLER] No photos found', 'color: #ff9800');
                    showNoPhotosState();
                }
            })
            .catch(error => {
                console.error('%c[PHOTOS HANDLER] Error loading photos:', 'color: #f44336; font-weight: bold', error);
                hideLoadingState();
                showErrorState();
            });
    }
    
    function renderPhotos() {
        console.log('[PHOTOS HANDLER] renderPhotos() called');
        
        const galleryContainer = document.getElementById('gallery-container');
        
        if (!galleryContainer) {
            console.error('[PHOTOS HANDLER] Gallery container not found');
            return;
        }
        
        // Limpiar container
        galleryContainer.innerHTML = '';
        
        // Crear grids para inicial y adicional
        const initialGrid = document.createElement('div');
        initialGrid.id = 'photos-grid';
        initialGrid.className = 'photos-grid';
        
        const additionalGrid = document.createElement('div');
        additionalGrid.id = 'photos-additional';
        additionalGrid.className = 'photos-grid';
        additionalGrid.style.display = 'none';
        
        // Separar fotos
        const initialPhotos = allPhotosData.slice(0, photosToShowInitial);
        const additionalPhotos = allPhotosData.slice(photosToShowInitial);
        
        console.log('[PHOTOS HANDLER] Initial photos:', initialPhotos.length);
        console.log('[PHOTOS HANDLER] Additional photos:', additionalPhotos.length);
        
        // Renderizar fotos iniciales
        initialPhotos.forEach((photo, index) => {
            const photoElement = createPhotoElement(photo, index);
            initialGrid.appendChild(photoElement);
        });
        
        // Renderizar fotos adicionales
        additionalPhotos.forEach((photo, index) => {
            const photoElement = createPhotoElement(photo, index + photosToShowInitial);
            additionalGrid.appendChild(photoElement);
        });
        
        // Agregar grids al container
        galleryContainer.appendChild(initialGrid);
        galleryContainer.appendChild(additionalGrid);
        
        // Animar entrada
        setTimeout(() => {
            applyPhotosStaggeredAnimations('photos-grid');
        }, 100);
    }
    
    function createPhotoElement(photo, index) {
        const photoItem = document.createElement('div');
        photoItem.className = 'photo-item';
        photoItem.setAttribute('data-photo-id', photo.id || index);
        photoItem.setAttribute('data-index', index);
        
        const img = document.createElement('img');
        img.src = photo.img_path;
        img.alt = `Photo ${index + 1}`;
        img.loading = 'lazy';
        img.className = 'photo-image';
        
        // Click para abrir lightbox
        img.addEventListener('click', () => {
            openLightbox(index);
        });
        
        photoItem.appendChild(img);
        
        return photoItem;
    }
    
    function setupPhotosToggleButton() {
        console.log('[PHOTOS HANDLER] setupPhotosToggleButton() called');
        
        const buttonContainer = document.getElementById('photos-button-container');
        const toggleButton = document.getElementById('togglePhotos');
        
        if (!buttonContainer || !toggleButton) {
            console.error('[PHOTOS HANDLER] Button elements not found');
            return;
        }
        
        console.log('[PHOTOS HANDLER] Total photos:', allPhotosData.length);
        console.log('[PHOTOS HANDLER] Should show button:', allPhotosData.length > photosToShowInitial);
        
        if (allPhotosData.length > photosToShowInitial) {
            console.log('%c[PHOTOS HANDLER] Showing toggle button', 'color: #4CAF50; font-weight: bold');
            showElement('photos-button-container');
            
            // Remover clase d-none si existe
            buttonContainer.classList.remove('d-none');
            
            // Clonar botón para limpiar listeners
            const newToggleButton = toggleButton.cloneNode(true);
            toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
            
            // Agregar listener
            newToggleButton.addEventListener('click', function(e) {
                console.log('%c[PHOTOS HANDLER] Toggle button clicked!', 'color: #FF5722; font-weight: bold');
                e.preventDefault();
                togglePhotosVisibility();
            });
            
            console.log('[PHOTOS HANDLER] Button listener attached');
        } else {
            console.log('[PHOTOS HANDLER] NOT showing toggle button');
            hideElement('photos-button-container');
        }
    }
    
    function togglePhotosVisibility() {
        console.log('%c[PHOTOS HANDLER] togglePhotosVisibility() called', 'color: #FF5722; font-weight: bold');
        console.log('[PHOTOS HANDLER] Current state - photosExpanded:', photosExpanded);
        
        const photosAdditional = document.getElementById('photos-additional');
        const toggleButton = document.getElementById('togglePhotos');
        const buttonText = toggleButton.querySelector('.button-text');
        const chevronIcon = toggleButton.querySelector('.chevron-icon');
        
        if (!photosExpanded) {
            console.log('%c[PHOTOS HANDLER] EXPANDING photos', 'color: #4CAF50; font-weight: bold');
            
            // Mostrar adicionales
            showElement('photos-additional');
            
            // Animar
            setTimeout(() => {
                applyPhotosStaggeredAnimations('photos-additional');
            }, 50);
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Show Less';
            if (toggleButton) toggleButton.classList.add('active');
            photosExpanded = true;
            
            console.log('[PHOTOS HANDLER] State updated - photosExpanded:', photosExpanded);
            
        } else {
            console.log('%c[PHOTOS HANDLER] COLLAPSING photos', 'color: #FF9800; font-weight: bold');
            
            // Ocultar adicionales
            hideElement('photos-additional');
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Show More Photos';
            if (toggleButton) toggleButton.classList.remove('active');
            photosExpanded = false;
            
            console.log('[PHOTOS HANDLER] State updated - photosExpanded:', photosExpanded);
            
            // Scroll
            setTimeout(() => {
                const photosSection = document.getElementById('photos-1');
                if (photosSection) {
                    photosSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    }
    
    function applyPhotosStaggeredAnimations(containerId) {
        console.log('[PHOTOS HANDLER] Applying animations to:', containerId);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('[PHOTOS HANDLER] Animation container not found:', containerId);
            return;
        }
        
        const items = container.querySelectorAll('.photo-item');
        console.log('[PHOTOS HANDLER] Animating', items.length, 'items');
        
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            }, index * 80);
        });
    }
    
    // ========== LIGHTBOX FUNCTIONS ==========
    
    function initializeLightbox() {
        const lightbox = document.getElementById('lightbox');
        const closeBtn = lightbox?.querySelector('.close');
        const prevBtn = lightbox?.querySelector('.prev');
        const nextBtn = lightbox?.querySelector('.next');
        
        if (!lightbox) {
            console.log('[PHOTOS HANDLER] Lightbox not found');
            return;
        }
        
        // Click en lightbox para cerrar
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        // Botón cerrar
        if (closeBtn) {
            closeBtn.addEventListener('click', closeLightbox);
        }
        
        // Botones prev/next
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                showPrevPhoto();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                showNextPhoto();
            });
        }
        
        // Teclado
        document.addEventListener('keydown', (e) => {
            if (lightbox.classList.contains('show')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') showNextPhoto();
                if (e.key === 'ArrowLeft') showPrevPhoto();
            }
        });
        
        console.log('[PHOTOS HANDLER] Lightbox initialized');
    }
    
    function openLightbox(index) {
        console.log('[PHOTOS HANDLER] Opening lightbox for photo:', index);
        
        currentLightboxIndex = index;
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        
        if (lightbox && lightboxImg && allPhotosData[index]) {
            lightboxImg.src = allPhotosData[index].img_path;
            lightbox.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeLightbox() {
        console.log('[PHOTOS HANDLER] Closing lightbox');
        
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            lightbox.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
    
    function showNextPhoto() {
        currentLightboxIndex = (currentLightboxIndex + 1) % allPhotosData.length;
        const lightboxImg = document.getElementById('lightbox-img');
        if (lightboxImg) {
            lightboxImg.src = allPhotosData[currentLightboxIndex].img_path;
        }
    }
    
    function showPrevPhoto() {
        currentLightboxIndex = (currentLightboxIndex - 1 + allPhotosData.length) % allPhotosData.length;
        const lightboxImg = document.getElementById('lightbox-img');
        if (lightboxImg) {
            lightboxImg.src = allPhotosData[currentLightboxIndex].img_path;
        }
    }
    
    function hideLoadingState() {
        console.log('[PHOTOS HANDLER] Hiding loading state');
        hideElement('photos-loading');
    }
    
    function showNoPhotosState() {
        console.log('[PHOTOS HANDLER] Showing no photos state');
        showElement('no-photos-state');
        const noPhotosState = document.getElementById('no-photos-state');
        if (noPhotosState) {
            noPhotosState.classList.remove('d-none');
        }
    }
    
    function showErrorState() {
        console.log('[PHOTOS HANDLER] Showing error state');
        const galleryContainer = document.getElementById('gallery-container');
        if (galleryContainer) {
            galleryContainer.innerHTML = `
                <div class="no-photos-state" style="display: flex;">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-camera"></i>
                        </div>
                        <h3 class="empty-title">Unable to Load Photos</h3>
                        <p class="empty-text">Please try again later</p>
                        <button class="btn-photos-toggle" onclick="location.reload()" style="margin-top: 20px;">
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
            console.error('[PHOTOS HANDLER] showElement - Element not found:', elementId);
            return;
        }
        
        console.log('[PHOTOS HANDLER] Showing element:', elementId);
        
        // CRITICAL: Limpiar style inline para que CSS tome control
        element.style.display = '';
        element.classList.remove('d-none');
    }
    
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        console.log('[PHOTOS HANDLER] Hiding element:', elementId);
        element.style.display = 'none';
        element.classList.add('d-none');
    }
    
    // ========== FUNCIONES GLOBALES ==========
    
    window.refreshFrontendPhotos = function() {
        console.log('%c[PHOTOS HANDLER] Refresh requested', 'color: #00BCD4; font-weight: bold');
        photosExpanded = false;
        initializePhotos();
    };
    
    window.debugFrontendPhotos = function() {
        console.log('%c=== FRONTEND PHOTOS DEBUG ===', 'color: #00BCD4; font-weight: bold');
        console.log('Total photos:', allPhotosData.length);
        console.log('Photos expanded:', photosExpanded);
        console.log('Initial to show:', photosToShowInitial);
        console.log('Current lightbox index:', currentLightboxIndex);
        console.log('%c============================', 'color: #00BCD4; font-weight: bold');
    };
    
    console.log('%c[PHOTOS HANDLER] Script initialization complete', 'color: #26e3ff; font-weight: bold');
    
})();
