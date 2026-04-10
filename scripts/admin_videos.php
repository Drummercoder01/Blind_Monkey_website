<?php
try {
    require("../code/initialisatie_admin.inc.php");
    
    $_inhoud .= "
    <style>
        /* ========== ADMIN VIDEOS PAGE STYLES ========== */
        
        .admin-videos-container {
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
        
        /* Action Buttons */
        .action-buttons-bar {
            display: flex;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .btn-add-video {
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
        
        .btn-add-video::before {
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
        
        .btn-add-video:hover {
            background: linear-gradient(135deg, #8b0000 0%, #c41e1e 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(196, 30, 30, 0.5);
            color: #000;
        }
        
        .btn-add-video:hover::before {
            left: 100%;
        }
        
        /* Videos Container */
        .videos-container {
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
        
        /* ========== MODAL STYLES ========== */
        
        .modal-content {
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.98) 0%, 
                rgba(30, 41, 59, 0.98) 100%) !important;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(196, 30, 30, 0.3);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(196, 30, 30, 0.2);
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, 
                rgba(196, 30, 30, 0.08) 0%, 
                rgba(196, 30, 30, 0.03) 100%);
        }
        
        .modal-title {
            color: white !important;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .btn-close-white {
            filter: brightness(1.5);
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .btn-close-white:hover {
            opacity: 1;
            transform: rotate(90deg);
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        /* Form Styles */
        .form-label {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            margin-bottom: 0.75rem;
            letter-spacing: 0.3px;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            color: #c41e1e;
            font-size: 1.1rem;
        }
        
        .form-control {
            background: rgba(0, 0, 0, 0.3) !important;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            font-size: 0.95rem;
            color: white !important;
            transition: all 0.3s ease;
            font-family: 'Courier New', monospace;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 150px;
            line-height: 1.6;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
            font-family: 'Public Sans', sans-serif;
        }
        
        .form-control:focus {
            background: rgba(0, 0, 0, 0.4) !important;
            border-color: #c41e1e !important;
            box-shadow: 0 0 0 3px rgba(196, 30, 30, 0.15), 
                        0 0 20px rgba(196, 30, 30, 0.2) !important;
            color: white !important;
        }
        
        /* Form Text Helper */
        .form-text {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: start;
            gap: 0.5rem;
        }
        
        .form-text i {
            color: #c41e1e;
            margin-top: 0.125rem;
        }
        
        /* Alert Info Box */
        .alert-info-custom {
            background: linear-gradient(135deg, 
                rgba(196, 30, 30, 0.15) 0%, 
                rgba(196, 30, 30, 0.08) 100%);
            border: 1px solid rgba(196, 30, 30, 0.4);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .alert-info-custom strong {
            color: #c41e1e;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .alert-info-custom i {
            font-size: 1.25rem;
            color: #c41e1e;
        }
        
        .alert-info-custom ol {
            margin: 0.5rem 0 0 0;
            padding-left: 1.25rem;
            line-height: 1.8;
        }
        
        .alert-info-custom li {
            color: rgba(255, 255, 255, 0.85);
            margin: 0.25rem 0;
        }
        
        /* Modal Submit Button */
        .btn-modal-save {
            background: linear-gradient(135deg, #c41e1e 0%, #8b0000 100%);
            color: #000;
            border: 2px solid #c41e1e;
            padding: 0.875rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 10px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(196, 30, 30, 0.3);
        }
        
        .btn-modal-save:hover {
            background: linear-gradient(135deg, #8b0000 0%, #c41e1e 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(196, 30, 30, 0.5);
            color: #000;
        }
        
        .btn-modal-save i {
            font-size: 1.15rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-page-header {
                padding: 2rem 1rem;
            }
            
            .admin-page-title {
                font-size: 2rem;
            }
            
            .btn-add-video {
                padding: 0.875rem 2rem;
                font-size: 1rem;
                width: 100%;
            }
            
            .videos-container {
                padding: 0 1rem 1rem;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
            
            .btn-modal-save {
                width: 100%;
            }
            
            .alert-info-custom {
                padding: 1rem 1.25rem;
            }
        }
    </style>

    <div class='admin-videos-container'>
        <!-- Page Header -->
        <div class='admin-page-header'>
            <h1 class='admin-page-title'>
                <i class='bi bi-camera-video me-3'></i>
                Videos Manager
            </h1>
            <p class='admin-page-subtitle'>
                Add and manage YouTube videos, music videos, and performances
            </p>
        </div>

        <div class='container'>
            <!-- Action Button -->
            <div class='action-buttons-bar'>
                <button type='button' class='btn-add-video' data-bs-toggle='modal' data-bs-target='#addVideoModal'>
                    <i class='bi bi-plus-circle-fill me-2'></i>
                    Add New Video
                </button>
            </div>

            <!-- Videos Container -->
            <div class='videos-container'>
                <div class='loading-container'>
                    <div class='loading-spinner'></div>
                    <p class='loading-text'>Loading videos...</p>
                </div>
            </div>
        </div>
    </div>";

    // Modal para agregar videos
    $_inhoud .= "
    <!-- Video Modal -->
    <div class='modal fade' id='addVideoModal' tabindex='-1' aria-labelledby='addVideoModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='addVideoModalLabel'>
                <i class='bi bi-youtube me-2'></i>
                Add YouTube Video
            </h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <form id='videoForm'>
              <div class='mb-3'>
                <label for='embedCode' class='form-label'>
                    <i class='bi bi-code-slash me-2'></i>
                    YouTube Embed Code
                </label>
                <textarea class='form-control' 
                          name='embed_code' 
                          id='embedCode' 
                          rows='6' 
                          placeholder='<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/VIDEO_ID\" ...></iframe>' 
                          required></textarea>
                <div class='form-text'>
                    <i class='bi bi-info-circle'></i>
                    <span>Paste the complete YouTube embed iframe code (including &lt;iframe&gt; tags)</span>
                </div>
              </div>
              
              <div class='alert-info-custom'>
                <strong>
                    <i class='bi bi-lightbulb-fill'></i>
                    How to get the embed code:
                </strong>
                <ol>
                    <li>Go to your YouTube video</li>
                    <li>Click the <strong>Share</strong> button below the video</li>
                    <li>Select <strong>Embed</strong> from the options</li>
                    <li>Copy the entire <code>&lt;iframe&gt;</code> code</li>
                    <li>Paste it in the field above</li>
                </ol>
              </div>
              
              <div class='text-center mt-4'>
                <button type='submit' class='btn-modal-save'>
                    <i class='bi bi-plus-circle-fill me-2'></i>
                    Add Video
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js'></script>
    <script>
        console.log('🎥 Videos Manager initialized');
        
        $(document).ready(function() {
            // Cargar videos al iniciar
            refreshVideos();
        });
    </script>";

    $_jsInclude = array("../js/videos_manager.js");
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>
