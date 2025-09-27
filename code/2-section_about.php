<?php
$mainPoints = extractMainPoints('Y_about_text_I.html', 5);

$_inhoud .= "
        <section class='about py-5' id='about'>
        <div class='container'>
            <h1 class='text-white display-3 fw-bold text-center py-5' id='nav-about'>About</h1>
            <!-- Cuadro con fondo negro transparente -->
            <div class='about-box p-5'>
                <!-- Imagen de la banda -->
                <div class='text-center'>
                    <img src='/img/round_the5am.jpeg' alt='The 5 AM Band' class='about-image mb-4'>
                </div>

                <!-- Texto Justificado -->
                <section id='the-5am-info'>
                    <h2>The 5 AM #inbullets #inshort</h2>

                    <!-- Shortened Text -->
                    <div id='about-text-short'>
                        <ul>";
                        
foreach ($mainPoints as $point) {
    $_inhoud .= "<li>" . htmlspecialchars($point) . "</li>";
}

$_inhoud .= "               </ul>
                    </div>

                    <!-- Full Text (oculto inicialmente mediante CSS) -->
                    <div id='about-text-full'>
                        " . inlezen('Y_about_text_I.html') . "                       
                    </div>

                    <!-- Read More / Read Less button -->
                    <div class='text-center mt-4' id='about-button-container'>
                        <button id='toggle-button' class='btn btn-light px-4 py-2 fw-bold'>
                            <i class='bi bi-arrow-down-circle me-2'></i>
                            <span class='button-text'>Read more</span>
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </section>
";
?>