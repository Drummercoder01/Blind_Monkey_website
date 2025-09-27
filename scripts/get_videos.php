<?php
require("../code/initialisatie.inc.php");

// Headers mejorados
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $query = "SELECT id, iframe, video_order FROM t_videos ORDER BY video_order ASC, id DESC";
    $result = $_PDO->query($query);
    
    $videos = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Validación básica del iframe
        $iframe = trim($row['iframe']);
        if (!empty($iframe) && strpos($iframe, '<iframe') !== false) {
            $videos[] = [
                'id' => (int)$row['id'],
                'iframe' => $iframe,
                'video_order' => (int)$row['video_order']
            ];
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'videos' => $videos,
        'total_count' => count($videos)
    ], JSON_UNESCAPED_SLASHES);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'database_error']);
}
exit();
?>