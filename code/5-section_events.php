<?php
$_inhoud .= "
    <section id='events' class='py-5'>
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
                        <a href='https://www.facebook.com/p/Blind-Monkey-100063621306043/' target='_blank' class='cs-link cs-facebook'>
                            <i class='fab fa-facebook-f'></i> Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
