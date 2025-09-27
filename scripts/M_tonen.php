<?php
try
{
  
require("../code/M_initialisatie.inc.php");
 
// Als er nog geen mnemonic tabel geselecteerd is
// start M_home.php (tussen- en selectie pagina)  
if (!isset($_SESSION['mnemTabel']) )
{
		header('Location: ../scripts/M_home.php');
}
	
$_inhoud.="<h1>".$_SESSION['mnemTabel']."</h1>";
	
// toon alle mnemonics uit de geselecteerde tabel
$_query = "SELECT * FROM ".$_SESSION['mnemTabel'].";";
	
$_result = $_PDO -> query("$_query"); 

if ($_result -> rowCount() > 0)
{
		$_inhoud.="<table style='width:60%'> ";
  while ($_row = $_result -> fetch(PDO::		FETCH_ASSOC)) 
		{
		   $_inhoud.= "<tr><td width='>50%'>".$_row['d_index']."</td><td width='50%'>".$_row['d_mnemonic']."</td></td></tr>";
	
		}
		
		$_inhoud.="</table>";
}
else
{
	 $_inhoud = "<br><br><br><br><br><br><h2>Geen menmonics in ".$_SESSION['mnemTabel']."</h2>";
}
	
/*********************************************
*    output
**********************************************/
	
$_commentaar = 'M_lezen_C.html';
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