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

// Cargar items de press
function loadPressItems() {
    const container = document.querySelector('.press-items-container');
    if (container) {
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    fetch('get_press.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderPressItems(data.press_items);
            } else {
                showNotification('❌ Error loading press items', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error loading press items', 'error');
        });
}

// Renderizar items
function renderPressItems(items) {
    const container = document.querySelector('.press-items-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (items.length === 0) {
        container.innerHTML = '<div class="text-center text-white py-5"><p>No press items yet. Add your first press item!</p></div>';
        return;
    }
    
    let html = '<div class="row">';
    
    items.forEach(item => {
        html += `
        <div class="col-md-6 mb-4">
            <div class="press-item card bg-dark text-white h-100 position-relative">
                <!-- Botones de acción en esquina superior derecha -->
                <div class="position-absolute top-0 end-0 m-2">
                    <button class="btn btn-sm btn-outline-warning edit-btn me-1" data-id="${item.id}" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${item.id}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                
                <div class="card-body">
                    <h6 class="text-muted mb-3">#${item.id}</h6>
                    <p class="text-white fst-italic press-excerpt">"${item.press_text}"</p>
                    <p class="text-muted author-name"><strong>${item.press_author}</strong></p>
                    ${item.press_comment ? `<p class="text-muted small comment-text">${item.press_comment}</p>` : ''}
                    ${item.press_link ? `
                    <div class="mt-2">
                        <a href="${item.press_link}" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-box-arrow-up-right me-1"></i>View Article
                        </a>
                    </div>
                    ` : ''}
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>${item.press_time}
                        </small>
                    </div>
                </div>
            </div>
        </div>`;
    });
    
    html += '</div>';
    container.innerHTML = html;
    
    // Agregar event listeners
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            editPressItem(id, items);
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            deletePressItem(id);
        });
    });
}

// Editar item
function editPressItem(id, items) {
    // Encontrar el item en los datos cargados
    const item = items.find(i => i.id == id);
    if (!item) {
        showNotification('❌ Press item not found', 'error');
        return;
    }
    
    // Llenar el modal con todos los datos
    document.getElementById('edit_id').value = item.id;
    document.getElementById('pressText').value = item.press_text;
    document.getElementById('pressAuthor').value = item.press_author;
    document.getElementById('pressComment').value = item.press_comment || '';
    document.getElementById('pressLink').value = item.press_link || '';
    document.getElementById('pressTime').value = item.press_time;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('pressModal'));
    modal.show();
    document.getElementById('pressModalLabel').textContent = 'Edit Press Item';
}

// Eliminar item
function deletePressItem(id) {
    if (!confirm('Are you sure you want to delete this press item?')) return;
    
    const formData = new FormData();
    formData.append('press_id', id);
    
    fetch('delete_press.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Press item deleted successfully', 'success');
            loadPressItems(); // Recargar
        } else {
            showNotification('❌ Error deleting press item', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error deleting press item', 'error');
    });
}

// Configurar el formulario
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing press manager...');
    
    // Inicializar
    loadPressItems();
    
    // Set today's date as default for new items
    document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
    
    // Manejar envío del formulario
    document.getElementById('pressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = document.getElementById('edit_id').value !== '';
        
        // Validación básica
        if (!formData.get('press_text') || !formData.get('press_author') || !formData.get('press_time')) {
            showNotification('❌ Please fill all required fields', 'error');
            return;
        }
        
        fetch(isEdit ? 'update_press.php' : 'add_press.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(isEdit ? '✅ Press item updated successfully' : '✅ Press item added successfully', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('pressModal'));
                if (modal) modal.hide();
                this.reset();
                document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
                loadPressItems(); // Recargar
            } else {
                showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error saving press item', 'error');
        });
    });
    
    // Resetear modal cuando se cierra
    document.getElementById('pressModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('pressForm').reset();
        document.getElementById('edit_id').value = '';
        document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
        document.getElementById('pressModalLabel').textContent = 'Add Press Item';
    });
    
    console.log('Press manager initialized successfully');
});