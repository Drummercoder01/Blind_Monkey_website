<?php
/* Smarty version 3.1.31, created on 2026-02-18 11:57:31
  from "C:\wamp\www\1-Full-Site\smarty\templates\admin.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_6995a92babbb95_48112329',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd481d31e32221b45644e065ba14a25309c923f1d' => 
    array (
      0 => 'C:\\wamp\\www\\1-Full-Site\\smarty\\templates\\admin.tpl',
      1 => 1771415845,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6995a92babbb95_48112329 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The 5 AM | Admin Dashboard</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <?php echo '<script'; ?>
 src="https://kit.fontawesome.com/f74746c02f.js" crossorigin="anonymous"><?php echo '</script'; ?>
>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="/scss/style_admin.css">

    <!-- CKEditor -->
    <?php echo '<script'; ?>
 src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"><?php echo '</script'; ?>
>
</head>

<body class="admin-body">

    <!-- Main Container -->
    <div class="admin-container">

        <!-- Desktop Sidebar (shows only on large screens) -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo-container text-center">
                    <div class="brand-text">
                        <h5 class="brand-title mb-0"><img src="../img/5am_Logo_01-01.png" alt="The 5 AM"
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
                    </ul>

                    <div class="nav-divider"></div>

                    <ul class="nav-list nav-bottom">
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
                </nav>
            </div>
        </aside>

        <!-- Mobile Header (shows only on small/medium screens) -->
        <header class="mobile-header">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <!-- Mobile Brand -->
                    <div class="mobile-brand">
                        <img src="../img/5am_Logo_01-01.png" alt="The 5 AM" class="mobile-logo">
                        <span class="mobile-brand-text">The 5 AM Admin</span>
                    </div>

                    <!-- Mobile Toggle Button -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav"
                        aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Mobile Navigation Menu -->
                    <div class="collapse navbar-collapse" id="mobileNav">
                        <ul class="navbar-nav w-100">
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="../scripts/admin_about.php">
                                    <i class="bi bi-person-lines-fill text-secondary"></i>
                                    About
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="../scripts/admin_music.php">
                                    <i class="bi bi-music-note-beamed text-secondary"></i>
                                    Music
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="../scripts/admin_press.php">
                                    <i class="bi bi-newspaper text-secondary"></i>
                                    Press
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="../scripts/admin_events.php">
                                    <i class="bi bi-calendar-event text-secondary"></i>
                                    Events
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="../scripts/admin_videos.php">
                                    <i class="bi bi-camera-video text-secondary"></i>
                                    Videos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="../scripts/admin_photos.php">
                                    <i class="bi bi-camera text-secondary"></i>
                                    Photos
                                </a>
                            </li>
                            
                            <!-- Divider -->
                            <li class="nav-item">
                                <hr class="dropdown-divider">
                            </li>
                            
                            <!-- Secondary Links -->
                            <li class="nav-item">
                                <a class="nav-link text-secondary" href="/scripts/A_home.php" target="_blank">
                                    <i class="bi bi-globe text-secondary"></i>
                                    View Website
                                    <i class="bi bi-box-arrow-up-right ms-auto text-secondary"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="../scripts/Z_uitloggen.php">
                                    <i class="bi bi-box-arrow-left text-secondary"></i>
                                    Logout
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
                <?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>

            </div>
        </main>

    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <?php echo '<script'; ?>
 src="/js/jquery-3.6.0.min.js"><?php echo '</script'; ?>
>

    <!-- Bootstrap JS -->
    <?php echo '<script'; ?>
 src="/js/bootstrap.min.js"><?php echo '</script'; ?>
>

    <!-- Dynamic JS Includes -->
    <?php
$__section_teller_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_teller']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller'] : false;
$__section_teller_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['jsInclude']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_teller_0_total = $__section_teller_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_teller'] = new Smarty_Variable(array());
if ($__section_teller_0_total != 0) {
for ($__section_teller_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] = 0; $__section_teller_0_iteration <= $__section_teller_0_total; $__section_teller_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']++){
?>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['jsInclude']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_teller']->value['index'] : null)];?>
"><?php echo '</script'; ?>
>
    <?php
}
}
if ($__section_teller_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_teller'] = $__section_teller_0_saved;
}
?>

    <!-- Custom Scripts -->
    <?php echo '<script'; ?>
 src="/js_lib/popUp.js"><?php echo '</script'; ?>
>

    <!-- Admin Initialization Script -->
    <?php echo '<script'; ?>
>
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

            // Mobile nav collapse on link click
            $('.mobile-header .nav-link').on('click', function() {
                $('.navbar-collapse').collapse('hide');
            });

            // Handle window resize for navigation
            let resizeTimer;
            $(window).resize(function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    // Force recalculation of navigation state
                    if ($(window).width() >= 992) {
                        // Desktop mode - ensure sidebar is shown
                        $('.navbar-collapse').collapse('hide');
                        $('body').removeClass('nav-switching');
                    } else {
                        // Mobile mode
                        $('body').addClass('nav-switching');
                        setTimeout(() => {
                            $('body').removeClass('nav-switching');
                        }, 300);
                    }
                }, 150);
            });

            // Sidebar scroll behavior (desktop only)
            if ($(window).width() >= 992) {
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
            }

            // Initialize tooltips if any
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltips.forEach(tooltip => {
                    new bootstrap.Tooltip(tooltip);
                });
            }

            // Mobile menu smooth animations
            $('#mobileNav').on('show.bs.collapse', function() {
                $(this).find('.nav-link').each(function(index) {
                    $(this).css('animation-delay', (index * 0.05) + 's');
                    $(this).addClass('animate-in');
                });
            });

            $('#mobileNav').on('hidden.bs.collapse', function() {
                $(this).find('.nav-link').removeClass('animate-in');
            });

            console.log('Admin navigation initialized for:', currentPage);
            console.log('Screen width:', $(window).width(), 'Navigation mode:', $(window).width() >= 992 ? 'Desktop' : 'Mobile');
        });

        // Add CSS animation class
        const style = document.createElement('style');
        style.textContent = `
            .animate-in {
                animation: slideInLeft 0.3s ease forwards;
            }
            
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        `;
        document.head.appendChild(style);
    <?php echo '</script'; ?>
>

</body>

</html><?php }
}
