<?php
/*******************************************
*    output
********************************************/  
//$_smarty instantieren en initialiseren  
require_once("../smarty/mySmarty.inc.php");
//functie om "menu" samen te stellen
require_once("../php_lib/menu.inc.php");
//functie om tekst/html in te lezen
require_once("../php_lib/inlezen.inc.php");
// Cache busting para CSS/JS del admin
$_root = dirname(__DIR__);
$_adminCssVersion = file_exists($_root . '/scss/style_admin.css')
    ? filemtime($_root . '/scss/style_admin.css')
    : time();

// We kennen de variabelen toe
$_smarty->assign('inhoud', $_inhoud);
$_smarty->assign('adminCssVersion', $_adminCssVersion);
/* $_smarty->assign('nav', $_nav); */
$_smarty->assign('jsInclude',$_jsInclude);
// display it
$_smarty->display('admin.tpl');
?>
