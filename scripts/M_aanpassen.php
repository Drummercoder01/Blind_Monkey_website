<?php
try
{
  
require("../code/M_initialisatie.inc.php");
  
// is er al een mnemonic tabel geselecteerd ?
	if (!isset($_SESSION['mnemTabel']))
	{
		header('Location: ../scripts/M_home.php');
	}
	
	$_inhoud.="<h1>".$_SESSION['mnemTabel']."</h1>";
	
	if (!isset($_POST['submit'])) //via url
	{

// toon alle mnemonics 
		$_query = "SELECT * FROM ".$_SESSION['mnemTabel'].";";
	
		$_result = $_PDO -> query("$_query"); 

		if ($_result -> rowCount() > 0)
		{
		
			$_inhoud.="<form  method='post' action='$_srv'><table style='width:60%'> ";
		
    	while ($_row = $_result -> fetch(PDO::		FETCH_ASSOC)) 
			{
// toon oude mnemonic in input veld --> newMnem[]
// hide oude mnemonic in hidden veld --> oldMnem[]	
// hide numerieke waarde (index) in hidden veld --> index[]				
				$_inhoud.= "
    <tr>
      <td width='20%'>".$_row['d_index'].
      "</td><td width='80%'><input type='text' name='newMnem[]' size='20'value='".$_row['d_mnemonic']."'>
		 		  <input type='hidden' name='oldMnem[]' value='".$_row['d_mnemonic']."'>
				   <input type='hidden' name='index[]' value='".$_row['d_index']."'></td>
    </tr>";
	
			}
// 1 submit button voor alle aanpassingen	
			$_inhoud.="</table><br><input name='submit' id='submit' type='submit' value='verzenden'>";
  }
  else
  {
	   	$_inhoud = "<br><br><br><br><br><br><h2>Geen menmonics in ".$_SESSION['mnemTabel']."</h2>";
  }
	}
	 
	else // verwerk formulier
	{

		
//$_newMnem, $_oldMnem en $_index zijn arrays
		 $_newMnem = $_POST['newMnem'];
   $_oldMnem = $_POST['oldMnem'];
   $_index = $_POST['index'];
		
   $_aantal = 0;  
   // output parameter --> hoeveel mnemonics zijn er aangepast
		
// voor elke mnemonic in de input		
		foreach ($_index as $_key => $_waarde)
  {
    if ($_newMnem[$_key] != $_oldMnem[$_key]) // is er een verandering
				{
					$_aantal++;
					$_query ="UPDATE ".$_SESSION['mnemTabel'].
						" SET d_mnemonic = '".$_newMnem[$_key].
						"'WHERE d_index = '$_waarde';";	
					
					$_result = $_PDO -> query("$_query"); 
				}
  }
     $_inhoud.= "$_aantal mnemonics aangepast in ".$_SESSION['mnemTabel'];
	}
	
/*********************************************
*    output
**********************************************/
	
$_commentaar = 'M_aanpassen_C.html';
$_menu = 103;
	
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