// ajax_add_song_simple.js - Solo maneja el formulario, NO renderiza canciones
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ ajax_add_song_simple.js loaded');
    
    const songForm = document.getElementById('songForm');
    if (songForm) {
        songForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('✅ Form submit intercepted');
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

            fetch('ajax_add_song.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('✅ AJAX success:', data);
                
                if (data.status === "success") {
                    showNotification('✅ ' + data.message, 'success');
                    
                    // Cerrar modal y limpiar formulario
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSongModal'));
                    modal.hide();
                    this.reset();
                    
                    // 🔄 REFRESCAR LA LISTA DE CANCIONES llamando a la función global
                    if (typeof window.refreshMusicList === 'function') {
                        window.refreshMusicList();
                    }
                    
                } else {
                    showNotification('❌ ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('❌ AJAX error:', error);
                showNotification('❌ Error: ' + error, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
            });
        });
    }
});

// Función simple de notificación para este script
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