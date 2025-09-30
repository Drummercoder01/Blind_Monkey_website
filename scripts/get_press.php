<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

try {
    $query = "SELECT id, `press-text`, `press-author`, `press-comment`, `press-time`, `press-link` 
              FROM vw_active_press";
    $result = $_PDO->query($query);
    
    $pressItems = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $pressItems[] = [
            'id' => $row['id'],
            'press_text' => $row['press-text'],
            'press_author' => $row['press-author'],
            'press_comment' => $row['press-comment'],
            'press_time' => $row['press-time'],
            'press_link' => $row['press-link']
        ];
    }
    
    echo json_encode([
        'status' => 'success', 
        'press_items' => $pressItems,
        'count' => count($pressItems)
    ]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'database_error']);
}
exit();
?>