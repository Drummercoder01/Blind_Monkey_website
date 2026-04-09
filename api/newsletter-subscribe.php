<?php
/**
 * Newsletter Subscription API
 * Uses $_PDO (not $_db) as defined in initialisatie.inc.php
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Load database configuration
require_once(__DIR__ . '/../code/initialisatie.inc.php');

// Verify $_PDO exists (global variable from initialisatie.inc.php)
global $_PDO;

if (!isset($_PDO) || !$_PDO) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get and validate email
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$email = isset($data['email']) ? trim($data['email']) : '';

if (empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

try {
    // Check if email already exists
    $checkStmt = $_PDO->prepare("SELECT id FROM t_newsletter WHERE email = ? LIMIT 1");
    $checkStmt->execute([$email]);
    
    if ($checkStmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode([
            'success' => false,
            'message' => 'This email is already subscribed to our newsletter'
        ]);
        exit;
    }
    
    // Insert new subscriber
    $insertStmt = $_PDO->prepare("INSERT INTO t_newsletter (email, subscribed_at, status) VALUES (?, NOW(), 'active')");
    $insertStmt->execute([$email]);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Welcome to the Blind Monkey tribe! 🐒🎸',
        'email' => $email
    ]);
    
    // Log successful subscription
    error_log("Newsletter subscription: $email");
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again later.'
    ]);
    error_log("Newsletter error: " . $e->getMessage());
}
?>
