<?php
try
{
require("../code/initialisatie.inc.php");

/*******************************************
*    (Input en) verwerking
********************************************/

// welkom.txt zal in het "inhoud" veld  op het scherm komen
  require_once("../php_lib/inlezen.inc.php");
  $_inhoud = inlezen('A_home_I.html');

  
/*******************************************
*    output
********************************************/  
// menu definieren  
  $_menu =  100;
// commentaar file definieren  
  $_commentaar = inlezen('Y_home_C.html');
  
  require("../code/output_admin.inc.php");
  
}
 
catch (Exception $e)
{
 // exception handling funtions 
  include("../php_lib/myExceptionHandling.inc.php"); 
  echo myExceptionHandling($_e,"../logs/error_log.csv");
}



?>