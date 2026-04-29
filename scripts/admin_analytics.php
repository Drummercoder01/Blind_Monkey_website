<?php
try {
    session_start();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true
        || (int)($_SESSION['rol'] ?? 0) !== 1) {
        header('Location: ../scripts/A_logon.php');
        exit;
    }

    $_jsInclude = [
        'https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js',
        '../js/analytics_admin.js',
    ];

    $_inhoud = "
    <style>
      /* ── Header ── */
      .an-header {
        background:linear-gradient(135deg,rgba(38,227,255,.1),rgba(38,227,255,.05));
        border-bottom:2px solid rgba(38,227,255,.2);
        padding:2rem 0;margin-bottom:2rem;text-align:center;
      }
      .an-header h1 { font-size:2rem;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:1px; }
      .an-header p  { color:rgba(38,227,255,.8);margin:0;font-size:.92rem; }

      /* ── Cards ── */
      .an-cards { display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem; }
      .an-card {
        background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
        border-radius:14px;padding:1.25rem 1.5rem;text-align:center;
        transition:border-color .2s;
      }
      .an-card:hover { border-color:rgba(38,227,255,.4); }
      .an-card-label { color:rgba(255,255,255,.45);font-size:.78rem;font-weight:600;
                       text-transform:uppercase;letter-spacing:.5px;margin-bottom:.4rem; }
      .an-card-value { font-size:2rem;font-weight:900;color:#26e3ff;line-height:1; }
      .an-card-sub   { font-size:.75rem;color:rgba(255,255,255,.3);margin-top:.3rem; }

      /* ── Section titles ── */
      .an-section-title {
        font-size:1rem;font-weight:700;color:#fff;text-transform:uppercase;
        letter-spacing:.5px;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;
      }
      .an-section-title i { color:#26e3ff; }

      /* ── Chart card ── */
      .an-chart-card {
        background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
        border-radius:16px;padding:1.5rem;margin-bottom:2rem;
      }

      /* ── Country table ── */
      .an-country-card {
        background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
        border-radius:16px;overflow:hidden;margin-bottom:2rem;
      }
      .an-country-row {
        display:flex;align-items:center;gap:.9rem;padding:.75rem 1.25rem;
        border-bottom:1px solid rgba(255,255,255,.05);transition:background .15s;
      }
      .an-country-row:last-child { border-bottom:none; }
      .an-country-row:hover { background:rgba(255,255,255,.04); }
      .an-rank  { width:22px;text-align:right;color:rgba(255,255,255,.3);font-size:.8rem;flex-shrink:0; }
      .an-flag  { width:24px;height:18px;border-radius:3px;object-fit:cover;flex-shrink:0;
                  box-shadow:0 1px 4px rgba(0,0,0,.4); }
      .an-cc    { width:28px;font-size:.72rem;font-weight:700;color:rgba(255,255,255,.5);flex-shrink:0; }
      .an-cname { flex:1;font-size:.85rem;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
      .an-bar-wrap { width:100px;height:6px;background:rgba(255,255,255,.08);border-radius:3px;flex-shrink:0; }
      .an-bar   { height:100%;border-radius:3px;background:linear-gradient(90deg,#26e3ff,#1bc4dd);transition:width .5s; }
      .an-visits{ width:42px;text-align:right;font-size:.82rem;font-weight:700;color:#26e3ff;flex-shrink:0; }

      /* ── Refresh bar ── */
      .an-refresh-bar {
        display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;
        gap:.75rem;padding:.75rem 1.25rem;
        background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);
        border-radius:10px;margin-bottom:2rem;font-size:.82rem;color:rgba(255,255,255,.4);
      }
      .an-refresh-bar strong { color:#26e3ff; }
      .an-btn-refresh {
        background:rgba(38,227,255,.1);color:#26e3ff;border:1px solid rgba(38,227,255,.3);
        border-radius:7px;padding:.3rem .8rem;font-size:.8rem;font-weight:600;cursor:pointer;
        transition:all .2s;
      }
      .an-btn-refresh:hover { filter:brightness(1.2); }
      .an-loading { opacity:.4;pointer-events:none; }
    </style>

    <div class='an-header'>
      <h1><i class='bi bi-bar-chart-line-fill me-2'></i>Visitor Analytics</h1>
      <p>Real visitor data tracked server-side — no cookies, no third-party scripts</p>
    </div>

    <div class='container'>

      <!-- Refresh status bar -->
      <div class='an-refresh-bar'>
        <span><i class='bi bi-clock me-1'></i>Last updated: <strong id='anLastUpdated'>—</strong></span>
        <span>Auto-refresh in: <strong id='anCountdown'>10:00</strong></span>
        <button class='an-btn-refresh' onclick='anLoad()'><i class='bi bi-arrow-clockwise me-1'></i>Refresh now</button>
      </div>

      <!-- Summary cards -->
      <div class='an-cards' id='anCards'>
        <div class='an-card'><div class='an-card-label'>Today</div>
          <div class='an-card-value' id='anToday'>—</div>
          <div class='an-card-sub' id='anTodaySub'></div></div>
        <div class='an-card'><div class='an-card-label'>Unique today</div>
          <div class='an-card-value' id='anUnique'>—</div>
          <div class='an-card-sub'>distinct IPs</div></div>
        <div class='an-card'><div class='an-card-label'>This month</div>
          <div class='an-card-value' id='anMonth'>—</div>
          <div class='an-card-sub' id='anMonthName'></div></div>
        <div class='an-card'><div class='an-card-label'>This year</div>
          <div class='an-card-value' id='anYear'>—</div>
          <div class='an-card-sub' id='anYearNum'></div></div>
        <div class='an-card'><div class='an-card-label'>All time</div>
          <div class='an-card-value' id='anTotal'>—</div>
          <div class='an-card-sub'>total visits</div></div>
      </div>

      <!-- Chart: last 30 days -->
      <div class='an-chart-card'>
        <div class='an-section-title'>
          <i class='bi bi-graph-up'></i>Visits — last 30 days
        </div>
        <canvas id='anChart' height='90'></canvas>
      </div>

      <!-- Country ranking -->
      <div class='an-section-title'>
        <i class='bi bi-globe2'></i>Visits by country
      </div>
      <div class='an-country-card' id='anCountries'>
        <div style='padding:2rem;text-align:center;color:rgba(255,255,255,.3);'>
          <div class='an-spinner'></div>
        </div>
      </div>

    </div>";

    require("../code/output_admin.inc.php");

} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
