<?php
try {
    require(__DIR__ . "/../code/initialisatie.inc.php");
    
    global $_PDO;
    
    // Variable para mensajes
    $_mensaje = "";
    
    // Get active subscribers count
    $stats = $_PDO->query("SELECT COUNT(*) as total FROM t_newsletter WHERE status = 'active'")->fetch(PDO::FETCH_ASSOC);
    $totalRecipients = $stats['total'];
    
    // Handle send
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_newsletter'])) {
        $subject = trim($_POST['subject']);
        $htmlContent = $_POST['html_content'];
        $fromName = trim($_POST['from_name']);
        $fromEmail = trim($_POST['from_email']);
        
        if (empty($subject) || empty($htmlContent) || empty($fromEmail)) {
            $_mensaje = "<div class='alert-error'>
                <i class='bi bi-x-circle-fill me-2'></i>
                <strong>Please fill all required fields</strong>
            </div>";
        } else {
            // Get all active subscribers
            $subscribers = $_PDO->query("SELECT email FROM t_newsletter WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($subscribers) > 0) {
                $sentCount = 0;
                $failedCount = 0;
                
                // Headers
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                $headers .= "From: $fromName <$fromEmail>\r\n";
                $headers .= "Reply-To: $fromEmail\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
                
                foreach ($subscribers as $sub) {
                    // Personalize content (replace {email} with actual email)
                    $personalizedContent = str_replace('{email}', $sub['email'], $htmlContent);
                    
                    if (mail($sub['email'], $subject, $personalizedContent, $headers)) {
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                    
                    // Small delay to avoid spam detection
                    usleep(100000); // 0.1 second
                }
                
                // Log the send
                $logStmt = $_PDO->prepare("INSERT INTO t_newsletter_log (subject, recipients, sent_at) VALUES (?, ?, NOW())");
                $logStmt->execute([$subject, $sentCount]);
                
                $message = "Newsletter sent successfully to $sentCount recipient(s)";
                if ($failedCount > 0) {
                    $message .= " ($failedCount failed)";
                }
                $_mensaje = "<div class='alert-success'>
                    <i class='bi bi-check-circle-fill me-2'></i>
                    <strong>$message</strong>
                </div>";
            } else {
                $_mensaje = "<div class='alert-error'>
                    <i class='bi bi-x-circle-fill me-2'></i>
                    <strong>No active subscribers to send to</strong>
                </div>";
            }
        }
    }
    
    // Build content
    $_inhoud = "
    <div class='admin-newsletter-send-page'>
        <!-- Header -->
        <div class='admin-page-header'>
            <div class='container-fluid'>
                <div class='row align-items-center'>
                    <div class='col'>
                        <a href='admin_newsletter.php' class='back-link mb-2 d-inline-block'>
                            <i class='bi bi-arrow-left me-2'></i>Back to Subscribers
                        </a>
                        <h1 class='page-title mb-2'>
                            <i class='bi bi-send-fill me-3'></i>
                            Send Newsletter
                        </h1>
                        <p class='page-subtitle text-secondary mb-0'>
                            Compose and send newsletter to $totalRecipients active subscriber(s)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class='container-fluid'>
            " . $_mensaje . "

            <form method='POST' class='newsletter-form' id='newsletterForm'>
                <input type='hidden' name='send_newsletter' value='1'>
                
                <!-- Email Settings -->
                <div class='form-section'>
                    <h2 class='section-title'>
                        <i class='bi bi-gear'></i>
                        Email Settings
                    </h2>
                    
                    <div class='form-grid'>
                        <div class='form-group'>
                            <label class='form-label'>
                                From Name <span class='required'>*</span>
                            </label>
                            <input type='text' name='from_name' class='form-input' value='Blind Monkey' required>
                        </div>
                        
                        <div class='form-group'>
                            <label class='form-label'>
                                From Email <span class='required'>*</span>
                            </label>
                            <input type='email' name='from_email' class='form-input' value='info@BlindMonkey.be' required>
                        </div>
                    </div>
                    
                    <div class='form-group'>
                        <label class='form-label'>
                            Subject Line <span class='required'>*</span>
                        </label>
                        <input type='text' name='subject' class='form-input' placeholder='e.g., New Tour Dates Announced!' required>
                    </div>
                </div>

                <!-- Newsletter Content -->
                <div class='form-section'>
                    <h2 class='section-title'>
                        <i class='bi bi-file-text'></i>
                        Newsletter Content
                    </h2>
                    
                    <div class='editor-toolbar'>
                        <button type='button' onclick='insertTemplate(\"welcome\")' class='btn btn-secondary btn-sm'>
                            <i class='bi bi-file-earmark-text'></i> Welcome Template
                        </button>
                        <button type='button' onclick='insertTemplate(\"event\")' class='btn btn-secondary btn-sm'>
                            <i class='bi bi-calendar'></i> Event Template
                        </button>
                        <button type='button' onclick='insertTemplate(\"music\")' class='btn btn-secondary btn-sm'>
                            <i class='bi bi-music-note'></i> New Music Template
                        </button>
                    </div>
                    
                    <div class='form-group'>
                        <label class='form-label'>
                            HTML Content <span class='required'>*</span>
                            <span class='form-hint'>Use {email} to personalize with subscriber's email</span>
                        </label>
                        <textarea name='html_content' id='htmlEditor' class='form-textarea' rows='20' required></textarea>
                    </div>
                </div>

                <!-- Preview -->
                <div class='form-section'>
                    <h2 class='section-title'>
                        <i class='bi bi-eye'></i>
                        Preview
                    </h2>
                    <button type='button' onclick='previewNewsletter()' class='btn btn-secondary'>
                        <i class='bi bi-eye-fill'></i> Preview Newsletter
                    </button>
                    <div id='previewContainer' class='preview-container' style='display:none;'>
                        <iframe id='previewFrame' class='preview-frame'></iframe>
                    </div>
                </div>

                <!-- Send Actions -->
                <div class='form-actions'>
                    <button type='button' onclick='sendTestEmail()' class='btn btn-secondary'>
                        <i class='bi bi-envelope'></i>
                        Send Test Email
                    </button>
                    <button type='submit' class='btn btn-primary' onclick='return confirmSend()'>
                        <i class='bi bi-send-fill'></i>
                        Send to $totalRecipients Subscriber(s)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Test Email Modal -->
    <div id='testEmailModal' class='modal'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h3>Send Test Email</h3>
                <button onclick='closeTestModal()' class='btn-close'>&times;</button>
            </div>
            <div class='modal-body'>
                <input type='email' id='testEmail' class='form-input' placeholder='Enter test email address'>
            </div>
            <div class='modal-footer'>
                <button onclick='closeTestModal()' class='btn btn-secondary'>Cancel</button>
                <button onclick='sendTest()' class='btn btn-primary'>Send Test</button>
            </div>
        </div>
    </div>

    <script>
    // Newsletter Templates
    const templates = {
        welcome: `
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #000 0%, #333 100%); color: white; padding: 40px 20px; text-align: center; }
            .content { padding: 30px 20px; }
            .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { display: inline-block; background: #000; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to Blind Monkey Family! 🎸</h1>
            </div>
            <div class='content'>
                <p>Hey there!</p>
                <p>Thanks for subscribing to our newsletter. You're now part of Blind Monkey family, and we couldn't be more excited to have you with us!</p>
                <p>Here's what you can expect:</p>
                <ul>
                    <li>🎵 Exclusive music releases and previews</li>
                    <li>📅 Early access to tour dates and tickets</li>
                    <li>🎁 Special offers and behind-the-scenes content</li>
                </ul>
                <p style='text-align: center;'>
                    <a href='https://BlindMonkey.be' class='button'>Visit Our Website</a>
                </p>
            </div>
            <div class='footer'>
                <p>You're receiving this because you subscribed at {email}</p>
                <p>© 2025 Blind Monkey | <a href='#'>Unsubscribe</a></p>
            </div>
        </div>
    </body>
    </html>`,
        
        event: `
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #000 0%, #333 100%); color: white; padding: 40px 20px; text-align: center; }
            .content { padding: 30px 20px; }
            .event-box { background: #f9f9f9; border-left: 4px solid #000; padding: 15px; margin: 15px 0; }
            .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { display: inline-block; background: #000; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>📅 New Tour Dates!</h1>
            </div>
            <div class='content'>
                <p>We're hitting the road and we want YOU there! 🎸</p>
                
                <div class='event-box'>
                    <h3>📍 Brussels, Belgium</h3>
                    <p><strong>Date:</strong> March 15, 2025<br>
                    <strong>Venue:</strong> AB - Ancienne Belgique<br>
                    <strong>Time:</strong> 20:00</p>
                    <a href='#' class='button'>Get Tickets</a>
                </div>
                
                <div class='event-box'>
                    <h3>📍 Amsterdam, Netherlands</h3>
                    <p><strong>Date:</strong> March 20, 2025<br>
                    <strong>Venue:</strong> Paradiso<br>
                    <strong>Time:</strong> 21:00</p>
                    <a href='#' class='button'>Get Tickets</a>
                </div>
            </div>
            <div class='footer'>
                <p>© 2025 Blind Monkey | <a href='#'>Unsubscribe</a></p>
            </div>
        </div>
    </body>
    </html>`,
        
        music: `
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #000 0%, #333 100%); color: white; padding: 40px 20px; text-align: center; }
            .content { padding: 30px 20px; }
            .album-cover { width: 100%; max-width: 400px; display: block; margin: 20px auto; border-radius: 10px; }
            .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .button { display: inline-block; background: #000; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎵 New Music Alert!</h1>
            </div>
            <div class='content'>
                <p>We're thrilled to announce our latest release is now live!</p>
                <img src='#' alt='Album Cover' class='album-cover'>
                <h2 style='text-align: center;'>Track Name</h2>
                <p style='text-align: center;'>
                    <a href='https://open.spotify.com/artist/2KrYW1HYywqXzW12p4lsc1' class='button'>
                        <i>▶</i> Listen on Spotify
                    </a>
                    <a href='https://music.apple.com' class='button'>
                        <i>🎵</i> Apple Music
                    </a>
                </p>
            </div>
            <div class='footer'>
                <p>© 2025 Blind Monkey | <a href='#'>Unsubscribe</a></p>
            </div>
        </div>
    </body>
    </html>`
    };

    function insertTemplate(type) {
        document.getElementById('htmlEditor').value = templates[type];
    }

    function previewNewsletter() {
        const html = document.getElementById('htmlEditor').value;
        const preview = document.getElementById('previewContainer');
        const frame = document.getElementById('previewFrame');
        
        preview.style.display = 'block';
        frame.srcdoc = html;
    }

    function sendTestEmail() {
        document.getElementById('testEmailModal').style.display = 'flex';
    }

    function closeTestModal() {
        document.getElementById('testEmailModal').style.display = 'none';
    }

    function sendTest() {
        const email = document.getElementById('testEmail').value;
        if (!email) {
            alert('Please enter an email address');
            return;
        }
        alert('Test email would be sent to: ' + email + '\\n\\n(Feature coming soon)');
        closeTestModal();
    }

    function confirmSend() {
        const count = <?php echo $totalRecipients; ?>;
        return confirm(\`Are you sure you want to send this newsletter to \${count} subscriber(s)?\\n\\nThis action cannot be undone.\`);
    }
    </script>
    ";
    
    // Output
    $_menu = 0;
    $_jsInclude = array("../js/newsletter_handler.js");
    require_once(__DIR__ . "/../code/output_admin.inc.php");
    
} catch (Exception $_e) {
    include(__DIR__ . "/../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($_e, "../logs/error_log.csv");
}
?>
