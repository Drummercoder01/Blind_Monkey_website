<?php
/* Smarty version 3.1.31, created on 2025-04-21 09:55:43
  from "D:\Alexis Code Projects\The5AM-Website\1-Full-Site\public_html\smarty\templates\admin.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_6806161f82ea99_21633950',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf021d2d04e5cbadf9bea8607b60a61ea8f1d697' => 
    array (
      0 => 'D:\\Alexis Code Projects\\The5AM-Website\\1-Full-Site\\public_html\\smarty\\templates\\admin.tpl',
      1 => 1745229338,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6806161f82ea99_21633950 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The 5 AM | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/scss/style_admin.css">

    <?php echo '<script'; ?>
 src="/js/jquery-3.6.0.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://kit.fontawesome.com/f74746c02f.js" crossorigin="anonymous"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            // Hide success and error messages after 1 second
            setTimeout(function() {
                $('.success').fadeOut(500);
                $('.error').fadeOut(500);
            }, 1000);

            // Get the current URL path
            var currentPath = window.location.pathname;

            // Find the corresponding nav item with a matching href
            $('.nav-link').each(function() {
                var href = $(this).attr('href');
                if (href.endsWith(currentPath)) {
                    $(this).addClass('active');
                }
            });

            // Mobile navigation (smaller than 768px)
            $('.navbar-collapse a.nav-link').on('click', function() {
                $('.navbar-collapse a.nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
    <?php echo '</script'; ?>
>

</head>

<body>
    <div class="row mx-0">
        <!-- NAVIGATION DESKTOP STARTING FROM (768PX) -->
        <div class="col-12 col-lg-3 d-none d-lg-block position-fixed pt-4 pb-4" id="sidebar">
            <ul class="navbar-nav flex-column w-100">

                <li class="nav-item text-center mb-5">
                    <a class="logoHeader" href="../scripts/admin_reservas.php">The5AM adminpanel</a>
                    <p>Welcome</p>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_about.php"><i class="fas fa-address-card"></i>About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_music.php"><i class="fas fa-music"></i>Music</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_press.php"><i class="fas fa-newspaper"></i>Press</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_events.php"><i class="fas fa-calendar"></i>Events</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_videos.php"><i class="fas fa-video"></i>Videos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_photos.php"><i class="fas fa-camera"></i>Photos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_reservas.php"><i class="fas fa-calendar"></i>Reservas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/admin_mensajes.php"><i
                            class="fas fa-regular fa-envelope"></i>Mensajes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../scripts/Z_uitloggen.php"><i class="fa fa-sign-out"></i>Logout</a>
                </li>
            </ul>
        </div>
        <div class="col-12 px-0">
            <!-- MOBILE NAVIGATION SCREEN SMALLER THAN (768PX) -->
            <div class="col-12 d-block d-lg-none px-0" class="navHolder" id="sidebarMobile">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container">
                        <a class="navbar-brand" href="../scripts/admin_home.php">The5AM adminpanel</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">

                                <li class="nav-item">
                                    <a class="nav-link" href="../scripts/admin_reservas.php"><i
                                            class="fas fa-calendar"></i>Reservas</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="../scripts/admin_mensajes.php"><i
                                            class="fas fa-regular fa-envelope"></i>Mensajes</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="../scripts/Z_uitloggen.php"><i
                                            class="fa fa-sign-out"></i>Salir</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="row mx-0">
        <div class="col-12 col-lg-9 p-4 pb-0 offset-lg-3 contentHolder">
            <?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>

        </div>
    </div>

</body>

</html><?php }
}
