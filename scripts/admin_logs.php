<?php
try {
    session_start();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true
        || (int)($_SESSION['rol'] ?? 0) !== 1) {
        header('Location: ../scripts/A_logon.php');
        exit;
    }

    $_jsInclude = ['../js/logs_admin.js'];

    $_inhoud = '
    <style>
      /* ── Header ── */
      .lg-header {
        background: linear-gradient(135deg, rgba(245,158,11,.1), rgba(245,158,11,.04));
        border-bottom: 2px solid rgba(245,158,11,.2);
        padding: 2rem 0; margin-bottom: 2rem; text-align: center;
      }
      .lg-header h1 { font-size: 2rem; font-weight: 900; color: #fff;
                      text-transform: uppercase; letter-spacing: 1px; }
      .lg-header p  { color: rgba(245,158,11,.8); margin: 0; font-size: .92rem; }

      /* ── Toolbar ── */
      .lg-toolbar {
        display: flex; align-items: center; flex-wrap: wrap;
        gap: .75rem; margin-bottom: 1.5rem;
      }
      .lg-filters { display: flex; flex-wrap: wrap; gap: .4rem; flex: 1; min-width: 0; }
      .lg-filter-btn {
        background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
        color: rgba(255,255,255,.55); border-radius: 20px;
        padding: .3rem .85rem; font-size: .78rem; font-weight: 600;
        cursor: pointer; transition: all .2s; white-space: nowrap;
      }
      .lg-filter-btn:hover   { border-color: rgba(245,158,11,.4); color: #fff; }
      .lg-filter-btn.active  {
        background: rgba(245,158,11,.15); border-color: rgba(245,158,11,.5);
        color: #f59e0b;
      }
      .lg-filter-btn .lg-badge {
        background: rgba(255,255,255,.1); border-radius: 10px;
        padding: 0 5px; font-size: .7rem; margin-left: 4px;
      }
      .lg-filter-btn.active .lg-badge { background: rgba(245,158,11,.25); }

      /* ── Search ── */
      .lg-search-wrap { position: relative; }
      .lg-search {
        background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
        color: #fff; border-radius: 8px; padding: .35rem .85rem .35rem 2rem;
        font-size: .83rem; width: 200px; transition: border-color .2s;
      }
      .lg-search:focus { outline: none; border-color: rgba(245,158,11,.4); }
      .lg-search::placeholder { color: rgba(255,255,255,.3); }
      .lg-search-icon {
        position: absolute; left: .65rem; top: 50%; transform: translateY(-50%);
        color: rgba(255,255,255,.3); font-size: .8rem; pointer-events: none;
      }
      .lg-btn-refresh {
        background: rgba(245,158,11,.1); color: #f59e0b;
        border: 1px solid rgba(245,158,11,.3); border-radius: 7px;
        padding: .3rem .85rem; font-size: .8rem; font-weight: 600;
        cursor: pointer; transition: all .2s; white-space: nowrap;
      }
      .lg-btn-refresh:hover { filter: brightness(1.2); }

      /* ── Status bar ── */
      .lg-status {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: .5rem;
        font-size: .78rem; color: rgba(255,255,255,.35);
        margin-bottom: 1rem;
      }
      .lg-status strong { color: #f59e0b; }

      /* ── Entry list ── */
      .lg-list {
        display: flex; flex-direction: column; gap: .5rem;
      }

      .lg-entry {
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
        border-radius: 12px; padding: .85rem 1.1rem;
        display: grid; grid-template-columns: auto 1fr auto;
        align-items: start; gap: .75rem;
        transition: background .15s, border-color .15s;
      }
      .lg-entry:hover { background: rgba(255,255,255,.07); border-color: rgba(255,255,255,.13); }

      /* Level badge */
      .lg-lvl {
        display: inline-flex; align-items: center; justify-content: center;
        width: 78px; padding: .2rem 0; border-radius: 5px;
        font-size: .68rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: .5px; flex-shrink: 0; margin-top: 1px;
      }
      .lg-lvl-error    { background: rgba(239,68,68,.15);   color: #ef4444; border: 1px solid rgba(239,68,68,.25); }
      .lg-lvl-security { background: rgba(245,158,11,.15);  color: #f59e0b; border: 1px solid rgba(245,158,11,.25); }
      .lg-lvl-mail     { background: rgba(38,227,255,.12);  color: #26e3ff; border: 1px solid rgba(38,227,255,.2); }
      .lg-lvl-info     { background: rgba(148,163,184,.1);  color: #94a3b8; border: 1px solid rgba(148,163,184,.15); }

      /* Body */
      .lg-body { min-width: 0; }
      .lg-msg  { font-size: .875rem; color: #e2e8f0; word-break: break-word;
                 line-height: 1.45; margin-bottom: .15rem; }
      .lg-detail {
        font-size: .72rem; color: rgba(255,255,255,.35);
        font-family: monospace; word-break: break-all;
      }
      .lg-src-tag {
        font-size: .65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .4px; color: rgba(255,255,255,.25);
        margin-left: .5rem; border: 1px solid rgba(255,255,255,.1);
        border-radius: 4px; padding: 0 4px;
      }

      /* Timestamp */
      .lg-ts {
        font-size: .72rem; color: rgba(255,255,255,.35); white-space: nowrap;
        text-align: right; flex-shrink: 0; min-width: 90px;
        line-height: 1.6;
      }
      .lg-ts-date { display: block; }
      .lg-ts-time { display: block; color: rgba(255,255,255,.55); font-weight: 600; }

      /* ── Empty / loading states ── */
      .lg-empty {
        padding: 3rem; text-align: center; color: rgba(255,255,255,.25);
        font-size: .9rem;
      }
      .lg-show-more {
        text-align: center; margin-top: 1rem;
      }
      .lg-show-more button {
        background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
        color: rgba(255,255,255,.5); border-radius: 8px; padding: .4rem 1.2rem;
        font-size: .8rem; cursor: pointer; transition: all .2s;
      }
      .lg-show-more button:hover { border-color: rgba(245,158,11,.4); color: #f59e0b; }

      /* ── Highlight search matches ── */
      .lg-hl { background: rgba(245,158,11,.3); border-radius: 2px; padding: 0 1px; }

      /* ── Responsive ── */
      @media (max-width: 600px) {
        .lg-entry { grid-template-columns: auto 1fr; }
        .lg-ts { display: none; }
        .lg-search { width: 140px; }
      }
    </style>

    <div class="lg-header">
      <h1><i class="bi bi-journal-text me-2"></i>System Logs</h1>
      <p>All application events — newest first</p>
    </div>

    <div class="container">

      <!-- Toolbar -->
      <div class="lg-toolbar">
        <div class="lg-filters" id="lgFilters">
          <button class="lg-filter-btn active" data-src="all" onclick="lgSetFilter(this)">
            All <span class="lg-badge" id="lgCountAll">—</span>
          </button>
        </div>
        <div class="lg-search-wrap">
          <i class="bi bi-search lg-search-icon"></i>
          <input type="text" class="lg-search" id="lgSearch"
                 placeholder="Search…" oninput="lgRender()">
        </div>
        <button class="lg-btn-refresh" onclick="lgLoad()">
          <i class="bi bi-arrow-clockwise me-1"></i>Refresh
        </button>
      </div>

      <!-- Status -->
      <div class="lg-status">
        <span id="lgStatusText">Loading…</span>
        <span>Updated: <strong id="lgLastUpdated">—</strong></span>
      </div>

      <!-- Log entries -->
      <div id="lgList" class="lg-list">
        <div class="lg-empty"><i class="bi bi-hourglass-split me-2"></i>Loading logs…</div>
      </div>

      <!-- Show more -->
      <div class="lg-show-more" id="lgShowMore" style="display:none">
        <button onclick="lgShowAll()">
          <i class="bi bi-chevron-down me-1"></i>Show all <span id="lgHiddenCount"></span> more entries
        </button>
      </div>

    </div>';

    require("../code/output_admin.inc.php");

} catch (Exception $e) {
    include("../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($e, "../logs/error_log.csv");
}
