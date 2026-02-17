// ========== VARIABLE GLOBAL ==========
let globalVideos = [];
let sortableInstance = null;

// ========== SISTEMA DE NOTIFICACIONES GLASSMORPHISM ==========
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    // Icons según tipo
    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };
    
    // Colores glassmorphism según tipo
    const colors = {
        success: {
            bg: 'linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%)',
            border: 'rgba(16, 185, 129, 0.6)',
            shadow: 'rgba(16, 185, 129, 0.4)'
        },
        error: {
            bg: 'linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%)',
            border: 'rgba(239, 68, 68, 0.6)',
            shadow: 'rgba(239, 68, 68, 0.4)'
        },
        warning: {
            bg: 'linear-gradient(135deg, rgba(251, 146, 60, 0.95) 0%, rgba(249, 115, 22, 0.95) 100%)',
            border: 'rgba(251, 146, 60, 0.6)',
            shadow: 'rgba(251, 146, 60, 0.4)'
        },
        info: {
            bg: 'linear-gradient(135deg, rgba(38, 227, 255, 0.95) 0%, rgba(26, 159, 184, 0.95) 100%)',
            border: 'rgba(38, 227, 255, 0.6)',
            shadow: 'rgba(38, 227, 255, 0.4)'
        }
    };
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi ${icons[type]} me-2" style="font-size: 1.25rem;"></i>
            <span>${message}</span>
        </div>
    `;
    
    const color = colors[type];
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${color.bg};
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 2px solid ${color.border};
        z-index: 10000;
        box-shadow: 0 8px 32px ${color.shadow}, 0 4px 12px rgba(0,0,0,0.3);
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 400px;
        min-width: 250px;
        font-weight: 500;
        letter-spacing: 0.3px;
        cursor: pointer;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.style.transform = 'translateX(0)', 10);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => notification.parentNode?.removeChild(notification), 400);
    }, 4000);
    
    notification.addEventListener('click', function() {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => notification.parentNode?.removeChild(notification), 400);
    });
}

// ========== FUNCIONES PRINCIPALES ==========

function refreshVideos() {
    const container = document.querySelector('.videos-container');
    if (container) {
        container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading videos...</p>
            </div>
        `;
    }
    
    fetch('get_videos.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalVideos = data.videos;
                renderVideos(data.videos);
                initSortable();
            } else {
                showNotification('❌ Error loading videos', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error loading videos', 'error');
        });
}

