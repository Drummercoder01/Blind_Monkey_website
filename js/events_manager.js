// ========== VARIABLE GLOBAL PARA EVENTOS ==========
let globalEvents = [];

// ========== LIMPIEZA DE BACKDROP RESIDUAL (Bootstrap 5 bug) ==========
function cleanupModalBackdrop() {
    // Eliminar todos los backdrops huérfanos
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    // Restaurar scroll del body
    document.body.classList.remove('modal-open');
    document.body.style.overflow   = '';
    document.body.style.paddingRight = '';
}

// ========== SISTEMA DE NOTIFICACIONES GLASSMORPHISM ==========
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    
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

// ========== FUNCIONES DE UTILIDAD ==========

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const formattedHour = hour % 12 || 12;
    return `${formattedHour}:${minutes} ${ampm}`;
}

function getDefaultDate() {
    return new Date().toISOString().split('T')[0];
}

function getDefaultTime() {
    const now = new Date();
    now.setHours(now.getHours() + 1);
    return now.toTimeString().substring(0, 5);
}

// ========== FUNCIONES PRINCIPALES ==========

function refreshEvents() {
    const container = document.querySelector('.events-container');
    if (container) {
        container.innerHTML = `
            <div class="loading-container">
                <div class="loading-spinner"></div>
                <p class="loading-text">Loading events...</p>
            </div>
        `;
    }
    
    fetch('get_events.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalEvents = data.events;
                renderEvents(data.events);
            } else {
                showNotification('❌ Error loading events', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error loading events', 'error');
        });
}

