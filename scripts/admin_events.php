<?php
try {
    require("../code/initialisatie_admin.inc.php");
    
    $_inhoud .= "
    <style>
        /* ========== ADMIN EVENTS PAGE STYLES ========== */
        
        .admin-events-container {
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
        
        .btn-add-event {
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
        
        .btn-add-event::before {
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
        
        .btn-add-event:hover {
            background: linear-gradient(135deg, #8b0000 0%, #c41e1e 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(196, 30, 30, 0.5);
            color: #000;
        }
        
        .btn-add-event:hover::before {
            left: 100%;
        }
        
        /* Events Container */
        .events-container {
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
        
        /* Date & Time inputs special styling */
        input[type='date']::-webkit-calendar-picker-indicator,
        input[type='time']::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
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
        .form-label:has(+ .form-control[required])::after,
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
            
            .btn-add-event {
                padding: 0.875rem 2rem;
                font-size: 1rem;
                width: 100%;
            }
            
            .events-container {
                padding: 0 1rem 1rem;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
            
            .btn-modal-save {
                width: 100%;
            }
            
            .row > div {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
    </style>

    <div class='admin-events-container'>
        <!-- Page Header -->
        <div class='admin-page-header'>
            <h1 class='admin-page-title'>
                <i class='bi bi-calendar-event me-3'></i>
                Events Manager
            </h1>
            <p class='admin-page-subtitle'>
                Schedule and manage upcoming shows, concerts, and performances
            </p>
        </div>

        <div class='container'>
            <!-- Action Button -->
            <div class='action-buttons-bar'>
                <button type='button' class='btn-add-event' data-bs-toggle='modal' data-bs-target='#eventModal'>
                    <i class='bi bi-plus-circle-fill me-2'></i>
                    Add New Event
                </button>
            </div>

            <!-- Events Container -->
            <div class='events-container'>
                <div class='loading-container'>
                    <div class='loading-spinner'></div>
                    <p class='loading-text'>Loading events...</p>
                </div>
            </div>
        </div>
    </div>";

    // Modal para agregar/editar eventos
    $_inhoud .= "
    <!-- Event Modal -->
    <div class='modal fade' id='eventModal' tabindex='-1' aria-labelledby='eventModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='eventModalLabel'>
                <i class='bi bi-calendar-event me-2'></i>
                Add Event
            </h5>
            <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <!-- Info Box -->
            <div class='info-box'>
                <i class='bi bi-info-circle-fill info-box-icon'></i>
                <div class='info-box-text'>
                    Add upcoming concerts, shows, or performances. Include complete details for your fans.
                </div>
            </div>

            <form id='eventForm'>
              <input type='hidden' id='edit_id' name='edit_id' value=''>
              
              <div class='mb-3'>
                <label for='eventName' class='form-label'>Event Name</label>
                <input type='text' class='form-control' name='event_name' id='eventName' required placeholder='e.g., Rock Festival 2025'>
              </div>
              
              <div class='mb-3'>
                <label for='eventLocation' class='form-label'>Location</label>
                <input type='text' class='form-control' name='event_location' id='eventLocation' required placeholder='e.g., Madison Square Garden, NYC'>
              </div>
              
              <div class='row'>
                <div class='col-md-6 mb-3'>
                  <label for='eventDate' class='form-label'>Date</label>
                  <input type='date' class='form-control' name='event_date' id='eventDate' required>
                </div>
                <div class='col-md-6 mb-3'>
                  <label for='eventTime' class='form-label'>Time</label>
                  <input type='time' class='form-control' name='event_time' id='eventTime' required>
                </div>
              </div>
              
              <div class='text-center mt-4'>
                <button type='submit' class='btn-modal-save'>
                    <i class='bi bi-check-circle-fill me-2'></i>
                    Save Event
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script>
        console.log('📅 Events Manager loaded');
        // refreshEvents() is called by DOMContentLoaded in events_manager.js
    </script>";

    $_jsInclude = array("../js/events_manager.js");
    require("../code/output_admin.inc.php");
    
} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
?>
