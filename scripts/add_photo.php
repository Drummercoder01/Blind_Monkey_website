<?php
// Configuración de límites
@ini_set('upload_max_filesize', '20M');
@ini_set('post_max_size', '22M');
@ini_set('max_execution_time', '300');
@ini_set('max_input_time', '300');
@ini_set('memory_limit', '256M');

require("../code/initialisatie_admin.inc.php");

header('Content-Type: application/json');

// Obtener límites actuales
$currentUploadLimit = ini_get('upload_max_filesize');
$currentPostLimit = ini_get('post_max_size');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $img_path = '';
    $method = $_POST['photo_method'] ?? 'upload';

    try {
        if ($method === 'upload' && isset($_FILES['photo_file'])) {
            
            // Verificar errores de subida
            if ($_FILES['photo_file']['error'] !== UPLOAD_ERR_OK) {
                
                $uploadErrors = [
                    UPLOAD_ERR_INI_SIZE => "File exceeds server upload limit ($currentUploadLimit). Please contact administrator to increase limit to 20MB.",
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds form upload limit',
                    UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by PHP extension'
                ];
                
                $errorMessage = $uploadErrors[$_FILES['photo_file']['error']] ?? 'Unknown upload error';
                
                echo json_encode([
                    'status' => 'error', 
                    'message' => $errorMessage,
                    'current_limit' => $currentUploadLimit,
                    'required_limit' => '20M'
                ]);
                exit();
            }
            
            // Continuar con el proceso de subida...
            $uploadDir = '../img/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['photo_file']['name']);
            $targetPath = $uploadDir . $fileName;
            
            // Validaciones de archivo
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileExtension = strtolower(pathinfo($_FILES['photo_file']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file extension. Only JPG, PNG, GIF and WEBP are allowed.']);
                exit();
            }
            
            // Validar tipo MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $_FILES['photo_file']['tmp_name']);
            finfo_close($finfo);
            
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            
            if (!in_array($fileType, $allowedMimeTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, GIF and WEBP are allowed.']);
                exit();
            }
            
            // Validar tamaño (20MB max)
            $maxSize = 20 * 1024 * 1024;
            if ($_FILES['photo_file']['size'] > $maxSize) {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'File too large. Max size is 20MB.',
                    'file_size' => round($_FILES['photo_file']['size'] / (1024 * 1024), 2) . 'MB'
                ]);
                exit();
            }
            
            // Mover archivo
            if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $targetPath)) {
                $img_path = 'img/' . $fileName;
            } else {
                throw new Exception('Failed to move uploaded file');
            }
            
        } elseif ($method === 'link' && !empty($_POST['photo_link'])) {
            $img_path = trim($_POST['photo_link']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Please provide either a file or a valid URL']);
            exit();
        }
        
        // Insertar en base de datos
        $orderQuery = "SELECT COALESCE(MAX(img_order), 0) + 1 as new_order FROM t_photos";
        $orderResult = $_PDO->query($orderQuery);
        $newOrder = $orderResult->fetch(PDO::FETCH_ASSOC)['new_order'];
        
        $query = "INSERT INTO t_photos (img_path, img_order) VALUES (:img_path, :img_order)";
        $stmt = $_PDO->prepare($query);
        
        $stmt->execute([
            ':img_path' => $img_path,
            ':img_order' => $newOrder
        ]);

        $newPhotoId = $_PDO->lastInsertId();

        $response = [
            'status' => 'success', 
            'message' => 'Photo added successfully!',
            'photo_id' => $newPhotoId
        ];

        echo json_encode($response);
        exit();

    } catch (Exception $e) {
        error_log("Photo upload error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error processing photo: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid_request']);
    exit();
}
?>