function renderEvents(events) {
    const container = document.querySelector('.events-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (events.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h3 class="empty-state-title">No Events Scheduled</h3>
                <p class="empty-state-text">Add your first upcoming show or concert!</p>
            </div>
        `;
        return;
    }
    
    const eventsGrid = document.createElement('div');
    eventsGrid.className = 'events-grid';
    
    events.forEach(event => {
        eventsGrid.appendChild(createEventElement(event));
    });
    
    container.appendChild(eventsGrid);
    injectEventStyles();
}

function createEventElement(event) {
    const eventDiv = document.createElement('div');
    eventDiv.className = 'event-card-wrapper';
    eventDiv.setAttribute('data-id', event.id);
    
    // Determinar si el evento es pasado, hoy o futuro
    const eventDate = new Date(event.event_date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    eventDate.setHours(0, 0, 0, 0);
    
    const isPast    = eventDate < today;
    const isToday   = eventDate.getTime() === today.getTime();
    const statusClass = isPast ? 'event-past' : isToday ? 'event-today' : 'event-upcoming';
    const statusLabel = isPast ? 'Past' : isToday ? 'Today' : 'Upcoming';
    const statusColor = isPast
        ? 'rgba(255, 255, 255, 0.4)'
        : isToday
        ? '#f59e0b'
        : '#26e3ff';
    
    eventDiv.innerHTML = `
        <div class="event-card ${statusClass}">
            <!-- Action Buttons -->
            <div class="event-actions">
                <button class="event-btn-edit edit-event" data-id="${event.id}" title="Edit event">
                    <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="event-btn-delete delete-event" data-id="${event.id}" title="Delete event">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
            
            <!-- Status Badge -->
            <div class="event-status-badge" style="background: ${statusColor}20; border-color: ${statusColor}; color: ${statusColor};">
                ${statusLabel}
            </div>
            
            <!-- Date Block -->
            <div class="event-date-block">
                <div class="event-day">${eventDate.getDate()}</div>
                <div class="event-month">${eventDate.toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}</div>
            </div>
            
            <!-- Divider -->
            <div class="event-divider"></div>
            
            <!-- Event Info -->
            <div class="event-info">
                <h4 class="event-name">${event.event_name}</h4>
                <p class="event-location">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    ${event.event_location}
                </p>
                <p class="event-time-label">
                    <i class="bi bi-clock-fill me-1"></i>
                    ${formatTime(event.event_time)}
                </p>
            </div>
        </div>
    `;
    
    eventDiv.querySelector('.edit-event').addEventListener('click', () => editEvent(event.id));
    eventDiv.querySelector('.delete-event').addEventListener('click', () => deleteEvent(event.id));
    
    return eventDiv;
}

function injectEventStyles() {
    if (document.getElementById('events-cards-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'events-cards-styles';
    style.textContent = `
        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 1rem 0 2rem;
        }
        
        @media (max-width: 768px) {
            .events-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        /* Event Card */
        .event-card {
            position: relative;
            background: linear-gradient(135deg,
                rgba(0, 0, 0, 0.6) 0%,
                rgba(15, 23, 42, 0.8) 100%);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(38, 227, 255, 0.2);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }
        
        .event-card::before {
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
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 8px 30px rgba(0, 0, 0, 0.4),
                0 0 25px rgba(38, 227, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-color: rgba(38, 227, 255, 0.4);
        }
        
        .event-card:hover::before {
            opacity: 1;
        }
        
        /* Past event dimmed */
        .event-past {
            opacity: 0.6;
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        .event-past::before {
            background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
        }
        
        /* Today event highlight */
        .event-today {
            border-color: rgba(245, 158, 11, 0.5);
        }
        
        .event-today::before {
            background: linear-gradient(90deg, #f59e0b, #d97706);
            opacity: 1;
        }
        
        /* Action Buttons */
        .event-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
        }
        
        .event-card:hover .event-actions {
            opacity: 1;
        }
        
        .event-btn-edit,
        .event-btn-delete {
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
        
        .event-btn-edit {
            background: linear-gradient(135deg, rgba(38, 227, 255, 0.2), rgba(26, 159, 184, 0.2));
            border: 1px solid rgba(38, 227, 255, 0.4);
            color: #26e3ff;
        }
        
        .event-btn-edit:hover {
            background: linear-gradient(135deg, rgba(38, 227, 255, 0.4), rgba(26, 159, 184, 0.4));
            transform: scale(1.1);
            box-shadow: 0 2px 10px rgba(38, 227, 255, 0.4);
        }
        
        .event-btn-delete {
            background: linear-gradient(135deg, #dc3545, #b91c1c);
            border: none;
            color: white;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        }
        
        .event-btn-delete:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.6);
        }
        
        /* Status Badge */
        .event-status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            border: 1px solid;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }
        
        /* Date Block */
        .event-date-block {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .event-day {
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            line-height: 1;
        }
        
        .event-month {
            font-size: 1rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Divider */
        .event-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(38, 227, 255, 0.4), transparent);
            margin-bottom: 1rem;
        }
        
        /* Event Info */
        .event-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin: 0 0 0.5rem;
            line-height: 1.3;
        }
        
        .event-location,
        .event-time-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 0 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .event-location i,
        .event-time-label i {
            color: #26e3ff;
            font-size: 0.85rem;
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

// ========== EDITAR EVENTO ==========

function editEvent(id) {
    const event = globalEvents.find(e => e.id == id);
    if (!event) {
        showNotification('❌ Event not found', 'error');
        return;
    }
    
    document.getElementById('edit_id').value       = event.id;
    document.getElementById('eventName').value     = event.event_name;
    document.getElementById('eventLocation').value = event.event_location;
    document.getElementById('eventDate').value     = event.event_date;
    document.getElementById('eventTime').value     = event.event_time;
    
    // Cambiar título e ícono del modal
    const modalLabel = document.getElementById('eventModalLabel');
    if (modalLabel) {
        modalLabel.innerHTML = '<i class="bi bi-pencil-fill me-2"></i>Edit Event';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    modal.show();
}

// ========== ELIMINAR EVENTO ==========

function deleteEvent(id) {
    if (!confirm('Are you sure you want to delete this event?')) return;
    
    const formData = new FormData();
    formData.append('event_id', id);
    
    fetch('delete_event.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification('✅ Event deleted successfully', 'success');
            refreshEvents();
        } else {
            showNotification('❌ Error deleting event', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error deleting event', 'error');
    });
}

// ========== EVENT LISTENERS ==========

document.addEventListener('DOMContentLoaded', function() {
    console.log('📅 Events Manager initialized');
    
    // Defaults de fecha/hora
    document.getElementById('eventDate').value = getDefaultDate();
    document.getElementById('eventTime').value = getDefaultTime();
    
    // Form submit (add + edit)
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit   = document.getElementById('edit_id').value !== '';
        
        showNotification(isEdit ? '⏳ Updating event...' : '⏳ Adding event...', 'info');
        
        fetch(isEdit ? 'update_event.php' : 'add_event.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(
                    isEdit ? '✅ Event updated successfully' : '✅ Event added successfully',
                    'success'
                );
                // Cerrar modal y limpiar backdrop
                const modalEl = document.getElementById('eventModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                } else {
                    // Fallback: cerrar manualmente si getInstance falla
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    cleanupModalBackdrop();
                }
                this.reset();
                document.getElementById('eventDate').value = getDefaultDate();
                document.getElementById('eventTime').value = getDefaultTime();
            } else {
                showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error saving event', 'error');
        });
    });
    
    // Reset modal al cerrar — y limpiar backdrop por si quedó pegado
    document.getElementById('eventModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('eventForm').reset();
        document.getElementById('edit_id').value = '';

        const modalLabel = document.getElementById('eventModalLabel');
        if (modalLabel) {
            modalLabel.innerHTML = '<i class="bi bi-calendar-event me-2"></i>Add Event';
        }

        document.getElementById('eventDate').value = getDefaultDate();
        document.getElementById('eventTime').value = getDefaultTime();

        // Limpiar backdrop residual (bug conocido de Bootstrap 5)
        cleanupModalBackdrop();

        refreshEvents();
    });

    // Cargar eventos (solo aquí — el script inline ya no llama refreshEvents)
    refreshEvents();
});
