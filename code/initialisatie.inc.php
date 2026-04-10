<?php
// session opstarten
session_start();
// instantiering van $_PDO
// (connectie met dbms en selectie van de datbase)
require_once("../connections/pdo.inc.php");
// authorised() removed — public data scripts (get_photos, get_events, etc.)
// must be accessible to all visitors without login.
// Admin scripts use their own initialisatie with auth check.

//$_srv gebruiken we als "action" in onze formulieren
$_srv = $_SERVER['PHP_SELF'];
$_inhoud="";
$_jsInclude = array();

// model (database) based drop-downs  
/* require_once("../php_lib/dropDown.inc.php"); */
// functie om selectie query samen te stellen  
/* require_once("../php_lib/createSelect.inc.php");   */
// primary key van t_gemeente p te zoeken op basis van gemeente naam en/of postcode
/* require_once("../php_lib/PK_t_gemeente.inc.php");   */

?>
