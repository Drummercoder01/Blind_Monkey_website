<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

try {
    $query = "SELECT id, event_name, event_location, event_date, event_time 
              FROM t_events ORDER BY event_date DESC, event_time DESC";
    $result = $_PDO->query($query);
    
    $events = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $events[] = [
            'id' => $row['id'],
            'event_name' => $row['event_name'],
            'event_location' => $row['event_location'],
            'event_date' => $row['event_date'],
            'event_time' => $row['event_time']
        ];
    }
    
    echo json_encode(['status' => 'success', 'events' => $events]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'database_error']);
}
exit();
?>