<?php
// Initialisatie voor admin scripts — vereist login
require_once(__DIR__ . "/initialisatie.inc.php");
// Admin auth check
require_once(__DIR__ . "/../php_lib/authorised.inc.php");
authorised();
?>
