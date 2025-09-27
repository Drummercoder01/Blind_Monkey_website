<?php
$_inhoud .= "
    <section id='music' class='py-5'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-music'>Music</h1>
        </div>

        <!-- Contenedor principal -->
        <div class='music-container'>
            <!-- Contenedor para todas las canciones -->
            <div id='music-content' style='display: none;'>
                <!-- Grid principal para canciones iniciales -->
                <div id='music-grid' class='row row-cols-1 row-cols-md-2 g-4'>
                    <!-- Las canciones iniciales se cargarán aquí -->
                </div>
                
                <!-- Grid adicional para canciones extra (oculto inicialmente) -->
                <div id='music-additional' class='row row-cols-1 row-cols-md-2 g-4 pt-4' style='display: none;'>
                    <!-- Canciones adicionales se cargarán aquí -->
                </div>

                <!-- Contenedor del botón -->
                <div id='music-button-container' class='text-center mt-4'>
                    <button id='toggleMusic' class='btn px-4 py-2 fw-bold'>
                        <i class='bi bi-music-note-list me-2'></i>
                        <span class='button-text'>More Music</span>
                    </button>
                </div>
            </div>

            <!-- Estado cuando no hay música -->
            <div id='no-music-state' class='text-center py-5' style='display: none;'>
                <i class='bi bi-music-note text-white-50' style='font-size: 4rem;'></i>
                <p class='text-white-50 mt-3 fs-5'>No music available at the moment</p>
            </div>
        </div>
    </section>
";
?>