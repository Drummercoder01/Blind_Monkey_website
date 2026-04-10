<?php
$_inhoud .= "
    <section id='events' class='py-5'>
        <div class='section-sparks' aria-hidden='true'>
            <span class='hero-spark' style='left:7%;  animation-duration:12s; animation-delay:0s;'></span>
            <span class='hero-spark' style='left:23%; animation-duration:9s;  animation-delay:2s;'></span>
            <span class='hero-spark' style='left:48%; animation-duration:7s;  animation-delay:5s;'></span>
            <span class='hero-spark' style='left:64%; animation-duration:13s; animation-delay:1s;'></span>
            <span class='hero-spark' style='left:82%; animation-duration:10s; animation-delay:3.5s;'></span>
            <span class='hero-spark hero-spark--white' style='left:35%; animation-duration:11s; animation-delay:4s;'></span>
            <span class='hero-spark hero-spark--white' style='left:72%; animation-duration:8s;  animation-delay:2.5s;'></span>
        </div>
        <div class='container'>
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-calendar-event'></i>
                </div>
                <h1 class='section-title'>Shows</h1>
                <p class='section-subtitle'>Catch us live</p>
                <div class='section-divider'></div>
            </div>
        </div>

        <div class='events-container'>
            <div id='events-grid' class='events-grid' style='display: none;'></div>
            <div id='events-additional' class='events-grid' style='display: none;'></div>

            <div id='events-button-container' class='text-center mt-5' style='display: none;'>
                <button id='toggleEvents' class='btn-events-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-calendar-event me-2'></i>
                        <span class='button-text'>View All Shows</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>

            <!-- Empty state -->
            <div id='no-events-state' class='no-events-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-calendar-event'></i>
                    </div>
                    <h3 class='empty-title'>New Shows Being Booked</h3>
                    <p class='empty-text'>Follow our socials to be the first to know</p>
                    <div class='coming-soon-socials'>
                        <a href='https://www.instagram.com/blind_monkey_antwerpen/' target='_blank' class='cs-link cs-instagram'>
                            <i class='fab fa-instagram'></i> Instagram
                        </a>
                        <a href='https://www.facebook.com/people/Blind-Monkey/100066979562626/#' target='_blank' class='cs-link cs-facebook'>
                            <i class='fab fa-facebook-f'></i> Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
