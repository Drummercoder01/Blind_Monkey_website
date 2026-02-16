<?php
$_inhoud .= "
    <!-- Sección de Eventos Profesional -->
    <section id='events' class='py-5'>
        <div class='container'>
            <!-- Header de sección mejorado -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-calendar-event'></i>
                </div>
                <h1 class='section-title'>Upcoming Events</h1>
                <p class='section-subtitle'>Join us live</p>
                <div class='section-divider'></div>
            </div>
        </div>
        
        <!-- Container principal para eventos -->
        <div class='events-container'>
            <!-- Grid de eventos iniciales (SIN clases de Bootstrap) -->
            <div id='events-grid' class='events-grid' style='display: none;'>
                <!-- Los eventos iniciales se cargarán aquí -->
            </div>
            
            <!-- Grid de eventos adicionales (ocultos inicialmente, SIN clases de Bootstrap) -->
            <div id='events-additional' class='events-grid' style='display: none;'>
                <!-- Eventos adicionales se cargarán aquí -->
            </div>

            <!-- Botón para ver más/menos eventos -->
            <div id='events-button-container' class='text-center mt-5' style='display: none;'>
                <button id='toggleEvents' class='btn-events-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-calendar-event me-2'></i>
                        <span class='button-text'>View All Events</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>
            
            <!-- Estado cuando no hay eventos -->
            <div id='no-events-state' class='no-events-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-calendar-x'></i>
                    </div>
                    <h3 class='empty-title'>No Events Scheduled</h3>
                    <p class='empty-text'>Stay tuned for upcoming shows</p>
                    <div class='calendar-animation'>
                        <div class='calendar-page'>
                            <div class='calendar-header'></div>
                            <div class='calendar-body'>
                                <div class='calendar-dot'></div>
                                <div class='calendar-dot'></div>
                                <div class='calendar-dot'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
