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
* is er een formulier (submit)
*                            
* anders --> illegale toegang
***************************************************/

	if (! isset($_SESSION["actie"]) 
			|| 
			$_SESSION["actie"] != 4 
			||
			! isset($_POST["submit"]))
	{
		 throw new Exception("illegal access");
	}
	
	
//verwerk inhoud van het formulier	
// copieer de inhoud van $_POST[lidnr'] (super global) naar lokale parameter	$_lidnr
	$_lidnr = $_POST['lidnr'];

// Query samenstellen		
		$_query = "Select * FROM v_leden WHERE d_lidnr = '$_lidnr'"; 
   
   // Query naar DB sturen
  $_result = $_PDO -> query($_query); 
   
// Resultaat van query verwerken
	
// verwerk resultaat
	if ($_result -> rowCount() == 0) // geen resultaat is db inconsistency 
	{
		throw new Exception("database inconsistency");
	}
	
// hier gaan komen we enkel indien er geen 'db inconsistency'  was		
    while ($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
		{
// maak voor het geselecteerde lid een formulier en 
// vul de velden in met de huidige waarden	
// voorzie een 'hidden field' met de lidnr			
			$_inhoud = 
      "<h1>Aanpassen</h1>
       <form  method='post' action='../scripts/L_data_aanpassen.php'>
         <input type ='hidden' name ='lidnr' value ='".$_row['d_lidnr']."'>
         <label>Naam</label>
           <input type='text' name='naam' value ='".$_row['d_naam']."'>
         <label >Voornaam</label>
           <input type='text' name='voornaam'value ='".$_row['d_voornaam']."'>
		       <label >Gender</label>";
		  $_inhoud .= dropDown("gender","t_gender","d_index", "d_mnemonic",1,$_row['d_gender']);
    $_inhoud.="<label >Soort lid</label>";
    $_inhoud .= dropDown("soort","t_soort_lid","d_index", "d_mnemonic",1,$_row['d_soortlid']);
    $_inhoud.="
        <label >Straat</label>
           <input type='text' name='straat'  value ='".$_row['d_straat']."'>
        <label >Nr & Extra</label>
          <input type='text' name='nr' size='10' value ='".$_row['d_nr']."'>
          <input type='text' name='xtr' size='10' value ='".$_row['d_Xtr']."'>
       <label >Postcode</label>
          <input type='text' name='postcode' size='10' value ='".$_row['d_Postnummer']."'>
       <label >Gemeente</label>
          <input type='text' name='gemnaam'size='20' value ='".$_row['d_GemeenteNaam']."'>
       <label >Telefoon</label>
         <input type='text' name='tel' size='15' value ='".$_row['d_tel']."'>
       <label >Mobiel</label>
         <input type='text' name='mob' size='15' value ='".$_row['d_mob']."'>
 	     <label >E-mail</label>
         <input type='text' name='mail' size='80' value ='".$_row['d_mail']."'>
		    <input type=submit name=submit_aanpassen  value=Aanpassen>
     </form>";	
		}
				
/*********************************************
*    output
**********************************************/	
 
	$_menu = 1;
	$_commentaar="L_data_aanpassen_C.html";
			
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