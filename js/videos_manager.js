// ========== VARIABLE GLOBAL ==========
let globalVideos = [];
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
    
    notification.addEventListener('click', function() {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    });
}

// ========== FUNCIONES PRINCIPALES ==========

function refreshVideos() {
    const container = document.querySelector('.videos-container');
    if (container) {
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    fetch('get_videos.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalVideos = data.videos;
                renderVideos(data.videos);
                initSortable();
            } else {
                showNotification('⚠ Error loading videos', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('⚠ Error loading videos', 'error');
        });
}

function renderVideos(videos) {
    const container = document.querySelector('.videos-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (videos.length === 0) {
        container.innerHTML = '<div class="text-center text-white py-5"><p>No videos yet. Add your first video!</p></div>';
        return;
    }
    
    // Crear botón para guardar orden
    const orderControls = document.createElement('div');
    orderControls.className = 'text-center mb-4';
    orderControls.innerHTML = `
        <button id="saveVideoOrderBtn" class="btn btn-outline-light px-4 py-2 fw-bold">
            <i class="bi bi-floppy me-2"></i>Save Order
        </button>
        <small class="text-muted d-block mt-2">Drag and drop videos to reorder them</small>
    `;
    container.appendChild(orderControls);
    
    const videosGrid = document.createElement('div');
    videosGrid.className = 'row videos-grid';
    videosGrid.id = 'videosGrid';
    
    videos.forEach(video => {
        const videoElement = createVideoElement(video);
        videosGrid.appendChild(videoElement);
    });
    
    container.appendChild(videosGrid);
    
    // Event listener para el botón de guardar orden
    document.getElementById('saveVideoOrderBtn').addEventListener('click', saveVideoOrder);
}

function createVideoElement(video) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col-md-6 col-lg-4 mb-4 video-item';
    colDiv.setAttribute('data-id', video.id);
    
    colDiv.innerHTML = `
        <div class="card video-card h-100 bg-dark text-white">
            <div class="card-body p-3 position-relative">
                <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                    <button class="btn btn-sm btn-outline-danger delete-video" data-id="${video.id}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                
                <div class="video-order-badge position-absolute top-0 start-0 m-2" style="z-index: 10;">
                    <span class="badge bg-primary">#${video.video_order}</span>
                </div>
                
                <div class="video-embed-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    ${video.iframe.replace('<iframe', '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"')}
                </div>
                
                <div class="mt-2 text-center">
                    <small class="text-muted">ID: ${video.id}</small>
                </div>
            </div>
        </div>
    `;
    
    // Agregar event listener para eliminar
    const deleteBtn = colDiv.querySelector('.delete-video');
    deleteBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.getAttribute('data-id');
        deleteVideo(id);
    });
    
    return colDiv;
}

function initSortable() {
    const videosGrid = document.getElementById('videosGrid');
    if (!videosGrid) return;
    
    if (sortableInstance) {
        sortableInstance.destroy();
    }
    
    sortableInstance = new Sortable(videosGrid, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        handle: '.video-card', // Solo se puede arrastrar desde la tarjeta
        onEnd: function() {
            // Cambiar el estilo del botón para indicar cambios no guardados
            const saveBtn = document.getElementById('saveVideoOrderBtn');
            if (saveBtn) {
                saveBtn.classList.remove('btn-outline-light');
                saveBtn.classList.add('btn-warning');
                saveBtn.innerHTML = '<i class="bi bi-floppy me-2"></i>Save Order (Modified)';
            }
        }
    });
}

function saveVideoOrder() {
    const videoItems = document.querySelectorAll('.video-item');
    const videoOrder = Array.from(videoItems).map(item => item.getAttribute('data-id'));
    
    const formData = new FormData();
    formData.append('video_order', JSON.stringify(videoOrder));
    
    fetch('update_video_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Video order saved successfully', 'success');
            const saveBtn = document.getElementById('saveVideoOrderBtn');
            if (saveBtn) {
                saveBtn.classList.remove('btn-warning');
                saveBtn.classList.add('btn-outline-light');
                saveBtn.innerHTML = '<i class="bi bi-floppy me-2"></i>Save Order';
            }
            refreshVideos(); // Recargar para actualizar números de orden
        } else {
            showNotification('⚠ Error saving video order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('⚠ Error saving video order', 'error');
    });
}

function deleteVideo(id) {
    if (!confirm('Are you sure you want to delete this video?')) return;
    
    const formData = new FormData();
    formData.append('video_id', id);
    
    fetch('delete_video.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Video deleted successfully', 'success');
            refreshVideos();
        } else {
            showNotification('⚠ Error deleting video', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('⚠ Error deleting video', 'error');
    });
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    
    // Form submit para agregar videos
    const videoForm = document.getElementById('videoForm');
    if (videoForm) {
        videoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const embedCode = document.getElementById('embedCode').value.trim();
            
            if (!embedCode) {
                showNotification('⚠ Please enter a YouTube embed code', 'error');
                return;
            }
            
            fetch('add_video.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification('✅ Video added successfully', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addVideoModal'));
                    if (modal) modal.hide();
                    this.reset();
                    refreshVideos();
                } else {
                    showNotification('⚠ Error: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('⚠ Error adding video', 'error');
            });
        });
    }
    
    // Reset modal on hide
    const videoModal = document.getElementById('addVideoModal');
    if (videoModal) {
        videoModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('videoForm');
            if (form) form.reset();
        });
    }
    
    // Cargar videos al iniciar
    refreshVideos();
});

// ========== CSS STYLES ==========
document.addEventListener('DOMContentLoaded', function() {
    // Agregar estilos CSS para sortable
    const style = document.createElement('style');
    style.textContent = `
        .sortable-ghost {
            opacity: 0.4;
            transform: rotate(5deg);
        }
        
        .sortable-chosen {
            cursor: grabbing !important;
        }
        
        .sortable-drag {
            transform: rotate(10deg);
        }
        
        .video-item {
            cursor: grab;
            transition: transform 0.2s ease;
        }
        
        .video-item:hover {
            transform: translateY(-5px);
        }
        
        .video-card {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .video-card:hover {
            border-color: #007bff;
            box-shadow: 0 8px 16px rgba(0,123,255,0.3);
        }
        
        .video-embed-container iframe {
            border-radius: 8px;
        }
    `;
    document.head.appendChild(style);
});