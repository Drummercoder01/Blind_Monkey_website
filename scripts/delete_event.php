<?php
require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['event_id'])) {
    
    $eventId = $_POST['event_id'];
    
    try {
        $query = "DELETE FROM t_events WHERE id = :event_id";
        $stmt = $_PDO->prepare($query);
        $stmt->execute([':event_id' => $eventId]);
        
        if ($stmt->rowCount() > 0) {
            $response = ['status' => 'success', 'message' => 'Event deleted successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Event not found'];
        }
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $response = ['status' => 'error', 'message' => 'database_error'];
    }
    
} else {
    $response = ['status' => 'error', 'message' => 'invalid_request'];
}

echo json_encode($response);
exit();
?>