function renderVideos(videos) {
    const container = document.querySelector('.videos-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (videos.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-camera-video"></i>
                </div>
                <h3 class="empty-state-title">No Videos Yet</h3>
                <p class="empty-state-text">Add your first YouTube video to get started!</p>
            </div>
        `;
        return;
    }
    
    // Botón para guardar orden
    const orderControls = document.createElement('div');
    orderControls.className = 'order-controls-wrapper';
    orderControls.innerHTML = `
        <button id="saveVideoOrderBtn" class="btn-save-order-inline">
            <i class="bi bi-save me-2"></i>Save Order
        </button>
        <small class="order-hint">Drag and drop videos to reorder them</small>
    `;
    container.appendChild(orderControls);
    
    const videosGrid = document.createElement('div');
    videosGrid.className = 'videos-grid';
    videosGrid.id = 'videosGrid';
    
    videos.forEach(video => {
        const videoElement = createVideoElement(video);
        videosGrid.appendChild(videoElement);
    });
    
    container.appendChild(videosGrid);
    
    // Inyectar estilos si no existen
    injectVideoStyles();
    
    // Event listener para guardar orden
    document.getElementById('saveVideoOrderBtn').addEventListener('click', saveVideoOrder);
}

function createVideoElement(video) {
    const videoDiv = document.createElement('div');
    videoDiv.className = 'video-card-wrapper';
    videoDiv.setAttribute('data-id', video.id);
    
    videoDiv.innerHTML = `
        <div class="video-card">
            <!-- Drag Handle -->
            <div class="video-drag-handle">
                <i class="bi bi-grip-vertical"></i>
            </div>
            
            <!-- Order Badge -->
            <div class="video-order-badge">
                <span class="badge">#${video.video_order}</span>
            </div>
            
            <!-- Delete Button -->
            <button class="video-delete-btn delete-video" data-id="${video.id}" title="Delete video">
                <i class="bi bi-trash-fill"></i>
            </button>
            
            <!-- Video Embed Container -->
            <div class="video-embed-container">
                ${video.iframe.replace('<iframe', '<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; border-radius: 12px;"')}
            </div>
            
            <!-- Video ID -->
            <div class="video-id-label">
                <small>ID: ${video.id}</small>
            </div>
        </div>
    `;
    
    // Event listener para eliminar
    const deleteBtn = videoDiv.querySelector('.delete-video');
    deleteBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.getAttribute('data-id');
        deleteVideo(id);
    });
    
    return videoDiv;
}

function injectVideoStyles() {
    if (document.getElementById('videos-cards-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'videos-cards-styles';
    style.textContent = `
        /* Videos Grid */
        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            padding: 2rem 0;
        }
        
        @media (max-width: 768px) {
            .videos-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        /* Video Card Wrapper */
        .video-card-wrapper {
            cursor: grab;
            transition: all 0.3s ease;
        }
        
        .video-card-wrapper:active {
            cursor: grabbing;
        }
        
        /* Video Card */
        .video-card {
            position: relative;
            background: linear-gradient(135deg, 
                rgba(0, 0, 0, 0.6) 0%, 
                rgba(15, 23, 42, 0.8) 100%);
            backdrop-filter: blur(10px);
            padding: 25px 20px 20px;
            border-radius: 16px;
            border: 1px solid rgba(38, 227, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 4px 15px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 8px 30px rgba(0, 0, 0, 0.4),
                0 0 25px rgba(38, 227, 255, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-color: rgba(38, 227, 255, 0.4);
        }
        
        /* Drag Handle */
        .video-drag-handle {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.5;
            transition: opacity 0.2s ease;
            z-index: 20;
            cursor: grab;
            padding: 4px 8px;
            background: rgba(38, 227, 255, 0.1);
            border-radius: 8px;
        }
        
        .video-drag-handle i {
            font-size: 1.3rem;
            color: rgba(38, 227, 255, 0.9);
        }
        
        .video-card:hover .video-drag-handle {
            opacity: 1;
        }
        
        /* Order Badge */
        .video-order-badge {
            position: absolute;
            top: 12px;
            left: 15px;
            z-index: 5;
        }
        
        .video-order-badge .badge {
            font-size: 0.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #26e3ff 0%, #1a9fb8 100%);
            padding: 0.4rem 0.7rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(38, 227, 255, 0.4);
            letter-spacing: 0.5px;
            color: #000;
        }
        
        /* Delete Button */
        .video-delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            opacity: 0;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #dc3545 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
            cursor: pointer;
        }
        
        .video-card:hover .video-delete-btn {
            opacity: 1;
        }
        
        .video-delete-btn:hover {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.6);
        }
        
        /* Video Embed Container */
        .video-embed-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        /* Video ID Label */
        .video-id-label {
            text-align: center;
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }
        
        /* Order Controls */
        .order-controls-wrapper {
            text-align: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.5rem;
        }
        
        .btn-save-order-inline {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.875rem 2.5rem;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 12px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
        
        .btn-save-order-inline:hover {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.2) 0%, 
                rgba(255, 255, 255, 0.1) 100%);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.2);
        }
        
        .btn-save-order-inline.modified {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #f59e0b;
            color: #000;
        }
        
        .order-hint {
            display: block;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-top: 0.75rem;
        }
        
        /* Sortable States */
        .sortable-ghost {
            opacity: 0.4;
        }
        
        .sortable-chosen {
            transform: scale(1.02);
        }
        
        .sortable-drag {
            opacity: 0.8;
            transform: rotate(3deg);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.05) 0%, 
                rgba(255, 255, 255, 0.02) 100%);
            border-radius: 20px;
            border: 2px dashed rgba(38, 227, 255, 0.3);
            margin: 2rem auto;
            max-width: 600px;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: rgba(38, 227, 255, 0.5);
            margin-bottom: 1.5rem;
        }
        
        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .empty-state-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
        }
    `;
    document.head.appendChild(style);
}

function initSortable() {
    const videosGrid = document.getElementById('videosGrid');
    if (!videosGrid) return;
    
    if (sortableInstance) {
        sortableInstance.destroy();
    }
    
    sortableInstance = new Sortable(videosGrid, {
        animation: 200,
        easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        handle: '.video-card',
        onStart: function() {
            console.log('🖱️ Drag started');
        },
        onEnd: function() {
            console.log('🖱️ Drag ended');
            const saveBtn = document.getElementById('saveVideoOrderBtn');
            if (saveBtn) {
                saveBtn.classList.add('modified');
                saveBtn.innerHTML = '<i class="bi bi-save me-2"></i>Save Order (Modified)';
            }
        }
    });
}

function saveVideoOrder() {
    const videoItems = document.querySelectorAll('.video-card-wrapper');
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
                saveBtn.classList.remove('modified');
                saveBtn.innerHTML = '<i class="bi bi-save me-2"></i>Save Order';
            }
            refreshVideos();
        } else {
            showNotification('❌ Error saving video order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error saving video order', 'error');
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
            showNotification('❌ Error deleting video', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error deleting video', 'error');
    });
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('🎥 Videos Manager initialized');
    
    // Form submit
    const videoForm = document.getElementById('videoForm');
    if (videoForm) {
        videoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const embedCode = document.getElementById('embedCode').value.trim();
            
            if (!embedCode) {
                showNotification('❌ Please enter a YouTube embed code', 'error');
                return;
            }
            
            showNotification('⏳ Adding video...', 'info');
            
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
                    showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Error adding video', 'error');
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
