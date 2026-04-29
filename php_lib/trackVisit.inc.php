<?php
/**
 * Track a public page visit.
 * Call once per page load from A_home.php (or any public script).
 * - Skips admin sessions, bots, and localhost.
 * - Caches IP→country in t_visitor_ips (30 days) to minimise API calls.
 * - Logs every visit to t_visits.
 */
function trackVisit(PDO $pdo): void
{
    // ── Skip admin sessions ──────────────────────────────────────────────────
    if (!empty($_SESSION['authenticated'])) return;

    // ── Skip common bots / crawlers ──────────────────────────────────────────
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (preg_match('/bot|crawl|spider|slurp|mediapartners|facebookexternalhit/i', $ua)) return;

    // ── Get real visitor IP ──────────────────────────────────────────────────
    $ip = '0.0.0.0';
    foreach (['HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','HTTP_X_REAL_IP','REMOTE_ADDR'] as $k) {
        if (!empty($_SERVER[$k])) {
            $candidate = trim(explode(',', $_SERVER[$k])[0]);
            if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                $ip = $candidate;
                break;
            }
        }
    }

    // ── Skip localhost ───────────────────────────────────────────────────────
    if (in_array($ip, ['127.0.0.1', '::1', '0.0.0.0'])) return;

    // ── Resolve country (cached) ─────────────────────────────────────────────
    $country_code = 'XX';
    $country_name = 'Unknown';

    try {
        $chk = $pdo->prepare(
            "SELECT country_code, country_name FROM t_visitor_ips
             WHERE ip = ? AND cached_at > DATE_SUB(NOW(), INTERVAL 30 DAY)"
        );
        $chk->execute([$ip]);
        $cached = $chk->fetch(PDO::FETCH_ASSOC);

        if ($cached) {
            $country_code = $cached['country_code'];
            $country_name = $cached['country_name'];
        } else {
            // Geo lookup via ip-api.com (free, no key needed)
            $geo = null;
            $url = "http://ip-api.com/json/{$ip}?fields=countryCode,country&lang=en";

            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 3,
                    CURLOPT_CONNECTTIMEOUT => 2,
                ]);
                $res = curl_exec($ch);
                curl_close($ch);
                if ($res) $geo = json_decode($res, true);
            } elseif (ini_get('allow_url_fopen')) {
                $ctx = stream_context_create(['http' => ['timeout' => 3]]);
                $res = @file_get_contents($url, false, $ctx);
                if ($res) $geo = json_decode($res, true);
            }

            if ($geo && !empty($geo['countryCode'])) {
                $country_code = strtoupper(substr($geo['countryCode'], 0, 2));
                $country_name = $geo['country'] ?? 'Unknown';
            }

            // Cache the result
            $ins = $pdo->prepare(
                "INSERT INTO t_visitor_ips (ip, country_code, country_name, cached_at)
                 VALUES (?, ?, ?, NOW())
                 ON DUPLICATE KEY UPDATE
                   country_code = VALUES(country_code),
                   country_name = VALUES(country_name),
                   cached_at    = NOW()"
            );
            $ins->execute([$ip, $country_code, $country_name]);
        }

        // ── Log the visit ────────────────────────────────────────────────────
        $log = $pdo->prepare(
            "INSERT INTO t_visits (ip, country_code, country_name) VALUES (?, ?, ?)"
        );
        $log->execute([$ip, $country_code, $country_name]);

    } catch (Exception $e) {
        // Never break the public page because of tracking errors
        error_log("trackVisit error: " . $e->getMessage());
    }
}
