<?php
try{
  
/******************
*Initialisatie
*******************/
require("../code/initialisatie.inc.php");

/***************************************************
* is het script op een "legale" manier opgestart
* is er een actie gedefinieerd
* is de actie gelijk aan  --> 4 - verwijderen
* is er een formulier (aanpassen)
*                            
* anders --> illegale toegang
***************************************************/

	if (! isset($_SESSION["actie"]) 
			|| 
			$_SESSION["actie"] != 4 
			||
			! isset($_POST["submit_aanpassen"]))
	{
		 throw new Exception("illegal access");
	}	
	
// verwerk inhoud van het formulier	
// copieer de inhoud van $_POST (super global) naar lokale parameters	
		
		// verwerk inhoud van het formulier	
		require("../code/inputUitpakken.inc.php");
    
  $_lidnr =$_POST["lidnr"];
    
		$_gemeentePK = PK_t_gemeente($_postcode, $_gemeenteNaam);
		
	 $_query= "UPDATE t_leden
				SET d_naam = '$_naam',
				d_voornaam = '$_voornaam',
				d_straat = '$_straat',
				d_nr = '$_nr',
				d_Xtr = '$_xtr',
				d_gemeente = '$_gemeentePK',
				d_tel = '$_telefoon',
				d_mob = '$_mob',
				d_mail = '$_mail',
				d_gender ='$_gender',
				d_soort = '$_soort'
				WHERE d_lidnr = '$_lidnr';";	
// Query naar DB sturen
  
		$_result = $_PDO -> query($_query);  
  
//gegevens van het lid zijn aangepast
		
		$_inhoud = "<br><br><br><br><br><br><h2>de gegevens voor&nbsp;&nbsp;$_voornaam&nbsp;&nbsp;$_naam zijn aangepast</h2>";
		
	
/*********************************************
*    output
**********************************************/	
  $_menu= 1;
	$_commentaar="L_data_aanpassen.html";
			
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
