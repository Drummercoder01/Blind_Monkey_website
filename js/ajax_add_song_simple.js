// ajax_add_song_simple.js — handles add-song form with URL-to-embed parser

// ── Platform URL parser ──────────────────────────────────────────────────────

function parseMusicUrl(url) {
    url = url.trim();

    // ── Spotify: track / album / playlist / artist ───────────────────────
    let sp = url.match(/open\.spotify\.com\/(track|album|playlist|artist)\/([A-Za-z0-9]+)/);
    if (sp) {
        const type = sp[1], id = sp[2];
        return {
            platform : 'spotify',
            type     : type,
            label    : 'Spotify · ' + type.charAt(0).toUpperCase() + type.slice(1),
            icon     : '<i class="fa-brands fa-spotify" style="color:#1DB954;"></i>',
            embedUrl : 'https://open.spotify.com/embed/' + type + '/' + id +
                       '?utm_source=generator&theme=0',
            height   : (type === 'track') ? 152 : 352
        };
    }

    // ── SoundCloud: soundcloud.com/artist/track ──────────────────────────
    let sc = url.match(/soundcloud\.com\/[^\s?#]+/);
    if (sc) {
        return {
            platform : 'soundcloud',
            type     : 'track',
            label    : 'SoundCloud',
            icon     : '<i class="fa-brands fa-soundcloud" style="color:#FF5500;"></i>',
            embedUrl : 'https://w.soundcloud.com/player/?url=' + encodeURIComponent(url) +
                       '&color=%2326e3ff&auto_play=false&hide_related=true' +
                       '&show_comments=false&show_user=true&show_reposts=false&show_teaser=false',
            height   : 166
        };
    }

    // ── Apple Music ──────────────────────────────────────────────────────
    let am = url.match(/music\.apple\.com\/([a-z]{2})\/(album|playlist|song)\/([^\/]+)\/([^?#\s]+)/);
    if (am) {
        const country = am[1], type = am[2], name = am[3], id = am[4];
        return {
            platform : 'apple',
            type     : type,
            label    : 'Apple Music · ' + type.charAt(0).toUpperCase() + type.slice(1),
            icon     : '<i class="fa-brands fa-apple" style="color:#FC3C44;"></i>',
            embedUrl : 'https://embed.music.apple.com/' + country + '/' + type +
                       '/' + name + '/' + id,
            height   : 175
        };
    }

    return null;
}

function buildMusicIframe(parsed) {
    return '<iframe style="border-radius:12px" src="' + parsed.embedUrl +
           '" width="100%" height="' + parsed.height +
           '" frameborder="0" allowfullscreen="" ' +
           'allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"' +
           ' loading="lazy"></iframe>';
}

// ── DOMContentLoaded ─────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ ajax_add_song_simple.js loaded');

    const urlInput   = document.getElementById('musicUrl');
    const embedField = document.getElementById('embedCode');
    const preview    = document.getElementById('musicPreview');
    const prePlayer  = document.getElementById('previewPlayer');
    const platBadge  = document.getElementById('platformBadge');
    const platIcon   = document.getElementById('platformIcon');
    const platLabel  = document.getElementById('platformLabel');
    const feedback   = document.getElementById('urlFeedback');
    const addBtn     = document.getElementById('addSongBtn');

    // ── URL input handler ────────────────────────────────────────────────
    if (urlInput) {
        urlInput.addEventListener('input', function() {
            const parsed = parseMusicUrl(this.value);

            if (parsed) {
                // Build and store embed iframe
                embedField.value = buildMusicIframe(parsed);

                // Show player preview
                prePlayer.src    = parsed.embedUrl;
                prePlayer.height = parsed.height;
                platIcon.innerHTML = parsed.icon;
                platLabel.textContent = parsed.label;
                preview.style.display = 'block';

                // Enable button
                addBtn.disabled = false;
                addBtn.style.opacity = '1';

                feedback.innerHTML =
                    '<i class="bi bi-check-circle-fill" style="color:#10b981"></i>' +
                    '<span style="color:#10b981"> ' + parsed.label + ' detected</span>';

            } else {
                embedField.value = '';
                prePlayer.src    = '';
                preview.style.display = 'none';
                addBtn.disabled = true;
                addBtn.style.opacity = '.4';

                if (this.value.length > 10) {
                    feedback.innerHTML =
                        '<i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b"></i>' +
                        '<span style="color:#f59e0b"> URL not recognised — Spotify, SoundCloud, Apple Music supported</span>';
                } else {
                    feedback.innerHTML =
                        '<i class="bi bi-info-circle"></i>' +
                        '<span>Paste the URL from your browser — Spotify, SoundCloud and Apple Music supported</span>';
                }
            }
        });
    }

    // ── Form submit ──────────────────────────────────────────────────────
    const songForm = document.getElementById('songForm');
    if (songForm) {
        songForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!embedField.value.trim()) {
                showMusicNotification('❌ Please paste a valid music URL first', 'error');
                return;
            }

            const submitBtn = addBtn;
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Adding...';

            fetch('ajax_add_song.php', {
                method: 'POST',
                body  : new FormData(this)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    showMusicNotification('✅ ' + data.message, 'success');

                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById('addSongModal')
                    );
                    if (modal) modal.hide();
                    this.reset();

                    if (typeof window.refreshMusicList === 'function') {
                        window.refreshMusicList();
                    }
                } else {
                    showMusicNotification('❌ ' + data.message, 'error');
                }
            })
            .catch(err => {
                console.error('❌ AJAX error:', err);
                showMusicNotification('❌ Network error', 'error');
            })
            .finally(() => {
                submitBtn.disabled  = false;
                submitBtn.style.opacity = '1';
                submitBtn.innerHTML =
                    '<i class="bi bi-plus-circle-fill me-2"></i>Add Song';
            });
        });
    }

    // ── Reset on modal close ─────────────────────────────────────────────
    const modal = document.getElementById('addSongModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('songForm');
            if (form) form.reset();
            if (preview)  { preview.style.display = 'none'; prePlayer.src = ''; }
            if (addBtn)   { addBtn.disabled = true; addBtn.style.opacity = '.4'; }
            if (feedback) {
                feedback.innerHTML =
                    '<i class="bi bi-info-circle"></i>' +
                    '<span>Paste the URL from your browser — Spotify, SoundCloud and Apple Music supported</span>';
            }
        });
    }
});

