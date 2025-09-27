// ========== FUNCIONES DE UTILIDAD ==========

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Función para refrescar la lista de videos
function refreshVideos() {
    console.log('🔄 Refreshing videos...');
    
    const videosContainer = document.querySelector('.videos-container');
    if (videosContainer) {
        videosContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    $.ajax({
        url: 'get_videos.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.status === 'success') {
                renderVideos(data.videos);
            } else {
                console.error('Error getting videos:', data.message);
                showNotification('❌ Error loading videos', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching videos:', error);
            showNotification('❌ Error loading videos', 'error');
        }
    });
}

// Función para renderizar los videos
function renderVideos(videos) {
    const videosContainer = document.querySelector('.videos-container');
    if (!videosContainer) return;
    
    videosContainer.innerHTML = '';
    
    if (videos.length === 0) {
        videosContainer.innerHTML = '<div class="text-center text-white py-5"><p>No videos yet. Add your first video!</p></div>';
        return;
    }
    
    // Crear grid responsive
    const gridContainer = document.createElement('div');
    gridContainer.className = 'videos-grid';
    
    videos.forEach(video => {
        const videoElement = createVideoElement(video);
        gridContainer.appendChild(videoElement);
    });
    
    videosContainer.appendChild(gridContainer);
}

// Función para crear elemento de video
function createVideoElement(video) {
    const videoDiv = document.createElement('div');
    videoDiv.className = 'video-item position-relative';
    videoDiv.setAttribute('data-video-id', video.id);
    
    // Botón de eliminar
    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'delete-video-btn btn btn-danger btn-sm position-absolute top-0 end-0 m-1';
    deleteBtn.setAttribute('data-video-id', video.id);
    deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
    
    // Agregar event listener
    deleteBtn.addEventListener('click', function() {
        const videoId = this.getAttribute('data-video-id');
        const videoElement = this.closest('.video-item');
        
        if (confirm('Are you sure you want to delete this video?')) {
            deleteVideoFromDatabase(videoId, videoElement);
        }
    });
    
    // Agregar iframe
    const iframeContainer = document.createElement('div');
    iframeContainer.innerHTML = video.iframe;
    iframeContainer.classList.add('video-embed');
    
    videoDiv.appendChild(deleteBtn);
    videoDiv.appendChild(iframeContainer);
    
    return videoDiv;
}

// ========== EVENT LISTENERS ==========

$(document).ready(function() {
    console.log('✅ Videos JS loaded');
    
    // Submit del formulario
    $('#videoForm').on('submit', function(e) {
        e.preventDefault();
        console.log('✅ Video form submit intercepted');
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

        $.ajax({
            url: 'add_video.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data) {
                console.log('✅ AJAX success:', data);
                
                if (data.status === "success") {
                    showNotification('✅ ' + data.message, 'success');
                    
                    // Cerrar modal y limpiar formulario
                    $('#addVideoModal').modal('hide');
                    $('#videoForm')[0].reset();
                    
                    // Refrescar lista
                    refreshVideos();
                    
                } else {
                    showNotification('❌ ' + data.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX error:', error);
                showNotification('❌ Error: ' + error, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Submit');
            }
        });
    });
});

// ========== FUNCIÓN DE ELIMINACIÓN ==========

window.deleteVideoFromDatabase = function(videoId, videoElement) {
    console.log('Deleting video:', videoId);
    
    const deleteBtn = videoElement.querySelector('.delete-video-btn');
    const originalHtml = deleteBtn.innerHTML;
    
    deleteBtn.innerHTML = '<i class="bi bi-hourglass"></i>';
    deleteBtn.disabled = true;
    
    videoElement.style.opacity = '0.5';
    
    $.ajax({
        url: 'delete_video.php',
        method: 'POST',
        data: { video_id: videoId },
        dataType: 'json',
        success: function(data) {
            if (data.status === 'success') {
                showNotification('✅ Video deleted successfully!', 'success');
                refreshVideos();
            } else {
                showNotification('❌ Error: ' + data.message, 'error');
                videoElement.style.opacity = '1';
                deleteBtn.innerHTML = originalHtml;
                deleteBtn.disabled = false;
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            showNotification('❌ An error occurred while deleting the video.', 'error');
            videoElement.style.opacity = '1';
            deleteBtn.innerHTML = originalHtml;
            deleteBtn.disabled = false;
        }
    });
};