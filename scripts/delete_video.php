<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['video_id'])) {
    
    $videoId = (int)$_POST['video_id'];
    
    try {
        $query = "DELETE FROM t_videos WHERE id = :id";
        $stmt = $_PDO->prepare($query);
        $stmt->execute([':id' => $videoId]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Video deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Video not found']);
        }
        
    } catch (Exception $e) {
        error_log("Video delete error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error deleting video']);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
exit();
?>