<?php
/*******************************************
*    output
********************************************/  
//$_smarty instantieren en initialiseren  
require_once("../smarty/mySmarty.inc.php");
//functie om "menu" samen te stellen
require_once("../php_lib/menu.inc.php");
//functie om tekst/html in te lezen
/* require_once("../php_lib/inlezen.inc.php"); */
// We kennen de variabelen toe
$_smarty->assign('inhoud', $_inhoud);
$_smarty->assign('nav', $_nav);
$_smarty->assign('footer', $_footer);
/* $_smarty->assign('commentaar',inlezen($_commentaar)); */
$_smarty->assign('menu',menu($_menu));
$_smarty->assign('jsInclude',$_jsInclude);
// display it
$_smarty->display('home.tpl');
?>