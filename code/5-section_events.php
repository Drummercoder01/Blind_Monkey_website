<?php
$_inhoud .= "
    <!-- Sección de Eventos -->
    <section id='events' class='py-5'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-events'>Events</h1>
        </div>
        
        <!-- Container principal para eventos -->
        <div class='events-container'>
            <!-- Container donde se mostrarán los eventos iniciales -->
            <div id='events-grid' class='row row-cols-1 row-cols-md-2 g-4' style='display: none;'>
                <!-- Los eventos iniciales se cargarán aquí -->
            </div>
            
            <!-- Container para eventos adicionales (ocultos inicialmente) -->
            <div id='events-additional' class='row row-cols-1 row-cols-md-2 g-4 pt-4' style='display: none;'>
                <!-- Eventos adicionales se cargarán aquí -->
            </div>

            <!-- Botón para ver más/menos eventos -->
            <div id='events-button-container' class='text-center mt-4' style='display: none;'>
                <button id='toggleEvents' class='btn btn-light px-4 py-2 fw-bold'>
                    <i class='bi bi-calendar-event me-2'></i>
                    <span class='button-text'>View more events</span>
                </button>
            </div>
            
            <!-- Estado cuando no hay eventos -->
            <div id='no-events-state' class='text-center py-5' style='display: none;'>
                <i class='bi bi-calendar-x text-white-50' style='font-size: 4rem;'></i>
                <p class='text-white-50 mt-3 fs-5'>No events available at the moment</p>
            </div>
        </div>
    </section>
";
?>