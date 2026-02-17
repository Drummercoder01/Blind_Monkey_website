// ========== YOUTUBE BACKGROUND VIDEO PROFESIONAL ==========
// Video ID: b741UP5cbfY
// Características:
// - Solo desktop y tablet (>768px)
// - Mobile muestra imagen estática
// - Fallback a imagen si YouTube falla
// - Sin controles visibles
// - Loop infinito
// - Optimizado para performance

(function() {
    'use strict';
    
    console.log('%c🎥 YouTube Background Video Initializing...', 'color: #26e3ff; font-weight: bold');
    
    // ========== CONFIGURACIÓN ==========
    const CONFIG = {
        videoId: 'b741UP5cbfY',
        minWidth: 768, // Mínimo ancho para mostrar video
        fallbackImage: '../img/big-lights-city-banner.webp',
        overlayOpacity: 0.5 // Oscuridad del overlay
    };
    
    // ========== VARIABLES GLOBALES ==========
    let player = null;
    let isDesktop = window.innerWidth >= CONFIG.minWidth;
    let youtubeAPIReady = false;
    let playerReady = false;
    
    // ========== DETECTAR DISPOSITIVO ==========
    function checkDevice() {
        const width = window.innerWidth;
        isDesktop = width >= CONFIG.minWidth;
        
        console.log(`📱 Device: ${isDesktop ? 'Desktop/Tablet' : 'Mobile'} (${width}px)`);
        
        return isDesktop;
    }
    
    // ========== CREAR ESTRUCTURA HTML ==========
    function createVideoContainer() {
        // Buscar o crear el contenedor de background
        let backgroundDiv = document.querySelector('.background');
        
        if (!backgroundDiv) {
            backgroundDiv = document.createElement('div');
            backgroundDiv.className = 'background';
            document.body.insertBefore(backgroundDiv, document.body.firstChild);
        }
        
        // Limpiar contenido existente
        backgroundDiv.innerHTML = '';
        
        if (isDesktop) {
            // DESKTOP/TABLET: Crear contenedor para YouTube
            backgroundDiv.innerHTML = `
                <div id="youtube-background-container" style="opacity: 0; transition: opacity 0.5s ease;">
                    <div id="youtube-player"></div>
                </div>
                <div class="video-overlay"></div>
            `;
            
            console.log('✅ YouTube container created');
            
            // CRÍTICO: Aplicar estilos INMEDIATAMENTE después de crear el contenedor
            setTimeout(() => {
                forceAllContainerStyles();
            }, 50);
            
        } else {
            // MOBILE: Mantener imagen estática
            backgroundDiv.style.background = `
                linear-gradient(rgba(0, 0, 0, ${CONFIG.overlayOpacity}), rgba(0, 0, 0, ${CONFIG.overlayOpacity})),
                url('${CONFIG.fallbackImage}') no-repeat center center
            `;
            backgroundDiv.style.backgroundSize = 'cover';
            
            console.log('📱 Mobile: Using static image');
        }
        
        // Hacer visible el background
        setTimeout(() => {
            backgroundDiv.style.opacity = '1';
        }, 100);
    }
    
    // ========== FORZAR ESTILOS DE TODOS LOS CONTENEDORES ==========
    function forceAllContainerStyles() {
        // PASO 1: Forzar estilos del contenedor principal
        const backgroundDiv = document.querySelector('.background');
        if (backgroundDiv) {
            backgroundDiv.style.setProperty('position', 'fixed', 'important');
            backgroundDiv.style.setProperty('top', '0', 'important');
            backgroundDiv.style.setProperty('left', '0', 'important');
            backgroundDiv.style.setProperty('width', '100vw', 'important');
            backgroundDiv.style.setProperty('height', '100vh', 'important');
            backgroundDiv.style.setProperty('z-index', '-1', 'important');
            backgroundDiv.style.setProperty('overflow', 'hidden', 'important');
        }
        
        // PASO 2: Forzar estilos del contenedor de YouTube
        const youtubeContainer = document.getElementById('youtube-background-container');
        if (youtubeContainer) {
            youtubeContainer.style.setProperty('position', 'absolute', 'important');
            youtubeContainer.style.setProperty('top', '50%', 'important');
            youtubeContainer.style.setProperty('left', '50%', 'important');
            youtubeContainer.style.setProperty('width', '100vw', 'important');
            youtubeContainer.style.setProperty('height', '56.25vw', 'important');
            youtubeContainer.style.setProperty('min-height', '100vh', 'important');
            youtubeContainer.style.setProperty('min-width', '177.77vh', 'important');
            youtubeContainer.style.setProperty('transform', 'translate(-50%, -50%)', 'important');
            youtubeContainer.style.setProperty('pointer-events', 'none', 'important');
            youtubeContainer.style.setProperty('z-index', '0', 'important');
            console.log('✅ Container styles applied pre-emptively');
        }
        
        // PASO 3: Forzar estilos del player
        const youtubePlayer = document.getElementById('youtube-player');
        if (youtubePlayer) {
            youtubePlayer.style.setProperty('position', 'absolute', 'important');
            youtubePlayer.style.setProperty('top', '0', 'important');
            youtubePlayer.style.setProperty('left', '0', 'important');
            youtubePlayer.style.setProperty('width', '100%', 'important');
            youtubePlayer.style.setProperty('height', '100%', 'important');
            youtubePlayer.style.setProperty('pointer-events', 'none', 'important');
        }
    }
    
    // ========== CARGAR YOUTUBE API ==========
    function loadYouTubeAPI() {
        return new Promise((resolve, reject) => {
            // Verificar si ya está cargada
            if (window.YT && window.YT.Player) {
                console.log('✅ YouTube API already loaded');
                resolve();
                return;
            }
            
            // Crear callback global
            window.onYouTubeIframeAPIReady = function() {
                console.log('✅ YouTube API loaded');
                youtubeAPIReady = true;
                resolve();
            };
            
            // Cargar script
            const tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            tag.onerror = () => {
                console.error('❌ Failed to load YouTube API');
                reject(new Error('YouTube API failed to load'));
            };
            
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            
            console.log('⏳ Loading YouTube API...');
            
            // Timeout de 10 segundos
            setTimeout(() => {
                if (!youtubeAPIReady) {
                    reject(new Error('YouTube API timeout'));
                }
            }, 10000);
        });
    }
    
    // ========== CREAR YOUTUBE PLAYER ==========
    function createPlayer() {
        if (!isDesktop) {
            console.log('📱 Skipping player creation (mobile)');
            return;
        }
        
        const playerElement = document.getElementById('youtube-player');
        if (!playerElement) {
            console.error('❌ Player element not found');
            showFallback();
            return;
        }
        
        try {
            player = new YT.Player('youtube-player', {
                height: '100%',
                width: '100%',
                videoId: CONFIG.videoId,
                playerVars: {
                    autoplay: 1,           // Autoplay
                    controls: 0,           // Sin controles
                    showinfo: 0,           // Sin info
                    modestbranding: 1,     // Sin logo grande de YouTube
                    loop: 1,               // Loop infinito
                    playlist: CONFIG.videoId, // Necesario para loop
                    mute: 1,               // Muted (requerido para autoplay)
                    rel: 0,                // No videos relacionados
                    iv_load_policy: 3,     // Sin anotaciones
                    fs: 0,                 // Sin fullscreen
                    playsinline: 1,        // Inline en mobile
                    disablekb: 1,          // Sin teclado
                    enablejsapi: 1,        // Habilitar API
                    origin: window.location.origin
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': onPlayerError
                }
            });
            
            console.log('✅ YouTube player created');
            
        } catch (error) {
            console.error('❌ Error creating player:', error);
            showFallback();
        }
    }
    
    // ========== PLAYER READY ==========
    function onPlayerReady(event) {
        console.log('✅ YouTube player ready');
        playerReady = true;
        
        try {
            // Mutear y reproducir
            event.target.mute();
            event.target.playVideo();
            
            // CRÍTICO: Forzar estilos del iframe con reintentos
            let attempts = 0;
            const maxAttempts = 10;
            
            const tryForceStyles = () => {
                attempts++;
                const success = forceIframeStyles();
                
                if (!success && attempts < maxAttempts) {
                    setTimeout(tryForceStyles, 200); // Reintentar cada 200ms
                } else if (success) {
                    console.log(`✅ Iframe styled after ${attempts} attempt(s)`);
                    
                    // MOSTRAR el contenedor con fade-in cuando todo esté listo
                    const container = document.getElementById('youtube-background-container');
                    if (container) {
                        container.style.opacity = '1';
                        console.log('✅ Video container visible');
                    }
                } else {
                    console.error('❌ Failed to style iframe after', maxAttempts, 'attempts');
                    showFallback();
                }
            };
            
            // Primer intento inmediato
            setTimeout(tryForceStyles, 50); // Reducido de 100ms a 50ms
            
        } catch (error) {
            console.error('❌ Error starting playback:', error);
            showFallback();
        }
    }
    
    // ========== FORZAR ESTILOS DEL IFRAME ==========
    function forceIframeStyles() {
        // Intentar múltiples selectores para el iframe
        let iframe = document.querySelector('#youtube-player iframe');
        
        if (!iframe) {
            iframe = document.querySelector('#youtube-background-container iframe');
        }
        
        if (!iframe) {
            iframe = document.querySelector('.background iframe');
        }
        
        if (iframe) {
            // Forzar estilos con setProperty para máxima prioridad
            iframe.style.setProperty('position', 'absolute', 'important');
            iframe.style.setProperty('top', '0', 'important');
            iframe.style.setProperty('left', '0', 'important');
            iframe.style.setProperty('width', '100%', 'important');
            iframe.style.setProperty('height', '100%', 'important');
            iframe.style.setProperty('border', 'none', 'important');
            iframe.style.setProperty('pointer-events', 'none', 'important');
            
            console.log('✅ Iframe styles forced successfully');
            console.log('  Iframe position:', iframe.style.position);
            console.log('  Iframe dimensions:', iframe.style.width, 'x', iframe.style.height);
            return true; // Éxito
        } else {
            console.warn('⚠️ Iframe not found for styling (will retry)');
            return false; // Fallo, reintentar
        }
    }
    
    // ========== PLAYER STATE CHANGE ==========
    function onPlayerStateChange(event) {
        const states = {
            '-1': 'unstarted',
            '0': 'ended',
            '1': 'playing',
            '2': 'paused',
            '3': 'buffering',
            '5': 'video cued'
        };
        
        console.log(`🎬 Player state: ${states[event.data] || event.data}`);
        
        // Si el video termina, reiniciar (backup del loop)
        if (event.data === YT.PlayerState.ENDED) {
            console.log('🔄 Restarting video');
            event.target.playVideo();
        }
        
        // Si hay error de buffering por mucho tiempo
        if (event.data === YT.PlayerState.BUFFERING) {
            setTimeout(() => {
                if (player && player.getPlayerState() === YT.PlayerState.BUFFERING) {
                    console.warn('⚠️ Buffering too long, showing fallback');
                    showFallback();
                }
            }, 5000);
        }
    }
    
    // ========== PLAYER ERROR ==========
    function onPlayerError(event) {
        const errors = {
            2: 'Invalid video ID',
            5: 'HTML5 player error',
            100: 'Video not found or private',
            101: 'Video not allowed to embed',
            150: 'Video not allowed to embed'
        };
        
        console.error(`❌ YouTube player error: ${errors[event.data] || event.data}`);
        showFallback();
    }
    
    // ========== MOSTRAR FALLBACK (IMAGEN) ==========
    function showFallback() {
        console.log('🖼️ Showing fallback image');
        
        const backgroundDiv = document.querySelector('.background');
        if (backgroundDiv) {
            // Ocultar video container
            const youtubeContainer = document.getElementById('youtube-background-container');
            if (youtubeContainer) {
                youtubeContainer.style.display = 'none';
            }
            
            // Mostrar imagen de fondo
            backgroundDiv.style.background = `
                linear-gradient(rgba(0, 0, 0, ${CONFIG.overlayOpacity}), rgba(0, 0, 0, ${CONFIG.overlayOpacity})),
                url('${CONFIG.fallbackImage}') no-repeat center center
            `;
            backgroundDiv.style.backgroundSize = 'cover';
        }
    }
    
    // ========== OPTIMIZACIONES ==========
    
    // Pausar video cuando tab no visible
    document.addEventListener('visibilitychange', function() {
        if (!player || !playerReady) return;
        
        try {
            if (document.hidden) {
                console.log('⏸️ Pausing video (tab hidden)');
                player.pauseVideo();
            } else {
                console.log('▶️ Resuming video (tab visible)');
                player.playVideo();
            }
        } catch (error) {
            console.error('Error toggling playback:', error);
        }
    });
    
    // Manejar resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const wasDesktop = isDesktop;
            checkDevice();
            
            // Si cambió de mobile a desktop o viceversa
            if (wasDesktop !== isDesktop) {
                console.log('🔄 Device changed, reinitializing...');
                init();
            }
        }, 500);
    });
    
    // ========== INICIALIZACIÓN ==========
    async function init() {
        console.log('🚀 Initializing background video...');
        
        // 1. Detectar dispositivo
        checkDevice();
        
        // 2. Crear estructura HTML
        createVideoContainer();
        
        // 3. Si es mobile, terminar aquí
        if (!isDesktop) {
            console.log('✅ Mobile setup complete');
            return;
        }
        
        // 4. Cargar YouTube API
        try {
            await loadYouTubeAPI();
            
            // 5. Crear player
            createPlayer();
            
        } catch (error) {
            console.error('❌ Failed to initialize YouTube:', error);
            showFallback();
        }
    }
    
    // ========== FUNCIONES GLOBALES DE DEBUG ==========
    window.debugYouTubeBackground = function() {
        console.log('🛠️ YouTube Background Debug:');
        console.log('  Device:', isDesktop ? 'Desktop/Tablet' : 'Mobile');
        console.log('  Screen width:', window.innerWidth + 'px');
        console.log('  API ready:', youtubeAPIReady);
        console.log('  Player ready:', playerReady);
        console.log('  Player state:', player ? player.getPlayerState() : 'N/A');
    };
    
    window.forceVideoFallback = function() {
        console.log('🔧 Forcing fallback...');
        showFallback();
    };
    
    // ========== EJECUTAR AL CARGAR ==========
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    console.log('%c✅ YouTube Background Video Script Loaded', 'color: #4CAF50; font-weight: bold');
    
})();
