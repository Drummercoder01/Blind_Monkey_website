<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true
    || (int)($_SESSION['rol'] ?? 0) !== 1) {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/connections/pdo.inc.php');

try {
    // ── Summary cards ────────────────────────────────────────────────────────
    $summary = [];

    $rows = $_PDO->query(
        "SELECT
           SUM(DATE(visited_at) = CURDATE())                                    AS today,
           SUM(DATE(visited_at) = CURDATE() - INTERVAL 1 DAY)                  AS yesterday,
           COUNT(DISTINCT CASE WHEN DATE(visited_at) = CURDATE()
                               THEN ip END)                                     AS today_unique,
           SUM(YEAR(visited_at)  = YEAR(NOW())
               AND MONTH(visited_at) = MONTH(NOW()))                            AS month,
           SUM(YEAR(visited_at)  = YEAR(NOW()))                                 AS year,
           COUNT(*)                                                              AS total
         FROM t_visits"
    )->fetch(PDO::FETCH_ASSOC);

    $summary = [
        'today'        => (int)$rows['today'],
        'yesterday'    => (int)$rows['yesterday'],
        'today_unique' => (int)$rows['today_unique'],
        'month'        => (int)$rows['month'],
        'year'         => (int)$rows['year'],
        'total'        => (int)$rows['total'],
    ];

    // ── Last 30 days chart data ──────────────────────────────────────────────
    $chart_rows = $_PDO->query(
        "SELECT DATE(visited_at) AS day, COUNT(*) AS visits
         FROM t_visits
         WHERE visited_at >= CURDATE() - INTERVAL 29 DAY
         GROUP BY DATE(visited_at)
         ORDER BY day ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    // Fill in missing days with 0
    $chart_map = [];
    foreach ($chart_rows as $r) $chart_map[$r['day']] = (int)$r['visits'];

    $labels = [];
    $visits = [];
    for ($i = 29; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-{$i} days"));
        $labels[] = date('d M', strtotime($d));
        $visits[] = $chart_map[$d] ?? 0;
    }

    // ── Country ranking (top 20) ─────────────────────────────────────────────
    $total_visits = max(1, $summary['total']);

    $countries = $_PDO->query(
        "SELECT country_code, country_name, COUNT(*) AS visits
         FROM t_visits
         WHERE country_code != 'XX'
         GROUP BY country_code, country_name
         ORDER BY visits DESC
         LIMIT 20"
    )->fetchAll(PDO::FETCH_ASSOC);

    $max_visits = !empty($countries) ? (int)$countries[0]['visits'] : 1;

    $country_list = array_map(function($r) use ($max_visits) {
        return [
            'code'    => strtolower($r['country_code']),
            'name'    => $r['country_name'],
            'visits'  => (int)$r['visits'],
            'pct_bar' => round((int)$r['visits'] / max(1, $max_visits) * 100),
        ];
    }, $countries);

    echo json_encode([
        'summary'      => $summary,
        'chart'        => ['labels' => $labels, 'visits' => $visits],
        'countries'    => $country_list,
        'last_updated' => date('d M Y H:i:s'),
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
