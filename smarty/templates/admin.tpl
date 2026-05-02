<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blind Monkey | Admin Dashboard</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600&family=Bebas+Neue&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f74746c02f.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="/scss/style_admin.css?v={$adminCssVersion}">

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<body class="admin-body">

    <!-- Main Container -->
    <div class="admin-container">

        <!-- Desktop Sidebar (shows only on large screens) -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo-container text-center">
                    <div class="brand-text">
                        <h5 class="brand-title mb-0"><img src="../img/blind_monkey_logo.jpg" alt="Blind Monkey"
                                class="admin-logo"></h5>
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

                    <!-- Scrollable main sections -->
                    <div class="sidebar-nav-scroll">
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="../scripts/admin_about.php" class="nav-link">
                                    <i class="bi bi-person-lines-fill nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">About</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_music.php" class="nav-link">
                                    <i class="bi bi-music-note-beamed nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Music</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_press.php" class="nav-link">
                                    <i class="bi bi-newspaper nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Press</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_events.php" class="nav-link">
                                    <i class="bi bi-calendar-event nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Events</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_videos.php" class="nav-link">
                                    <i class="bi bi-camera-video nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Videos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_photos.php" class="nav-link">
                                    <i class="bi bi-camera nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Photos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_newsletter.php" class="nav-link">
                                    <i class="bi bi-envelope-fill nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Newsletter</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_users.php" class="nav-link">
                                    <i class="bi bi-people-fill nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Users</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_analytics.php" class="nav-link">
                                    <i class="bi bi-bar-chart-line-fill nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Analytics</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/admin_logs.php" class="nav-link">
                                    <i class="bi bi-journal-text nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Logs</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Fixed bottom actions -->
                    <div class="sidebar-nav-bottom">
                        <div class="nav-divider"></div>
                        <ul class="nav-list nav-bottom">
                            <li class="nav-item">
                                <a href="../scripts/change_password.php" class="nav-link">
                                    <i class="bi bi-key-fill nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Change Password</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/A_home.php" class="nav-link nav-link-secondary" target="_blank">
                                    <i class="bi bi-globe nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">View Website</span>
                                    <i class="bi bi-box-arrow-up-right ms-auto text-xs text-secondary"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../scripts/Z_uitloggen.php" class="nav-link nav-link-logout">
                                    <i class="bi bi-box-arrow-left nav-icon text-secondary"></i>
                                    <span class="nav-text text-secondary">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </nav>
            </div>
        </aside>

        <!-- Mobile Header — styled like visitor navbar, visible only on small/medium screens -->
        <header class="admin-mobile-navbar" id="adminMobileNav">
            <div class="admin-nav-inner">

                <!-- Brand -->
                <a href="/home" class="admin-nav-brand" target="_blank">
                    <img src="../img/blind_monkey_logo.jpg" alt="Blind Monkey" class="admin-nav-logo">
                    <span class="admin-nav-brand-name">Blind<br>Monkey</span>
                </a>

                <!-- Admin badge -->
                <span class="admin-nav-badge">Admin</span>

                <!-- Hamburger -->
                <button class="admin-hamburger" id="adminHamburger" aria-label="Menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div>

            <!-- Slide-down menu -->
            <div class="admin-mobile-menu" id="adminMobileMenu">
                <ul class="admin-mobile-links">
                    <li><a class="admin-mobile-link" href="../scripts/admin_about.php"><i class="bi bi-person-lines-fill"></i>About</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_music.php"><i class="bi bi-music-note-beamed"></i>Music</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_press.php"><i class="bi bi-newspaper"></i>Press</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_events.php"><i class="bi bi-calendar-event"></i>Events</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_videos.php"><i class="bi bi-camera-video"></i>Videos</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_photos.php"><i class="bi bi-camera"></i>Photos</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_newsletter.php"><i class="bi bi-envelope-fill"></i>Newsletter</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_users.php"><i class="bi bi-people-fill"></i>Users</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_analytics.php"><i class="bi bi-bar-chart-line-fill"></i>Analytics</a></li>
                    <li><a class="admin-mobile-link" href="../scripts/admin_logs.php"><i class="bi bi-journal-text"></i>Logs</a></li>
                    <li class="admin-mobile-divider"></li>
                    <li><a class="admin-mobile-link" href="../scripts/change_password.php"><i class="bi bi-key-fill"></i>Change Password</a></li>
                    <li><a class="admin-mobile-link admin-mobile-link--secondary" href="/home" target="_blank"><i class="bi bi-globe"></i>View Website<i class="bi bi-box-arrow-up-right admin-ext-icon"></i></a></li>
                    <li><a class="admin-mobile-link admin-mobile-link--logout" href="../scripts/Z_uitloggen.php"><i class="bi bi-box-arrow-left"></i>Logout</a></li>
                </ul>
            </div>
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
        document.addEventListener('DOMContentLoaded', function () {

            // ── Auto-ocultar mensajes ─────────────────────────────────────
            setTimeout(function () {
                document.querySelectorAll('.success, .error, .alert').forEach(function (el) {
                    el.style.transition = 'opacity 0.5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 4000);

            // ── Marcar link activo (desktop + mobile) ─────────────────────
            const currentPage = window.location.pathname.split('/').pop();

            document.querySelectorAll('.admin-sidebar .nav-link, .admin-mobile-link').forEach(function (link) {
                const href = link.getAttribute('href');
                if (href && href.includes(currentPage)) {
                    link.classList.add('active');
                }
            });

            // ── Hamburger mobile — mismo sistema que el sitio visitante ───
            const hamburger   = document.getElementById('adminHamburger');
            const mobileMenu  = document.getElementById('adminMobileMenu');
            const mobileNav   = document.getElementById('adminMobileNav');

            if (hamburger && mobileMenu) {

                // Toggle al hacer click en el botón
                hamburger.addEventListener('click', function () {
                    const isOpen = this.classList.toggle('open');
                    this.setAttribute('aria-expanded', isOpen);
                    mobileMenu.classList.toggle('open', isOpen);
                });

                // Cerrar al hacer click fuera del navbar
                document.addEventListener('click', function (e) {
                    if (mobileNav && !mobileNav.contains(e.target)) {
                        hamburger.classList.remove('open');
                        hamburger.setAttribute('aria-expanded', 'false');
                        mobileMenu.classList.remove('open');
                    }
                });

                // Cerrar al hacer click en un link
                mobileMenu.querySelectorAll('.admin-mobile-link').forEach(function (link) {
                    link.addEventListener('click', function () {
                        hamburger.classList.remove('open');
                        hamburger.setAttribute('aria-expanded', 'false');
                        mobileMenu.classList.remove('open');
                    });
                });
            }

            // ── Sidebar scroll compact (solo desktop) ─────────────────────
            if (window.innerWidth >= 992) {
                let lastScrollTop = 0;
                window.addEventListener('scroll', function () {
                    const scrollTop = window.scrollY;
                    const sidebar   = document.querySelector('.admin-sidebar');
                    if (!sidebar) return;
                    sidebar.classList.toggle('sidebar-compact', scrollTop > lastScrollTop && scrollTop > 100);
                    lastScrollTop = scrollTop;
                }, { passive: true });
            }

            console.log('✅ Admin Dashboard Ready — página:', currentPage);
        });
    </script>

</body>

</html>