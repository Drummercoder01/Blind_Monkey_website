<?php
try {
    require("../code/initialisatie.inc.php");
    
    $_inhoud .= "
    <section class='press' id='press'>
        <header class='text-center py-4'>
            <div class='container'>
                <h1 class='display-3 fw-bold text-center text-white'>Press</h1>
            </div>
        </header>

        <div class='container'>
            <div class='text-center mt-4 py-3'>
                <button type='button' class='btn btn-light px-4 py-2 fw-bold' data-bs-toggle='modal' data-bs-target='#pressModal'>
                    Add new press item +
                </button>
            </div>

            <!-- Contenedor para items de press -->
            <div class='press-items-container'>
                <div class='text-center py-5'>
                    <div class='spinner-border text-light' role='status'>
                        <span class='visually-hidden'>Loading press items...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>";

    // Modal para agregar/editar press items
    $_inhoud .= "
    <!-- Modal -->
    <div class='modal fade' id='pressModal' tabindex='-1' aria-labelledby='pressModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content bg-dark text-white'>
          <div class='modal-header'>
            <h5 class='modal-title text-white' id='pressModalLabel'>Add Press Item</h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <form id='pressForm'>
              <input type='hidden' id='edit_id' name='edit_id' value=''>
              <div class='mb-3'>
                <label for='pressText' class='form-label text-white'>Press Excerpt *</label>
                <textarea class='form-control' name='press_text' id='pressText' rows='3' required></textarea>
              </div>
              <div class='mb-3'>
                <label for='pressAuthor' class='form-label text-white'>Author/Source *</label>
                <input type='text' class='form-control' name='press_author' id='pressAuthor' required>
              </div>
              <div class='mb-3'>
                <label for='pressComment' class='form-label text-white'>Comment</label>
                <input type='text' class='form-control' name='press_comment' id='pressComment' placeholder='Optional comment'>
              </div>
              <div class='mb-3'>
                <label for='pressLink' class='form-label text-white'>Article URL</label>
                <input type='url' class='form-control' name='press_link' id='pressLink' placeholder='https://example.com/article'>
              </div>
              <div class='mb-3'>
                <label for='pressTime' class='form-label text-white'>Publication Date *</label>
                <input type='date' class='form-control' name='press_time' id='pressTime' required>
              </div>
              <div class='text-center'>
                <button type='submit' class='btn btn-light fw-bold px-4'>Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>";

    $_jsInclude = array("../js/simple_press_editor.js");
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>