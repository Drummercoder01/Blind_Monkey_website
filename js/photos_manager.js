// ========== VARIABLE GLOBAL ==========
let globalPhotos = [];
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

function refreshPhotos() {
    const container = document.querySelector('.photos-container');
    if (container) {
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    fetch('get_photos.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalPhotos = data.photos;
                renderPhotos(data.photos);
                initSortable();
            } else {
                showNotification('❌ Error loading photos', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error loading photos', 'error');
        });
}

function renderPhotos(photos) {
    const container = document.querySelector('.photos-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (photos.length === 0) {
        container.innerHTML = '<div class="text-center text-white py-5"><p>No photos yet. Add your first photo!</p></div>';
        return;
    }
    
    const photosGrid = document.createElement('div');
    photosGrid.className = 'row photos-grid';
    photosGrid.id = 'photosGrid';
    
    photos.forEach(photo => {
        const photoElement = createPhotoElement(photo);
        photosGrid.appendChild(photoElement);
    });
    
    container.appendChild(photosGrid);
}

function createPhotoElement(photo) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col-md-4 col-lg-3 mb-4 photo-item';
    colDiv.setAttribute('data-id', photo.id);
    
    // Verificar si es una URL absoluta o relativa
    let imageSrc = photo.img_path;
    if (!imageSrc.startsWith('http') && !imageSrc.startsWith('//')) {
        // Si es una ruta relativa, agregar la base URL
        imageSrc = '../' + imageSrc;
    }
    
    colDiv.innerHTML = `
        <div class="card photo-card h-100">
            <div class="card-body p-2 position-relative">
                <div class="position-absolute top-0 end-0 m-1">
                    <button class="btn btn-sm btn-outline-danger delete-photo" data-id="${photo.id}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                
                <div class="photo-thumbnail">
                    <img src="${imageSrc}" alt="Photo ${photo.id}" 
                         class="img-fluid rounded" 
                         onerror="handleImageError(this, ${photo.id})"
                         style="max-height: 200px; object-fit: cover; width: 100%;">
                </div>
                
                <div class="photo-order-badge">
                    <span class="badge bg-primary">#${photo.img_order}</span>
                </div>
            </div>
        </div>
    `;
    
    // Agregar event listener para eliminar
    const deleteBtn = colDiv.querySelector('.delete-photo');
    deleteBtn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        deletePhoto(id);
    });
    
    return colDiv;
}

// Función para manejar errores de imagen
function handleImageError(imgElement, photoId) {
    console.error(`Image load error for photo ID: ${photoId}`);
    imgElement.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMzMzIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iI2ZmZiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIEVycm9yPC90ZXh0Pjwvc3ZnPg==';
    imgElement.alt = 'Image not found';
    
    // Mostrar notificación de error
    showNotification('⚠️ Image could not be loaded (ID: ' + photoId + ')', 'error');
}

function initSortable() {
    const photosGrid = document.getElementById('photosGrid');
    if (!photosGrid) return;
    
    if (sortableInstance) {
        sortableInstance.destroy();
    }
    
    sortableInstance = new Sortable(photosGrid, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function() {
            // Habilitar botón de guardar orden
            document.getElementById('saveOrderBtn').classList.remove('btn-outline-light');
            document.getElementById('saveOrderBtn').classList.add('btn-warning');
        }
    });
}

function savePhotoOrder() {
    const photoItems = document.querySelectorAll('.photo-item');
    const photoOrder = Array.from(photoItems).map(item => item.getAttribute('data-id'));
    
    const formData = new FormData();
    formData.append('photo_order', JSON.stringify(photoOrder));
    
    fetch('update_photo_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Photo order saved successfully', 'success');
            document.getElementById('saveOrderBtn').classList.remove('btn-warning');
            document.getElementById('saveOrderBtn').classList.add('btn-outline-light');
            refreshPhotos(); // Recargar para actualizar números de orden
        } else {
            showNotification('❌ Error saving order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error saving order', 'error');
    });
}

function deletePhoto(id) {
    if (!confirm('Are you sure you want to delete this photo?')) return;
    
    const formData = new FormData();
    formData.append('photo_id', id);
    
    fetch('delete_photo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Photo deleted successfully', 'success');
            refreshPhotos();
        } else {
            showNotification('❌ Error deleting photo', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error deleting photo', 'error');
    });
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    // Toggle entre upload y link
    document.querySelectorAll('input[name="photo_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('uploadSection').style.display = 
                this.value === 'upload' ? 'block' : 'none';
            document.getElementById('linkSection').style.display = 
                this.value === 'link' ? 'block' : 'none';
        });
    });
    
    // Form submit
    document.getElementById('photoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const method = document.querySelector('input[name="photo_method"]:checked').value;
        
        if (method === 'upload' && !document.getElementById('photoFile').files[0]) {
            showNotification('❌ Please select a file to upload', 'error');
            return;
        }
        
        if (method === 'link' && !document.getElementById('photoLink').value) {
            showNotification('❌ Please enter an image URL', 'error');
            return;
        }
        
        fetch('add_photo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification('✅ Photo added successfully', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('photoModal'));
                if (modal) modal.hide();
                this.reset();
                refreshPhotos();
            } else {
                showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error adding photo', 'error');
        });
    });
    
    // Save order button
    document.getElementById('saveOrderBtn').addEventListener('click', savePhotoOrder);
    
    // Reset modal on hide
    document.getElementById('photoModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('photoForm').reset();
        document.getElementById('uploadSection').style.display = 'block';
        document.getElementById('linkSection').style.display = 'none';
        document.getElementById('methodUpload').checked = true;
    });
    
    // Cargar fotos al iniciar
    refreshPhotos();
});