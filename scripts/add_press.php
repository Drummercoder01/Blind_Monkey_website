<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST['press_text']) || empty($_POST['press_author']) || empty($_POST['press_time'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit();
    }

    $press_text = trim($_POST['press_text']);
    $press_author = trim($_POST['press_author']);
    $press_comment = isset($_POST['press_comment']) ? trim($_POST['press_comment']) : '';
    $press_time = trim($_POST['press_time']);
    $press_link = isset($_POST['press_link']) ? trim($_POST['press_link']) : '';

    try {
        $query = "INSERT INTO t_press (`press-text`, `press-author`, `press-comment`, `press-time`, `press-link`) 
                 VALUES (:press_text, :press_author, :press_comment, :press_time, :press_link)";
        $stmt = $_PDO->prepare($query);
        $stmt->execute([
            ':press_text' => $press_text,
            ':press_author' => $press_author,
            ':press_comment' => $press_comment,
            ':press_time' => $press_time,
            ':press_link' => $press_link
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Press item added successfully!']);
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