// ── Notification helper (glassmorphism style) ────────────────────────────────

function showMusicNotification(message, type) {
    const colors = {
        success : { bg: 'rgba(16,185,129,.95)',  border: 'rgba(16,185,129,.6)'  },
        error   : { bg: 'rgba(239,68,68,.95)',   border: 'rgba(239,68,68,.6)'   },
        info    : { bg: 'rgba(38,227,255,.95)',   border: 'rgba(38,227,255,.6)'  }
    };
    const c = colors[type] || colors.info;
    const n = document.createElement('div');
    n.style.cssText = `position:fixed;top:20px;right:20px;background:${c.bg};
        backdrop-filter:blur(15px);color:#fff;padding:1rem 1.5rem;border-radius:12px;
        border:2px solid ${c.border};z-index:10000;box-shadow:0 8px 32px rgba(0,0,0,.3);
        transform:translateX(120%);transition:transform .4s cubic-bezier(.4,0,.2,1);
        max-width:380px;font-weight:500;cursor:pointer;`;
    n.textContent = message;
    document.body.appendChild(n);
    setTimeout(() => n.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
        n.style.transform = 'translateX(120%)';
        setTimeout(() => n.parentNode?.removeChild(n), 400);
    }, 4000);
    n.addEventListener('click', () => {
        n.style.transform = 'translateX(120%)';
        setTimeout(() => n.parentNode?.removeChild(n), 400);
    });
}
