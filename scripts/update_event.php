<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST['edit_id']) || empty($_POST['event_name']) || 
        empty($_POST['event_location']) || empty($_POST['event_date']) || empty($_POST['event_time'])) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit();
    }

    $id = $_POST['edit_id'];
    $event_name = trim($_POST['event_name']);
    $event_location = trim($_POST['event_location']);
    $event_date = trim($_POST['event_date']);
    $event_time = trim($_POST['event_time']);

    try {
        $query = "UPDATE t_events SET 
                 event_name = :event_name,
                 event_location = :event_location,
                 event_date = :event_date,
                 event_time = :event_time
                 WHERE id = :id";
                 
        $stmt = $_PDO->prepare($query);
        $stmt->execute([
            ':event_name' => $event_name,
            ':event_location' => $event_location,
            ':event_date' => $event_date,
            ':event_time' => $event_time,
            ':id' => $id
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Event updated successfully!']);
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