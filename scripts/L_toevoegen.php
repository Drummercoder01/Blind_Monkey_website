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
* is de actie gelijk aan  --> 2 - toevoegen
* is er een formulier 
*                            
* anders --> illegale toegang
***************************************************/

	if (! isset($_SESSION["actie"]) 
			|| 
			$_SESSION["actie"] != 2 
			||
			! isset($_POST["submit"])
		 )
	{
		 throw new Exception("illegal access");
	}

// verwerk inhoud van het formulier	
// input uitpakken
		require("../code/inputUitpakken.inc.php");
		
		$_gemeentePK = PK_t_gemeente($_postcode, $_gemeenteNaam);
		

/*******************************************
*    consistency checks
********************************************/		
// nakijken of "nieuw lid" al bestaat
// hiervoor gebruiken we de functie createSelect
// Parameter 1 --> de bijhorende tabel/view
// Parameter 2 --> de lijst van ingevoerde waarden (array)		
// Parameter 3 --> de lijst van bijhorende velden in de tabel/view (array)				

  require("../code/useCreateSelect.inc.php");
		
// verstuur de query naar het dbms
		$_result = $_PDO -> query("$_query"); 

// verwerk de resultaten van de query			
		if ($_result -> rowCount() > 0) 
// lid bestaat reeds
		{
// meld dat lid reeds bestaat
		 $_inhoud = "<br><br><h2> Lid is al ingevoerd!</h2>";	
   	}
   	else 
// lid bestaat nog niet			
   	{
// maak insert query
// tabel --> t_leden
// primary key wordt niet megegeven vermits we voor de tabel "auto increment (ai)" geactiveerd hebben
			
   		// Query samenstellen				
   		 $_query = "INSERT INTO t_leden (d_naam, d_voornaam, d_straat, d_nr, d_xtr, d_gemeente, d_tel, d_mob,  d_mail, d_gender, d_soort) VALUES ('$_naam', '$_voornaam','$_straat', '$_nr', '$_xtr', '$_gemeentePK', '$_telefoon','$_mob','$_mail', '$_gender', '$_soort');"; 
// primary key wordt niet meegegeven. --> "auto -increment (ai)  
      
      // Query naar DB sturen
		    $_result = $_PDO -> query("$_query"); 

//nieuw lid is toegevoegd			
	   $_inhoud = "<br><br><br><br><br><br><h2>Lid &nbsp;&nbsp;$_voornaam &nbsp;&nbsp;$_naam&nbsp;&nbsp;is toegevoegd</h2>";
   	}	
	
/**********************************************
*    output
**********************************************/


// menu initialiseren  
$_menu = 1;
// linkse commentaar veld  
$_commentaar = 'L_toevoegen_C.html';
  
require("../code/output.inc.php");
}

catch (Exception $_e)
{
  // exception handling funtions 
  include("../php_lib/myExceptionHandling.inc.php"); 
  echo myExceptionHandling($_e,"../logs/error_log.csv");
}

?>