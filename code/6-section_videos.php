<?php
$_inhoud .= "
    <!-- Sección de Videos Profesional -->
    <section id='videos' class='py-5'>
        <div class='container'>
            <!-- Header de sección mejorado -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-camera-video'></i>
                </div>
                <h1 class='section-title'>Videos</h1>
                <p class='section-subtitle'>Watch our latest content</p>
                <div class='section-divider'></div>
            </div>
        </div>
        
        <!-- Container principal para videos -->
        <div class='videos-container'>
            <!-- Loading state inicial -->
            <div id='videos-loading' class='loading-state' style='display: none;'>
                <div class='spinner-border text-light' role='status' style='width: 3rem; height: 3rem;'>
                    <span class='visually-hidden'>Loading videos...</span>
                </div>
                <p class='text-white mt-3'>Loading videos...</p>
            </div>
            
            <!-- Grid de videos iniciales (SIN clases de Bootstrap) -->
            <div id='videos-grid' class='videos-grid' style='display: none;'>
                <!-- Los videos iniciales se cargarán aquí -->
            </div>
            
            <!-- Grid de videos adicionales (ocultos inicialmente, SIN clases de Bootstrap) -->
            <div id='videos-additional' class='videos-grid' style='display: none;'>
                <!-- Videos adicionales se cargarán aquí -->
            </div>

            <!-- Botón para ver más/menos videos -->
            <div id='videos-button-container' class='text-center mt-5' style='display: none;'>
                <button id='toggleVideos' class='btn-videos-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-play-circle me-2'></i>
                        <span class='button-text'>Watch More Videos</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>
            
            <!-- Estado cuando no hay videos -->
            <div id='no-videos-state' class='no-videos-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-camera-video'></i>
                    </div>
                    <h3 class='empty-title'>No Videos Available</h3>
                    <p class='empty-text'>Check back soon for new content</p>
                    <div class='video-wave'>
                        <div class='wave-play'></div>
                        <div class='wave-play'></div>
                        <div class='wave-play'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
