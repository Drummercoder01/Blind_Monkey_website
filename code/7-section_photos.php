<?php
$_inhoud .= "
   <section id='photos-1' class='photos'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-photos'>Photos</h1>
        </div>
        
        <!-- Contenedor de galería simplificado -->
        <div class='gallery-container' id='gallery-container'>
            <!-- Las fotos se cargarán aquí -->
        </div>

        <!-- Estado de carga -->
        <div id='photos-loading' class='text-center text-white py-4'>
            <div class='spinner-border text-light' role='status'>
                <span class='visually-hidden'>Loading...</span>
            </div>
            <p class='mt-2'>Loading photos...</p>
        </div>

        <!-- Estado sin fotos -->
        <div id='no-photos-state' class='text-center text-white py-4 d-none'>
            <i class='fas fa-camera fa-3x mb-3'></i>
            <p>No photos available at the moment.</p>
        </div>

        <!-- Botón simplificado -->
        <div class='text-center mt-4 d-none' id='photos-button-container'>
            <button id='togglePhotos' class='btn px-4 py-2 fw-bold border-white bg-black text-white'>
                <i class='bi bi-images me-2'></i>
                <span class='button-text'>Show more photos</span>
            </button>
        </div>
    </section>

    <!-- Lightbox simplificado -->
    <div id='lightbox' class='lightbox'>
        <span class='close'>&times;</span>
        <img class='lightbox-image' id='lightbox-img' src='' alt=''>
        <button class='prev'>&#10094;</button>
        <button class='next'>&#10095;</button>
    </div>
   ";
?>