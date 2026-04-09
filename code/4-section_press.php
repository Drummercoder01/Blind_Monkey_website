<?php
$_inhoud .= "
    <section id='press' class='py-5'>
        <div class='container'>
            <div class='section-header'>
                <div class='section-icon'>
                    <i class='bi bi-newspaper'></i>
                </div>
                <h1 class='section-title'>Press &amp; Media</h1>
                <p class='section-subtitle'>What they say about us</p>
                <div class='section-divider'></div>
            </div>
        </div>

        <div class='press-container'>
            <div id='press-grid' class='press-grid' style='display: none;'></div>
            <div id='press-additional' class='press-grid' style='display: none;'></div>

            <div id='press-button-container' class='text-center mt-5' style='display: none;'>
                <button id='togglePress' class='btn-press-toggle'>
                    <span class='btn-content'>
                        <i class='bi bi-newspaper me-2'></i>
                        <span class='button-text'>Load More Articles</span>
                        <i class='bi bi-chevron-down ms-2 chevron-icon'></i>
                    </span>
                    <span class='btn-glow'></span>
                </button>
            </div>

            <!-- Empty state -->
            <div id='no-press-state' class='no-press-state' style='display: none;'>
                <div class='empty-state'>
                    <div class='empty-icon'>
                        <i class='bi bi-newspaper'></i>
                    </div>
                    <h3 class='empty-title'>Press Coverage Coming</h3>
                    <p class='empty-text'>Reviews, interviews &amp; features on their way — check back soon</p>
                    <div class='press-wave'>
                        <div class='wave-line'></div>
                        <div class='wave-line'></div>
                        <div class='wave-line'></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
";
?>
