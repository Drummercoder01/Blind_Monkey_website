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

// Cache busting — versión basada en fecha de modificación del archivo
$_root = __DIR__ . '/..';
$_cssVersion = array(
    'style'   => filemtime($_root . '/css/style-v2.css'),
    'cross'   => filemtime($_root . '/css/cross-browser-fixes.css'),
);
$_jsVersion = filemtime($_root . '/js/scroll.js'); // cualquier JS como referencia global

// JS con versión individual
$_jsIncludeVersioned = array_map(function($path) use ($_root) {
    $absPath = $_root . '/' . ltrim(str_replace('../', '', $path), '/');
    $v = file_exists($absPath) ? filemtime($absPath) : time();
    return $path . '?v=' . $v;
}, $_jsInclude);

// We kennen de variabelen toe
$_smarty->assign('inhoud', $_inhoud);
$_smarty->assign('nav', $_nav);
$_smarty->assign('footer', $_footer);
/* $_smarty->assign('commentaar',inlezen($_commentaar)); */
$_smarty->assign('menu', menu($_menu));
$_smarty->assign('jsInclude', $_jsIncludeVersioned);
$_smarty->assign('cssVersion', $_cssVersion);
// display it
$_smarty->display('home.tpl');
?>