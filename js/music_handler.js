// music_handler.js - sin spinner
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('music')) return;
    initializeMusic();
});

let allSongs = [];
let songsToShowInitial = 4;
let musicExpanded = false;

function initializeMusic() {
    loadSongsFromDB();
}

function loadSongsFromDB() {
    // Eliminamos todas las referencias al estado de carga
    showElement('music-content', false);
    showElement('no-music-state', false);
    showElement('music-button-container', false);
    
    fetch('../scripts/get_songs.php')
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.status === 'success' && data.songs?.length > 0) {
                allSongs = data.songs;
                processSongs();
            } else {
                showNoMusicState();
            }
        })
        .catch(error => {
            console.error('Error loading music:', error);
            showErrorState();
        });
}

function processSongs() {
    if (allSongs.length === 0) {
        showNoMusicState();
        return;
    }
    renderMusic();
    setupMusicToggleButton();
    showElement('music-content', true);
}

function renderMusic() {
    const musicGrid = document.getElementById('music-grid');
    const musicAdditional = document.getElementById('music-additional');
    if (!musicGrid || !musicAdditional) return;
    
    musicGrid.innerHTML = '';
    musicAdditional.innerHTML = '';
    
    const initialSongs = allSongs.slice(0, songsToShowInitial);
    const additionalSongs = allSongs.slice(songsToShowInitial);
    
    initialSongs.forEach((song, index) => {
        musicGrid.appendChild(createSongElement(song, index));
    });
    additionalSongs.forEach((song, index) => {
        musicAdditional.appendChild(createSongElement(song, index + songsToShowInitial));
    });
    
    setTimeout(() => applyMusicStaggeredAnimations(), 100);
}

function createSongElement(song, index) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col music-item';
    colDiv.style.opacity = '0';
    colDiv.style.transform = 'translateY(20px)';
    colDiv.style.transition = 'all 0.5s ease';
    
    let safeEmbed = song.embed || '';
    if (safeEmbed && !safeEmbed.includes('sandbox')) {
        safeEmbed = safeEmbed.replace('<iframe', '<iframe sandbox="allow-same-origin allow-scripts allow-popups"');
    }
    
    // Asegurar que el embed sea responsivo
    const responsiveEmbed = safeEmbed.replace(/width="[^"]*"/, 'width="100%"')
                                   .replace(/height="[^"]*"/, 'height="166"')
                                   .replace(/style="[^"]*"/, 'style="width:100%;height:166px;border:none;border-radius:10px;"');
    
    colDiv.innerHTML = `
    <div class="card h-100 bg-black border-white music-card" style="border-width: 1.5px !important;">
        <div class="card-body d-flex flex-column">
            <h5 class="text-white text-center mb-3">${song.song_name || 'Untitled'}</h5>
            <div class="song-embed mt-auto">${responsiveEmbed}</div>
        </div>
    </div>
    `;
    return colDiv;
}

function setupMusicToggleButton() {
    const buttonContainer = document.getElementById('music-button-container');
    const toggleButton = document.getElementById('toggleMusic');
    if (!buttonContainer || !toggleButton) return;
    
    if (allSongs.length > songsToShowInitial) {
        showElement('music-button-container', true);
        const newToggleButton = toggleButton.cloneNode(true);
        toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);
        newToggleButton.addEventListener('click', toggleMusicVisibility);
    } else {
        showElement('music-button-container', false);
    }
}

function toggleMusicVisibility() {
    const musicAdditional = document.getElementById('music-additional');
    const toggleButton = document.getElementById('toggleMusic');
    const buttonText = toggleButton.querySelector('.button-text');
    const buttonIcon = toggleButton.querySelector('i');
    
    if (!musicExpanded) {
        // MOSTRAR canciones adicionales
        showElement('music-additional', true);
        musicAdditional.querySelectorAll('.music-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, index * 100);
        });
        buttonText.textContent = 'Show less music';
        buttonIcon.className = 'bi bi-eye-slash me-2';
        musicExpanded = true;
    } else {
        // OCULTAR canciones adicionales
        showElement('music-additional', false);
        buttonText.textContent = 'More Music';
        buttonIcon.className = 'bi bi-music-note-list me-2';
        musicExpanded = false;
        document.getElementById('music').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function applyMusicStaggeredAnimations() {
    document.querySelectorAll('.music-item').forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 150);
    });
}

function showNoMusicState() {
    showElement('no-music-state', true);
    showElement('music-content', false);
}

function showErrorState() {
    const musicContainer = document.querySelector('.music-container');
    if (musicContainer) {
        musicContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <p class="text-white mt-3 fs-5">Unable to load music at the moment</p>
                <button class="btn mt-2" onclick="window.refreshMusic()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                </button>
            </div>
        `;
    }
}

function showElement(elementId, show) {
    const element = document.getElementById(elementId);
    if (!element) return;
    element.style.display = show ? '' : 'none';
    if ((elementId === 'music-grid' || elementId === 'music-additional') && show) {
        element.style.display = 'flex';
    }
}

window.refreshMusic = function() {
    initializeMusic();
};
