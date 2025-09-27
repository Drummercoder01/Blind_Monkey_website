<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $embed_code = trim($_POST['embed_code'] ?? '');
    
    if (empty($embed_code)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide embed code']);
        exit();
    }
    
    // Validar que sea un iframe válido
    if (strpos($embed_code, '<iframe') === false || strpos($embed_code, 'youtube.com') === false) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid YouTube embed code']);
        exit();
    }
    
    try {
        // Obtener el siguiente número de orden
        $orderQuery = "SELECT COALESCE(MAX(video_order), 0) + 1 as new_order FROM t_videos";
        $orderResult = $_PDO->query($orderQuery);
        $newOrder = $orderResult->fetch(PDO::FETCH_ASSOC)['new_order'];
        
        // Insertar el video con el nuevo orden
        $query = "INSERT INTO t_videos (iframe, video_order) VALUES (:iframe, :video_order)";
        $stmt = $_PDO->prepare($query);
        
        $stmt->execute([
            ':iframe' => $embed_code,
            ':video_order' => $newOrder
        ]);

        $newVideoId = $_PDO->lastInsertId();

        echo json_encode([
            'status' => 'success', 
            'message' => 'Video added successfully!',
            'video_id' => $newVideoId
        ]);
        
    } catch (Exception $e) {
        error_log("Video add error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error adding video: ' . $e->getMessage()]);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
exit();
?>