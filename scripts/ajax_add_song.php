<?php
require("../code/initialisatie.inc.php");

// Establecer headers primero
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar que los campos no estén vacíos
    if (empty($_POST['song_name']) || empty($_POST['embed_code'])) {
        echo json_encode(['status' => 'error', 'message' => 'empty_fields']);
        exit();
    }

    $_song_name = trim($_POST['song_name']);
    $_embed_code = trim($_POST['embed_code']);

    try {
        // Obtener el próximo order number
        $orderQuery = "SELECT COALESCE(MAX(song_order), 0) + 1 as new_order FROM t_music";
        $orderResult = $_PDO->query($orderQuery);
        $newOrder = $orderResult->fetch(PDO::FETCH_ASSOC)['new_order'];

        // Usar prepared statements para evitar SQL injection
        $_query = "INSERT INTO t_music (song_name, embed, song_order) VALUES (:song_name, :embed_code, :song_order)";
        $_stmt = $_PDO->prepare($_query);

        $_stmt->execute([
            ':song_name' => $_song_name,
            ':embed_code' => $_embed_code,
            ':song_order' => $newOrder
        ]);

        // Obtener el ID de la nueva canción
        $newSongId = $_PDO->lastInsertId();

        // Crear respuesta JSON
        $response = [
            'status' => 'success', 
            'message' => 'Song added successfully!',
            'song_id' => $newSongId,
            'song_name' => $_song_name,
            'embed_code' => $_embed_code,
            'song_order' => $newOrder
        ];

        echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
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