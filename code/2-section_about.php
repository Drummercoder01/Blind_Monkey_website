<?php
$mainPoints = extractMainPoints('Y_about_text_I.html', 5);

$_inhoud .= "
    <section class='about py-5' id='about'>
        <div class='container'>
            <!-- Header de sección mejorado -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-people-fill'></i>
                </div>
                <h1 class='section-title'>About Us</h1>
                <p class='section-subtitle'>Get to know The 5 AM</p>
                <div class='section-divider'></div>
            </div>

            <!-- Contenedor principal con glassmorphism -->
            <div class='about-container'>
                <!-- Imagen de la banda con efectos mejorados -->
                <div class='about-image-wrapper'>
                    <div class='image-glow'></div>
                    <img src='/img/round_the5am.jpeg' alt='The 5 AM Band' class='about-image'>
                    <div class='image-ring'></div>
                </div>

                <!-- Sección de información -->
                <div class='about-content'>
                    <!-- Título con diseño mejorado -->
                    <div class='about-title-section'>
                        <h2 class='about-subtitle'>
                            <span class='hashtag'>#inbullets</span>
                            <span class='hashtag'>#inshort</span>
                        </h2>
                    </div>

                    <!-- Lista de puntos principales (versión corta) -->
                    <div id='about-text-short' class='about-bullets'>
                        <ul class='bullet-list'>";
                        
foreach ($mainPoints as $index => $point) {
    $_inhoud .= "
                            <li class='bullet-item' style='animation-delay: " . ($index * 0.1) . "s'>
                                <span class='bullet-icon'>
                                    <i class='bi bi-check-circle-fill'></i>
                                </span>
                                <span class='bullet-text'>" . htmlspecialchars($point) . "</span>
                            </li>";
}

$_inhoud .= "
                        </ul>
                    </div>

                    <!-- Texto completo (oculto inicialmente) -->
                    <div id='about-text-full' class='about-full-text'>
                        <div class='full-text-content'>
                            " . inlezen('Y_about_text_I.html') . "
                        </div>
                    </div>

                    <!-- Botón Read More/Less mejorado -->
                    <div class='text-center mt-5' id='about-button-container'>
                        <button id='toggle-button' class='btn-about-toggle'>
                            <span class='btn-content'>
                                <i class='bi bi-book me-2 icon-start'></i>
                                <span class='button-text'>Read Full Story</span>
                                <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                            </span>
                            <span class='btn-glow'></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
