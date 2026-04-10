<?php
try {
    require("../code/initialisatie_admin.inc.php");
    require("../code/admin_music_modal.php");

    $_inhoud .= "
        <style>
            /* ========== ADMIN MUSIC PAGE STYLES ========== */
            
            .admin-music-container {
                padding: 0;
                max-width: 100%;
            }
            
            /* Page Header */
            .admin-page-header {
                background: linear-gradient(135deg, 
                    rgba(196, 30, 30, 0.1) 0%, 
                    rgba(196, 30, 30, 0.05) 100%);
                border-bottom: 2px solid rgba(196, 30, 30, 0.2);
                padding: 2.5rem 0;
                margin-bottom: 2.5rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .admin-page-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 100%;
                background: radial-gradient(circle at top right, 
                    rgba(196, 30, 30, 0.15), 
                    transparent 70%);
                pointer-events: none;
            }
            
            .admin-page-title {
                font-size: 2.5rem;
                font-weight: 900;
                color: white;
                margin: 0;
                letter-spacing: 1px;
                text-transform: uppercase;
                position: relative;
                z-index: 1;
            }
            
            .admin-page-subtitle {
                color: rgba(196, 30, 30, 0.9);
                font-size: 1rem;
                margin-top: 0.5rem;
                font-weight: 500;
                letter-spacing: 0.5px;
                position: relative;
                z-index: 1;
            }
            
            /* Action Buttons Bar */
            .action-buttons-bar {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 1rem;
                padding: 2rem 0;
                flex-wrap: wrap;
            }
            
            /* Add Song Button */
            .btn-add-song {
                background: linear-gradient(135deg, #c41e1e 0%, #8b0000 100%);
                color: #000;
                border: 2px solid #c41e1e;
                padding: 1rem 2.5rem;
                font-size: 1.1rem;
                font-weight: 700;
                border-radius: 12px;
                letter-spacing: 0.5px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 15px rgba(196, 30, 30, 0.3);
                position: relative;
                overflow: hidden;
            }
            
            .btn-add-song::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, 
                    transparent, 
                    rgba(255, 255, 255, 0.3), 
                    transparent);
                transition: left 0.5s ease;
            }
            
            .btn-add-song:hover {
                background: linear-gradient(135deg, #8b0000 0%, #c41e1e 100%);
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(196, 30, 30, 0.5);
                color: #000;
            }
            
            .btn-add-song:hover::before {
                left: 100%;
            }
            
            .btn-add-song i {
                font-size: 1.2rem;
                margin-left: 0.5rem;
            }
            
            /* Save Order Button */
            .btn-save-order {
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.1) 0%, 
                    rgba(255, 255, 255, 0.05) 100%);
                backdrop-filter: blur(10px);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
                padding: 1rem 2.5rem;
                font-size: 1.1rem;
                font-weight: 700;
                border-radius: 12px;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }
            
            .btn-save-order:hover {
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.2) 0%, 
                    rgba(255, 255, 255, 0.1) 100%);
                border-color: rgba(255, 255, 255, 0.5);
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
                color: white;
            }
            
            .btn-save-order i {
                margin-right: 0.5rem;
            }
            
            /* Music Container */
            .music-container {
                padding: 0 2rem 2rem;
                max-width: 1400px;
                margin: 0 auto;
            }
            
            /* Loading Spinner */
            .loading-container {
                text-align: center;
                padding: 4rem 0;
            }
            
            .loading-spinner {
                width: 4rem;
                height: 4rem;
                border: 4px solid rgba(196, 30, 30, 0.2);
                border-top-color: #c41e1e;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 1.5rem;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            .loading-text {
                color: rgba(255, 255, 255, 0.7);
                font-size: 1.1rem;
                font-weight: 500;
            }
            
            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
                background: linear-gradient(135deg, 
                    rgba(255, 255, 255, 0.05) 0%, 
                    rgba(255, 255, 255, 0.02) 100%);
                border-radius: 20px;
                border: 2px dashed rgba(196, 30, 30, 0.3);
                margin: 2rem auto;
                max-width: 600px;
            }
            
            .empty-state-icon {
                font-size: 4rem;
                color: rgba(196, 30, 30, 0.5);
                margin-bottom: 1.5rem;
            }
            
            .empty-state-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: white;
                margin-bottom: 0.5rem;
            }
            
            .empty-state-text {
                color: rgba(255, 255, 255, 0.7);
                font-size: 1rem;
            }
            
            /* Songs Grid - Already styled in style_admin.css but enhanced here */
            .songs-sortable-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 25px;
                padding: 0;
                width: 100%;
            }
            
            /* Info Banner */
            .info-banner {
                background: linear-gradient(135deg, 
                    rgba(196, 30, 30, 0.1) 0%, 
                    rgba(196, 30, 30, 0.05) 100%);
                border: 1px solid rgba(196, 30, 30, 0.3);
                border-radius: 12px;
                padding: 1.25rem 1.5rem;
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                max-width: 1400px;
                margin-left: auto;
                margin-right: auto;
            }
            
            .info-banner-icon {
                font-size: 1.5rem;
                color: #c41e1e;
                flex-shrink: 0;
            }
            
            .info-banner-text {
                color: rgba(255, 255, 255, 0.9);
                font-size: 0.95rem;
                line-height: 1.5;
            }
            
            .info-banner-text strong {
                color: #c41e1e;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                .admin-page-header {
                    padding: 2rem 1rem;
                }
                
                .admin-page-title {
                    font-size: 2rem;
                }
                
                .action-buttons-bar {
                    padding: 1.5rem 0;
                }
                
                .btn-add-song,
                .btn-save-order {
                    padding: 0.875rem 2rem;
                    font-size: 1rem;
                    width: 100%;
                }
                
                .music-container {
                    padding: 0 1rem 1rem;
                }
                
                .songs-sortable-container {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 15px;
                }
                
                .info-banner {
                    flex-direction: column;
                    text-align: center;
                    padding: 1rem;
                }
            }
            
            @media (max-width: 480px) {
                .songs-sortable-container {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
            }
        </style>

        <div class='admin-music-container'>
            <!-- Page Header -->
            <div class='admin-page-header'>
                <h1 class='admin-page-title'>
                    <i class='bi bi-music-note-beamed me-3'></i>
                    Music Manager
                </h1>
                <p class='admin-page-subtitle'>
                    Add, organize, and manage your music collection
                </p>
            </div>

            <!-- Info Banner -->
            <div class='container'>
                <div class='info-banner'>
                    <i class='bi bi-info-circle-fill info-banner-icon'></i>
                    <div class='info-banner-text'>
                        <strong>Drag & Drop</strong> to reorder songs. Click <strong>Save Order</strong> to apply changes. Each song can be previewed directly from Spotify.
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class='action-buttons-bar'>
                <button type='button' class='btn-add-song' data-bs-toggle='modal' data-bs-target='#addSongModal'>
                    Add New Song
                    <i class='bi bi-plus-circle-fill'></i>
                </button>
                <button type='button' class='btn-save-order' id='saveOrderBtn'>
                    <i class='bi bi-save'></i>
                    Save Order
                </button>
            </div>

            <!-- Music Container -->
            <div class='music-container' id='musicContainer'>
                <div class='loading-container'>
                    <div class='loading-spinner'></div>
                    <p class='loading-text'>Loading songs...</p>
                </div>
            </div>
        </div>

        <script>
            // Enhanced loading state
            console.log('🎵 Music Manager initialized');
            
            // Show success message after saving order
            document.getElementById('saveOrderBtn')?.addEventListener('click', function() {
                console.log('💾 Saving song order...');
            });
        </script>";

    $_jsInclude = array(
        "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js",
        "../js/admin_music_handler.js",
        "../js/ajax_add_song_simple.js"
    );

    require("../code/output_admin.inc.php");
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
