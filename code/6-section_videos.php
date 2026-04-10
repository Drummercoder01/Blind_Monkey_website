<?php
$_inhoud .= "
    <section id='videos' class='py-5'>
        <div class='section-sparks' aria-hidden='true'>
            <span class='hero-spark' style='left:9%;  animation-duration:8s;  animation-delay:1s;'></span>
            <span class='hero-spark' style='left:27%; animation-duration:12s; animation-delay:3.5s;'></span>
            <span class='hero-spark' style='left:50%; animation-duration:10s; animation-delay:0.5s;'></span>
            <span class='hero-spark' style='left:67%; animation-duration:7s;  animation-delay:4s;'></span>
            <span class='hero-spark' style='left:87%; animation-duration:13s; animation-delay:2s;'></span>
            <span class='hero-spark hero-spark--white' style='left:38%; animation-duration:9s;  animation-delay:6s;'></span>
            <span class='hero-spark hero-spark--white' style='left:76%; animation-duration:11s; animation-delay:1.5s;'></span>
        </div>
        <div class='container'>
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-camera-video'></i>
                </div>
                <h1 class='section-title'>Videos</h1>
                <p class='section-subtitle'>Live footage &amp; studio sessions</p>
                <div class='section-divider'></div>
            </div>
        </div>

        <div class='videos-container'>
            <!-- Grid (auto-populated by admin) -->
            <div id='videos-grid' class='videos-grid' style='display: none;'></div>
            <div id='videos-additional' class='videos-grid' style='display: none;'></div>

            <div id='videos-button-container' class='text-center mt-5' style='display: none;'>
                <button id='toggleVideos' class='btn-videos-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-play-circle me-2'></i>
                        <span class='button-text'>Watch More Videos</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>

            <!-- Empty state — shown until YouTube content is added -->
            <div id='no-videos-state' class='no-videos-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='fab fa-youtube'></i>
                    </div>
                    <h3 class='empty-title'>Coming Soon</h3>
                    <p class='empty-text'>Live footage &amp; clips dropping soon — follow us to be the first to watch</p>
                    <div class='coming-soon-socials'>
                        <a href='https://www.youtube.com/@BlindMonkey_reloaded' target='_blank' class='cs-link cs-youtube'>
                            <i class='fab fa-youtube'></i> YouTube
                        </a>
                        <a href='https://www.tiktok.com/@blind.monkey7' target='_blank' class='cs-link cs-tiktok'>
                            <i class='fab fa-tiktok'></i> TikTok
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
