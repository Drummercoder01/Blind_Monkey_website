<?php
error_reporting(13);

if ($_SERVER['SERVER_NAME'] != "localhost")
{
	// database
	$_hostname = "";
	$_username = "";
	$_password = "";
	$_database = "";	
	// $_hostname = "localhost";
	// $_username = "a102235_db_don_mateo";
	// $_password = "pas123";
	// $_database = "a102235_db_don_mateo";
	
	// andere project afhangkelijke waarden
	$_domain = "";
}
else
{
	// database
	$_hostname = "localhost";
	$_username = "root";
	$_password = "";
	$_database = "db_the5am";
	
	// andere project afhangkelijke waarden
	//$_domain = "localhost:8888/webo/C_applicaties/APP_13_final";
}
$_PDO = new PDO("mysql:host=$_hostname; dbname=$_database","$_username", "$_password");

$_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
