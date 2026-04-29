/* ── Admin Analytics Dashboard ─────────────────────────────────────── */
let anChart  = null;
let anTimer  = null;
let anSeconds = 600; // 10 minutes

const anMonths = ['Jan','Feb','Mar','Apr','May','Jun',
                  'Jul','Aug','Sep','Oct','Nov','Dec'];

// ── Load data ────────────────────────────────────────────────────────
function anLoad() {
  const countdownEl = document.getElementById('anCountdown');
  if (countdownEl) countdownEl.textContent = '…';

  fetch('/scripts/ajax_analytics_data.php')
    .then(function(r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.text();
    })
    .then(function(raw) {
      var d;
      try { d = JSON.parse(raw); }
      catch(e) {
        console.error('Analytics: JSON parse error. Raw response:', raw);
        var lu = document.getElementById('anLastUpdated');
        if (lu) lu.textContent = 'Error: invalid response (check console)';
        return;
      }
      if (d.error) {
        console.error('Analytics error:', d.error);
        var lu = document.getElementById('anLastUpdated');
        if (lu) lu.textContent = 'Error: ' + d.error;
        return;
      }

      var s = d.summary;

      // Cards
      document.getElementById('anToday').textContent  = s.today.toLocaleString();
      document.getElementById('anUnique').textContent = s.today_unique.toLocaleString();
      document.getElementById('anMonth').textContent  = s.month.toLocaleString();
      document.getElementById('anYear').textContent   = s.year.toLocaleString();
      document.getElementById('anTotal').textContent  = s.total.toLocaleString();

      var diff = s.today - s.yesterday;
      document.getElementById('anTodaySub').innerHTML =
        diff === 0 ? 'same as yesterday'
        : diff > 0
          ? '<span style="color:#10b981">+' + diff + ' vs yesterday</span>'
          : '<span style="color:#ef4444">'  + diff + ' vs yesterday</span>';

      var now = new Date();
      document.getElementById('anMonthName').textContent = anMonths[now.getMonth()] + ' ' + now.getFullYear();
      document.getElementById('anYearNum').textContent   = now.getFullYear();

      // Chart
      var ctx = document.getElementById('anChart').getContext('2d');
      if (anChart) {
        anChart.data.labels              = d.chart.labels;
        anChart.data.datasets[0].data    = d.chart.visits;
        anChart.update('active');
      } else {
        anChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: d.chart.labels,
            datasets: [{
              label: 'Visits',
              data: d.chart.visits,
              borderColor: '#26e3ff',
              backgroundColor: 'rgba(38,227,255,.08)',
              borderWidth: 2,
              pointRadius: 3,
              pointBackgroundColor: '#26e3ff',
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: 'rgba(13,17,23,.95)',
                borderColor: 'rgba(38,227,255,.3)',
                borderWidth: 1,
                titleColor: '#26e3ff',
                bodyColor: '#e2e8f0'
              }
            },
            scales: {
              x: {
                ticks: { color: 'rgba(255,255,255,.4)', maxRotation: 45, font: { size: 11 } },
                grid:  { color: 'rgba(255,255,255,.05)' }
              },
              y: {
                beginAtZero: true,
                ticks: {
                  color: 'rgba(255,255,255,.4)',
                  font: { size: 11 },
                  callback: function(v) { return Number.isInteger(v) ? v : ''; }
                },
                grid: { color: 'rgba(255,255,255,.05)' }
              }
            }
          }
        });
      }

      // Country ranking
      var box = document.getElementById('anCountries');
      if (!d.countries.length) {
        box.innerHTML = '<div style="padding:2rem;text-align:center;color:rgba(255,255,255,.3);">No country data yet</div>';
      } else {
        var html = '';
        for (var i = 0; i < d.countries.length; i++) {
          var c = d.countries[i];
          html +=
            '<div class="an-country-row">' +
              '<span class="an-rank">' + (i + 1) + '</span>' +
              '<img class="an-flag"' +
                   ' src="https://flagcdn.com/24x18/' + c.code + '.png"' +
                   ' onerror="this.style.display=\'none\'"' +
                   ' alt="' + c.code.toUpperCase() + '">' +
              '<span class="an-cc">' + c.code.toUpperCase() + '</span>' +
              '<span class="an-cname">' + c.name + '</span>' +
              '<div class="an-bar-wrap"><div class="an-bar" style="width:' + c.pct_bar + '%"></div></div>' +
              '<span class="an-visits">' + c.visits.toLocaleString() + '</span>' +
            '</div>';
        }
        box.innerHTML = html;
      }

      document.getElementById('anLastUpdated').textContent = d.last_updated;

      // Reset countdown
      anSeconds = 600;
    })
    .catch(function(e) {
      console.error('Analytics fetch error:', e);
      var lu = document.getElementById('anLastUpdated');
      if (lu) lu.textContent = 'Fetch error: ' + e.message;
    });
}

// ── Countdown timer ──────────────────────────────────────────────────
function anTick() {
  anSeconds--;
  if (anSeconds <= 0) {
    anLoad();
  } else {
    var m = String(Math.floor(anSeconds / 60)).padStart(2, '0');
    var s = String(anSeconds % 60).padStart(2, '0');
    var el = document.getElementById('anCountdown');
    if (el) el.textContent = m + ':' + s;
  }
}

// ── Init (wait for DOM ready) ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  anLoad();
  anTimer = setInterval(anTick, 1000);
});
