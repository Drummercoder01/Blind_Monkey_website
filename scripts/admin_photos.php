<?php
try {
    require("../code/initialisatie.inc.php");
    
    $_inhoud .= "
    <style>
        /* ========== ADMIN PHOTOS PAGE STYLES ========== */
        
        .admin-photos-container {
            padding: 0;
            max-width: 100%;
        }
        
        /* Page Header */
        .admin-page-header {
            background: linear-gradient(135deg, 
                rgba(38, 227, 255, 0.1) 0%, 
                rgba(38, 227, 255, 0.05) 100%);
            border-bottom: 2px solid rgba(38, 227, 255, 0.2);
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
                rgba(38, 227, 255, 0.15), 
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
            color: rgba(38, 227, 255, 0.9);
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
        
        /* Add Photo Button */
        .btn-add-photo {
            background: linear-gradient(135deg, #26e3ff 0%, #1a9fb8 100%);
            color: #000;
            border: 2px solid #26e3ff;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 12px;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(38, 227, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-add-photo::before {
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
        
        .btn-add-photo:hover {
            background: linear-gradient(135deg, #1a9fb8 0%, #26e3ff 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(38, 227, 255, 0.5);
            color: #000;
        }
        
        .btn-add-photo:hover::before {
            left: 100%;
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
        
        /* Photos Container */
        .photos-container {
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
            border: 4px solid rgba(38, 227, 255, 0.2);
            border-top-color: #26e3ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* ========== MODAL STYLES ========== */
        
        .modal-content {
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.98) 0%, 
                rgba(30, 41, 59, 0.98) 100%) !important;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(38, 227, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(38, 227, 255, 0.2);
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, 
                rgba(38, 227, 255, 0.08) 0%, 
                rgba(38, 227, 255, 0.03) 100%);
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
        
        /* Form Label */
        .form-label {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            margin-bottom: 0.75rem;
            letter-spacing: 0.3px;
            font-size: 0.95rem;
        }
        
        /* Radio Buttons Custom */
        .form-check {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-check:hover {
            background: rgba(0, 0, 0, 0.3);
            border-color: rgba(38, 227, 255, 0.3);
        }
        
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-top: 0.125rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.3);
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: #26e3ff;
            border-color: #26e3ff;
            box-shadow: 0 0 10px rgba(38, 227, 255, 0.5);
        }
        
        .form-check-input:focus {
            border-color: #26e3ff;
            box-shadow: 0 0 0 3px rgba(38, 227, 255, 0.2);
        }
        
        .form-check-label {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            cursor: pointer;
            margin-left: 0.5rem;
        }
        
        .form-check:has(.form-check-input:checked) {
            background: linear-gradient(135deg, 
                rgba(38, 227, 255, 0.15) 0%, 
                rgba(38, 227, 255, 0.08) 100%);
            border-color: rgba(38, 227, 255, 0.5);
        }
        
        .form-check:has(.form-check-input:checked) .form-check-label {
            color: #26e3ff !important;
            font-weight: 600;
        }
        
        /* Form Controls */
        .form-control {
            background: rgba(0, 0, 0, 0.3) !important;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 10px;
            padding: 0.85rem 1.25rem;
            font-size: 1rem;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-control:focus {
            background: rgba(0, 0, 0, 0.4) !important;
            border-color: #26e3ff !important;
            box-shadow: 0 0 0 3px rgba(38, 227, 255, 0.15), 
                        0 0 20px rgba(38, 227, 255, 0.2) !important;
            color: white !important;
        }
        
        /* File Input Custom */
        input[type='file'].form-control {
            padding: 0.75rem;
            cursor: pointer;
        }
        
        input[type='file'].form-control::file-selector-button {
            background: linear-gradient(135deg, 
                rgba(38, 227, 255, 0.2) 0%, 
                rgba(38, 227, 255, 0.1) 100%);
            border: 1px solid rgba(38, 227, 255, 0.4);
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            color: #26e3ff;
            font-weight: 600;
            margin-right: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        input[type='file'].form-control::file-selector-button:hover {
            background: linear-gradient(135deg, 
                rgba(38, 227, 255, 0.3) 0%, 
                rgba(38, 227, 255, 0.2) 100%);
            border-color: rgba(38, 227, 255, 0.6);
            transform: translateY(-2px);
        }
        
        /* Form Text */
        .form-text {
            color: rgba(255, 255, 255, 0.6) !important;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        /* Modal Submit Button */
        .btn-modal-save {
            background: linear-gradient(135deg, #26e3ff 0%, #1a9fb8 100%);
            color: #000;
            border: 2px solid #26e3ff;
            padding: 0.875rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 10px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(38, 227, 255, 0.3);
        }
        
        .btn-modal-save:hover {
            background: linear-gradient(135deg, #1a9fb8 0%, #26e3ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(38, 227, 255, 0.5);
            color: #000;
        }
        
        /* Section Transitions */
        #uploadSection,
        #linkSection {
            transition: all 0.3s ease;
        }
        
        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, 
                rgba(38, 227, 255, 0.1) 0%, 
                rgba(38, 227, 255, 0.05) 100%);
            border: 1px solid rgba(38, 227, 255, 0.3);
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
            color: #26e3ff;
            flex-shrink: 0;
        }
        
        .info-banner-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            line-height: 1.5;
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
            
            .btn-add-photo,
            .btn-save-order {
                padding: 0.875rem 2rem;
                font-size: 1rem;
                width: 100%;
            }
            
            .photos-container {
                padding: 0 1rem 1rem;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
            
            .btn-modal-save {
                width: 100%;
            }
            
            .info-banner {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <div class='admin-photos-container'>
        <!-- Page Header -->
        <div class='admin-page-header'>
            <h1 class='admin-page-title'>
                <i class='bi bi-camera me-3'></i>
                Photos Manager
            </h1>
            <p class='admin-page-subtitle'>
                Upload and organize band photos, concert shots, and behind-the-scenes
            </p>
        </div>

        <div class='container'>
            <!-- Info Banner -->
            <div class='info-banner'>
                <i class='bi bi-info-circle-fill info-banner-icon'></i>
                <div class='info-banner-text'>
                    <strong>Drag & Drop</strong> to reorder photos. Click <strong>Save Order</strong> to apply changes. Max file size: 10MB.
                </div>
            </div>

            <!-- Action Buttons -->
            <div class='action-buttons-bar'>
                <button type='button' class='btn-add-photo' data-bs-toggle='modal' data-bs-target='#photoModal'>
                    <i class='bi bi-plus-circle-fill me-2'></i>
                    Add New Photo
                </button>
                <button type='button' class='btn-save-order' id='saveOrderBtn'>
                    <i class='bi bi-save me-2'></i>
                    Save Order
                </button>
            </div>

            <!-- Photos Container -->
            <div class='photos-container'>
                <div class='loading-container'>
                    <div class='loading-spinner'></div>
                    <p class='loading-text'>Loading photos...</p>
                </div>
            </div>
        </div>
    </div>";

    // Modal para agregar/editar fotos
    $_inhoud .= "
    <!-- Photo Modal -->
    <div class='modal fade' id='photoModal' tabindex='-1' aria-labelledby='photoModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='photoModalLabel'>
                <i class='bi bi-camera me-2'></i>
                Add Photo
            </h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <form id='photoForm' enctype='multipart/form-data'>
              <input type='hidden' id='edit_id' name='edit_id' value=''>
              
              <div class='mb-4'>
                <label class='form-label'>
                    <i class='bi bi-upload me-2' style='color: #26e3ff;'></i>
                    Choose upload method:
                </label>
                <div class='form-check'>
                  <input class='form-check-input' type='radio' name='photo_method' id='methodUpload' value='upload' checked>
                  <label class='form-check-label' for='methodUpload'>
                      Upload Image File
                  </label>
                </div>
                <div class='form-check'>
                  <input class='form-check-input' type='radio' name='photo_method' id='methodLink' value='link'>
                  <label class='form-check-label' for='methodLink'>
                      Use Image URL
                  </label>
                </div>
              </div>
              
              <div id='uploadSection'>
                <div class='mb-3'>
                  <label for='photoFile' class='form-label'>
                      <i class='bi bi-file-image me-2' style='color: #26e3ff;'></i>
                      Choose Image File
                  </label>
                  <input type='file' class='form-control' name='photo_file' id='photoFile' accept='image/*'>
                  <small class='form-text'>Supported: JPG, PNG, GIF, WEBP • Max size: 10MB</small>
                </div>
              </div>
              
              <div id='linkSection' style='display: none;'>
                <div class='mb-3'>
                  <label for='photoLink' class='form-label'>
                      <i class='bi bi-link-45deg me-2' style='color: #26e3ff;'></i>
                      Image URL
                  </label>
                  <input type='url' class='form-control' name='photo_link' id='photoLink' placeholder='https://example.com/image.jpg'>
                  <small class='form-text'>Enter the direct URL to an image</small>
                </div>
              </div>
              
              <div class='text-center mt-4'>
                <button type='submit' class='btn-modal-save'>
                    <i class='bi bi-check-circle-fill me-2'></i>
                    Save Photo
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script>
        console.log('📸 Photos Manager initialized');
        
        // Toggle entre upload y link
        document.querySelectorAll('input[name=\"photo_method\"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'upload') {
                    document.getElementById('uploadSection').style.display = 'block';
                    document.getElementById('linkSection').style.display = 'none';
                } else {
                    document.getElementById('uploadSection').style.display = 'none';
                    document.getElementById('linkSection').style.display = 'block';
                }
            });
        });
    </script>";

    $_jsInclude = array(
        "https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js",
        "../js/photos_manager.js"
    );
    
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>
