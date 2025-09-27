<?php
try {
    require("../code/initialisatie.inc.php");
    require("../code/admin_music_modal.php");

    $_inhoud .= "
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-music'>Music</h1>
        </div>

        <div class='text-center mt-4 py-3'>
            <button type='button' class='btn btn-light px-4 py-2 fw-bold' data-bs-toggle='modal' data-bs-target='#addSongModal'>
                Add new song +
            </button>
            <button type='button' class='btn btn-outline-light px-4 py-2 fw-bold ms-2' id='saveOrderBtn'>
                <i class='bi bi-save me-1'></i>Save Order
            </button>
        </div>

        <!-- Contenedor para canciones con drag & drop -->
        <div class='music-container' id='musicContainer'>
            <div class='text-center py-5'>
                <div class='spinner-border text-light' role='status'>
                    <span class='visually-hidden'>Loading songs...</span>
                </div>
            </div>
        </div>";

    $_jsInclude = array(
        "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js",
        "../js/admin_music_handler.js",
        "../js/ajax_add_song_simple.js"
    );

    require("../code/output_admin.inc.php");
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}