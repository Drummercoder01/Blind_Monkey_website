<?php
$_inhoud .= "
    <section id='music' class='py-5'>
        <div class='container'>
            <!-- Título de sección mejorado -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-music-note-beamed'></i>
                </div>
                <h1 class='section-title'>Our Music</h1>
                <p class='section-subtitle'>Explore our latest tracks and albums</p>
                <div class='section-divider'></div>
            </div>
        </div>

        <!-- Contenedor principal -->
        <div class='music-container'>
            <!-- Contenedor para todas las canciones -->
            <div id='music-content' style='display: none;'>
                
                <!-- Grid principal para canciones iniciales con layout mejorado -->
                <div id='music-grid' class='music-grid'>
                    <!-- Las canciones iniciales se cargarán aquí -->
                </div>
                
                <!-- Grid adicional para canciones extra (oculto inicialmente) -->
                <div id='music-additional' class='music-grid' style='display: none;'>
                    <!-- Canciones adicionales se cargarán aquí -->
                </div>

                <!-- Contenedor del botón mejorado -->
                <div id='music-button-container' class='text-center mt-5'>
                    <button id='toggleMusic' class='btn-music-toggle'>
                        <span class='btn-content'>
                            <i class='bi bi-music-note-list me-2'></i>
                            <span class='button-text'>Load More Tracks</span>
                            <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                        </span>
                        <span class='btn-glow'></span>
                    </button>
                </div>
            </div>

            <!-- Estado cuando no hay música - Mejorado -->
            <div id='no-music-state' class='no-music-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-music-note-beamed'></i>
                    </div>
                    <h3 class='empty-title'>No Music Available</h3>
                    <p class='empty-text'>Check back soon for new releases</p>
                    <div class='music-wave'>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
