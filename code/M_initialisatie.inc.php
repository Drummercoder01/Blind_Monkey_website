<?php
session_start();


//$_srv gebruiken we als "action" in onze formulieren
$_srv = $_SERVER['PHP_SELF']; 
$_jsInclude = array();
//*********** includes  
  
// instantiering van $_PDO 
// (connectie met dbms en selectie van de datbase)
require("../connections/pdo.inc.php");
//authorised???
require_once("../php_lib/authorised.inc.php");
authorised();

//functie om "radio-buttons" samen te stellen
include("../php_lib/radioButton.inc.php");

// initialisatie van variabelen
$_inhoud="";
?>