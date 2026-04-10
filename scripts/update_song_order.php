<?php
require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

// Log para debugging
error_log("Update song order request received: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['song_order'])) {
    
    $songOrder = json_decode($_POST['song_order'], true);
    
    if (!is_array($songOrder) || empty($songOrder)) {
        error_log("Invalid song order data received");
        echo json_encode(['status' => 'error', 'message' => 'invalid_order_data']);
        exit();
    }
    
    try {
        $_PDO->beginTransaction();
        
        foreach ($songOrder as $index => $songId) {
            // Validar que songId sea numérico
            if (!is_numeric($songId)) {
                throw new Exception("Invalid song ID: $songId");
            }
            
            $query = "UPDATE t_music SET song_order = :order WHERE id = :id";
            $stmt = $_PDO->prepare($query);
            $stmt->execute([
                ':order' => $index + 1,
                ':id' => $songId
            ]);
            
            if ($stmt->rowCount() === 0) {
                error_log("No rows affected for song ID: $songId");
                // No necesariamente es un error, podría ser que el orden ya era el mismo
            }
        }
        
        $_PDO->commit();
        error_log("Song order updated successfully for " . count($songOrder) . " songs");
        echo json_encode(['status' => 'success', 'message' => 'Song order updated successfully']);
        
    } catch (Exception $e) {
        $_PDO->rollBack();
        error_log("Order update error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'database_error', 
            'debug' => $e->getMessage()
        ]);
    }
    
} else {
    error_log("Invalid request method or missing song_order parameter");
    echo json_encode([
        'status' => 'error', 
        'message' => 'invalid_request',
        'debug' => 'Request method: ' . $_SERVER["REQUEST_METHOD"] . ', song_order set: ' . (isset($_POST['song_order']) ? 'yes' : 'no')
    ]);
}
exit();
?>