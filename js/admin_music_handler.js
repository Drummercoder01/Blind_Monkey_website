// ========== VARIABLE GLOBAL ==========
let globalSongs = [];
let sortableInstance = null;

// ========== SISTEMA DE NOTIFICACIONES ==========
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : type === 'error' ? 'bi-exclamation-circle' : 'bi-info-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 350px;
        display: flex;
        align-items: center;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// ========== FUNCIONES PRINCIPALES ==========

function refreshSongs() {
    const container = document.getElementById('musicContainer');
    if (container) {
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    fetch('get_songs.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalSongs = data.songs;
                renderSongs(data.songs);
                initSortable();
            } else {
                showNotification('❌ Error loading songs', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error loading songs', 'error');
        });
}

function renderSongs(songs) {
    const container = document.getElementById('musicContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (songs.length === 0) {
        container.innerHTML = '<div class="text-center text-white py-5"><p>No songs yet. Add your first song!</p></div>';
        return;
    }
    
    // Crear contenedor principal para Sortable
    const songsContainer = document.createElement('div');
    songsContainer.className = 'songs-sortable-container';
    songsContainer.id = 'songsSortableContainer';
    
    // Crear todas las canciones en un solo contenedor (no en columnas)
    songs.forEach(song => {
        const songElement = createSongElement(song);
        songsContainer.appendChild(songElement);
    });
    
    container.appendChild(songsContainer);
}

function createSongElement(song) {
    const songDiv = document.createElement('div');
    songDiv.className = 'song position-relative';
    songDiv.setAttribute('data-song-id', song.id);
    
    // Badge de orden - FIXED: Ahora se muestra correctamente
    const orderBadge = document.createElement('div');
    orderBadge.className = 'song-order-badge position-absolute top-0 start-0 m-1';
    orderBadge.innerHTML = `<span class="badge bg-primary">#${song.song_order}</span>`;
    
    // Botón de eliminar
    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'delete-song-btn btn btn-danger btn-sm position-absolute top-0 end-0 m-1';
    deleteBtn.setAttribute('data-song-id', song.id);
    deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
    deleteBtn.title = 'Delete song';
    
    // Agregar event listener al botón de eliminar
    deleteBtn.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevenir que el drag se active
        const songId = this.getAttribute('data-song-id');
        const songElement = this.closest('.song');
        
        if (confirm('Are you sure you want to delete this song?')) {
            deleteSongFromDatabase(songId, songElement);
        }
    });
    
    // Contenido de la canción
    const songContent = document.createElement('div');
    songContent.innerHTML = `
        <h4 class="text-white text-center mb-3">${song.song_name}</h4>
        <div class="song-embed-container">
            ${song.embed}
        </div>
    `;
    
    // Handle para arrastrar (mejora UX) - FIXED: Ahora es visible
    const dragHandle = document.createElement('div');
    dragHandle.className = 'drag-handle position-absolute';
    dragHandle.style.cssText = 'top: 10px; left: 50%; transform: translateX(-50%); cursor: grab; z-index: 10;';
    dragHandle.innerHTML = '<i class="bi bi-grip-horizontal text-white fs-4"></i>';
    
    songDiv.appendChild(dragHandle);
    songDiv.appendChild(deleteBtn);
    songDiv.appendChild(orderBadge);
    songDiv.appendChild(songContent);
    
    return songDiv;
}

function initSortable() {
    const songsContainer = document.getElementById('songsSortableContainer');
    if (!songsContainer) {
        console.error('Sortable container not found');
        return;
    }
    
    // Destruir instancia anterior si existe
    if (sortableInstance) {
        sortableInstance.destroy();
    }
    
    // Crear nueva instancia de Sortable
    sortableInstance = new Sortable(songsContainer, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        handle: '.drag-handle', // Usar el handle para arrastrar
        filter: '.delete-song-btn', // Ignorar el botón de eliminar
        preventOnFilter: false,
        onStart: function() {
            console.log('Drag started');
        },
        onEnd: function(evt) {
            console.log('Drag ended', evt.oldIndex, evt.newIndex);
            // Habilitar botón de guardar orden
            const saveBtn = document.getElementById('saveOrderBtn');
            if (saveBtn) {
                saveBtn.classList.remove('btn-outline-light');
                saveBtn.classList.add('btn-warning');
                saveBtn.classList.add('pulse-animation');
            }
            
            // Actualizar visualmente los números de orden
            updateOrderBadges();
        }
    });
    
    console.log('Sortable initialized');
}

function updateOrderBadges() {
    const songs = document.querySelectorAll('.song');
    songs.forEach((song, index) => {
        const badge = song.querySelector('.badge');
        if (badge) {
            badge.textContent = `#${index + 1}`;
        }
    });
}

function saveSongOrder() {
    const songItems = document.querySelectorAll('.song');
    const songOrder = Array.from(songItems).map(item => item.getAttribute('data-song-id'));
    
    console.log('Saving order:', songOrder);
    
    // Mostrar loading en el botón
    const saveBtn = document.getElementById('saveOrderBtn');
    const originalHtml = saveBtn.innerHTML;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Saving...';
    saveBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('song_order', JSON.stringify(songOrder));
    
    fetch('update_song_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Song order saved successfully', 'success');
            if (saveBtn) {
                saveBtn.classList.remove('btn-warning');
                saveBtn.classList.remove('pulse-animation');
                saveBtn.classList.add('btn-outline-light');
            }
        } else {
            showNotification('❌ Error saving order: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error saving order', 'error');
    })
    .finally(() => {
        // Restaurar botón
        if (saveBtn) {
            saveBtn.innerHTML = originalHtml;
            saveBtn.disabled = false;
        }
    });
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - Initializing music admin');
    
    // Save order button
    const saveOrderBtn = document.getElementById('saveOrderBtn');
    if (saveOrderBtn) {
        saveOrderBtn.addEventListener('click', saveSongOrder);
    }
    
    // Cargar canciones al iniciar
    refreshSongs();
    
    // Re-inicializar Sortable cuando se cierra el modal de agregar canción
    document.addEventListener('hidden.bs.modal', function(e) {
        if (e.target.id === 'addSongModal') {
            setTimeout(initSortable, 100);
        }
    });
});

// ========== FUNCIÓN DE ELIMINACIÓN ==========

function deleteSongFromDatabase(songId, songElement) {
    console.log('Deleting song:', songId);
    
    const deleteBtn = songElement.querySelector('.delete-song-btn');
    const originalHtml = deleteBtn.innerHTML;
    
    deleteBtn.innerHTML = '<i class="bi bi-hourglass"></i>';
    deleteBtn.disabled = true;
    
    fetch('ajax_delete_song.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'song_id=' + encodeURIComponent(songId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Song deleted successfully!', 'success');
            refreshSongs();
        } else {
            showNotification('❌ Error: ' + data.message, 'error');
            deleteBtn.innerHTML = originalHtml;
            deleteBtn.disabled = false;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('❌ An error occurred while deleting the song.', 'error');
        deleteBtn.innerHTML = originalHtml;
        deleteBtn.disabled = false;
    });
}

// ========== MANEJO DE NUEVAS CANCIONES ==========

// Esta función será llamada desde ajax_add_song.js después de agregar una canción
window.refreshMusicList = function() {
    refreshSongs();
};

// Asegurar que Sortable se cargue correctamente
function checkSortable() {
    if (typeof Sortable === 'undefined') {
        console.error('Sortable.js no está cargado correctamente');
        setTimeout(checkSortable, 100);
    } else {
        console.log('Sortable.js está cargado correctamente');
    }
}

// Verificar periódicamente hasta que Sortable esté cargado
checkSortable();