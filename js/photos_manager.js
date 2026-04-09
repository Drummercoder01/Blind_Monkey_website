// ========== VARIABLE GLOBAL ==========
let globalPhotos = [];
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
    
    // Slide in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto dismiss
    setTimeout(() => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }, 4000);
    
    // Click to dismiss
    notification.addEventListener('click', function() {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    });
}

// ========== FUNCIONES PRINCIPALES ==========

function refreshPhotos() {
    const container = document.querySelector('.photos-container');
    if (container) {
        container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading photos...</p>
            </div>
        `;
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
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-camera"></i>
                </div>
                <h3 class="empty-state-title">No Photos Yet</h3>
                <p class="empty-state-text">Add your first photo to get started!</p>
            </div>
        `;
        return;
    }
    
    const photosGrid = document.createElement('div');
    photosGrid.className = 'photos-grid';
    photosGrid.id = 'photosGrid';
    
    photos.forEach(photo => {
        const photoElement = createPhotoElement(photo);
        photosGrid.appendChild(photoElement);
    });
    
    container.appendChild(photosGrid);
}

function createPhotoElement(photo) {
    const photoDiv = document.createElement('div');
    photoDiv.className = 'photo-card-wrapper';
    photoDiv.setAttribute('data-id', photo.id);
    
    // Verificar si es una URL absoluta o relativa
    let imageSrc = photo.img_path;
    if (!imageSrc.startsWith('http') && !imageSrc.startsWith('//')) {
        imageSrc = '../' + imageSrc;
    }
    
    photoDiv.innerHTML = `
        <div class="photo-card">
            <!-- Drag Handle -->
            <div class="photo-drag-handle">
                <i class="bi bi-grip-vertical"></i>
            </div>
            
            <!-- Order Badge -->
            <div class="photo-order-badge">
                <span class="badge">#${photo.img_order}</span>
            </div>
            
            <!-- Delete Button -->
            <button class="photo-delete-btn delete-photo" data-id="${photo.id}" title="Delete photo">
                <i class="bi bi-trash-fill"></i>
            </button>
            
            <!-- Image Container -->
            <div class="photo-image-container">
                <img src="${imageSrc}" 
                     alt="Photo ${photo.id}" 
                     class="photo-image" 
                     onerror="handleImageError(this, ${photo.id})">
            </div>
        </div>
    `;
    
    // Agregar CSS inline para los estilos de las cards
    if (!document.getElementById('photos-cards-styles')) {
        const style = document.createElement('style');
        style.id = 'photos-cards-styles';
        style.textContent = `
            /* Photos Grid */
            .photos-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 25px;
                padding: 0;
            }
            
            @media (max-width: 768px) {
                .photos-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 15px;
                }
            }
            
            @media (max-width: 480px) {
                .photos-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
            }
            
            /* Photo Card Wrapper */
            .photo-card-wrapper {
                cursor: grab;
                transition: all 0.3s ease;
            }
            
            .photo-card-wrapper:active {
                cursor: grabbing;
            }
            
            /* Photo Card */
            .photo-card {
                position: relative;
                background: linear-gradient(135deg, 
                    rgba(0, 0, 0, 0.6) 0%, 
                    rgba(15, 23, 42, 0.8) 100%);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                padding: 20px;
                border-radius: 16px;
                border: 1px solid rgba(38, 227, 255, 0.2);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 
                    0 4px 15px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.05);
                overflow: hidden;
            }
            
            .photo-card:hover {
                transform: translateY(-5px);
                box-shadow: 
                    0 8px 30px rgba(0, 0, 0, 0.4),
                    0 0 25px rgba(38, 227, 255, 0.25),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
                border-color: rgba(38, 227, 255, 0.4);
            }
            
            /* Drag Handle */
            .photo-drag-handle {
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
            
            .photo-drag-handle i {
                font-size: 1.3rem;
                color: rgba(38, 227, 255, 0.9);
            }
            
            .photo-card:hover .photo-drag-handle {
                opacity: 1;
            }
            
            .photo-card-wrapper:active .photo-drag-handle {
                cursor: grabbing;
            }
            
            /* Order Badge */
            .photo-order-badge {
                position: absolute;
                top: 12px;
                left: 15px;
                z-index: 5;
            }
            
            .photo-order-badge .badge {
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
            .photo-delete-btn {
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
            
            .photo-card:hover .photo-delete-btn {
                opacity: 1;
            }
            
            .photo-delete-btn:hover {
                transform: scale(1.1) rotate(5deg);
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                box-shadow: 0 4px 15px rgba(220, 53, 69, 0.6);
            }
            
            .photo-delete-btn i {
                font-size: 1.1rem;
            }
            
            /* Image Container */
            .photo-image-container {
                position: relative;
                width: 100%;
                padding-top: 75%; /* 4:3 aspect ratio */
                border-radius: 12px;
                overflow: hidden;
                background: rgba(0, 0, 0, 0.3);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            }
            
            .photo-image {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 12px;
                transition: transform 0.3s ease;
            }
            
            .photo-card:hover .photo-image {
                transform: scale(1.05);
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
    
    // Agregar event listener para eliminar
    const deleteBtn = photoDiv.querySelector('.delete-photo');
    deleteBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.getAttribute('data-id');
        deletePhoto(id);
    });
    
    return photoDiv;
}

// Función para manejar errores de imagen
function handleImageError(imgElement, photoId) {
    console.error(`Image load error for photo ID: ${photoId}`);
    imgElement.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImciIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCUiIHN0eWxlPSJzdG9wLWNvbG9yOnJnYigzMCw0MCw1MCk7c3RvcC1vcGFjaXR5OjEiIC8+PHN0b3Agb2Zmc2V0PSIxMDAlIiBzdHlsZT0ic3RvcC1jb2xvcjpyZ2IoMTUsMjAsMzApO3N0b3Atb3BhY2l0eToxIiAvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZykiLz48dGV4dCB4PSI1MCUiIHk9IjQ1JSIgZm9udC1mYW1pbHk9IkFyaWFsLHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjAiIGZpbGw9IiMyNmUzZmYiIHRleHQtYW5jaG9yPSJtaWRkbGUiPjxpbnRpdGxlPkltYWdlIEVycm9yPC90aXRsZT7wn5qrPC90ZXh0Pjx0ZXh0IHg9IjUwJSIgeT0iNTglIiBmb250LWZhbWlseT0iQXJpYWwsc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjYpIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5JbWFnZSBub3QgZm91bmQ8L3RleHQ+PC9zdmc+';
    imgElement.alt = 'Image not found';
    showNotification(`⚠️ Image could not be loaded (ID: ${photoId})`, 'warning');
}

function initSortable() {
    const photosGrid = document.getElementById('photosGrid');
    if (!photosGrid) return;
    
    if (sortableInstance) {
        sortableInstance.destroy();
    }
    
    sortableInstance = new Sortable(photosGrid, {
        animation: 200,
        easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        handle: '.photo-card', // Toda la card es draggable
        onStart: function() {
            console.log('🖱️ Drag started');
        },
        onEnd: function() {
            console.log('🖱️ Drag ended');
            // Cambiar botón a estado warning
            const saveBtn = document.getElementById('saveOrderBtn');
            if (saveBtn) {
                saveBtn.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                saveBtn.style.borderColor = '#f59e0b';
                saveBtn.style.color = '#000';
            }
        }
    });
}

function savePhotoOrder() {
    const photoItems = document.querySelectorAll('.photo-card-wrapper');
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
            // Restaurar botón a estado normal
            const saveBtn = document.getElementById('saveOrderBtn');
            if (saveBtn) {
                saveBtn.style.background = '';
                saveBtn.style.borderColor = '';
                saveBtn.style.color = '';
            }
            refreshPhotos();
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

// ========== MODAL HELPER ==========

/**
 * Closes the photo modal safely and removes any orphaned Bootstrap backdrops.
 * Handles the case where modal.hide() doesn't fire cleanup (race conditions,
 * fetch errors, etc.) which leaves .modal-backdrop divs blocking the page.
 */
function closePhotoModal() {
    const modalEl = document.getElementById('photoModal');
    if (!modalEl) return;

    // Try Bootstrap's own hide first
    const bsModal = bootstrap.Modal.getInstance(modalEl);
    if (bsModal) {
        bsModal.hide();
    }

    // Fallback: force-clean regardless (runs after hide animation ~300ms)
    setTimeout(() => {
        // Remove modal open state from body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');

        // Remove the modal's own show state
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.removeAttribute('aria-modal');
        modalEl.setAttribute('aria-hidden', 'true');

        // Nuke every orphaned backdrop
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    }, 320);
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('📸 Photos Manager initialized');

    // Toggle entre upload y link
    document.querySelectorAll('input[name="photo_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const uploadSection = document.getElementById('uploadSection');
            const linkSection = document.getElementById('linkSection');

            if (this.value === 'upload') {
                uploadSection.style.display = 'block';
                linkSection.style.display = 'none';
            } else {
                uploadSection.style.display = 'none';
                linkSection.style.display = 'block';
            }
        });
    });

    // Form submit
    const photoForm = document.getElementById('photoForm');
    if (photoForm) {
        photoForm.addEventListener('submit', function(e) {
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

            showNotification('⏳ Uploading photo...', 'info');

            fetch('add_photo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Guard: if response is not OK or not JSON, throw a readable error
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) {
                    throw new Error('Server returned ' + response.status);
                }
                if (!contentType.includes('application/json')) {
                    throw new Error('Unexpected response type: ' + contentType);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    showNotification('✅ Photo added successfully', 'success');
                    closePhotoModal();
                    photoForm.reset();
                    refreshPhotos();
                } else {
                    showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
                    // Don't close modal on validation error — let user fix and retry
                }
            })
            .catch(error => {
                console.error('Photo upload error:', error);
                showNotification('❌ Error adding photo: ' + error.message, 'error');
                // Always close modal and clean backdrops on unrecoverable errors
                closePhotoModal();
            });
        });
    }

    // Save order button
    const saveOrderBtn = document.getElementById('saveOrderBtn');
    if (saveOrderBtn) {
        saveOrderBtn.addEventListener('click', savePhotoOrder);
    }

    // Reset modal fields when hidden
    const photoModal = document.getElementById('photoModal');
    if (photoModal) {
        photoModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('photoForm').reset();
            document.getElementById('uploadSection').style.display = 'block';
            document.getElementById('linkSection').style.display = 'none';
            document.getElementById('methodUpload').checked = true;
            // Extra safety: remove any stray backdrops after hide animation
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        });
    }

    // Cargar fotos al iniciar
    refreshPhotos();
});
