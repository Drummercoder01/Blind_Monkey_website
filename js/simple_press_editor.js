// ========== VARIABLE GLOBAL ==========
let globalPressItems = [];

// ========== SISTEMA DE NOTIFICACIONES GLASSMORPHISM ==========
function showNotification(message, type = 'info') {
    const icons = {
        success: 'bi-check-circle-fill',
        error:   'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info:    'bi-info-circle-fill'
    };
    
    const colors = {
        success: {
            bg:     'linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%)',
            border: 'rgba(16, 185, 129, 0.6)',
            shadow: 'rgba(16, 185, 129, 0.4)'
        },
        error: {
            bg:     'linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%)',
            border: 'rgba(239, 68, 68, 0.6)',
            shadow: 'rgba(239, 68, 68, 0.4)'
        },
        warning: {
            bg:     'linear-gradient(135deg, rgba(251, 146, 60, 0.95) 0%, rgba(249, 115, 22, 0.95) 100%)',
            border: 'rgba(251, 146, 60, 0.6)',
            shadow: 'rgba(251, 146, 60, 0.4)'
        },
        info: {
            bg:     'linear-gradient(135deg, rgba(38, 227, 255, 0.95) 0%, rgba(26, 159, 184, 0.95) 100%)',
            border: 'rgba(38, 227, 255, 0.6)',
            shadow: 'rgba(38, 227, 255, 0.4)'
        }
    };
    
    const color = colors[type] || colors.info;
    const notification = document.createElement('div');
    
    notification.innerHTML = `
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <i class="bi ${icons[type] || icons.info}" style="font-size:1.25rem; flex-shrink:0;"></i>
            <span>${message}</span>
        </div>
    `;
    
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
    notification.addEventListener('click', () => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => notification.parentNode?.removeChild(notification), 400);
    });
}

// ========== CARGAR ITEMS ==========

function loadPressItems() {
    const container = document.querySelector('.press-items-container');
    if (container) {
        container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading press items...</p>
            </div>
        `;
    }
    
    fetch('get_press.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalPressItems = data.press_items;
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

// ========== RENDERIZAR ITEMS ==========

function renderPressItems(items) {
    const container = document.querySelector('.press-items-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (items.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-newspaper"></i>
                </div>
                <h3 class="empty-state-title">No Press Items Yet</h3>
                <p class="empty-state-text">Add your first press coverage or review!</p>
            </div>
        `;
        injectPressStyles();
        return;
    }
    
    const grid = document.createElement('div');
    grid.className = 'press-grid';
    
    items.forEach(item => grid.appendChild(createPressCard(item)));
    
    container.appendChild(grid);
    injectPressStyles();
}

// ========== CREAR CARD ==========

function createPressCard(item) {
    const cardDiv = document.createElement('div');
    cardDiv.className = 'press-card-wrapper';
    cardDiv.setAttribute('data-id', item.id);
    
    const dateFormatted = item.press_time
        ? new Date(item.press_time).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
        : '';
    
    cardDiv.innerHTML = `
        <div class="press-card">
            <!-- Action Buttons -->
            <div class="press-actions">
                <button class="press-btn-edit edit-btn" data-id="${item.id}" title="Edit">
                    <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="press-btn-delete delete-btn" data-id="${item.id}" title="Delete">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
            
            <!-- Quote Icon -->
            <div class="press-quote-icon">
                <i class="bi bi-quote"></i>
            </div>
            
            <!-- Excerpt -->
            <p class="press-excerpt">"${item.press_text}"</p>
            
            <!-- Divider -->
            <div class="press-divider"></div>
            
            <!-- Footer -->
            <div class="press-footer">
                <div class="press-author-block">
                    <span class="press-author">${item.press_author}</span>
                    ${item.press_comment ? `<span class="press-comment">${item.press_comment}</span>` : ''}
                </div>
                
                <div class="press-meta">
                    ${dateFormatted ? `
                    <span class="press-date">
                        <i class="bi bi-calendar3 me-1"></i>${dateFormatted}
                    </span>` : ''}
                    ${item.press_link ? `
                    <a href="${item.press_link}" target="_blank" class="press-link-btn">
                        <i class="bi bi-box-arrow-up-right me-1"></i>View Article
                    </a>` : ''}
                </div>
            </div>
        </div>
    `;
    
    cardDiv.querySelector('.edit-btn').addEventListener('click', () => editPressItem(item.id));
    cardDiv.querySelector('.delete-btn').addEventListener('click', () => deletePressItem(item.id));
    
    return cardDiv;
}

// ========== ESTILOS INYECTADOS ==========

