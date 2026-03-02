// ========== LOCAL VIDEO BACKGROUND - ALL DEVICES ==========
// Características:
// - Video local optimizado (30-35MB)
// - Funciona en desktop, tablet Y mobile
// - Fallback a imagen si video falla
// - Loop infinito
// - Autoplay muted
// - Optimizado para performance

(function() {
    'use strict';
    
    console.log('%c🎬 Local Video Background Initializing...', 'color: #ffffff; font-weight: bold');
    
    // ========== CONFIGURACIÓN ==========
    const CONFIG = {
        videoSrc: '../video/background-video-1080p.mp4',
        fallbackImage: '../img/big-lights-city-banner.webp',
        overlayOpacity: 0.6,
        enableMobile: true, // TRUE = video también en mobile
        mobileOverlay: 0.45  // Reducido de 0.7 a 0.45 para mejor visibilidad
    };
    
    // ========== VARIABLES GLOBALES ==========
    let videoElement = null;
    let backgroundDiv = null;
    let isMobile = window.innerWidth < 768;
    let videoLoaded = false;
    let videoError = false;
    
    // ========== DETECTAR DISPOSITIVO ==========
    function checkDevice() {
        const width = window.innerWidth;
        isMobile = width < 768;
        
        console.log(`📱 Device: ${isMobile ? 'Mobile' : 'Desktop/Tablet'} (${width}px)`);
        
        return isMobile;
    }
    
    // ========== CREAR ESTRUCTURA HTML ==========
    function createVideoContainer() {
        // Buscar o crear el contenedor de background
        backgroundDiv = document.querySelector('.background');
        
        if (!backgroundDiv) {
            backgroundDiv = document.createElement('div');
            backgroundDiv.className = 'background';
            document.body.insertBefore(backgroundDiv, document.body.firstChild);
        }
        
        // Limpiar contenido existente
        backgroundDiv.innerHTML = '';
        
        // Aplicar estilos base
        applyBackgroundStyles();
        
        // Crear video element
        videoElement = document.createElement('video');
        videoElement.id = 'background-video';
        videoElement.autoplay = true;
        videoElement.loop = true;
        videoElement.muted = true;
        videoElement.playsInline = true; // CRÍTICO para mobile
        videoElement.preload = 'auto';
        
        // Aplicar estilos al video
        applyVideoStyles();
        
        // Crear overlay
        const overlay = document.createElement('div');
        overlay.className = 'video-overlay';
        overlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, ${isMobile ? CONFIG.mobileOverlay : CONFIG.overlayOpacity});
            z-index: 1;
            pointer-events: none;
        `;
        
        // Agregar elementos al DOM
        backgroundDiv.appendChild(videoElement);
        backgroundDiv.appendChild(overlay);
        
        console.log('✅ Video container created');
    }
    
    // ========== APLICAR ESTILOS AL BACKGROUND ==========
    function applyBackgroundStyles() {
        backgroundDiv.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        `;
    }
    
    // ========== APLICAR ESTILOS AL VIDEO ==========
    function applyVideoStyles() {
        videoElement.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            z-index: 0;
            pointer-events: none;
        `;
    }
    
    // ========== CARGAR VIDEO ==========
    function loadVideo() {
        console.log('⏳ Loading video:', CONFIG.videoSrc);
        
        // Event listeners
        videoElement.addEventListener('loadeddata', onVideoLoaded);
        videoElement.addEventListener('canplaythrough', onVideoCanPlay);
        videoElement.addEventListener('error', onVideoError);
        videoElement.addEventListener('stalled', onVideoStalled);
        
        // Cargar video
        videoElement.src = CONFIG.videoSrc;
        videoElement.load();
        
        // Timeout de 10 segundos
        setTimeout(() => {
            if (!videoLoaded && !videoError) {
                console.warn('⚠️ Video loading timeout, showing fallback');
                showFallback();
            }
        }, 10000);
    }
    
    // ========== VIDEO LOADED ==========
    function onVideoLoaded() {
        console.log('📹 Video loaded successfully');
        videoLoaded = true;
    }
    
    // ========== VIDEO CAN PLAY ==========
    function onVideoCanPlay() {
        console.log('▶️ Video ready to play');
        
        // Intentar reproducir
        const playPromise = videoElement.play();
        
        if (playPromise !== undefined) {
            playPromise
                .then(() => {
                    console.log('✅ Video playing');
                    // Hacer visible el background
                    backgroundDiv.style.opacity = '1';
                })
                .catch(error => {
                    console.error('❌ Autoplay failed:', error);
                    
                    // En mobile algunos navegadores bloquean autoplay
                    if (isMobile) {
                        console.log('📱 Mobile autoplay blocked, trying user interaction...');
                        
                        // Intentar reproducir al primer touch/click
                        const playOnInteraction = () => {
                            videoElement.play()
                                .then(() => {
                                    console.log('✅ Video playing after user interaction');
                                    backgroundDiv.style.opacity = '1';
                                    document.removeEventListener('touchstart', playOnInteraction);
                                    document.removeEventListener('click', playOnInteraction);
                                })
                                .catch(err => {
                                    console.error('❌ Video play failed:', err);
                                    showFallback();
                                });
                        };
                        
                        document.addEventListener('touchstart', playOnInteraction, { once: true });
                        document.addEventListener('click', playOnInteraction, { once: true });
                        
                        // Mostrar background con imagen mientras tanto
                        showFallback();
                    } else {
                        showFallback();
                    }
                });
        }
    }
    
    // ========== VIDEO ERROR ==========
    function onVideoError(e) {
        console.error('❌ Video error:', e);
        videoError = true;
        
        // Detalles del error
        if (videoElement.error) {
            const errorCodes = {
                1: 'MEDIA_ERR_ABORTED - Video loading aborted',
                2: 'MEDIA_ERR_NETWORK - Network error',
                3: 'MEDIA_ERR_DECODE - Video decoding error',
                4: 'MEDIA_ERR_SRC_NOT_SUPPORTED - Video format not supported'
            };
            console.error('Error details:', errorCodes[videoElement.error.code] || 'Unknown error');
        }
        
        showFallback();
    }
    
    // ========== VIDEO STALLED ==========
    function onVideoStalled() {
        console.warn('⚠️ Video loading stalled');
        
        // Si después de 5 segundos sigue stalled, mostrar fallback
        setTimeout(() => {
            if (!videoLoaded) {
                console.warn('⚠️ Video stalled too long, showing fallback');
                showFallback();
            }
        }, 5000);
    }
    
    // ========== MOSTRAR FALLBACK (IMAGEN) ==========
    function showFallback() {
        console.log('🖼️ Showing fallback image');
        
        if (backgroundDiv) {
            // Ocultar video si existe
            if (videoElement) {
                videoElement.style.display = 'none';
            }
            
            // Mostrar imagen de fondo
            backgroundDiv.style.background = `
                linear-gradient(rgba(0, 0, 0, ${isMobile ? CONFIG.mobileOverlay : CONFIG.overlayOpacity}), 
                                rgba(0, 0, 0, ${isMobile ? CONFIG.mobileOverlay : CONFIG.overlayOpacity})),
                url('${CONFIG.fallbackImage}')
            `;
            backgroundDiv.style.backgroundSize = 'cover';
            backgroundDiv.style.backgroundPosition = isMobile ? 'center 30%' : 'center center';
            backgroundDiv.style.backgroundRepeat = 'no-repeat';
            backgroundDiv.style.backgroundAttachment = isMobile ? 'fixed' : 'scroll';
            backgroundDiv.style.opacity = '1';
        }
    }
    
    // ========== OPTIMIZACIONES ==========
    
    // Pausar video cuando tab no visible (ahorra batería)
    document.addEventListener('visibilitychange', function() {
        if (!videoElement || videoError) return;
        
        try {
            if (document.hidden) {
                console.log('⏸️ Pausing video (tab hidden)');
                videoElement.pause();
            } else {
                console.log('▶️ Resuming video (tab visible)');
                videoElement.play();
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
            const wasMobile = isMobile;
            checkDevice();
            
            // Si cambió de mobile a desktop o viceversa, reiniciar
            if (wasMobile !== isMobile) {
                console.log('🔄 Device changed, reinitializing...');
                init();
            }
        }, 500);
    });
    
    // Reducir calidad en conexiones lentas
    if (navigator.connection) {
        const connection = navigator.connection;
        
        if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
            console.warn('⚠️ Slow connection detected, using fallback image');
            CONFIG.videoSrc = ''; // Forzar fallback
        }
    }
    
    // ========== INICIALIZACIÓN ==========
    function init() {
        console.log('🚀 Initializing local video background...');
        
        // 1. Detectar dispositivo
        checkDevice();
        
        // 2. Crear estructura HTML
        createVideoContainer();
        
        // 3. Cargar video
        loadVideo();
    }
    
    // ========== FUNCIONES GLOBALES DE DEBUG ==========
    window.debugVideoBackground = function() {
        console.log('🛠️ Video Background Debug:');
        console.log('  Device:', isMobile ? 'Mobile' : 'Desktop/Tablet');
        console.log('  Screen width:', window.innerWidth + 'px');
        console.log('  Video loaded:', videoLoaded);
        console.log('  Video error:', videoError);
        console.log('  Video state:', videoElement ? videoElement.readyState : 'N/A');
        console.log('  Video current time:', videoElement ? videoElement.currentTime : 'N/A');
        console.log('  Video paused:', videoElement ? videoElement.paused : 'N/A');
    };
    
    window.forceVideoFallback = function() {
        console.log('🔧 Forcing fallback...');
        showFallback();
    };
    
    window.retryVideoLoad = function() {
        console.log('🔄 Retrying video load...');
        videoLoaded = false;
        videoError = false;
        loadVideo();
    };
    
    // ========== EJECUTAR AL CARGAR ==========
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    console.log('%c✅ Local Video Background Script Loaded', 'color: #4CAF50; font-weight: bold');
    
})();
