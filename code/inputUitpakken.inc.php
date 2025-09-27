<?php
// input opschonen
require_once("../php_lib/cleanInput.inc.php");
cleanInput();

// verwerk inhoud van het formulier	
$_naam =$_POST["naam"];
$_voornaam = $_POST["voornaam"];
$_straat = $_POST["straat"];
$_nr = $_POST["nr"];
$_xtr = $_POST["xtr"];
$_telefoon = $_POST["tel"];
$_gemeenteNaam = $_POST["gemnaam"];
$_postcode = $_POST["postcode"];
$_mob = $_POST["mob"];
$_mail = $_POST["mail"];
$_gender =$_POST["gender"];
$_soort =$_POST["soort"];

?>