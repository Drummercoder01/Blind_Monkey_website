<?php
require("../code/initialisatie.inc.php");

// Headers mejorados
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Configuración de cache (5 minutos)
$cache_duration = 300;
$cache_key = 'videos_cache_' . md5('vw_active_videos');

function getVideosFromDB($_PDO) {
    try {
        $query = "SELECT id, iframe, video_order FROM vw_active_videos";
        $result = $_PDO->query($query);
        
        $videos = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $iframe = trim($row['iframe']);
            if (!empty($iframe) && strpos($iframe, '<iframe') !== false) {
                $videos[] = [
                    'id' => (int)$row['id'],
                    'iframe' => $iframe,
                    'video_order' => (int)$row['video_order']
                ];
            }
        }
        return $videos;
        
    } catch (PDOException $e) {
        // Fallback a consulta original
        $query = "SELECT id, iframe, video_order FROM t_videos ORDER BY video_order ASC, id DESC";
        $result = $_PDO->query($query);
        
        $videos = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $iframe = trim($row['iframe']);
            if (!empty($iframe) && strpos($iframe, '<iframe') !== false) {
                $videos[] = [
                    'id' => (int)$row['id'],
                    'iframe' => $iframe,
                    'video_order' => (int)$row['video_order']
                ];
            }
        }
        return $videos;
    }
}

try {
    // ✅ INTENTAR CACHE PRIMERO (si tienes Redis/Memcached)
    $cached_videos = null; // Aquí iría tu lógica de cache
    
    if ($cached_videos) {
        echo json_encode([
            'status' => 'success', 
            'videos' => $cached_videos,
            'total_count' => count($cached_videos),
            'source' => 'cached'
        ], JSON_UNESCAPED_SLASHES);
    } else {
        // ✅ OBTENER DE LA BD CON VIEW
        $videos = getVideosFromDB($_PDO);
        
        // Guardar en cache (implementar según tu sistema)
        // saveToCache($cache_key, $videos, $cache_duration);
        
        echo json_encode([
            'status' => 'success', 
            'videos' => $videos,
            'total_count' => count($videos),
            'source' => 'database_view'
        ], JSON_UNESCAPED_SLASHES);
    }
    
} catch (Exception $e) {
    error_log("Videos API error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'server_error']);
}
exit();
?>