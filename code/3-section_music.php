<?php
$_inhoud .= "
    <section id='music' class='py-5'>
        <div class='container'>
            <!-- Header -->
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-music-note-beamed'></i>
                </div>
                <h1 class='section-title'>Our Music</h1>
                <p class='section-subtitle'>Streaming now on Spotify</p>
                <div class='section-divider'></div>
            </div>

            <!-- Spotify Artist Embed -->
            <div class='spotify-artist-block mb-5'>
                <iframe
                    style='border-radius:16px'
                    src='https://open.spotify.com/embed/artist/6o0z83KmtPJtdpUFwJKhEj?utm_source=generator&theme=0'
                    width='100%'
                    height='352'
                    frameBorder='0'
                    allowfullscreen=''
                    allow='autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture'
                    loading='lazy'>
                </iframe>
            </div>
        </div>

        <!-- Tracks from DB -->
        <div class='music-container'>
            <div id='music-content' style='display: none;'>
                <div class='container mb-3'>
                    <h3 class='tracks-label'>
                        <i class='bi bi-vinyl-fill me-2'></i>Tracks
                    </h3>
                </div>

                <div id='music-grid' class='music-grid'></div>
                <div id='music-additional' class='music-grid' style='display: none;'></div>

                <div id='music-button-container' class='text-center mt-5'>
                    <button id='toggleMusic' class='btn-music-toggle'>
                        <span class='btn-content'>
                            <i class='bi bi-music-note-list me-2'></i>
                            <span class='button-text'>Load More Tracks</span>
                            <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                        </span>
                        <span class='btn-glow'></span>
                    </button>
                </div>
            </div>

            <!-- No music state -->
            <div id='no-music-state' class='no-music-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-music-note-beamed'></i>
                    </div>
                    <h3 class='empty-title'>More Coming Soon</h3>
                    <p class='empty-text'>Follow us on Spotify to stay updated</p>
                    <div class='music-wave'>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                        <div class='wave-bar'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
