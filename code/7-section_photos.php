<?php
$_inhoud .= "
    <!-- Sección de Fotos Profesional -->
    <section id='photos-1' class='py-5'>
        <div class='section-sparks' aria-hidden='true'>
            <span class='hero-spark' style='left:6%;  animation-duration:11s; animation-delay:2s;'></span>
            <span class='hero-spark' style='left:21%; animation-duration:7s;  animation-delay:0s;'></span>
            <span class='hero-spark' style='left:43%; animation-duration:13s; animation-delay:3s;'></span>
            <span class='hero-spark' style='left:61%; animation-duration:9s;  animation-delay:1.5s;'></span>
            <span class='hero-spark' style='left:84%; animation-duration:8s;  animation-delay:4.5s;'></span>
            <span class='hero-spark hero-spark--white' style='left:32%; animation-duration:10s; animation-delay:5s;'></span>
            <span class='hero-spark hero-spark--white' style='left:74%; animation-duration:12s; animation-delay:2.5s;'></span>
        </div>
        <div class='container'>
            <!-- Header de sección mejorado -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-camera'></i>
                </div>
                <h1 class='section-title'>Photos</h1>
                <p class='section-subtitle'>Our visual journey</p>
                <div class='section-divider'></div>
            </div>
        </div>
        
        <!-- Container principal para fotos -->
        <div class='photos-container'>
            <!-- Loading state inicial -->
            <div id='photos-loading' class='loading-state text-center py-5'>
                <div class='spinner-border text-light' role='status' style='width: 3rem; height: 3rem;'>
                    <span class='visually-hidden'>Loading photos...</span>
                </div>
                <p class='text-white mt-3'>Loading photos...</p>
            </div>
            
            <!-- Contenedor de galería (aquí se cargarán los grids) -->
            <div id='gallery-container' class='gallery-container'>
                <!-- Los grids #photos-grid y #photos-additional se crearán aquí -->
            </div>
            
            <!-- Botón para ver más/menos fotos -->
            <div id='photos-button-container' class='text-center mt-5 d-none' style='display: none;'>
                <button id='togglePhotos' class='btn-photos-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-images me-2'></i>
                        <span class='button-text'>Show More Photos</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>
            
            <!-- Estado cuando no hay fotos -->
            <div id='no-photos-state' class='no-photos-state d-none' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-camera'></i>
                    </div>
                    <h3 class='empty-title'>No Photos Available</h3>
                    <p class='empty-text'>Check back soon for new photos</p>
                    <div class='photo-wave'>
                        <div class='wave-camera'></div>
                        <div class='wave-camera'></div>
                        <div class='wave-camera'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox Mejorado -->
    <div id='lightbox' class='lightbox'>
        <span class='close'>&times;</span>
        <img class='lightbox-image' id='lightbox-img' src='' alt=''>
        <button class='prev'>&#10094;</button>
        <button class='next'>&#10095;</button>
    </div>
";
?>
