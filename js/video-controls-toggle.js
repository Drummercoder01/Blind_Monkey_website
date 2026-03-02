// ========== VIDEO CONTROLS TOGGLE (FAB) ==========
// Controles profesionales para video y audio background
// Botón flotante (Floating Action Button) con glassmorphism

(function() {
    'use strict';
    
    console.log('🎛️ Video Controls initializing...');
    
    // ========== ESTADO GLOBAL ==========
    let videoEnabled = true;
    let audioEnabled = false; // Siempre muted por defecto
    let controlsVisible = false;
    
    // ========== CREAR CONTROLES UI ==========
    function createControls() {
        // Container principal (FAB)
        const fabContainer = document.createElement('div');
        fabContainer.id = 'video-controls-fab';
        fabContainer.innerHTML = `
            <!-- Botón principal (siempre visible) -->
            <button id="fab-main-button" class="fab-button" aria-label="Video controls">
                <i class="bi bi-sliders"></i>
            </button>
            
            <!-- Controles expandibles -->
            <div id="fab-controls" class="fab-controls">
                <!-- Toggle Video -->
                <button id="toggle-video" class="fab-control-btn" data-enabled="true" aria-label="Toggle video">
                    <i class="bi bi-camera-video-fill"></i>
                    <span class="fab-tooltip">Video ON</span>
                </button>
                
                <!-- Toggle Audio -->
                <button id="toggle-audio" class="fab-control-btn" data-enabled="false" aria-label="Toggle audio">
                    <i class="bi bi-volume-mute-fill"></i>
                    <span class="fab-tooltip">Audio OFF</span>
                </button>
            </div>
        `;
        
        document.body.appendChild(fabContainer);
        
        console.log('✅ Video controls UI created');
    }
    
    // ========== ESTILOS CSS (INYECTADOS) ==========
    function injectStyles() {
        const style = document.createElement('style');
        style.id = 'video-controls-styles';
        style.textContent = `
            /* ========== VIDEO CONTROLS FAB ========== */
            
            #video-controls-fab {
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 9999;
                display: flex;
                flex-direction: column-reverse;
                align-items: center;
                gap: 15px;
            }
            
            /* Botón principal */
            .fab-button {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.95) 0%, 
                    rgba(204, 204, 204, 0.95) 100%);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 2px solid rgba(255, 255, 255, 0.3);
                box-shadow: 
                    0 8px 25px rgba(255, 255, 255, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
                color: #000;
                font-size: 1.5rem;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
            
            .fab-button::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, 
                    transparent, 
                    rgba(255, 255, 255, 0.4), 
                    transparent);
                transition: left 0.5s ease;
            }
            
            .fab-button:hover {
                transform: translateY(-3px) scale(1.05);
                box-shadow: 
                    0 12px 35px rgba(255, 255, 255, 0.6),
                    inset 0 1px 0 rgba(255, 255, 255, 0.4);
            }
            
            .fab-button:hover::before {
                left: 100%;
            }
            
            .fab-button:active {
                transform: translateY(-1px) scale(1);
            }
            
            .fab-button.active {
                background: linear-gradient(135deg, 
                    rgba(204, 204, 204, 0.95) 0%, 
                    rgba(255, 255, 255, 0.95) 100%);
            }
            
            .fab-button i {
                transition: transform 0.3s ease;
            }
            
            .fab-button.active i {
                transform: rotate(90deg);
            }
            
            /* Controles expandibles */
            .fab-controls {
                display: flex;
                flex-direction: column-reverse;
                gap: 12px;
                opacity: 0;
                transform: translateY(20px);
                pointer-events: none;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .fab-controls.visible {
                opacity: 1;
                transform: translateY(0);
                pointer-events: all;
            }
            
            /* Botones de control */
            .fab-control-btn {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.15) 0%, 
                    rgba(255, 255, 255, 0.05) 100%);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 2px solid rgba(255, 255, 255, 0.2);
                box-shadow: 
                    0 4px 15px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
                color: rgba(255, 255, 255, 0.8);
                font-size: 1.3rem;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                animation: slideInRight 0.3s ease forwards;
            }
            
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .fab-control-btn:nth-child(1) {
                animation-delay: 0.05s;
            }
            
            .fab-control-btn:nth-child(2) {
                animation-delay: 0.1s;
            }
            
            .fab-control-btn:hover {
                transform: translateY(-2px) scale(1.05);
                border-color: rgba(255, 255, 255, 0.5);
                box-shadow: 
                    0 6px 20px rgba(0, 0, 0, 0.4),
                    0 0 20px rgba(255, 255, 255, 0.3);
                color: #ffffff;
            }
            
            .fab-control-btn:active {
                transform: scale(0.95);
            }
            
            /* Estado activo/inactivo */
            .fab-control-btn[data-enabled="true"] {
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.3) 0%, 
                    rgba(204, 204, 204, 0.3) 100%);
                border-color: rgba(255, 255, 255, 0.5);
                color: #ffffff;
            }
            
            .fab-control-btn[data-enabled="false"] {
                background: linear-gradient(135deg, 
                    rgba(239, 68, 68, 0.2) 0%, 
                    rgba(239, 68, 68, 0.1) 100%);
                border-color: rgba(239, 68, 68, 0.4);
                color: rgba(239, 68, 68, 0.9);
            }
            
            /* Tooltips */
            .fab-tooltip {
                position: absolute;
                right: 65px;
                background: linear-gradient(135deg, 
                    rgba(15, 23, 42, 0.98) 0%, 
                    rgba(30, 41, 59, 0.98) 100%);
                backdrop-filter: blur(15px);
                color: white;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 0.875rem;
                font-weight: 600;
                white-space: nowrap;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            }
            
            .fab-control-btn:hover .fab-tooltip {
                opacity: 1;
            }
            
            /* Responsive - Mobile */
            @media (max-width: 768px) {
                #video-controls-fab {
                    bottom: 20px;
                    right: 20px;
                    gap: 12px;
                }
                
                .fab-button {
                    width: 55px;
                    height: 55px;
                    font-size: 1.3rem;
                }
                
                .fab-control-btn {
                    width: 45px;
                    height: 45px;
                    font-size: 1.1rem;
                }
                
                .fab-tooltip {
                    font-size: 0.8rem;
                    padding: 6px 12px;
                    right: 55px;
                }
            }
            
            @media (max-width: 480px) {
                #video-controls-fab {
                    bottom: 15px;
                    right: 15px;
                }
                
                .fab-button {
                    width: 50px;
                    height: 50px;
                    font-size: 1.2rem;
                }
                
                .fab-control-btn {
                    width: 42px;
                    height: 42px;
                    font-size: 1rem;
                }
            }
            
            /* Animación de pulso para llamar la atención */
            @keyframes pulse {
                0%, 100% {
                    box-shadow: 
                        0 8px 25px rgba(255, 255, 255, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
                }
                50% {
                    box-shadow: 
                        0 8px 25px rgba(255, 255, 255, 0.6),
                        0 0 30px rgba(255, 255, 255, 0.4),
                        inset 0 1px 0 rgba(255, 255, 255, 0.3);
                }
            }
            
            /* Aplicar pulso al cargar la página */
            .fab-button.pulse {
                animation: pulse 2s ease-in-out 3;
            }
        `;
        
        document.head.appendChild(style);
        console.log('✅ Video controls styles injected');
    }
    
    // ========== FUNCIONALIDAD ==========
    function setupControls() {
        const fabButton = document.getElementById('fab-main-button');
        const fabControls = document.getElementById('fab-controls');
        const toggleVideo = document.getElementById('toggle-video');
        const toggleAudio = document.getElementById('toggle-audio');
        
        // Toggle mostrar/ocultar controles
        fabButton.addEventListener('click', () => {
            controlsVisible = !controlsVisible;
            fabButton.classList.toggle('active');
            fabControls.classList.toggle('visible');
            
            console.log(controlsVisible ? '📂 Controls opened' : '📁 Controls closed');
        });
        
        // Toggle Video
        toggleVideo.addEventListener('click', () => {
            videoEnabled = !videoEnabled;
            toggleVideo.setAttribute('data-enabled', videoEnabled);
            
            const icon = toggleVideo.querySelector('i');
            const tooltip = toggleVideo.querySelector('.fab-tooltip');
            
            if (videoEnabled) {
                icon.className = 'bi bi-camera-video-fill';
                tooltip.textContent = 'Video ON';
                enableVideo();
            } else {
                icon.className = 'bi bi-camera-video-off-fill';
                tooltip.textContent = 'Video OFF';
                disableVideo();
            }
            
            console.log(videoEnabled ? '📹 Video enabled' : '⏸️ Video disabled');
        });
        
        // Toggle Audio
        toggleAudio.addEventListener('click', () => {
            audioEnabled = !audioEnabled;
            toggleAudio.setAttribute('data-enabled', audioEnabled);
            
            const icon = toggleAudio.querySelector('i');
            const tooltip = toggleAudio.querySelector('.fab-tooltip');
            
            if (audioEnabled) {
                icon.className = 'bi bi-volume-up-fill';
                tooltip.textContent = 'Audio ON';
                enableAudio();
            } else {
                icon.className = 'bi bi-volume-mute-fill';
                tooltip.textContent = 'Audio OFF';
                disableAudio();
            }
            
            console.log(audioEnabled ? '🔊 Audio enabled' : '🔇 Audio muted');
        });
        
        // Cerrar controles al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#video-controls-fab') && controlsVisible) {
                controlsVisible = false;
                fabButton.classList.remove('active');
                fabControls.classList.remove('visible');
            }
        });
        
        // Efecto de pulso inicial para llamar la atención
        setTimeout(() => {
            fabButton.classList.add('pulse');
            setTimeout(() => {
                fabButton.classList.remove('pulse');
            }, 6000);
        }, 2000);
        
        console.log('✅ Video controls functionality ready');
    }
    
    // ========== CONTROL DEL VIDEO ==========
    function enableVideo() {
        const video = document.getElementById('background-video');
        const backgroundDiv = document.querySelector('.background');
        const overlay = document.querySelector('.video-overlay');
        
        if (video) {
            video.style.display = 'block';
            video.play().catch(err => console.warn('Video play failed:', err));
            
            // Limpiar background image
            if (backgroundDiv) {
                backgroundDiv.style.background = 'none';
            }
        }
        
        // Restaurar overlay normal para video (más claro en mobile)
        if (overlay) {
            const isMobile = window.innerWidth < 768;
            overlay.style.background = `rgba(0, 0, 0, ${isMobile ? 0.45 : 0.6})`;
        }
    }
    
    function disableVideo() {
        const video = document.getElementById('background-video');
        const backgroundDiv = document.querySelector('.background');
        const overlay = document.querySelector('.video-overlay');
        
        if (video) {
            video.pause();
            video.style.display = 'none';
        }
        
        // Reducir overlay para que la imagen se vea mejor
        if (overlay) {
            const isMobile = window.innerWidth < 768;
            overlay.style.background = `rgba(0, 0, 0, ${isMobile ? 0.25 : 0.3})`;
        }
        
        // Mostrar imagen fallback
        if (backgroundDiv) {
            const isMobile = window.innerWidth < 768;
            backgroundDiv.style.background = `
                linear-gradient(rgba(0, 0, 0, ${isMobile ? 0.25 : 0.3}), 
                                rgba(0, 0, 0, ${isMobile ? 0.25 : 0.3})),
                url('../img/big-lights-city-banner.webp')
            `;
            backgroundDiv.style.backgroundSize = 'cover';
            backgroundDiv.style.backgroundPosition = isMobile ? 'center 30%' : 'center center';
            backgroundDiv.style.backgroundRepeat = 'no-repeat';
        }
    }
    
    function enableAudio() {
        const video = document.getElementById('background-video');
        if (video) {
            video.muted = false;
            video.volume = 0.3; // Volumen al 30%
        }
    }
    
    function disableAudio() {
        const video = document.getElementById('background-video');
        if (video) {
            video.muted = true;
        }
    }
    
    // ========== PERSISTENCIA (LocalStorage) ==========
    function loadPreferences() {
        const savedVideo = localStorage.getItem('videoEnabled');
        const savedAudio = localStorage.getItem('audioEnabled');
        
        if (savedVideo !== null) {
            videoEnabled = savedVideo === 'true';
            if (!videoEnabled) {
                setTimeout(() => {
                    document.getElementById('toggle-video')?.click();
                }, 1000);
            }
        }
        
        if (savedAudio !== null) {
            audioEnabled = savedAudio === 'true';
            if (audioEnabled) {
                setTimeout(() => {
                    document.getElementById('toggle-audio')?.click();
                }, 1000);
            }
        }
        
        console.log('📥 Preferences loaded:', { videoEnabled, audioEnabled });
    }
    
    function savePreferences() {
        localStorage.setItem('videoEnabled', videoEnabled);
        localStorage.setItem('audioEnabled', audioEnabled);
        console.log('💾 Preferences saved');
    }
    
    // Guardar preferencias al cambiar
    window.addEventListener('beforeunload', savePreferences);
    
    // ========== INICIALIZACIÓN ==========
    function init() {
        console.log('🚀 Initializing video controls...');
        
        // Esperar a que el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                injectStyles();
                createControls();
                setupControls();
                loadPreferences();
            });
        } else {
            injectStyles();
            createControls();
            setupControls();
            loadPreferences();
        }
    }
    
    // ========== EJECUTAR ==========
    init();
    
    console.log('%c✅ Video Controls Script Loaded', 'color: #4CAF50; font-weight: bold');
    
})();
