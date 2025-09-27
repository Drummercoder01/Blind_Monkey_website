<?php
try {
    require("../code/initialisatie.inc.php");
    
    $_inhoud .= "
    <section id='photos' class='py-5'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-photos'>Photos</h1>
        </div>

        <div class='container'>
            <div class='text-center mt-4 py-3'>
                <button type='button' class='btn btn-light px-4 py-2 fw-bold' data-bs-toggle='modal' data-bs-target='#photoModal'>
                    Add new photo +
                </button>
                <button type='button' class='btn btn-outline-light px-4 py-2 fw-bold ms-2' id='saveOrderBtn'>
                    <i class='bi bi-save me-1'></i>Save Order
                </button>
            </div>

            <!-- Contenedor para fotos con drag & drop -->
            <div class='photos-container'>
                <div class='text-center py-5'>
                    <div class='spinner-border text-light' role='status'>
                        <span class='visually-hidden'>Loading photos...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>";

    // Modal para agregar/editar fotos
    $_inhoud .= "
    <!-- Modal -->
    <div class='modal fade' id='photoModal' tabindex='-1' aria-labelledby='photoModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content bg-dark text-white'>
          <div class='modal-header'>
            <h5 class='modal-title text-white' id='photoModalLabel'>Add Photo</h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <form id='photoForm' enctype='multipart/form-data'>
              <input type='hidden' id='edit_id' name='edit_id' value=''>
              
              <div class='mb-3'>
                <label class='form-label text-white'>Choose method:</label>
                <div class='form-check'>
                  <input class='form-check-input' type='radio' name='photo_method' id='methodUpload' value='upload' checked>
                  <label class='form-check-label text-white' for='methodUpload'>Upload Image</label>
                </div>
                <div class='form-check'>
                  <input class='form-check-input' type='radio' name='photo_method' id='methodLink' value='link'>
                  <label class='form-check-label text-white' for='methodLink'>Use Image URL</label>
                </div>
              </div>
              
              <div id='uploadSection'>
                <div class='mb-3'>
                  <label for='photoFile' class='form-label text-white'>Upload Image</label>
                  <input type='file' class='form-control' name='photo_file' id='photoFile' accept='image/*'>
                  <small class='form-text text-muted'>Max size: 10MB</small>
                </div>
              </div>
              
              <div id='linkSection' style='display: none;'>
                <div class='mb-3'>
                  <label for='photoLink' class='form-label'>Image URL</label>
                  <input type='url' class='form-control' name='photo_link' id='photoLink' placeholder='https://example.com/image.jpg'>
                </div>
              </div>
              
              <div class='text-center'>
                <button type='submit' class='btn btn-light fw-bold px-4'>Save Photo</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>";

    $_jsInclude = array(
        "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js",
        "../js/photos_manager.js"
    );
    
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>