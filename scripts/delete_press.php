<?php
require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['press_id'])) {
    
    $pressId = $_POST['press_id'];
    
    try {
        $query = "DELETE FROM t_press WHERE id = :press_id";
        $stmt = $_PDO->prepare($query);
        $stmt->execute([':press_id' => $pressId]);
        
        if ($stmt->rowCount() > 0) {
            $response = ['status' => 'success', 'message' => 'Press item deleted successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Press item not found'];
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