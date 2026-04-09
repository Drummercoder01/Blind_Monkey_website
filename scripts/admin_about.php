<?php
try
{
	require(__DIR__ . "/../code/initialisatie.inc.php");
	// Ruta del archivo a editar
	$file_path = __DIR__ . "/../content/Y_about_text_I.html";

	// Variable para mensajes
	$_mensaje = "";
	
	// Guardar si se envió el formulario
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editor'])) {
	    $_inhoud = $_POST['editor'];
	    if (file_put_contents($file_path, $_inhoud)) {
	        $_mensaje = "<div class='alert-success'>
	            <i class='bi bi-check-circle-fill me-2'></i>
	            <strong>Changes saved successfully!</strong>
	        </div>";
	    } else {
	        $_mensaje = "<div class='alert-error'>
	            <i class='bi bi-x-circle-fill me-2'></i>
	            <strong>Error saving changes.</strong> Please try again.
	        </div>";
	    }
	}
	
	// Leer el contenido del archivo
	if (file_exists($file_path)) {
	    $_inhoud = file_get_contents($file_path);
	} else {
	    $_inhoud = "";
	}

	// Inicializar variable con diseño profesional
	$_inhoud = "
	<style>
	    /* ========== ADMIN ABOUT PAGE STYLES ========== */
	    
	    .admin-about-container {
	        padding: 0;
	        max-width: 100%;
	    }
	    
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
	    
	    .admin-content-card {
	        background: linear-gradient(135deg, 
	            rgba(255, 255, 255, 0.08) 0%, 
	            rgba(255, 255, 255, 0.03) 100%);
	        backdrop-filter: blur(15px);
	        -webkit-backdrop-filter: blur(15px);
	        border: 1px solid rgba(255, 255, 255, 0.1);
	        border-radius: 20px;
	        padding: 2.5rem;
	        box-shadow: 
	            0 8px 32px rgba(0, 0, 0, 0.2),
	            inset 0 1px 0 rgba(255, 255, 255, 0.1);
	        transition: all 0.3s ease;
	    }
	    
	    .admin-content-card:hover {
	        transform: translateY(-4px);
	        box-shadow: 
	            0 12px 40px rgba(0, 0, 0, 0.3),
	            0 0 30px rgba(196, 30, 30, 0.15),
	            inset 0 1px 0 rgba(255, 255, 255, 0.15);
	        border-color: rgba(196, 30, 30, 0.3);
	    }
	    
	    /* CKEditor Styling */
	    .ck-editor {
	        background: rgba(0, 0, 0, 0.3) !important;
	        border-radius: 16px !important;
	        overflow: hidden;
	        border: 2px solid rgba(196, 30, 30, 0.2) !important;
	        transition: all 0.3s ease;
	    }
	    
	    .ck-editor:hover {
	        border-color: rgba(196, 30, 30, 0.4) !important;
	        box-shadow: 0 0 20px rgba(196, 30, 30, 0.2);
	    }
	    
	    .ck-editor__editable {
	        min-height: 500px !important;
	        background: rgba(0, 0, 0, 0.25) !important;
	        color: white !important;
	        border: none !important;
	        padding: 2rem !important;
	        font-size: 1rem;
	        line-height: 1.6;
	    }
	    
	    .ck-editor__editable:focus {
	        background: rgba(0, 0, 0, 0.3) !important;
	        box-shadow: inset 0 0 20px rgba(196, 30, 30, 0.1) !important;
	    }
	    
	    .ck-toolbar {
	        background: linear-gradient(135deg, 
	            rgba(0, 0, 0, 0.5) 0%, 
	            rgba(0, 0, 0, 0.3) 100%) !important;
	        border: none !important;
	        border-bottom: 1px solid rgba(196, 30, 30, 0.2) !important;
	        border-radius: 16px 16px 0 0 !important;
	        padding: 1rem !important;
	    }
	    
	    .ck-toolbar__items {
	        gap: 0.5rem;
	    }
	    
	    .ck-button {
	        background: rgba(255, 255, 255, 0.05) !important;
	        border: 1px solid rgba(255, 255, 255, 0.1) !important;
	        border-radius: 8px !important;
	        color: rgba(255, 255, 255, 0.8) !important;
	        transition: all 0.2s ease !important;
	    }
	    
	    .ck-button:hover {
	        background: rgba(196, 30, 30, 0.15) !important;
	        border-color: rgba(196, 30, 30, 0.4) !important;
	        color: white !important;
	    }
	    
	    .ck-button.ck-on {
	        background: rgba(196, 30, 30, 0.2) !important;
	        border-color: rgba(196, 30, 30, 0.5) !important;
	        color: #c41e1e !important;
	    }
	    
	    /* CKEditor Dropdowns & Panels */
	    .ck.ck-dropdown__panel {
	        background: linear-gradient(135deg, 
	            rgba(15, 23, 42, 0.98) 0%, 
	            rgba(30, 41, 59, 0.98) 100%) !important;
	        border: 2px solid rgba(196, 30, 30, 0.3) !important;
	        border-radius: 12px !important;
	        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5) !important;
	        backdrop-filter: blur(20px) !important;
	    }
	    
	    .ck.ck-list__item {
	        background: transparent !important;
	        color: rgba(255, 255, 255, 0.9) !important;
	        border-radius: 8px !important;
	        margin: 0.25rem 0.5rem !important;
	        padding: 0.5rem 1rem !important;
	    }
	    
	    .ck.ck-list__item:hover {
	        background: rgba(196, 30, 30, 0.15) !important;
	        color: white !important;
	    }
	    
	    .ck.ck-list__item.ck-on {
	        background: rgba(196, 30, 30, 0.25) !important;
	        color: #c41e1e !important;
	    }
	    
	    .ck.ck-heading_heading1,
	    .ck.ck-heading_heading2,
	    .ck.ck-heading_heading3,
	    .ck.ck-heading_heading4,
	    .ck.ck-heading_paragraph {
	        color: rgba(255, 255, 255, 0.9) !important;
	    }
	    
	    .ck.ck-heading_heading1:hover,
	    .ck.ck-heading_heading2:hover,
	    .ck.ck-heading_heading3:hover,
	    .ck.ck-heading_heading4:hover,
	    .ck.ck-heading_paragraph:hover {
	        color: white !important;
	    }
	    
	    /* Table insertion panel */
	    .ck.ck-insert-table-dropdown__grid {
	        background: rgba(0, 0, 0, 0.3) !important;
	        border-radius: 8px !important;
	        padding: 1rem !important;
	    }
	    
	    .ck.ck-insert-table-dropdown-grid-box {
	        border: 1px solid rgba(255, 255, 255, 0.2) !important;
	        background: rgba(255, 255, 255, 0.05) !important;
	    }
	    
	    .ck.ck-insert-table-dropdown-grid-box:hover {
	        background: rgba(196, 30, 30, 0.3) !important;
	        border-color: #c41e1e !important;
	    }
	    
	    .ck.ck-insert-table-dropdown-grid-box.ck-on {
	        background: rgba(196, 30, 30, 0.4) !important;
	        border-color: #c41e1e !important;
	    }
	    
	    /* Balloon panels (tooltips, link editor, etc) */
	    .ck.ck-balloon-panel {
	        background: linear-gradient(135deg, 
	            rgba(15, 23, 42, 0.98) 0%, 
	            rgba(30, 41, 59, 0.98) 100%) !important;
	        border: 2px solid rgba(196, 30, 30, 0.3) !important;
	        border-radius: 12px !important;
	        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5) !important;
	        backdrop-filter: blur(20px) !important;
	    }
	    
	    .ck.ck-balloon-panel .ck-button {
	        color: rgba(255, 255, 255, 0.9) !important;
	    }
	    
	    .ck.ck-input-text {
	        background: rgba(0, 0, 0, 0.3) !important;
	        border: 2px solid rgba(255, 255, 255, 0.2) !important;
	        color: white !important;
	        border-radius: 8px !important;
	    }
	    
	    .ck.ck-input-text:focus {
	        border-color: #c41e1e !important;
	        box-shadow: 0 0 0 3px rgba(196, 30, 30, 0.15) !important;
	    }
	    
	    /* Alert Messages */
	    .alert-success,
	    .alert-error {
	        padding: 1.25rem 1.5rem;
	        border-radius: 12px;
	        margin-bottom: 2rem;
	        font-weight: 600;
	        display: flex;
	        align-items: center;
	        backdrop-filter: blur(10px);
	        animation: slideInDown 0.4s ease-out;
	    }
	    
	    @keyframes slideInDown {
	        from {
	            opacity: 0;
	            transform: translateY(-20px);
	        }
	        to {
	            opacity: 1;
	            transform: translateY(0);
	        }
	    }
	    
	    .alert-success {
	        background: linear-gradient(135deg, 
	            rgba(16, 185, 129, 0.2) 0%, 
	            rgba(16, 185, 129, 0.1) 100%);
	        border: 2px solid rgba(16, 185, 129, 0.4);
	        color: #10b981;
	        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
	    }
	    
	    .alert-success i {
	        font-size: 1.5rem;
	        color: #10b981;
	    }
	    
	    .alert-error {
	        background: linear-gradient(135deg, 
	            rgba(239, 68, 68, 0.2) 0%, 
	            rgba(239, 68, 68, 0.1) 100%);
	        border: 2px solid rgba(239, 68, 68, 0.4);
	        color: #ef4444;
	        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
	    }
	    
	    .alert-error i {
	        font-size: 1.5rem;
	        color: #ef4444;
	    }
	    
	    /* Save Button */
	    .btn-save {
	        background: linear-gradient(135deg, #c41e1e 0%, #8b0000 100%);
	        color: #000;
	        border: 2px solid #c41e1e;
	        padding: 1rem 3rem;
	        font-size: 1.1rem;
	        font-weight: 700;
	        border-radius: 12px;
	        letter-spacing: 1px;
	        text-transform: uppercase;
	        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	        box-shadow: 0 4px 15px rgba(196, 30, 30, 0.3);
	        position: relative;
	        overflow: hidden;
	    }
	    
	    .btn-save::before {
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
	    
	    .btn-save:hover {
	        background: linear-gradient(135deg, #8b0000 0%, #c41e1e 100%);
	        transform: translateY(-3px);
	        box-shadow: 0 8px 25px rgba(196, 30, 30, 0.5);
	    }
	    
	    .btn-save:hover::before {
	        left: 100%;
	    }
	    
	    .btn-save:active {
	        transform: translateY(-1px);
	    }
	    
	    .btn-save i {
	        margin-right: 0.5rem;
	        font-size: 1.2rem;
	    }
	    
	    /* Action Bar */
	    .action-bar {
	        display: flex;
	        justify-content: center;
	        align-items: center;
	        gap: 1rem;
	        padding: 2rem 0 1rem;
	        border-top: 1px solid rgba(255, 255, 255, 0.1);
	        margin-top: 2rem;
	    }
	    
	    /* Responsive */
	    @media (max-width: 768px) {
	        .admin-page-header {
	            padding: 2rem 1rem;
	        }
	        
	        .admin-page-title {
	            font-size: 2rem;
	        }
	        
	        .admin-content-card {
	            padding: 1.5rem;
	            border-radius: 16px;
	        }
	        
	        .ck-editor__editable {
	            min-height: 400px !important;
	            padding: 1.5rem !important;
	        }
	        
	        .btn-save {
	            padding: 0.875rem 2rem;
	            font-size: 1rem;
	            width: 100%;
	        }
	    }
	</style>

	<div class='admin-about-container'>
	    <!-- Page Header -->
	    <div class='admin-page-header'>
	        <h1 class='admin-page-title'>
	            <i class='bi bi-person-lines-fill me-3'></i>
	            About Section
	        </h1>
	        <p class='admin-page-subtitle'>
	            Edit the band's story and information
	        </p>
	    </div>

	    <div class='container'>
	        <div class='row justify-content-center'>
	            <div class='col-lg-11 col-xl-10'>
	                
	                <!-- Success/Error Messages -->
	                $_mensaje
	                
	                <!-- Main Content Card -->
	                <div class='admin-content-card'>
	                    <form method='post'>
	                        <textarea name='editor' id='editor'>".htmlspecialchars($_inhoud)."</textarea>
	                        
	                        <!-- Action Bar -->
	                        <div class='action-bar'>
	                            <button type='submit' class='btn-save'>
	                                <i class='bi bi-check-circle-fill'></i>
	                                Save Changes
	                            </button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<script>
	    ClassicEditor
	        .create(document.querySelector('#editor'), {
	            toolbar: {
	                items: [
	                    'heading',
	                    '|',
	                    'fontSize',
	                    'fontColor',
	                    'fontBackgroundColor',
	                    '|',
	                    'bold',
	                    'italic',
	                    'underline',
	                    'strikethrough',
	                    '|',
	                    'alignment',
	                    '|',
	                    'link',
	                    'imageUpload',
	                    '|',
	                    'bulletedList',
	                    'numberedList',
	                    'outdent',
	                    'indent',
	                    '|',
	                    'blockQuote',
	                    'insertTable',
	                    'code',
	                    'codeBlock',
	                    '|',
	                    'horizontalLine',
	                    'specialCharacters',
	                    '|',
	                    'undo',
	                    'redo',
	                    '|',
	                    'sourceEditing'
	                ],
	                shouldNotGroupWhenFull: true
	            },
	            heading: {
	                options: [
	                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
	                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
	                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
	                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
	                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
	                ]
	            },
	            fontSize: {
	                options: [
	                    'tiny',
	                    'small',
	                    'default',
	                    'big',
	                    'huge'
	                ]
	            },
	            table: {
	                contentToolbar: [
	                    'tableColumn',
	                    'tableRow',
	                    'mergeTableCells',
	                    'tableProperties',
	                    'tableCellProperties'
	                ]
	            },
	            image: {
	                toolbar: [
	                    'imageTextAlternative',
	                    'imageStyle:full',
	                    'imageStyle:side',
	                    'linkImage'
	                ]
	            }
	        })
	        .then(editor => {
	            console.log('✅ CKEditor initialized successfully');
	            
	            // Auto-save draft every 30 seconds
	            setInterval(() => {
	                const content = editor.getData();
	                localStorage.setItem('about_draft', content);
	                console.log('💾 Draft auto-saved');
	            }, 30000);
	            
	            // Load draft on page load if exists
	            const draft = localStorage.getItem('about_draft');
	            if (draft && !editor.getData()) {
	                if (confirm('Found an auto-saved draft. Do you want to restore it?')) {
	                    editor.setData(draft);
	                }
	            }
	        })
	        .catch(error => {
	            console.error('❌ CKEditor initialization error:', error);
	        });
	</script>";


	require(__DIR__ . "/../code/output_admin.inc.php");
}
catch (Exception $e)
{
	include(__DIR__ . "/../php_lib/myExceptionHandling.inc.php"); 
	echo myExceptionHandling($e, __DIR__ . "/../logs/error_log.csv");
}
?>
