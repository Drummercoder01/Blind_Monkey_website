<?php
require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['photo_order'])) {
    
    $photoOrder = json_decode($_POST['photo_order'], true);
    
    try {
        $_PDO->beginTransaction();
        
        foreach ($photoOrder as $index => $photoId) {
            $order = $index + 1;
            $query = "UPDATE t_photos SET img_order = :img_order WHERE id = :id";
            $stmt = $_PDO->prepare($query);
            $stmt->execute([
                ':img_order' => $order,
                ':id' => $photoId
            ]);
        }
        
        $_PDO->commit();
        
        echo json_encode(['status' => 'success', 'message' => 'Photo order updated successfully!']);
        
    } catch (Exception $e) {
        $_PDO->rollBack();
        error_log("Order update error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error updating order']);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid_request']);
}
exit();
?>