function injectPressStyles() {
    if (document.getElementById('press-cards-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'press-cards-styles';
    style.textContent = `
        /* Press Grid */
        .press-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 25px;
            padding: 1rem 0 2rem;
        }
        
        @media (max-width: 768px) {
            .press-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        /* Press Card */
        .press-card {
            position: relative;
            background: linear-gradient(135deg,
                rgba(0, 0, 0, 0.6) 0%,
                rgba(15, 23, 42, 0.8) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(38, 227, 255, 0.2);
            border-radius: 16px;
            padding: 2rem 1.75rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }
        
        .press-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #26e3ff, #1a9fb8);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .press-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 8px 30px rgba(0, 0, 0, 0.4),
                0 0 25px rgba(38, 227, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-color: rgba(38, 227, 255, 0.4);
        }
        
        .press-card:hover::before {
            opacity: 1;
        }
        
        /* Action Buttons */
        .press-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }
        
        .press-card:hover .press-actions {
            opacity: 1;
        }
        
        .press-btn-edit,
        .press-btn-delete {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }
        
        .press-btn-edit {
            background: linear-gradient(135deg, rgba(38, 227, 255, 0.2), rgba(26, 159, 184, 0.2));
            border: 1px solid rgba(38, 227, 255, 0.4);
            color: #26e3ff;
        }
        
        .press-btn-edit:hover {
            background: linear-gradient(135deg, rgba(38, 227, 255, 0.4), rgba(26, 159, 184, 0.4));
            transform: scale(1.1);
            box-shadow: 0 2px 10px rgba(38, 227, 255, 0.4);
        }
        
        .press-btn-delete {
            background: linear-gradient(135deg, #dc3545, #b91c1c);
            color: white;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        }
        
        .press-btn-delete:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.6);
        }
        
        /* Quote Icon */
        .press-quote-icon {
            font-size: 3rem;
            line-height: 1;
            color: rgba(38, 227, 255, 0.25);
            margin-bottom: 0.75rem;
        }
        
        /* Excerpt */
        .press-excerpt {
            font-size: 1rem;
            font-style: italic;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin: 0 0 1.25rem;
        }
        
        /* Divider */
        .press-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(38, 227, 255, 0.4), transparent);
            margin-bottom: 1.25rem;
        }
        
        /* Footer */
        .press-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        /* Author Block */
        .press-author-block {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .press-author {
            font-size: 0.95rem;
            font-weight: 700;
            color: #26e3ff;
            letter-spacing: 0.3px;
        }
        
        .press-comment {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Meta */
        .press-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }
        
        .press-date {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            white-space: nowrap;
        }
        
        .press-date i {
            color: rgba(38, 227, 255, 0.7);
        }
        
        /* View Article Link */
        .press-link-btn {
            display: inline-flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: #26e3ff;
            text-decoration: none;
            padding: 0.3rem 0.75rem;
            border: 1px solid rgba(38, 227, 255, 0.3);
            border-radius: 20px;
            background: rgba(38, 227, 255, 0.08);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .press-link-btn:hover {
            background: rgba(38, 227, 255, 0.2);
            border-color: rgba(38, 227, 255, 0.6);
            color: #26e3ff;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(38, 227, 255, 0.2);
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

// ========== EDITAR ITEM ==========

function editPressItem(id) {
    const item = globalPressItems.find(i => i.id == id);
    if (!item) {
        showNotification('❌ Press item not found', 'error');
        return;
    }
    
    document.getElementById('edit_id').value      = item.id;
    document.getElementById('pressText').value    = item.press_text;
    document.getElementById('pressAuthor').value  = item.press_author;
    document.getElementById('pressComment').value = item.press_comment || '';
    document.getElementById('pressLink').value    = item.press_link || '';
    document.getElementById('pressTime').value    = item.press_time;
    
    const modalLabel = document.getElementById('pressModalLabel');
    if (modalLabel) {
        modalLabel.innerHTML = '<i class="bi bi-pencil-fill me-2"></i>Edit Press Item';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('pressModal'));
    modal.show();
}

// ========== ELIMINAR ITEM ==========

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
            loadPressItems();
        } else {
            showNotification('❌ Error deleting press item', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error deleting press item', 'error');
    });
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('📰 Press Manager initialized');
    
    // Fecha default de hoy
    document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
    
    // Cargar items
    loadPressItems();
    
    // Form submit (add + edit)
    document.getElementById('pressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit   = document.getElementById('edit_id').value !== '';
        
        if (!formData.get('press_text') || !formData.get('press_author') || !formData.get('press_time')) {
            showNotification('❌ Please fill all required fields', 'error');
            return;
        }
        
        showNotification(isEdit ? '⏳ Updating press item...' : '⏳ Adding press item...', 'info');
        
        fetch(isEdit ? 'update_press.php' : 'add_press.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(
                    isEdit ? '✅ Press item updated successfully' : '✅ Press item added successfully',
                    'success'
                );
                const modal = bootstrap.Modal.getInstance(document.getElementById('pressModal'));
                if (modal) modal.hide();
                this.reset();
                document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
                loadPressItems();
            } else {
                showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error saving press item', 'error');
        });
    });
    
    // Reset modal al cerrar
    document.getElementById('pressModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('pressForm').reset();
        document.getElementById('edit_id').value = '';
        document.getElementById('pressTime').value = new Date().toISOString().split('T')[0];
        
        const modalLabel = document.getElementById('pressModalLabel');
        if (modalLabel) {
            modalLabel.innerHTML = '<i class="bi bi-newspaper me-2"></i>Add Press Item';
        }
    });
});
