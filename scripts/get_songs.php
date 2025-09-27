<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

try {
    $query = "SELECT id, song_name, embed, song_order FROM t_music ORDER BY song_order ASC";
    $stmt = $_PDO->prepare($query);
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log para debugging
    error_log("Songs loaded: " . count($songs));
    
    echo json_encode([
        'status' => 'success',
        'songs' => $songs,
        'count' => count($songs) // Agregar count para debugging
    ]);
    
} catch (Exception $e) {
    error_log("Error loading songs: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to load songs: ' . $e->getMessage()
    ]);
}
exit();