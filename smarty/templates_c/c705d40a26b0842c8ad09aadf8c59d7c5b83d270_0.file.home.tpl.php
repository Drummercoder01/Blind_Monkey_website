<?php
/* Smarty version 3.1.31, created on 2023-02-24 18:47:55
  from "C:\wamp\www\Don_Mateo_website_responsive\smarty\templates\home.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_63f9065b7b1be6_78169957',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c705d40a26b0842c8ad09aadf8c59d7c5b83d270' => 
    array (
      0 => 'C:\\wamp\\www\\Don_Mateo_website_responsive\\smarty\\templates\\home.tpl',
      1 => 1677264393,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63f9065b7b1be6_78169957 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Don Mateo</title>
    <link rel="stylesheet" href="/scss/style.css">
    <?php echo '<script'; ?>
 src="js/jquery-3.6.0.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>
</head>

<body>
    <header class="header">
        <!-- overlay-al-abrir-menu-hamburger -->
        <div class="overlay has-fade"></div>
        <!-- navegacion -->
        <nav class="container container--pall flex flex-jc-sb flex-jc-end">

            <!-- hamburger-menu -->
            <a id="btnHamburger" href="#" class="header__toggle hide-for-desktop">
                <span></span>
                <span></span>
                <span></span>
            </a>

            <div class="header__links hide-for-mobile">
                <a href="#">Servicios</a><a href="#">Productos</a><a href="#">Contactos</a>
            </div>
        </nav>
        <!-- Hamb-menu-contenido -->
        <div class="header__menu has-fade">
            <a href="">Servicios</a>
            <a href="">Productos</a>
            <a href="">Contactos</a>
        </div>


    </header>

    <section class="start QQComer flex flex-jc-sb flex-jc-c flex-ai-c flex-fd-c">
    
    
        <?php echo $_smarty_tpl->tpl_vars['logo']->value;?>

    
        <?php echo $_smarty_tpl->tpl_vars['inhoud']->value;?>


    </section>

    <?php echo '<script'; ?>
 src="../js/script.js"><?php echo '</script'; ?>
>
</body>

</html><?php }
}
