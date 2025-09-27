<?php
try
{
/****************************************************
*			Initialisatie
****************************************************/
require("../code/initialisatie.inc.php"); 

/***************************************************
* is het script op een "legale" manier opgestart
* is er een actie gedefinieerd
*
* anders --> illegale toegang
***************************************************/
  if (! isset($_GET["act"]))  // geen actie gedefinieerd
	{
		 throw new Exception("illegal access");
	}
	
// copier de actie vanuit $_get naar de sessie variabele	
	$_SESSION["actie"]= $_GET["act"];

// verschillende details voor de verschillende acties	
// $_srv --> volgende functie/script	
// $_SESSION['comment']	--> text die in het commentaar-veld 
//                          van de template komt
// $_start --> start positie voor de drop-downs 
//             (soort-lid & gender)	
	switch ($_SESSION["actie"])					
	{		
		case 1: // lezen
			$_srv= "../scripts/l_tonen.php";
			$_SESSION['comment']= "L_lezen_C.html";
			$_start=0;
		break;
			
		case 2: // toevoegen
			$_srv= "../scripts/l_toevoegen.php";
			$_SESSION['comment'] = "L_toevoegen_C.html";	
			$_start=1;
		break;
		
		case 3: // verwijderen
		  $_srv= "../scripts/l_tonen.php";
		  $_SESSION['comment'] = "L_verwijderen_C.html";
		  $_start=0;
		break;
			
		case 4: // aanpassen
			$_srv= "../scripts/l_tonen.php";
			$_SESSION['comment'] = "L_aanpassen_c.html";	
			$_start=0;			
		break;
				
		default: // alle andere waarden
		throw new Exception("illegal action");
	
	}
  
 require("../code/selectionForm.inc.php");// selectie formulier	
		
	
/*********************************************
*    output
**********************************************/
	
	$_commentaar = $_SESSION['comment'];
	$_menu = 1;
	
	require("../code/output.inc.php");
// bevat alle code nodig om output te genereren
// instantiering van het smarty object
// toewijzen van de smarty variabelen
// koppelen met de gewenste template	
}

catch (Exception $_e)
{
  // exception handling funtions 
  include("../php_lib/myExceptionHandling.inc.php"); 
  echo myExceptionHandling($_e,"../logs/error_log.csv");
}


?>