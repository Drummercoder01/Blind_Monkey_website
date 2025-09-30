<?php
require("../code/initialisatie.inc.php");

header('Content-Type: application/json');

// Configuración de cache (5 minutos)
$cache_duration = 300;
$cache_key = 'songs_cache_' . md5('vw_active_songs');

function getSongsFromDB($_PDO) {
    try {
        $query = "SELECT id, song_name, embed, song_order FROM vw_active_songs";
        $stmt = $_PDO->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        // Fallback a consulta original
        error_log("View failed, using original query: " . $e->getMessage());
        $query = "SELECT id, song_name, embed, song_order FROM t_music ORDER BY song_order ASC";
        $stmt = $_PDO->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

try {
    // ✅ INTENTAR CACHE PRIMERO (si tienes Redis/Memcached)
    $cached_songs = null; // Aquí iría tu lógica de cache
    
    if ($cached_songs && is_array($cached_songs)) {
        error_log("Songs loaded from CACHE: " . count($cached_songs));
        
        echo json_encode([
            'status' => 'success',
            'songs' => $cached_songs,
            'count' => count($cached_songs),
            'source' => 'cached'
        ]);
    } else {
        // ✅ OBTENER DE LA BD CON VIEW
        $songs = getSongsFromDB($_PDO);
        
        // Filtrar canciones con embed válido (seguridad adicional)
        $valid_songs = array_filter($songs, function($song) {
            return !empty(trim($song['embed'])) && !empty(trim($song['song_name']));
        });
        
        // Re-indexar array
        $valid_songs = array_values($valid_songs);
        
        // Guardar en cache (implementar según tu sistema)
        // saveToCache($cache_key, $valid_songs, $cache_duration);
        
        error_log("Songs loaded from DATABASE: " . count($valid_songs));
        
        echo json_encode([
            'status' => 'success',
            'songs' => $valid_songs,
            'count' => count($valid_songs),
            'source' => 'database_view'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Songs API error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to load songs: ' . $e->getMessage()
    ]);
}
exit();
?>