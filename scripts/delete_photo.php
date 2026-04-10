<?php
require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['photo_id'])) {
    
    $photoId = $_POST['photo_id'];
    
    try {
        // Primero obtener la información de la foto para eliminar el archivo si es local
        $selectQuery = "SELECT img_path FROM t_photos WHERE id = :id";
        $selectStmt = $_PDO->prepare($selectQuery);
        $selectStmt->execute([':id' => $photoId]);
        $photo = $selectStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($photo) {
            // Si es un archivo local (no URL), eliminarlo
            if (strpos($photo['img_path'], 'http') !== 0 && file_exists('../' . $photo['img_path'])) {
                unlink('../' . $photo['img_path']);
            }
            
            // Eliminar de la base de datos
            $deleteQuery = "DELETE FROM t_photos WHERE id = :id";
            $deleteStmt = $_PDO->prepare($deleteQuery);
            $deleteStmt->execute([':id' => $photoId]);
            
            if ($deleteStmt->rowCount() > 0) {
                $response = ['status' => 'success', 'message' => 'Photo deleted successfully'];
            } else {
                $response = ['status' => 'error', 'message' => 'Photo not found'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Photo not found'];
        }
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $response = ['status' => 'error', 'message' => 'database_error'];
    }
    
} else {
    $response = ['status' => 'error', 'message' => 'invalid_request'];
}

echo json_encode($response);
exit();
?>