<?php
try
{

/****************************************************
*			Initialisatie
****************************************************/
require("../code/M_initialisatie.inc.php");

  
// is er al een mnemonic tabel geselecteerd (via formulier)
if (isset($_POST['mnem'])) // let op geen submit
{ 	
// verwerk inhoud van het formulier	
// copieer de inhoud van $_POST (super global) naar session variable	 
		$_SESSION['tabelIndex']= $_POST['mnem']; 
  
// consistency check (is de input correct?)  
			
		$_query = "SELECT d_tabel FROM t_mnemonic WHERE d_index = '".$_SESSION['tabelIndex']."';";
	
		$_result = $_PDO->query("$_query");
		
		if ($_result -> rowCount() > 0)
		{     
			 while ($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
			 {
// bewaar mnem tabel      
				  $_SESSION['mnemTabel']= $_row['d_tabel'];
    }
  }
		else
  {
// geselecteerde mnem tabel bestaat niet
	   throw new exception('database inconsistency'); 
		}
}
  
  
// toon formulier met vermelding van de geselecteerde tabel
if (isset($_SESSION['mnemTabel']))
{
		$_inhoud.= "je heb &quot;<strong>".$_SESSION['mnemTabel']."</strong>&quot; geselecteerd <br><hr><br>";
}
else
{
		$_inhoud.= "je hebt nog geen mnemonic tabel geselecteerd <br><hr><br>";
}
		 
// bepaal welke radio button (tabel) geselecteerd moet worden	
if (!isset($_SESSION['mnemTabel']))
{
		$_SESSION['tabelIndex']= 0;
}
  
// maak selectie formulier aan	
$_inhoud.="<h1>Mnemonic tabellen</h1>
           <form id='rbForm' method='post'action='$_srv'>"; 
	
// maak radio buttons (met event)
$_inhoud.= radioButton(
		            					"mnem",
   												    "t_mnemonic",
		 											     "d_index",
													      "d_mnemonic",
		 										     	0,
													      $_SESSION['tabelIndex'],											      "onclick=document.getElementById('rbForm').submit();");
$_inhoud.="</form>";
	
/*********************************************
*    output
**********************************************/
	
	$_commentaar = "M_home_C.html";
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