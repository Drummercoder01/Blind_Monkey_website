<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar campos requeridos
    if (empty($_POST['event_name']) || empty($_POST['event_location']) || 
        empty($_POST['event_date']) || empty($_POST['event_time'])) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit();
    }

    $event_name = trim($_POST['event_name']);
    $event_location = trim($_POST['event_location']);
    $event_date = trim($_POST['event_date']);
    $event_time = trim($_POST['event_time']);

    try {
        // Usar prepared statements
        $query = "INSERT INTO t_events (event_name, event_location, event_date, event_time) 
                 VALUES (:event_name, :event_location, :event_date, :event_time)";
        $stmt = $_PDO->prepare($query);
        
        $stmt->execute([
            ':event_name' => $event_name,
            ':event_location' => $event_location,
            ':event_date' => $event_date,
            ':event_time' => $event_time
        ]);

        // Obtener el ID del nuevo evento
        $newEventId = $_PDO->lastInsertId();

        $response = [
            'status' => 'success', 
            'message' => 'Event added successfully!',
            'event_id' => $newEventId
        ];

        echo json_encode($response);
        exit();

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'database_error']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid_request']);
    exit();
}
?>