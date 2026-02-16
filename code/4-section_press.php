<?php
$_inhoud .= "
    <!-- Sección de Prensa Profesional -->
    <section id='press' class='py-5'>
        <div class='container'>
            <!-- Header de sección mejorado -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-newspaper'></i>
                </div>
                <h1 class='section-title'>Press & Media</h1>
                <p class='section-subtitle'>Featured in the news</p>
                <div class='section-divider'></div>
            </div>
        </div>
        
        <!-- Container principal para prensa -->
        <div class='press-container'>
            <!-- Grid de items de prensa iniciales -->
            <div id='press-grid' class='press-grid' style='display: none;'>
                <!-- Los items de prensa iniciales se cargarán aquí -->
            </div>
            
            <!-- Grid de items adicionales (ocultos inicialmente) -->
            <div id='press-additional' class='press-grid' style='display: none;'>
                <!-- Items de prensa adicionales se cargarán aquí -->
            </div>

            <!-- Botón para ver más/menos items de prensa -->
            <div id='press-button-container' class='text-center mt-5' style='display: none;'>
                <button id='togglePress' class='btn-press-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-newspaper me-2'></i>
                        <span class='button-text'>Load More Articles</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>
            
            <!-- Estado cuando no hay items de prensa -->
            <div id='no-press-state' class='no-press-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-newspaper'></i>
                    </div>
                    <h3 class='empty-title'>No Press Coverage</h3>
                    <p class='empty-text'>Check back soon for latest media features</p>
                    <div class='press-wave'>
                        <div class='wave-line'></div>
                        <div class='wave-line'></div>
                        <div class='wave-line'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
