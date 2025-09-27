// ========== VARIABLE GLOBAL PARA EVENTOS ==========
let globalEvents = [];

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

// Cargar eventos
function refreshEvents() {
    const container = document.querySelector('.events-container');
    if (container) {
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    
    fetch('get_events.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                globalEvents = data.events; // Guardar en variable global
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

// Renderizar eventos
function renderEvents(events) {
    const container = document.querySelector('.events-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (events.length === 0) {
        container.innerHTML = '<div class="text-center text-white py-5"><p>No events yet. Add your first event!</p></div>';
        return;
    }
    
    const eventsGrid = document.createElement('div');
    eventsGrid.className = 'row';
    
    events.forEach(event => {
        const eventElement = createEventElement(event);
        eventsGrid.appendChild(eventElement);
    });
    
    container.appendChild(eventsGrid);
}

// Crear elemento de evento (CORREGIDO)
function createEventElement(event) {
    const colDiv = document.createElement('div');
    colDiv.className = 'col-md-6 col-lg-4 mb-4';
    
    colDiv.innerHTML = `
        <div class="event-item card bg-dark text-white h-100">
            <div class="card-body position-relative">
                <div class="position-absolute top-0 end-0 m-2">
                    <button class="btn btn-sm btn-outline-warning edit-event me-1" data-id="${event.id}" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-event" data-id="${event.id}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                
                <h5 class="event-date text-warning">${formatDate(event.event_date)}</h5>
                <p class="event-time text-muted">${formatTime(event.event_time)}</p>
                <h4 class="event-name">${event.event_name}</h4>
                <p class="event-location">
                    <i class="bi bi-geo-alt"></i> ${event.event_location}
                </p>
            </div>
        </div>
    `;
    
    // Agregar event listeners CORREGIDOS
    const editBtn = colDiv.querySelector('.edit-event');
    const deleteBtn = colDiv.querySelector('.delete-event');
    
    editBtn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        editEvent(id); // Ya no necesita pasar events
    });
    
    deleteBtn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        deleteEvent(id);
    });
    
    return colDiv;
}

// Editar evento (FUNCIÓN CORREGIDA)
function editEvent(id) {
    console.log('Edit event clicked, ID:', id);
    console.log('Global events:', globalEvents);
    
    const event = globalEvents.find(e => e.id == id);
    if (!event) {
        console.error('Event not found with ID:', id);
        showNotification('❌ Event not found', 'error');
        return;
    }
    
    console.log('Found event:', event);
    
    // Llenar el modal
    document.getElementById('edit_id').value = event.id;
    document.getElementById('eventName').value = event.event_name;
    document.getElementById('eventLocation').value = event.event_location;
    document.getElementById('eventDate').value = event.event_date;
    document.getElementById('eventTime').value = event.event_time;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    modal.show();
    document.getElementById('eventModalLabel').textContent = 'Edit Event';
    
    console.log('Modal should be shown now');
}

// Eliminar evento
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
    console.log('DOM loaded, initializing events manager...');
    
    // Set default date to today
    document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
    
    // Set default time to current time + 1 hour
    const now = new Date();
    now.setHours(now.getHours() + 1);
    document.getElementById('eventTime').value = now.toTimeString().substring(0, 5);
    
    // Form submit
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const isEdit = document.getElementById('edit_id').value !== '';
        
        fetch(isEdit ? 'update_event.php' : 'add_event.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification(isEdit ? '✅ Event updated successfully' : '✅ Event added successfully', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                if (modal) modal.hide();
                this.reset();
                
                // Reset default values
                document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
                const now = new Date();
                now.setHours(now.getHours() + 1);
                document.getElementById('eventTime').value = now.toTimeString().substring(0, 5);
                
                refreshEvents();
            } else {
                showNotification('❌ Error: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ Error saving event', 'error');
        });
    });
    
    // Reset modal on hide
    document.getElementById('eventModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('eventForm').reset();
        document.getElementById('edit_id').value = '';
        document.getElementById('eventModalLabel').textContent = 'Add Event';
        
        // Reset default values
        document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
        const now = new Date();
        now.setHours(now.getHours() + 1);
        document.getElementById('eventTime').value = now.toTimeString().substring(0, 5);
    });
    
    // Cargar eventos al iniciar
    refreshEvents();
    
    console.log('Events manager initialized successfully');
});