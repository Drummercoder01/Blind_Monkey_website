<?php
try {
    require("../code/initialisatie.inc.php");
    
    // Header
    $_inhoud .= "
    <div class='container'>
        <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-videos'>Videos</h1>
    </div>

    <div class='text-center mt-4 py-3'>
        <button type='button' class='btn btn-light px-4 py-2 fw-bold' data-bs-toggle='modal' data-bs-target='#addVideoModal'>
            <i class='bi bi-plus-circle me-2'></i>Add New Video
        </button>
    </div>

    <!-- Contenedor para videos (se llenará con JavaScript) -->
    <div class='videos-container px-4'>
        <div class='text-center py-5'>
            <div class='spinner-border text-light' role='status'>
                <span class='visually-hidden'>Loading videos...</span>
            </div>
        </div>
    </div>";

    // Modal para agregar videos
    $_inhoud .= "
    <!-- Modal -->
    <div class='modal fade' id='addVideoModal' tabindex='-1' aria-labelledby='addVideoModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content bg-dark text-white'>
          <div class='modal-header'>
            <h5 class='modal-title text-white' id='addVideoModalLabel'>
                <i class='bi bi-youtube me-2'></i>Add New Video
            </h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <form id='videoForm'>
              <div class='mb-3'>
                <label for='embedCode' class='form-label text-white'>
                    <i class='bi bi-code-slash me-2'></i>YouTube Embed Code
                </label>
                <textarea class='form-control bg-secondary text-white border-secondary' 
                          name='embed_code' id='embedCode' rows='5' 
                          placeholder='Paste YouTube embed iframe code here...' required></textarea>
                <div class='form-text text-muted mt-2'>
                    <small>
                        <i class='bi bi-info-circle me-1'></i>
                        Example: &lt;iframe src='https://www.youtube.com/embed/VIDEO_ID' ...&gt;&lt;/iframe&gt;
                    </small>
                </div>
              </div>
              
              <div class='alert alert-info bg-primary bg-opacity-25 border-primary text-white'>
                <i class='bi bi-lightbulb me-2'></i>
                <strong>How to get embed code:</strong><br>
                1. Go to YouTube video<br>
                2. Click 'Share' → 'Embed'<br>
                3. Copy the entire iframe code<br>
                4. Paste it above
              </div>
              
              <div class='text-center'>
                <button type='submit' class='btn btn-light fw-bold px-4 py-2'>
                    <i class='bi bi-plus-circle me-2'></i>Add Video
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>";

    // Script para inicializar
    $_inhoud .= "
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js'></script>
    <script>
    $(document).ready(function() {
        // Cargar videos al iniciar
        refreshVideos();
    });
    </script>";

    $_jsInclude = array("../js/videos_manager.js");
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>