<?php

$_inhoud .= "

   <!-- Sección de Videos -->
   <section id='videos' class='py-5'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-videos'>Videos</h1>
        </div>
        
        <!-- Container principal para videos -->
        <div class='videos-container'>
            <!-- Loading state inicial -->
            <div id='videos-loading' class='text-center py-5'>
                <div class='spinner-border text-light' role='status' style='width: 3rem; height: 3rem;'>
                    <span class='visually-hidden'>Loading videos...</span>
                </div>
                <p class='text-white mt-3'>Loading videos...</p>
            </div>
            
            <!-- Container donde se mostrarán los videos -->
            <div id='videos-grid' class='row row-cols-1 row-cols-md-2 g-4' style='display: none;'>
                <!-- Los videos se cargarán aquí dinámicamente -->
            </div>
            
            <!-- Container para videos adicionales (ocultos inicialmente) -->
            <div id='videos-additional' class='row row-cols-1 row-cols-md-2 g-4 pt-4' style='display: none;'>
                <!-- Videos adicionales se cargarán aquí -->
            </div>

            <!-- Botón para ver más/menos videos -->
            <div id='videos-button-container' class='text-center mt-4' style='display: none;'>
                <button id='toggleVideos' class='btn px-4 py-2 fw-bold border-white bg-black text-white'>
                    <i class='bi bi-play-circle me-2'></i>
                    <span class='button-text'>Watch more videos</span>
                </button>
            </div>
            
            <!-- Estado cuando no hay videos -->
            <div id='no-videos-state' class='text-center py-5' style='display: none;'>
                <i class='bi bi-camera-video text-white-50' style='font-size: 4rem;'></i>
                <p class='text-white-50 mt-3 fs-5'>No videos available at the moment</p>
            </div>
        </div>
    </section>

    ";
