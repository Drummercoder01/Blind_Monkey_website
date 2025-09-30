<?php

//require("../connections/pdo.inc.php"); //disable comments to insert new user
require("../php_lib/encrypt.inc.php");
$_srv= $_SERVER['PHP_SELF'];

$_string="WeCouldBeHeroes007"; // password
$_salt= "info@the5am.be"; //user_name
$_paswoord= encrypt($_string, $_salt);

//$_salt = 'WEBO'; 

$_identifier = encrypt ($_salt,$_salt);

$_token = encrypt(uniqid(rand(), TRUE)); 

/*$_expire = time() + 60 * 60 * 8;

setcookie('auth', "$_identifier:$_token", $_expire);*/

$_query= "INSERT INTO ts_authentication (d_logon,d_paswoord,d_identifier, d_token,d_rol)
VALUES('$_salt','$_paswoord','$_identifier','$_token',1);";

$_result = $_PDO->query($_query); 

exit;


?>

