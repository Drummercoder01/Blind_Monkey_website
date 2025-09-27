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

// toon alle mnemonics  > 0 (default mag niet verwijderd worden)
		$_query = "SELECT * FROM ".$_SESSION['mnemTabel']." WHERE d_index > 0;";
	
		$_result = $_PDO -> query("$_query"); 

		if ($_result -> rowCount() > 0)
		{
		
			$_inhoud.="<form  method='post' action='$_srv'><table style='width:60%'> ";
		
    	while ($_row = $_result -> fetch(PDO::		FETCH_ASSOC)) 
			{
	
// value voor checkbox verwijder[] --> $_row['d_index']			
				 $_inhoud.= "
     <tr>
      <td width='80%'>".$_row['d_mnemonic']."</td><td>
				   <input type=checkbox name= 'verwijder[]' value='".$_row['d_index']."'></td>
     </tr>";
	
			}
// 1 submit button voor alle aanpassingen	
			$_inhoud.="</table><br><input name='submit' id='submit' type='submit' value='verzenden'>";
  }
  else
  {
	   	$_inhoud = "<br><br><br><br><br><br><h2>Geen mnemonics in ".$_SESSION['mnemTabel']."</h2>";
  }
	}
	 
	else // verwerk formulier
	{

		
//$_verwijder is een array 
		 $_teVerwijderen = $_POST['verwijder'];

   
// output parameter --> hoeveel mnemonics zijn er aangepast
		 $_aantal = 0; 
// bepaal welke tabel + kolom (dataveld(en)) we moeten nazien

   $_query = "SELECT d_tabel, d_veld FROM t_mnemUsedBy where d_mnemtabel  = '".$_SESSION['tabelIndex']."';"; 
   
   $_result = $_PDO -> query("$_query"); 

   if ($_result -> rowCount() > 0)
   {
     
     while ($_row = $_result -> fetch(PDO::		FETCH_ASSOC)) 
		   {
        $_use[] =$_row;
       // $_use bevat alle 'usage' voor de geselecteerd mnemonic
     }
		 }
   else
   {
     throw("db inconsistency");
   }
		
  
  // voor elke mnemonic in de input		
		foreach ($_teVerwijderen as $_key => $_waarde)
  {
// enkel niet gebruikte mnemonics kunnen verwijderd worden  
    $_ok = true; // geen belet 
   
    foreach ($_use as $_usage) //elke "usage" nakijken
    {
      
      $_tabel=$_usage['d_tabel'];
      $_veld=$_usage['d_veld'];
     
      $_query= "SELECT * FROM $_tabel where $_veld= '$_waarde';";

      $_result_u = $_PDO -> query("$_query");
    
      if ($_result_u -> rowCount() > 0) // mnemonic in gebruik
      {
        $_ok = false;
        break;  
      }
    }
    
    if($_ok)
    {
      $_query="DELETE FROM ". $_SESSION['mnemTabel']." WHERE d_index = $_waarde;";
    
      $_result_d = $_PDO -> query("$_query");
      $_aantal++; // delete teller
    }
  }
		$_inhoud.= "$_aantal mnemonics verwijderd uit ".$_SESSION['mnemTabel'];
}
 
/*********************************************
*    output
**********************************************/
	
$_commentaar = 'M_verwijderen_C.html';
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