/* ── Admin Logs Viewer ──────────────────────────────────────────────── */
var lgAllEntries    = [];
var lgCurrentFilter = 'all';
var lgPageSize      = 100;
var lgShowing       = lgPageSize;

// ── Level display config ─────────────────────────────────────────────
var lgLevelConfig = {
  error:    { cls: 'lg-lvl-error',    label: 'Error' },
  security: { cls: 'lg-lvl-security', label: 'Security' },
  mail:     { cls: 'lg-lvl-mail',     label: 'Mail' },
  info:     { cls: 'lg-lvl-info',     label: 'Info' }
};

// ── Load data ────────────────────────────────────────────────────────
function lgLoad() {
  var btn = document.querySelector('.lg-btn-refresh');
  if (btn) btn.disabled = true;

  document.getElementById('lgStatusText').textContent = 'Loading…';

  fetch('/scripts/ajax_logs_data.php')
    .then(function(r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.text();
    })
    .then(function(raw) {
      var d;
      try { d = JSON.parse(raw); }
      catch(e) {
        lgShowError('Invalid response from server (check console)');
        console.error('Logs JSON error. Raw:', raw);
        return;
      }
      if (d.error) {
        lgShowError('Server error: ' + d.error);
        return;
      }

      lgAllEntries = d.entries || [];
      lgShowing    = lgPageSize;

      document.getElementById('lgLastUpdated').textContent = d.last_updated || '—';

      // Build filter buttons
      lgBuildFilters(d.sources || [], d.total || 0);

      // Render
      lgRender();
    })
    .catch(function(e) {
      lgShowError('Fetch error: ' + e.message);
    })
    .finally(function() {
      if (btn) btn.disabled = false;
    });
}

// ── Build filter buttons ─────────────────────────────────────────────
function lgBuildFilters(sources, total) {
  var container = document.getElementById('lgFilters');

  // Keep first "All" button, rebuild the rest
  container.innerHTML =
    '<button class="lg-filter-btn' + (lgCurrentFilter === 'all' ? ' active' : '') +
    '" data-src="all" onclick="lgSetFilter(this)">' +
    'All <span class="lg-badge">' + total + '</span></button>';

  // Group by source
  sources.forEach(function(src) {
    var isActive = lgCurrentFilter === src.id;
    container.innerHTML +=
      '<button class="lg-filter-btn' + (isActive ? ' active' : '') +
      '" data-src="' + lgEscape(src.id) + '" onclick="lgSetFilter(this)">' +
      lgEscape(src.label) +
      ' <span class="lg-badge">' + src.count + '</span></button>';
  });
}

// ── Set active filter ────────────────────────────────────────────────
function lgSetFilter(btn) {
  lgCurrentFilter = btn.getAttribute('data-src');
  lgShowing = lgPageSize;

  document.querySelectorAll('.lg-filter-btn').forEach(function(b) {
    b.classList.toggle('active', b === btn);
  });

  lgRender();
}

// ── Render entries ───────────────────────────────────────────────────
function lgRender() {
  var search  = (document.getElementById('lgSearch').value || '').toLowerCase().trim();
  var list    = document.getElementById('lgList');
  var showMoreDiv = document.getElementById('lgShowMore');

  // Filter
  var filtered = lgAllEntries.filter(function(e) {
    if (lgCurrentFilter !== 'all' && e.source !== lgCurrentFilter) return false;
    if (search) {
      var haystack = (e.message + ' ' + e.detail + ' ' + e.ts + ' ' + e.source).toLowerCase();
      return haystack.indexOf(search) !== -1;
    }
    return true;
  });

  // Status text
  document.getElementById('lgStatusText').textContent =
    'Showing ' + Math.min(filtered.length, lgShowing) +
    ' of ' + filtered.length + ' entries';

  if (filtered.length === 0) {
    list.innerHTML = '<div class="lg-empty"><i class="bi bi-inbox me-2"></i>' +
      (search ? 'No entries match your search.' : 'No log entries found.') + '</div>';
    showMoreDiv.style.display = 'none';
    return;
  }

  // Slice for pagination
  var visible = filtered.slice(0, lgShowing);
  var hidden  = filtered.length - lgShowing;

  // Build HTML
  var html = '';
  visible.forEach(function(e) {
    var cfg     = lgLevelConfig[e.level] || lgLevelConfig.info;
    var msgHtml = search ? lgHighlight(lgEscape(e.message), search) : lgEscape(e.message);
    var detHtml = e.detail ? (search ? lgHighlight(lgEscape(e.detail), search) : lgEscape(e.detail)) : '';

    // Split timestamp
    var tsParts  = e.ts.split(' ');
    var tsDate   = tsParts.slice(0, 3).join(' ');  // "29 Apr 2026"
    var tsTime   = tsParts[3] || '';               // "11:27:21"

    html +=
      '<div class="lg-entry">' +
        '<span class="lg-lvl ' + cfg.cls + '">' + cfg.label + '</span>' +
        '<div class="lg-body">' +
          '<div class="lg-msg">' + msgHtml +
            '<span class="lg-src-tag">' + lgEscape(e.source) + '</span>' +
          '</div>' +
          (detHtml ? '<div class="lg-detail">' + detHtml + '</div>' : '') +
        '</div>' +
        '<div class="lg-ts">' +
          '<span class="lg-ts-date">' + lgEscape(tsDate) + '</span>' +
          '<span class="lg-ts-time">' + lgEscape(tsTime) + '</span>' +
        '</div>' +
      '</div>';
  });

  list.innerHTML = html;

  // Show more button
  if (hidden > 0) {
    document.getElementById('lgHiddenCount').textContent = hidden;
    showMoreDiv.style.display = 'block';
  } else {
    showMoreDiv.style.display = 'none';
  }
}

// ── Show all entries (remove pagination) ────────────────────────────
function lgShowAll() {
  lgShowing = 999999;
  lgRender();
}

// ── Helpers ──────────────────────────────────────────────────────────
function lgEscape(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function lgHighlight(escapedStr, term) {
  if (!term) return escapedStr;
  var re = new RegExp('(' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
  return escapedStr.replace(re, '<span class="lg-hl">$1</span>');
}

function lgShowError(msg) {
  document.getElementById('lgList').innerHTML =
    '<div class="lg-empty" style="color:#ef4444"><i class="bi bi-exclamation-triangle me-2"></i>' +
    lgEscape(msg) + '</div>';
  document.getElementById('lgStatusText').textContent = 'Error';
}

// ── Init ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  lgLoad();
});
