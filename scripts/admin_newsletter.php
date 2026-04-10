<?php
try {
    require(__DIR__ . "/../code/initialisatie.inc.php");
    
    global $_PDO;
    
    // Variable para mensajes
    $_mensaje = "";
    
    // Handle actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'delete':
                    $id = intval($_POST['id']);
                    $stmt = $_PDO->prepare("DELETE FROM t_newsletter WHERE id = ?");
                    $stmt->execute([$id]);
                    $_mensaje = "<div class='alert-success'>
                        <i class='bi bi-check-circle-fill me-2'></i>
                        <strong>Subscriber deleted successfully!</strong>
                    </div>";
                    break;
                    
                case 'delete_multiple':
                    $ids = $_POST['ids'] ?? [];
                    if (!empty($ids)) {
                        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
                        $stmt = $_PDO->prepare("DELETE FROM t_newsletter WHERE id IN ($placeholders)");
                        $stmt->execute($ids);
                        $_mensaje = "<div class='alert-success'>
                            <i class='bi bi-check-circle-fill me-2'></i>
                            <strong>" . count($ids) . " subscriber(s) deleted successfully!</strong>
                        </div>";
                    }
                    break;
                    
                case 'export_csv':
                    // Export to CSV
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="newsletter_subscribers_' . date('Y-m-d') . '.csv"');
                    
                    $output = fopen('php://output', 'w');
                    fputcsv($output, ['ID', 'Email', 'Subscribed Date', 'Status']);
                    
                    $subscribers = $_PDO->query("SELECT * FROM t_newsletter ORDER BY subscribed_at DESC")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($subscribers as $sub) {
                        fputcsv($output, [$sub['id'], $sub['email'], $sub['subscribed_at'], $sub['status']]);
                    }
                    
                    fclose($output);
                    exit;
                    break;
            }
        }
    }
    
    // Get stats
    $stats = $_PDO->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN DATE(subscribed_at) = CURDATE() THEN 1 ELSE 0 END) as today
        FROM t_newsletter
    ")->fetch(PDO::FETCH_ASSOC);
    
    // Get all subscribers with pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $perPage = 20;
    $offset = ($page - 1) * $perPage;
    
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $whereClause = $search ? "WHERE email LIKE :search" : "";
    
    $subscribersQuery = "SELECT * FROM t_newsletter $whereClause ORDER BY subscribed_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $_PDO->prepare($subscribersQuery);
    if ($search) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Total pages
    $totalQuery = "SELECT COUNT(*) as total FROM t_newsletter $whereClause";
    $stmt = $_PDO->prepare($totalQuery);
    if ($search) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $perPage);
    
    // Build content
    $_inhoud = "
    <div class='admin-newsletter-page'>
        <!-- Header -->
        <div class='admin-page-header'>
            <div class='container-fluid'>
                <div class='row align-items-center'>
                    <div class='col'>
                        <h1 class='page-title mb-2'>
                            <i class='bi bi-envelope-fill me-3'></i>
                            Newsletter Management
                        </h1>
                        <p class='page-subtitle text-secondary mb-0'>
                            Manage subscribers and send newsletters
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class='container-fluid'>
            " . $_mensaje . "

            <!-- Stats Cards -->
            <div class='stats-grid'>
                <div class='stat-card'>
                    <div class='stat-icon'>
                        <i class='bi bi-people-fill'></i>
                    </div>
                    <div class='stat-content'>
                        <span class='stat-value'>{$stats['total']}</span>
                        <span class='stat-label'>Total Subscribers</span>
                    </div>
                </div>
                
                <div class='stat-card'>
                    <div class='stat-icon stat-icon-success'>
                        <i class='bi bi-check-circle-fill'></i>
                    </div>
                    <div class='stat-content'>
                        <span class='stat-value'>{$stats['active']}</span>
                        <span class='stat-label'>Active</span>
                    </div>
                </div>
                
                <div class='stat-card'>
                    <div class='stat-icon stat-icon-info'>
                        <i class='bi bi-calendar-check'></i>
                    </div>
                    <div class='stat-content'>
                        <span class='stat-value'>{$stats['today']}</span>
                        <span class='stat-label'>Today</span>
                    </div>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class='actions-bar'>
                <div class='actions-left'>
                    <a href='admin_newsletter_send.php' class='btn btn-primary'>
                        <i class='bi bi-send-fill'></i>
                        Send Newsletter
                    </a>
                    <button onclick='exportCSV()' class='btn btn-secondary'>
                        <i class='bi bi-download'></i>
                        Export CSV
                    </button>
                    <button onclick='deleteSelected()' class='btn btn-danger' id='deleteSelectedBtn' style='display:none;'>
                        <i class='bi bi-trash'></i>
                        Delete Selected
                    </button>
                </div>
                
                <div class='actions-right'>
                    <form method='GET' class='search-form'>
                        <input type='text' name='search' placeholder='Search by email...' value='" . htmlspecialchars($search) . "' class='search-input'>
                        <button type='submit' class='btn btn-search'>
                            <i class='bi bi-search'></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Subscribers Table -->
            <div class='table-container'>
                <table class='subscribers-table'>
                    <thead>
                        <tr>
                            <th class='th-checkbox'>
                                <input type='checkbox' id='selectAll' class='checkbox'>
                            </th>
                            <th class='th-id'>ID</th>
                            <th class='th-email'>Email</th>
                            <th class='th-date'>Subscribed</th>
                            <th class='th-status'>Status</th>
                            <th class='th-actions'>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
    
    if (count($subscribers) > 0) {
        foreach ($subscribers as $sub) {
            $date = date('M d, Y H:i', strtotime($sub['subscribed_at']));
            $statusClass = $sub['status'] === 'active' ? 'success' : 'inactive';
            
            $_inhoud .= "
                        <tr data-id='{$sub['id']}'>
                            <td>
                                <input type='checkbox' class='checkbox subscriber-checkbox' value='{$sub['id']}'>
                            </td>
                            <td class='td-id'>{$sub['id']}</td>
                            <td class='td-email'>{$sub['email']}</td>
                            <td class='td-date'>{$date}</td>
                            <td>
                                <span class='status-badge status-{$statusClass}'>
                                    <i class='bi bi-circle-fill'></i>
                                    {$sub['status']}
                                </span>
                            </td>
                            <td class='td-actions'>
                                <button onclick='deleteSubscriber({$sub['id']}, \"{$sub['email']}\")' class='btn-icon btn-icon-danger' title='Delete'>
                                    <i class='bi bi-trash'></i>
                                </button>
                            </td>
                        </tr>";
        }
    } else {
        $_inhoud .= "
                        <tr>
                            <td colspan='6' class='empty-state'>
                                <i class='bi bi-inbox'></i>
                                <p>No subscribers found</p>
                            </td>
                        </tr>";
    }
    
    $_inhoud .= "
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->";
    
    if ($totalPages > 1) {
        $_inhoud .= "
            <div class='pagination'>
                <div class='pagination-info'>
                    Showing " . (($page - 1) * $perPage + 1) . " to " . min($page * $perPage, $totalRecords) . " of {$totalRecords} subscribers
                </div>
                <div class='pagination-buttons'>";
        
        if ($page > 1) {
            $_inhoud .= "<a href='?page=" . ($page - 1) . ($search ? "&search=$search" : "") . "' class='btn btn-pagination'>
                <i class='bi bi-chevron-left'></i> Previous
            </a>";
        }
        
        for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) {
            $active = $i === $page ? 'active' : '';
            $_inhoud .= "<a href='?page=$i" . ($search ? "&search=$search" : "") . "' class='btn btn-pagination $active'>$i</a>";
        }
        
        if ($page < $totalPages) {
            $_inhoud .= "<a href='?page=" . ($page + 1) . ($search ? "&search=$search" : "") . "' class='btn btn-pagination'>
                Next <i class='bi bi-chevron-right'></i>
            </a>";
        }
        
        $_inhoud .= "
                </div>
            </div>";
    }
    
    $_inhoud .= "
        </div>
    </div>

    <script>
    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.subscriber-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Update delete button
    document.querySelectorAll('.subscriber-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.subscriber-checkbox:checked');
        const btn = document.getElementById('deleteSelectedBtn');
        btn.style.display = checkedBoxes.length > 0 ? 'inline-flex' : 'none';
        
        const icon = '<i class=\"bi bi-trash\"></i>';
        const text = checkedBoxes.length > 0 ? `Delete Selected (\${checkedBoxes.length})` : 'Delete Selected';
        btn.innerHTML = icon + ' ' + text;
    }

    function deleteSubscriber(id, email) {
        if (confirm(`Are you sure you want to delete subscriber:\\n\\n\${email}?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type='hidden' name='action' value='delete'>
                <input type='hidden' name='id' value='\${id}'>
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteSelected() {
        const checkedBoxes = Array.from(document.querySelectorAll('.subscriber-checkbox:checked'));
        if (checkedBoxes.length === 0) return;
        
        if (confirm(`Are you sure you want to delete \${checkedBoxes.length} subscriber(s)?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type='hidden' name='action' value='delete_multiple'>`;
            checkedBoxes.forEach(cb => {
                form.innerHTML += `<input type='hidden' name='ids[]' value='\${cb.value}'>`;
            });
            document.body.appendChild(form);
            form.submit();
        }
    }

    function exportCSV() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `<input type='hidden' name='action' value='export_csv'>`;
        document.body.appendChild(form);
        form.submit();
    }
    </script>
    ";
    
    // Output
    $_menu = 0;
    $_jsInclude = array("../js/newsletter_handler.js");
    require_once(__DIR__ . "/../code/output_admin.inc.php");
    
} catch (Exception $_e) {
    include(__DIR__ . "/../php_lib/myExceptionHandling.inc.php");
    echo myExceptionHandling($_e, "../logs/error_log.csv");
}
?>
