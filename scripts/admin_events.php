<?php
try {
    require("../code/initialisatie.inc.php");
    
    $_inhoud .= "
    <section id='events' class='py-5'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-events'>Events</h1>
        </div>

        <div class='container'>
            <div class='text-center mt-4 py-3'>
                <button type='button' class='btn btn-light px-4 py-2 fw-bold' data-bs-toggle='modal' data-bs-target='#eventModal'>
                    Add new event +
                </button>
            </div>

            <!-- Contenedor para eventos -->
            <div class='events-container'>
                <div class='text-center py-5'>
                    <div class='spinner-border text-light' role='status'>
                        <span class='visually-hidden'>Loading events...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>";

    // Modal para agregar/editar eventos
    $_inhoud .= "
    <!-- Modal -->
    <div class='modal fade' id='eventModal' tabindex='-1' aria-labelledby='eventModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
          <div class='modal-header'>
            <h5 class='modal-title text-white' id='eventModalLabel'>Add Event</h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <form id='eventForm'>
              <input type='hidden' id='edit_id' name='edit_id' value=''>
              <div class='mb-3'>
                <label for='eventName' class='form-label text-white'>Event Name *</label>
                <input type='text' class='form-control' name='event_name' id='eventName' required>
              </div>
              <div class='mb-3'>
                <label for='eventLocation' class='form-label text-white'>Location *</label>
                <input type='text' class='form-control' name='event_location' id='eventLocation' required>
              </div>
              <div class='row'>
                <div class='col-md-6 mb-3'>
                  <label for='eventDate' class='form-label text-white'>Date *</label>
                  <input type='date' class='form-control' name='event_date' id='eventDate' required>
                </div>
                <div class='col-md-6 mb-3'>
                  <label for='eventTime' class='form-label text-white'>Time *</label>
                  <input type='time' class='form-control' name='event_time' id='eventTime' required>
                </div>
              </div>
              <div class='text-center'>
                <button type='submit' class='btn btn-light fw-bold px-4'>Save Event</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>";

    // Script para inicializar
    $_inhoud .= "
    <script>
    $(document).ready(function() {
        // Cargar eventos al iniciar
        refreshEvents();
    });
    </script>";

    $_jsInclude = array("../js/events_manager.js");
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>