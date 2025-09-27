// Función simple para mostrar notificaciones
function showAlert(message, type = 'info') {
    alert(message);
}

// Cargar items de press
function loadPressItems() {
    fetch('get_press.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderPressItems(data.press_items);
            } else {
                showAlert('Error loading press items');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error loading press items');
        });
}

// Renderizar items
function renderPressItems(items) {
    const container = document.querySelector('.press-items-container');
    if (!container) return;
    
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
    if (!item) return;
    
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
            showAlert('Item deleted successfully');
            loadPressItems(); // Recargar
        } else {
            showAlert('Error deleting item: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error deleting item');
    });
}

// Configurar el formulario
document.addEventListener('DOMContentLoaded', function() {
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
            showAlert('Please fill all required fields');
            return;
        }
        
        fetch(isEdit ? 'update_press.php' : 'add_press.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showAlert(isEdit ? 'Item updated successfully' : 'Item added successfully');
                const modal = bootstrap.Modal.getInstance(document.getElementById('pressModal'));
                if (modal) modal.hide();
                this.reset();
                document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
                loadPressItems(); // Recargar
            } else {
                showAlert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error saving item');
        });
    });
    
    // Resetear modal cuando se cierra
    document.getElementById('pressModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('pressForm').reset();
        document.getElementById('edit_id').value = '';
        document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
        document.getElementById('pressModalLabel').textContent = 'Add Press Item';
    });
});