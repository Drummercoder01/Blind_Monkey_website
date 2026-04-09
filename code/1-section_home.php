<?php
$_inhoud .= "
<section id='home' class='min-vh-100 d-flex align-items-center'>

    <!-- ══ Atmospheric hero background ══ -->
    <div class='hero-bg' aria-hidden='true'>
        <div class='hero-glow hero-glow--1'></div>
        <div class='hero-glow hero-glow--2'></div>
        <div class='hero-glow hero-glow--3'></div>
        <div class='hero-slashes'></div>
        <span class='hero-spark' style='left:6%;  animation-duration:8s;  animation-delay:0s;'></span>
        <span class='hero-spark' style='left:19%; animation-duration:12s; animation-delay:2.5s;'></span>
        <span class='hero-spark' style='left:34%; animation-duration:9s;  animation-delay:5s;'></span>
        <span class='hero-spark' style='left:52%; animation-duration:14s; animation-delay:1s;'></span>
        <span class='hero-spark' style='left:68%; animation-duration:7s;  animation-delay:3s;'></span>
        <span class='hero-spark' style='left:83%; animation-duration:11s; animation-delay:4.5s;'></span>
        <span class='hero-spark hero-spark--white' style='left:41%; animation-duration:10s; animation-delay:6s;'></span>
        <span class='hero-spark hero-spark--white' style='left:76%; animation-duration:6s;  animation-delay:2s;'></span>
    </div>

    <div class='container'>
        <div class='home-content text-center'>

            <!-- Tagline sobre el logo -->
            <div class='hero-tagline'>
                <h1 class='band-name'>BLIND MONKEY</h1>
                <p class='tagline-text'>Rock &middot; Grunge &middot; Antwerpen</p>
            </div>

            <!-- Logo animado -->
            <div class='ticket-banner'>
                <div class='ticket-container'>
                    <div class='particle-effect'></div>
                    <div class='ticket-logo-overlay'>
                        <img src='../img/blind_monkey_logo.jpg' alt='Blind Monkey Logo' class='ticket-logo'>
                    </div>
                    <div class='shine-effect'></div>
                </div>
            </div>

            <!-- CTA Principal -->
            <div class='cta-section'>
                <a href='https://open.spotify.com/artist/6o0z83KmtPJtdpUFwJKhEj' class='cta-primary' target='_blank' rel='noopener noreferrer'>
                    <i class='fab fa-spotify'></i>
                    <span>Listen on Spotify</span>
                </a>
                <a href='#events' class='cta-secondary'>
                    <span>Upcoming Shows</span>
                    <i class='fas fa-calendar-alt'></i>
                </a>
            </div>

            <!-- Social Icons -->
            <div class='social-section'>
                <h3 class='social-title'>Follow the Monkey</h3>
                <div class='social-icons'>

                    <!-- Instagram -->
                    <a href='https://www.instagram.com/blind_monkey_antwerpen/' class='social-icon' target='_blank' aria-label='Instagram' data-tooltip='Instagram'>
                        <i class='fab fa-instagram'></i>
                    </a>

                    <!-- Spotify -->
                    <a href='https://open.spotify.com/artist/6o0z83KmtPJtdpUFwJKhEj' class='social-icon' target='_blank' aria-label='Spotify' data-tooltip='Spotify'>
                        <i class='fab fa-spotify'></i>
                    </a>

                    <!-- TikTok -->
                    <a href='https://www.tiktok.com/@blind.monkey7' class='social-icon' target='_blank' aria-label='TikTok' data-tooltip='TikTok'>
                        <i class='fab fa-tiktok'></i>
                    </a>

                    <!-- YouTube -->
                    <a href='https://www.youtube.com/@blindmonkey' class='social-icon' target='_blank' aria-label='YouTube' data-tooltip='YouTube'>
                        <i class='fab fa-youtube'></i>
                    </a>

                    <!-- VI.BE -->
                    <a href='https://vi.be/platform/bara' class='social-icon' target='_blank' aria-label='VI.BE' data-tooltip='VI.BE'>
                        <i class='fas fa-music'></i>
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>
";
?>
