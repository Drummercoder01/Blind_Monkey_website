// ========== FUNCIONES DE UTILIDAD ==========

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

// Función para refrescar la lista de press items
function refreshPressItems() {
    console.log('🔄 Refreshing press items...');
    
    const pressContainer = document.querySelector('.press-items-container');
    if (pressContainer) {
        pressContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    $.ajax({
        url: 'get_press.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.status === 'success') {
                renderPressItems(data.press_items);
            } else {
                console.error('Error getting press items:', data.message);
                showNotification('❌ Error loading press items', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching press items:', error);
            showNotification('❌ Error loading press items', 'error');
        }
    });
}

// Función para renderizar los press items
function renderPressItems(pressItems) {
    const pressContainer = document.querySelector('.press-items-container');
    if (!pressContainer) return;
    
    pressContainer.innerHTML = '';
    
    if (pressItems.length === 0) {
        pressContainer.innerHTML = '<div class="text-center text-white py-5"><p>No press items yet. Add your first press item!</p></div>';
        return;
    }
    
    const pressItemsDiv = document.createElement('div');
    pressItemsDiv.className = 'row press-items';
    
    pressItems.forEach(item => {
        const pressElement = createPressElement(item);
        pressItemsDiv.appendChild(pressElement);
    });
    
    pressContainer.appendChild(pressItemsDiv);
}

// Función para crear elemento de press
function createPressElement(item) {
    const pressDiv = document.createElement('div');
    pressDiv.className = 'press-item col-lg-6 mb-4';
    pressDiv.setAttribute('data-item-id', item.id);
    
    const pressContent = `
        <div class="press-item-content bg-dark p-4 rounded h-100 position-relative">
            <button class="delete-press-btn btn btn-danger btn-sm position-absolute top-0 end-0 m-2" 
                    data-press-id="${item.id}">
                <i class="bi bi-trash"></i>
            </button>
            <div class="press-excerpt text-white fs-5 fst-italic">
                "${item.press_text}"
            </div>
            <div class="press-author text-gray-custom fw-bold py-3">
                — <span class="press-name">${item.press_author}</span>
                ${item.press_comment ? ', ' + item.press_comment : ''}
            </div>
            <div class="press-link">
                ${item.press_link ? `
                <a href="${item.press_link}" target="_blank">
                    <div class="press-time text-gray-custom">
                        ${formatDate(item.press_time)}
                    </div>
                </a>
                ` : `
                <div class="press-time text-gray-custom">
                    ${formatDate(item.press_time)}
                </div>
                `}
            </div>
        </div>
    `;
    
    pressDiv.innerHTML = pressContent;
    
    // Agregar event listener al botón de eliminar
    const deleteBtn = pressDiv.querySelector('.delete-press-btn');
    deleteBtn.addEventListener('click', function() {
        const pressId = this.getAttribute('data-press-id');
        const pressElement = this.closest('.press-item');
        
        if (confirm('Are you sure you want to delete this press item?')) {
            deletePressFromDatabase(pressId, pressElement);
        }
    });
    
    return pressDiv;
}

// Función para formatear fecha
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

// ========== EVENT LISTENERS ==========

$(document).ready(function() {
    console.log('✅ Press JS loaded');
    
    // Set today's date as default
    $('#pressTime').val(new Date().toISOString().split('T')[0]);
    
    // Submit del formulario
    $('#pressForm').on('submit', function(e) {
        e.preventDefault();
        console.log('✅ Press form submit intercepted');
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');

        $.ajax({
            url: 'add_press.php',
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
                    $('#addPressModal').modal('hide');
                    $('#pressForm')[0].reset();
                    $('#pressTime').val(new Date().toISOString().split('T')[0]);
                    
                    // Refrescar lista
                    refreshPressItems();
                    
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

window.deletePressFromDatabase = function(pressId, pressElement) {
    console.log('Deleting press item:', pressId);
    
    const deleteBtn = pressElement.querySelector('.delete-press-btn');
    const originalHtml = deleteBtn.innerHTML;
    
    deleteBtn.innerHTML = '<i class="bi bi-hourglass"></i>';
    deleteBtn.disabled = true;
    
    pressElement.style.opacity = '0.5';
    
    $.ajax({
        url: 'delete_press.php',
        method: 'POST',
        data: { press_id: pressId },
        dataType: 'json',
        success: function(data) {
            if (data.status === 'success') {
                showNotification('✅ Press item deleted successfully!', 'success');
                refreshPressItems();
            } else {
                showNotification('❌ Error: ' + data.message, 'error');
                pressElement.style.opacity = '1';
                deleteBtn.innerHTML = originalHtml;
                deleteBtn.disabled = false;
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            showNotification('❌ An error occurred while deleting the press item.', 'error');
            pressElement.style.opacity = '1';
            deleteBtn.innerHTML = originalHtml;
            deleteBtn.disabled = false;
        }
    });
};