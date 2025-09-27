<?php
$_inhoud .= "
    <!-- Sección de Prensa -->
    <section id='press' class='py-5'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-press'>Press</h1>
        </div>
        
        <!-- Container principal para prensa -->
        <div class='press-container'>
            <!-- Container donde se mostrarán los items de prensa iniciales -->
            <div id='press-grid' class='row row-cols-1 g-4' style='display: none;'>
                <!-- Los items de prensa iniciales se cargarán aquí -->
            </div>
            
            <!-- Container para items de prensa adicionales (ocultos inicialmente) -->
            <div id='press-additional' class='row row-cols-1 g-4 pt-4' style='display: none;'>
                <!-- Items de prensa adicionales se cargarán aquí -->
            </div>

            <!-- Botón para ver más/menos items de prensa -->
            <div id='press-button-container' class='text-center mt-4' style='display: none;'>
                <button id='togglePress' class='btn px-4 py-2 fw-bold'>  <!-- ← QUITAR btn-light -->
                    <i class='bi bi-newspaper me-2'></i>
                    <span class='button-text'>Read more press</span>
                </button>
            </div>
            
            <!-- Estado cuando no hay items de prensa -->
            <div id='no-press-state' class='text-center py-5' style='display: none;'>
                <i class='bi bi-newspaper text-white-50' style='font-size: 4rem;'></i>
                <p class='text-white-50 mt-3 fs-5'>No press available at the moment</p>
            </div>
        </div>
    </section>
";
?>