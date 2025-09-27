<?php
try
{

/*******************************************
*Initialisatie
********************************************/
require("../code/initialisatie.inc.php"); 
	 
/***************************************************
* is het script op een "legale" manier opgestart
* is er een actie gedefinieerd
* is de actie gelijk aan  --> 3 - verwijderen
* is er een formulier 
*                            
* anders --> illegale toegang
***************************************************/
  if (! isset($_SESSION["actie"]) 
			|| 
			$_SESSION["actie"] != 3 
			||
			! isset($_POST["submit_bev"]))
	{
		 throw new Exception("illegal access");
	}

// input uitpakken
  $_lidnr = $_POST['lidnr'];
  $_naam = $_POST['naam']; 
  $_voornaam = $_POST['voornaam'];
		
// Query samenstellen
  $_query ="DELETE FROM t_leden WHERE d_lidnr = $_lidnr;"; 
    
// Query naar DB sturen
		$_result = $_PDO -> query($_query); 
		
//lid is verwijderd	  
		$_inhoud = "<br><br><br><br><br><br><h2>Lid $_voornaam $_naam is verwijderd.</h2>";

	
	
/*********************************************
*    output
**********************************************/
	
	  
  // menu initialiseren  
$_menu = 1;
// linkse commentaar veld  
$_commentaar = 'verwijderen_C.html';
  
require("../code/output.inc.php");
}

catch (Exception $_e)
{
  // exception handling funtions 
  include("../php_lib/myExceptionHandling.inc.php"); 
  echo myExceptionHandling($_e,"../logs/error_log.csv");
}


?>