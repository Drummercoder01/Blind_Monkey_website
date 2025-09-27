<?php
try{
  
/******************
*Initialisatie
*******************/
require("../code/initialisatie.inc.php"); 


/***************************************************
* is het script op een "legale" manier opgestart
* is er een actie gedefinieerd
* is de actie gelijk aan  --> 1 - lezen
*													               3 - verwijderen
*                             4 - aanpassen
* anders --> illegale toegang
***************************************************/
  if (
			! isset($_SESSION["actie"]) 
			||
			 ($_SESSION["actie"] != 1
				&&
		 		$_SESSION["actie"] != 3
				&&
		 		$_SESSION["actie"] != 4)
    ||
			! isset($_POST["submit"])
		 )
  {
	  throw new Exception("illegal access");
  }

	
// verwerk inhoud van het formulier	
// copieer de inhoud van $_POST (super global) naar lokale parameters	
		
 require("../code/inputUitpakken.inc.php");	

// Maak met de ingevoerde waarden de bijhorende query.
// hiervoor gebruiken we de functie createSelect
// Parameter 1 --> de bijhorende tabel/view
// Parameter 2 --> de lijst van ingevoerde waarden (array)		
// Parameter 3 --> de lijst van bijhorende velden in de tabel/view (array)		
		
  require("../code/useCreateSelect.inc.php");

// stuur de query naar het dbms
	
  $_result = $_PDO -> query("$_query"); 

// verwerk het resultaat van de query		
  if ($_result -> rowCount() > 0)
  {

	  while ($_row = $_result -> fetch(PDO::		FETCH_ASSOC)) 
	  {

//toon alle gevonden leden 	
		  require("../code/toonData.inc.php");
				
// verschillende details voor de verschillende acties	
// Lezen       --> na elk lid een horizontale lijn 
// verwijderen --> na elk lid een confirmatie	formulier
//                 met verwijzing naar de volgende functie
//                 (L_verwijder.php)
//                 gevolgd door een horizontale lijn	
// Aanpassen   --> na elk lid een confirmatie	formulier
//                 met verwijzing naar de volgende functie
//                 (L_aanpassen.php)
//                 gevolgd door een horizontale lijn
// exception voor alle andere waarden				
				
		  switch ($_SESSION["actie"])			
		 {		
		  case 1: //lezen
					  
			break;	
		
			case 3:	// verwijderen 	  
						$_inhoud.= "<form  method=post action=L_verwijderen.php>
        <input type=hidden name=lidnr  value='".$_row['d_lidnr']."'>
        <input type =hidden name=voornaam value='".$_row['d_voornaam']."'>
        <input type =hidden name=naam value='".$_row['d_naam']."'>
				    <input type=submit name=submit_bev  value=Verwijder>
      </form>";
			break;
		
			case 4: // aanpassen
						$_inhoud.= "<form  method='post' action='L_aanpassen.php'>
						<input name='lidnr' type='hidden' value='".$_row['d_lidnr']."'>
						<input class='knopl' name='submit' type='submit' value='Pas aan'>
						</form>";
			break;
				
			default: // alle andere waarden inclusief 2 (toevoegen)
			      throw new Exception("illegal action");
	
		 }
     $_inhoud.= "<br><br><hr>";
    }
   }
   else // geen resultaten voor de gegeven input
   {
	   $_inhoud = "<br><br><br><br><br><br><h2>Geen records gevonden voor deze input</h2><br><br><br><br><br><br><h2>Lid verwijderd.</h2>";
   }	
/*********************************************
*    output
**********************************************/

	 $_menu= 1;
	 $_commentaar=$_SESSION['comment'];
	
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