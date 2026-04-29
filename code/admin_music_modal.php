<?php
$_inhoud .= "
<!-- Add Song Modal -->
<div class='modal fade' id='addSongModal' tabindex='-1' aria-labelledby='addSongModalLabel' aria-hidden='true'>
  <div class='modal-dialog modal-dialog-centered modal-lg'>
    <div class='modal-content' style='background:linear-gradient(135deg,rgba(15,23,42,.98),rgba(30,41,59,.98));
         border:2px solid rgba(38,227,255,.3);border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,.5);'>

      <div class='modal-header' style='border-bottom:1px solid rgba(38,227,255,.2);padding:1.5rem 2rem;
           background:linear-gradient(135deg,rgba(38,227,255,.08),rgba(38,227,255,.03));'>
        <h5 class='modal-title text-white fw-700' id='addSongModalLabel' style='font-size:1.4rem;font-weight:700;'>
          <i class='bi bi-music-note-beamed me-2' style='color:#26e3ff;'></i>Add New Song
        </h5>
        <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'
                style='opacity:.7;transition:all .3s;'></button>
      </div>

      <div class='modal-body' style='padding:2rem;'>
        <form id='songForm'>
          <!-- Hidden embed field — filled automatically -->
          <input type='hidden' name='embed_code' id='embedCode'>

          <!-- URL Input -->
          <div class='mb-3'>
            <label for='musicUrl' class='form-label'
                   style='color:rgba(255,255,255,.9);font-weight:600;font-size:1rem;'>
              <i class='bi bi-link-45deg me-2' style='color:#26e3ff;'></i>Music URL
            </label>
            <input type='url' id='musicUrl' class='form-control'
                   placeholder='https://open.spotify.com/track/... or soundcloud.com/...'
                   autocomplete='off'
                   style='background:rgba(0,0,0,.3);border:2px solid rgba(255,255,255,.2);
                          border-radius:10px;padding:1rem 1.25rem;color:#fff;font-size:.95rem;'>
            <div id='urlFeedback' style='font-size:.85rem;margin-top:.5rem;min-height:1.3em;
                 color:rgba(255,255,255,.5);display:flex;align-items:center;gap:.4rem;'>
              <i class='bi bi-info-circle'></i>
              <span>Paste the URL from your browser — Spotify, SoundCloud and Apple Music supported</span>
            </div>
          </div>

          <!-- Live player preview -->
          <div id='musicPreview' style='display:none;margin-bottom:1.25rem;border-radius:12px;overflow:hidden;'>
            <!-- Platform badge -->
            <div id='platformBadge' style='display:flex;align-items:center;gap:.6rem;
                 margin-bottom:.75rem;'>
              <span id='platformIcon' style='font-size:1.4rem;'></span>
              <span id='platformLabel' style='color:#26e3ff;font-weight:600;font-size:.9rem;'></span>
            </div>
            <!-- Actual player iframe preview -->
            <iframe id='previewPlayer' src='' frameborder='0'
                    style='width:100%;border-radius:12px;border:none;display:block;'
                    allow='autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture'
                    loading='lazy'></iframe>
          </div>

          <!-- Song Name -->
          <div class='mb-4'>
            <label for='songName' class='form-label'
                   style='color:rgba(255,255,255,.9);font-weight:600;font-size:1rem;'>
              <i class='bi bi-tag-fill me-2' style='color:#26e3ff;'></i>Song / Release name
            </label>
            <input type='text' class='form-control' name='song_name' id='songName'
                   placeholder='e.g. Song Title — Artist Name'
                   required
                   style='background:rgba(0,0,0,.3);border:2px solid rgba(255,255,255,.2);
                          border-radius:10px;padding:1rem 1.25rem;color:#fff;font-size:.95rem;'>
          </div>

          <div class='text-center'>
            <button type='submit' id='addSongBtn'
                    style='background:linear-gradient(135deg,#26e3ff,#1a9fb8);color:#000;
                           border:none;border-radius:10px;padding:.875rem 3rem;font-size:1.1rem;
                           font-weight:700;cursor:pointer;transition:all .3s;opacity:.4;'
                    disabled>
              <i class='bi bi-plus-circle-fill me-2'></i>Add Song
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>";
?>
