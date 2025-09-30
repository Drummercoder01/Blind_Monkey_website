<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

try {
    $query = "SELECT id, img_path, img_order FROM vw_active_photos ORDER BY img_order ASC, id DESC";
    $result = $_PDO->query($query);
    
    $photos = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $photos[] = [
            'id' => $row['id'],
            'img_path' => $row['img_path'],
            'img_order' => $row['img_order']
        ];
    }
    
    echo json_encode(['status' => 'success', 'photos' => $photos]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'database_error']);
}
exit();
?>