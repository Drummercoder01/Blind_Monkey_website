<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The 5 AM | Admin Dashboard</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f74746c02f.js" crossorigin="anonymous"></script>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="/scss/style_admin.css">
    
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<body class="admin-body">
    
    <!-- Main Container -->
    <div class="admin-container">
        
        <!-- Desktop Sidebar -->
        <aside class="admin-sidebar d-none d-lg-block">
            <div class="sidebar-header">
                <div class="logo-container">
                    <img src="../img/5am_Logo_01-01.png" alt="The 5 AM" class="admin-logo">
                    <div class="brand-text">
                        <h5 class="brand-title mb-0">The 5 AM</h5>
                        <small class="brand-subtitle">Admin Dashboard</small>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-content">
                <div class="welcome-section">
                    <div class="user-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <p class="welcome-text">Welcome back!</p>
                </div>
                
                <nav class="sidebar-nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="../scripts/admin_about.php" class="nav-link">
                                <i class="bi bi-person-lines-fill nav-icon text-light"></i>
                                <span class="nav-text text-light">About</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../scripts/admin_music.php" class="nav-link">
                                <i class="bi bi-music-note-beamed nav-icon text-light text-light"></i>
                                <span class="nav-text text-light text-light">Music</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../scripts/admin_press.php" class="nav-link">
                                <i class="bi bi-newspaper nav-icon text-light text-light"></i>
                                <span class="nav-text text-light text-light">Press</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../scripts/admin_events.php" class="nav-link">
                                <i class="bi bi-calendar-event nav-icon text-light text-light"></i>
                                <span class="nav-text text-light text-light">Events</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../scripts/admin_videos.php" class="nav-link">
                                <i class="bi bi-camera-video nav-icon text-light text-light"></i>
                                <span class="nav-text text-light text-light">Videos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../scripts/admin_photos.php" class="nav-link">
                                <i class="bi bi-camera nav-icon text-light text-light"></i>
                                <span class="nav-text text-light text-light">Photos</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="nav-divider"></div>
                    
                    <ul class="nav-list nav-bottom">
                        <li class="nav-item">
                            <a href="/scripts/A_home.php" class="nav-link nav-link-secondary" target="_blank">
                                <i class="bi bi-globe nav-icon text-light"></i>
                                <span class="nav-text text-light">View Website</span>
                                <i class="bi bi-box-arrow-up-right ms-auto text-xs text-light"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../scripts/Z_uitloggen.php" class="nav-link nav-link-logout">
                                <i class="bi bi-box-arrow-left nav-icon text-light"></i>
                                <span class="nav-text text-light">Logout</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Mobile Header -->
        <header class="mobile-header d-lg-none">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <div class="mobile-brand">
                        <img src="../img/5am_Logo_01-01.png" alt="The 5 AM" class="mobile-logo">
                        <span class="mobile-brand-text">The 5 AM Admin</span>
                    </div>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#mobileNav" aria-controls="mobileNav" 
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="mobileNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link text-light" href="../scripts/admin_about.php">
                                    <i class="bi bi-person-lines-fill me-2 text-light"></i>About
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="../scripts/admin_music.php">
                                    <i class="bi bi-music-note-beamed me-2 text-light"></i>Music
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="../scripts/admin_press.php">
                                    <i class="bi bi-newspaper me-2 text-light"></i>Press
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="../scripts/admin_events.php">
                                    <i class="bi bi-calendar-event me-2 text-light"></i>Events
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="../scripts/admin_videos.php">
                                    <i class="bi bi-camera-video me-2 text-light"></i>Videos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="../scripts/admin_photos.php">
                                    <i class="bi bi-camera me-2 text-light"></i>Photos
                                </a>
                            </li>
                            <li class="nav-item">
                                <hr class="dropdown-divider">
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="/scripts/A_home.php" target="_blank">
                                    <i class="bi bi-globe me-2 text-light"></i>View Website
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="../scripts/Z_uitloggen.php">
                                    <i class="bi bi-box-arrow-left me-2 text-light"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        <!-- Main Content -->
        <main class="admin-content">
            <div class="content-wrapper">
                {$inhoud}
            </div>
        </main>
        
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="/js/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="/js/bootstrap.min.js"></script>
    
    <!-- Dynamic JS Includes -->
    {section name=teller loop=$jsInclude}
        <script src="{$jsInclude[teller]}"></script>
    {/section}
    
    <!-- Custom Scripts -->
    <script src="/js_lib/popUp.js"></script>
    
    <!-- Admin Initialization Script -->
    <script>
        $(document).ready(function() {
            console.log('✅ Admin Dashboard Ready');

            // Auto-hide success/error messages
            setTimeout(function() {
                $('.success, .error, .alert').fadeOut(500);
            }, 4000);

            // Set active navigation based on current path
            const currentPath = window.location.pathname;
            const pathSegments = currentPath.split('/');
            const currentPage = pathSegments[pathSegments.length - 1];
            
            // Desktop nav
            $('.admin-sidebar .nav-link').each(function() {
                const href = $(this).attr('href');
                if (href && href.includes(currentPage)) {
                    $(this).addClass('active');
                }
            });
            
            // Mobile nav
            $('.mobile-header .nav-link').each(function() {
                const href = $(this).attr('href');
                if (href && href.includes(currentPage)) {
                    $(this).addClass('active');
                }
            });

            // Mobile nav collapse on click
            $('.mobile-header .nav-link').on('click', function() {
                $('.navbar-collapse').collapse('hide');
            });
            
            // Sidebar scroll behavior
            let lastScrollTop = 0;
            $(window).scroll(function() {
                const scrollTop = $(this).scrollTop();
                const sidebar = $('.admin-sidebar');
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    sidebar.addClass('sidebar-compact');
                } else {
                    // Scrolling up
                    sidebar.removeClass('sidebar-compact');
                }
                lastScrollTop = scrollTop;
            });

            // Initialize tooltips if any
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltips.forEach(tooltip => {
                    new bootstrap.Tooltip(tooltip);
                });
            }
            
            console.log('Admin navigation initialized for:', currentPage);
        });
    </script>

</body>

</html>