<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true
    || (int)($_SESSION['rol'] ?? 0) !== 1) {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$logs_dir = $_SERVER['DOCUMENT_ROOT'] . '/logs/';
$all_entries = [];
$sources     = [];

// ── Level config ─────────────────────────────────────────────────────
function detectLevel(string $filename): string {
    $f = strtolower($filename);
    if (str_contains($f, 'security')) return 'security';
    if (str_contains($f, 'error'))    return 'error';
    if (str_contains($f, 'mail'))     return 'mail';
    return 'info';
}

function shortenPath(string $path): string {
    // Keep only last 2 path segments
    $parts = explode('/', str_replace('\\', '/', $path));
    $parts = array_filter($parts);
    $parts = array_values($parts);
    $cnt = count($parts);
    if ($cnt >= 2) return $parts[$cnt - 2] . '/' . $parts[$cnt - 1];
    return basename($path);
}

// ── Parse CSV files ───────────────────────────────────────────────────
foreach (glob($logs_dir . '*.csv') ?: [] as $filepath) {
    $filename = basename($filepath, '.csv');
    $level    = detectLevel($filename);
    $rows     = [];

    $fh = @fopen($filepath, 'r');
    if (!$fh) continue;

    while (($fields = fgetcsv($fh)) !== false) {
        if (empty($fields[0])) continue;

        $date_str = trim($fields[0], '"');

        // Support both d-m-Y and Y-m-d formats
        $dt = DateTime::createFromFormat('d-m-Y H:i:s', $date_str)
           ?: DateTime::createFromFormat('Y-m-d H:i:s', $date_str);
        $unix = $dt ? $dt->getTimestamp() : 0;

        $entry = [
            'ts'      => $dt ? $dt->format('d M Y H:i:s') : $date_str,
            'ts_sort' => $unix,
            'source'  => $filename,
            'level'   => $level,
            'message' => '',
            'detail'  => '',
        ];

        if ($filename === 'error_log') {
            $entry['message'] = isset($fields[1]) ? trim($fields[1], '"') : '';
            $file_part        = isset($fields[2]) ? trim($fields[2], '"') : '';
            $line_part        = isset($fields[3]) ? trim($fields[3], '"') : '';
            if ($file_part) {
                $entry['detail'] = shortenPath($file_part)
                                 . ($line_part !== '' ? ' : ' . $line_part : '');
            }
        } elseif ($filename === 'securityLog') {
            $user             = isset($fields[1]) ? trim($fields[1], '"') : '';
            $entry['message'] = isset($fields[2]) ? trim($fields[2], '"') : '';
            $entry['detail']  = $user ? 'user: ' . $user : '';
        } else {
            $entry['message'] = implode(' | ', array_map(fn($f) => trim($f, '"'), array_slice($fields, 1)));
        }

        $rows[] = $entry;
    }
    fclose($fh);

    if (!empty($rows)) {
        $sources[]    = ['id' => $filename, 'label' => ucwords(str_replace('_', ' ', $filename)), 'count' => count($rows)];
        $all_entries  = array_merge($all_entries, $rows);
    }
}

// ── Parse plain .log files ────────────────────────────────────────────
foreach (glob($logs_dir . '*.log') ?: [] as $filepath) {
    $filename = basename($filepath, '.log');
    $level    = detectLevel($filename);
    $rows     = [];

    $lines = @file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) continue;

    foreach ($lines as $line) {
        $unix   = 0;
        $ts_str = '';

        // Try [YYYY-MM-DD HH:MM:SS] or [DD-MM-YYYY HH:MM:SS]
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $m)) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $m[1]);
            if ($dt) { $unix = $dt->getTimestamp(); $ts_str = $dt->format('d M Y H:i:s'); }
            $line = trim(preg_replace('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]\s*/', '', $line));
        } elseif (preg_match('/\[(\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2})\]/', $line, $m)) {
            $dt = DateTime::createFromFormat('d-m-Y H:i:s', $m[1]);
            if ($dt) { $unix = $dt->getTimestamp(); $ts_str = $dt->format('d M Y H:i:s'); }
            $line = trim(preg_replace('/\[\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}\]\s*/', '', $line));
        }

        $rows[] = [
            'ts'      => $ts_str ?: '—',
            'ts_sort' => $unix,
            'source'  => $filename,
            'level'   => $level,
            'message' => trim($line),
            'detail'  => '',
        ];
    }

    if (!empty($rows)) {
        $sources[]   = ['id' => $filename, 'label' => ucwords(str_replace('_', ' ', $filename)), 'count' => count($rows)];
        $all_entries = array_merge($all_entries, $rows);
    }
}

// ── Sort newest first ─────────────────────────────────────────────────
usort($all_entries, fn($a, $b) => $b['ts_sort'] <=> $a['ts_sort']);

// Limit to 1000 total entries
$all_entries = array_slice($all_entries, 0, 1000);

echo json_encode([
    'sources'      => $sources,
    'entries'      => $all_entries,
    'total'        => count($all_entries),
    'last_updated' => date('d M Y H:i:s'),
]);
