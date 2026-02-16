// music_handler.js - Frontend Music Handler with CSS Grid
(function() {
    'use strict';
    
    console.log('%c[MUSIC HANDLER] Script loaded', 'color: #26e3ff; font-weight: bold');
    
    // ========== VARIABLES LOCALES ==========
    let allSongsData = [];
    let songsToShowInitial = 4;
    let musicExpanded = false;
    
    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('music')) {
            console.log('%c[MUSIC HANDLER] Music section not found - exiting', 'color: #ff0000');
            return;
        }
        
        console.log('%c[MUSIC HANDLER] Music section found - initializing...', 'color: #4CAF50');
        initializeMusic();
    });
    
    // ========== FUNCIONES PRINCIPALES ==========
    
    function initializeMusic() {
        console.log('[MUSIC HANDLER] initializeMusic() called');
        loadSongsFromDB();
    }
    
    function loadSongsFromDB() {
        console.log('[MUSIC HANDLER] Loading songs from database...');
        
        // Ocultar estados iniciales
        hideElement('music-content');
        hideElement('no-music-state');
        hideElement('music-button-container');
        
        fetch('../scripts/get_songs.php')
            .then(response => {
                console.log('[MUSIC HANDLER] Fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[MUSIC HANDLER] Data received:', data);
                
                if (data.status === 'success' && data.songs?.length > 0) {
                    allSongsData = data.songs;
                    console.log('%c[MUSIC HANDLER] Successfully loaded ' + allSongsData.length + ' songs', 'color: #4CAF50; font-weight: bold');
                    processSongs();
                } else {
                    console.log('%c[MUSIC HANDLER] No songs found', 'color: #ff9800');
                    showNoMusicState();
                }
            })
            .catch(error => {
                console.error('%c[MUSIC HANDLER] Error loading music:', 'color: #f44336; font-weight: bold', error);
                showErrorState();
            });
    }
    
    function processSongs() {
        console.log('[MUSIC HANDLER] Processing songs...');
        
        if (allSongsData.length === 0) {
            showNoMusicState();
            return;
        }
        
        renderMusic();
        setupMusicToggleButton();
        showElement('music-content');
    }
    
    function renderMusic() {
        console.log('[MUSIC HANDLER] renderMusic() called');
        
        const musicGrid = document.getElementById('music-grid');
        const musicAdditional = document.getElementById('music-additional');
        
        if (!musicGrid || !musicAdditional) {
            console.error('[MUSIC HANDLER] Grid containers not found');
            return;
        }
        
        // Limpiar containers
        musicGrid.innerHTML = '';
        musicAdditional.innerHTML = '';
        
        // Separar canciones
        const initialSongs = allSongsData.slice(0, songsToShowInitial);
        const additionalSongs = allSongsData.slice(songsToShowInitial);
        
        console.log('[MUSIC HANDLER] Initial songs:', initialSongs.length);
        console.log('[MUSIC HANDLER] Additional songs:', additionalSongs.length);
        
        // Renderizar canciones iniciales
        initialSongs.forEach((song, index) => {
            musicGrid.appendChild(createSongElement(song, index));
        });
        
        // Renderizar canciones adicionales
        additionalSongs.forEach((song, index) => {
            musicAdditional.appendChild(createSongElement(song, index + songsToShowInitial));
        });
        
        // Animar entrada
        setTimeout(() => {
            applyMusicStaggeredAnimations('music-grid');
        }, 100);
    }
    
    function createSongElement(song, index) {
        // CRITICAL: Crear solo el div item, sin clases de Bootstrap
        const musicItem = document.createElement('div');
        musicItem.className = 'music-item';
        musicItem.setAttribute('data-song-id', song.id || index);
        
        // Procesar embed
        let safeEmbed = song.embed || '';
        if (safeEmbed && !safeEmbed.includes('sandbox')) {
            safeEmbed = safeEmbed.replace('<iframe', '<iframe sandbox="allow-same-origin allow-scripts allow-popups"');
        }
        
        // Hacer embed responsivo
        const responsiveEmbed = safeEmbed
            .replace(/width="[^"]*"/g, 'width="100%"')
            .replace(/height="[^"]*"/g, 'height="152"')
            .replace(/style="[^"]*"/g, '')
            .replace('<iframe', '<iframe style="width:100%;height:152px;border:none;border-radius:12px;"');
        
        // Estructura profesional
        musicItem.innerHTML = `
            <div class="song music-card">
                <h4>${escapeHtml(song.song_name || 'Untitled')}</h4>
                <div class="song-embed">
                    ${responsiveEmbed}
                </div>
            </div>
        `;
        
        return musicItem;
    }
    
    function setupMusicToggleButton() {
        console.log('[MUSIC HANDLER] setupMusicToggleButton() called');
        
        const buttonContainer = document.getElementById('music-button-container');
        const toggleButton = document.getElementById('toggleMusic');
        
        if (!buttonContainer || !toggleButton) {
            console.error('[MUSIC HANDLER] Button elements not found');
            return;
        }
        
        console.log('[MUSIC HANDLER] Total songs:', allSongsData.length);
        console.log('[MUSIC HANDLER] Should show button:', allSongsData.length > songsToShowInitial);
        
        if (allSongsData.length > songsToShowInitial) {
            console.log('%c[MUSIC HANDLER] Showing toggle button', 'color: #4CAF50; font-weight: bold');
            showElement('music-button-container');
            
            // Clonar botón para limpiar listeners
            const newToggleButton = toggleButton.cloneNode(true);
            toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
            
            // Agregar listener
            newToggleButton.addEventListener('click', function(e) {
                console.log('%c[MUSIC HANDLER] Toggle button clicked!', 'color: #FF5722; font-weight: bold');
                e.preventDefault();
                toggleMusicVisibility();
            });
            
            console.log('[MUSIC HANDLER] Button listener attached');
        } else {
            console.log('[MUSIC HANDLER] NOT showing toggle button');
            hideElement('music-button-container');
        }
    }
    
    function toggleMusicVisibility() {
        console.log('%c[MUSIC HANDLER] toggleMusicVisibility() called', 'color: #FF5722; font-weight: bold');
        console.log('[MUSIC HANDLER] Current state - musicExpanded:', musicExpanded);
        
        const musicAdditional = document.getElementById('music-additional');
        const toggleButton = document.getElementById('toggleMusic');
        const buttonText = toggleButton.querySelector('.button-text');
        const chevronIcon = toggleButton.querySelector('.chevron-icon');
        
        if (!musicExpanded) {
            console.log('%c[MUSIC HANDLER] EXPANDING music', 'color: #4CAF50; font-weight: bold');
            
            // Mostrar adicionales
            showElement('music-additional');
            
            // Animar
            setTimeout(() => {
                applyMusicStaggeredAnimations('music-additional');
            }, 50);
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Show Less';
            if (toggleButton) toggleButton.classList.add('active');
            musicExpanded = true;
            
            console.log('[MUSIC HANDLER] State updated - musicExpanded:', musicExpanded);
            
        } else {
            console.log('%c[MUSIC HANDLER] COLLAPSING music', 'color: #FF9800; font-weight: bold');
            
            // Ocultar adicionales
            hideElement('music-additional');
            
            // Cambiar botón
            if (buttonText) buttonText.textContent = 'Load More Tracks';
            if (toggleButton) toggleButton.classList.remove('active');
            musicExpanded = false;
            
            console.log('[MUSIC HANDLER] State updated - musicExpanded:', musicExpanded);
            
            // Scroll
            setTimeout(() => {
                const musicSection = document.getElementById('music');
                if (musicSection) {
                    musicSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        }
    }
    
    function applyMusicStaggeredAnimations(containerId) {
        console.log('[MUSIC HANDLER] Applying animations to:', containerId);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('[MUSIC HANDLER] Animation container not found:', containerId);
            return;
        }
        
        const items = container.querySelectorAll('.music-item');
        console.log('[MUSIC HANDLER] Animating', items.length, 'items');
        
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
    
    function showNoMusicState() {
        console.log('[MUSIC HANDLER] Showing no music state');
        showElement('no-music-state');
        hideElement('music-content');
    }
    
    function showErrorState() {
        console.log('[MUSIC HANDLER] Showing error state');
        const musicContainer = document.querySelector('.music-container');
        if (musicContainer) {
            musicContainer.innerHTML = `
                <div class="no-music-state" style="display: flex;">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bi bi-music-note-beamed"></i>
                        </div>
                        <h3 class="empty-title">Unable to Load Music</h3>
                        <p class="empty-text">Please try again later</p>
                        <button class="btn-music-toggle" onclick="location.reload()" style="margin-top: 20px;">
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
            console.error('[MUSIC HANDLER] showElement - Element not found:', elementId);
            return;
        }
        
        console.log('[MUSIC HANDLER] Showing element:', elementId);
        
        // CRITICAL: Limpiar style inline para que CSS tome control
        element.style.display = '';
    }
    
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        console.log('[MUSIC HANDLER] Hiding element:', elementId);
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
    
    window.refreshFrontendMusic = function() {
        console.log('%c[MUSIC HANDLER] Refresh requested', 'color: #00BCD4; font-weight: bold');
        musicExpanded = false;
        initializeMusic();
    };
    
    window.debugFrontendMusic = function() {
        console.log('%c=== FRONTEND MUSIC DEBUG ===', 'color: #00BCD4; font-weight: bold');
        console.log('Total songs:', allSongsData.length);
        console.log('Music expanded:', musicExpanded);
        console.log('Initial to show:', songsToShowInitial);
        console.log('music-grid display:', document.getElementById('music-grid')?.style.display);
        console.log('music-additional display:', document.getElementById('music-additional')?.style.display);
        console.log('%c============================', 'color: #00BCD4; font-weight: bold');
    };
    
    console.log('%c[MUSIC HANDLER] Script initialization complete', 'color: #26e3ff; font-weight: bold');
    
})();
