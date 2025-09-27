<?php
try
{
  
require("../code/M_initialisatie.inc.php");
  
// is er al een mnemonic-tabel geselecteerd
	if (!isset($_SESSION['mnemTabel']))
	{
		header('Location: ../scripts/M_home.php');
	}
	
	$_inhoud.="<h1>".$_SESSION['mnemTabel']."</h1>";
	
	if (!isset($_POST['submit'])) // geen formulier
	{
	 //formulier om toe te voegen menmonics op te geven
		$_inhoud.="<form method='post' action='$_srv'>
               <label>Mnemonics</label>
                 <textarea name='mnem' rows=10 cols=30></textarea>
		             <input name='submit' id='submit' type='submit' value='verzenden'>
             </form>";
	}
	else // verwerk formulier
	{
// copieer $_POST['mnem']	naar $_mnemString 
// enverwijder alle spaties
		$_mnemString = str_replace(' ', '', $_POST['mnem']);
   
//zet de "csv" string $_mnemString om naar een array $_mnemArray		
		$_mnemArray = explode(",",$_mnemString);
		
// mnemonic tabellen zijn niet auto-increment	 (0)	
// zoek laatste d_index voor de gekozen mnemonic tabel
		
		$_query = "SELECT MAX(d_index) AS HoogsteIndex FROM ". $_SESSION['mnemTabel'] .";";
		
		$_result = $_PDO -> query("$_query"); 

  while ($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
		{
		   $_index = $_row['HoogsteIndex'];
		}
		
		// voeg alle mnemonics toe in 1 query
		
		$_first = TRUE;
   
		$_query = "INSERT INTO ". $_SESSION['mnemTabel'] .
            " (d_index,d_mnemonic) VALUES ";
   
		foreach ($_mnemArray as $_mnemonic)
  {
// de eerste keer moet er geen komma gezet worden VOOR de waarde,
			if (! $_first) 
			{
				$_query.= ", ";
			}
			else
			{
				$_first = FALSE;
			}
			
			$_index++; //verhoog met 1 (volgende record / lijn)
			
			// voeg combinatie index <--> mnemonic toe
			$_query.= "('$_index','$_mnemonic')";
					
  }

// verstuur query		
		$_result = $_PDO -> query($_query); 

// stel output samen
		$_inhoud .="<p>".count($_mnemArray)." mnemonics toegevoegd in ".$_SESSION['mnemTabel']."<br><a href='$_srv'>volgende</a>";
	}
   
/*********************************************
*    output
**********************************************/
	
$_commentaar = 'M_toevoegen_C.html';
$_menu =103;
	
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