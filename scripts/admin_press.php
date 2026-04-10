<?php
try {
    require("../code/initialisatie_admin.inc.php");
    
    $_inhoud .= "
    <style>
        /* ========== ADMIN PRESS PAGE STYLES ========== */
        
        .admin-press-container {
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
        
        .btn-add-press {
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
        
        .btn-add-press::before {
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
        
        .btn-add-press:hover {
            background: linear-gradient(135deg, #8b0000 0%, #c41e1e 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(196, 30, 30, 0.5);
            color: #000;
        }
        
        .btn-add-press:hover::before {
            left: 100%;
        }
        
        /* Press Items Container */
        .press-items-container {
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
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
            font-size: 0.95rem;
        }
        
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
            border-color: #c41e1e !important;
            box-shadow: 0 0 0 3px rgba(196, 30, 30, 0.15), 
                        0 0 20px rgba(196, 30, 30, 0.2) !important;
            color: white !important;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
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
        
        /* Required Field Indicator */
        .form-label::after {
            content: '';
        }
        
        .form-label:has(+ .form-control[required])::after,
        .form-label:has(+ textarea[required])::after,
        .form-label:has(+ input[required])::after {
            content: ' *';
            color: #c41e1e;
            font-weight: 700;
        }
        
        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, 
                rgba(196, 30, 30, 0.1) 0%, 
                rgba(196, 30, 30, 0.05) 100%);
            border: 1px solid rgba(196, 30, 30, 0.3);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: start;
            gap: 0.75rem;
        }
        
        .info-box-icon {
            font-size: 1.25rem;
            color: #c41e1e;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }
        
        .info-box-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
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
            
            .btn-add-press {
                padding: 0.875rem 2rem;
                font-size: 1rem;
                width: 100%;
            }
            
            .press-items-container {
                padding: 0 1rem 1rem;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
            
            .btn-modal-save {
                width: 100%;
            }
        }
    </style>

    <div class='admin-press-container'>
        <!-- Page Header -->
        <div class='admin-page-header'>
            <h1 class='admin-page-title'>
                <i class='bi bi-newspaper me-3'></i>
                Press Manager
            </h1>
            <p class='admin-page-subtitle'>
                Manage press coverage, reviews, and media mentions
            </p>
        </div>

        <div class='container'>
            <!-- Action Button -->
            <div class='action-buttons-bar'>
                <button type='button' class='btn-add-press' data-bs-toggle='modal' data-bs-target='#pressModal'>
                    <i class='bi bi-plus-circle-fill me-2'></i>
                    Add New Press Item
                </button>
            </div>

            <!-- Press Items Container -->
            <div class='press-items-container'>
                <div class='loading-container'>
                    <div class='loading-spinner'></div>
                    <p class='loading-text'>Loading press items...</p>
                </div>
            </div>
        </div>
    </div>";

    // Modal para agregar/editar press items
    $_inhoud .= "
    <!-- Press Modal -->
    <div class='modal fade' id='pressModal' tabindex='-1' aria-labelledby='pressModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='pressModalLabel'>
                <i class='bi bi-newspaper me-2'></i>
                Add Press Item
            </h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <!-- Info Box -->
            <div class='info-box'>
                <i class='bi bi-info-circle-fill info-box-icon'></i>
                <div class='info-box-text'>
                    Add press coverage, reviews, or media mentions. Include the source and a brief excerpt.
                </div>
            </div>

            <form id='pressForm'>
              <input type='hidden' id='edit_id' name='edit_id' value=''>
              
              <div class='mb-3'>
                <label for='pressText' class='form-label'>Press Excerpt</label>
                <textarea class='form-control' name='press_text' id='pressText' rows='4' required placeholder='Enter the press quote or excerpt...'></textarea>
              </div>
              
              <div class='mb-3'>
                <label for='pressAuthor' class='form-label'>Author/Source</label>
                <input type='text' class='form-control' name='press_author' id='pressAuthor' required placeholder='e.g., Rolling Stone, Music Magazine'>
              </div>
              
              <div class='mb-3'>
                <label for='pressComment' class='form-label'>Comment</label>
                <input type='text' class='form-control' name='press_comment' id='pressComment' placeholder='Optional additional comment'>
              </div>
              
              <div class='mb-3'>
                <label for='pressLink' class='form-label'>Article URL</label>
                <input type='url' class='form-control' name='press_link' id='pressLink' placeholder='https://example.com/article'>
              </div>
              
              <div class='mb-3'>
                <label for='pressTime' class='form-label'>Publication Date</label>
                <input type='date' class='form-control' name='press_time' id='pressTime' required>
              </div>
              
              <div class='text-center mt-4'>
                <button type='submit' class='btn-modal-save'>
                    <i class='bi bi-check-circle-fill me-2'></i>
                    Save Press Item
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script>
        console.log('📰 Press Manager initialized');
    </script>";

    $_jsInclude = array("../js/simple_press_editor.js");
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>
