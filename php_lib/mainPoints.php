<?php
/**
 * Extrae puntos principales manejando correctamente HTML entities
 */
function extractMainPoints($filename, $maxPoints = 5) {
    $content = inlezen($filename);
    
    // Extraer elementos <li> y decodificar HTML entities
    preg_match_all('/<li>(.*?)<\/li>/', $content, $matches);
    
    $points = [];
    foreach ($matches[1] as $match) {
        $point = trim(html_entity_decode(strip_tags($match), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if (!empty($point)) {
            $points[] = $point;
        }
        
        if (count($points) >= $maxPoints) {
            break;
        }
    }
    
    return $points;
}
?>