<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['video_order'])) {
    
    $videoOrder = json_decode($_POST['video_order'], true);
    
    try {
        $_PDO->beginTransaction();
        
        foreach ($videoOrder as $index => $videoId) {
            $order = $index + 1;
            $query = "UPDATE t_videos SET video_order = :video_order WHERE id = :id";
            $stmt = $_PDO->prepare($query);
            $stmt->execute([
                ':video_order' => $order,
                ':id' => $videoId
            ]);
        }
        
        $_PDO->commit();
        
        echo json_encode(['status' => 'success', 'message' => 'Video order updated successfully!']);
        
    } catch (Exception $e) {
        $_PDO->rollBack();
        error_log("Video order update error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error updating video order']);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid_request']);
}
exit();
?>