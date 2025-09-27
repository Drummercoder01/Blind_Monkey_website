<?php

$_inhoud .= "
<!-- Modal -->
<div class='modal fade' id='addSongModal' tabindex='-1' aria-labelledby='addSongModalLabel' aria-hidden='true'>
  <div class='modal-dialog modal-dialog-centered'>
    <div class='modal-content bg-dark text-white'>
      <div class='modal-header'>
        <h5 class='modal-title text-white' id='addSongModalLabel'>Add New Song</h5>
        <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Close'></button>
      </div>
      <div class='modal-body'>

        <form id='songForm'>
          <div class='mb-3'>
            <label for='songName' class='form-label text-white'>Song Name</label>
            <input type='text' class='form-control' name='song_name' id='songName' 
                   placeholder='Enter song name' required>
          </div>
          <div class='mb-3'>
            <label for='embedCode' class='form-label text-white'>Embed Code</label>
            <textarea class='form-control' name='embed_code' id='embedCode' rows='4' 
                      placeholder='Paste embed iframe code here' required></textarea>
          </div>
          <div class='text-center'>
            <button type='submit' class='btn btn-light fw-bold px-4'>Submit</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>";


?>