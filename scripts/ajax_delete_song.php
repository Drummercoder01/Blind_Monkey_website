<?php
require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['song_id'])) {
    try {
        $songId = $_POST['song_id'];
        
        $query = "DELETE FROM t_music WHERE id = :id";
        $stmt = $_PDO->prepare($query);
        $stmt->execute([':id' => $songId]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Song deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Song not found']);
        }
        
    } catch (Exception $e) {
        error_log("Delete song error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'database_error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid_request']);
}
exit();