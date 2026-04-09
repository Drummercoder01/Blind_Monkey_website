<?php
$mainPoints = extractMainPoints('Y_about_text_I.html', 5);

$_inhoud .= "
    <section class='about py-5' id='about'>
        <div class='container'>

            <!-- Header -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-people-fill'></i>
                </div>
                <h1 class='section-title'>The Band</h1>
                <p class='section-subtitle'>Get to know Blind Monkey</p>
                <div class='section-divider'></div>
            </div>

            <!-- Contenedor principal -->
            <div class='about-container'>

                <!-- Foto de la banda -->
                <div class='about-image-wrapper'>
                    <div class='image-glow'></div>
                    <img src='/img/band_photo.png' alt='Blind Monkey Band' class='about-image'>
                    <div class='image-ring'></div>
                </div>

                <!-- Info -->
                <div class='about-content'>
                    <div class='about-title-section'>
                        <h2 class='about-subtitle'>
                            <span class='hashtag'>#inbullets</span>
                            <span class='hashtag'>#inshort</span>
                        </h2>
                    </div>

                    <!-- Puntos principales -->
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

                    <!-- Texto completo -->
                    <div id='about-text-full' class='about-full-text'>
                        <div class='full-text-content'>
                            " . inlezen('Y_about_text_I.html') . "
                        </div>
                    </div>

                    <!-- Read More -->
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

            <!-- Miembros de la banda -->
            <div class='members-section mt-5'>
                <h3 class='members-title text-center'>Meet the Monkeys</h3>
                <div class='members-grid'>

                    <div class='member-card'>
                        <div class='member-icon'><i class='fas fa-microphone-alt'></i></div>
                        <h4 class='member-name'>Imke</h4>
                        <p class='member-role'>Lead Vocals</p>
                    </div>

                    <div class='member-card'>
                        <div class='member-icon'><i class='fas fa-guitar'></i></div>
                        <h4 class='member-name'>Barak</h4>
                        <p class='member-role'>Guitar &amp; Vocals</p>
                    </div>

                    <div class='member-card'>
                        <div class='member-icon'><i class='fas fa-guitar'></i></div>
                        <h4 class='member-name'>Bart</h4>
                        <p class='member-role'>Guitar</p>
                    </div>

                    <div class='member-card'>
                        <div class='member-icon'><i class='fas fa-bass-guitar'></i></div>
                        <h4 class='member-name'>Faisal</h4>
                        <p class='member-role'>Bass</p>
                    </div>

                    <div class='member-card'>
                        <div class='member-icon'><i class='fas fa-drum'></i></div>
                        <h4 class='member-name'>Alexis</h4>
                        <p class='member-role'>Drums</p>
                    </div>

                </div>
            </div>

        </div>
    </section>
";
?>
