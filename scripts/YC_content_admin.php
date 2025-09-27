<?php
try
{
	require("../code/initialisatie.inc.php");
	
	/*******************************************
*    (Input en) verwerking
********************************************/
	$_dir = opendir("../content"); // open de "content" folder

	if ($_dir == FALSE) // indien openen niet gelukt is ...
	{
		throw new Exception("Folder niet geopend");
	}


	while ( ($_file = readdir($_dir)) !== false) // lees alle files in folder
	{
		$_lijst[]=$_file; // plaats de file-naam in de array $_lijst
	}

	closedir($_dir); // sluit de folder

	sort($_lijst); // sorteer de array

	foreach ($_lijst as $_file)  // ga alle files uit de lijst af
	{
		$_file = trim($_file); // verwijder mogelijke spaties voor en achter

		// isoleer de I (info) of de C commentaar op het einde van  de file-naam
		$_lengte= strlen($_file);  //bepaal de lengte van de file-naam
		$_soort = substr($_file,($_lengte - 6),1); // isoleer I of C 

		if ($_soort == "I") // info files
		{
			$_info.= " <p><input type=button value='Aanpassen'
                       onclick='popUp(\"../scripts/Y_content_Aanpassen.php?content=$_file\")'> 
                                $_file </p>";
		}
		elseif ($_soort == "C") // commentaar files
		{
			$_comment.= " <p><input type=button value='Aanpassen'
                       onclick='popUp(\"../scripts/Y_content_Aanpassen.php?content=$_file\")'> 
                               $_file </p>";
		}
	}


	$_inhoud="<h2>Informatieve teksten (inhoud)</h2>$_info
					  <h2>Commentaar teksten (links)</h2>$_comment";
	/*******************************************
*    output
********************************************/  
	// menu definieren  
	$_menu = 100;
	// commentaar file definieren  
	$_commentaar = "Y_content_C.html";
	$_jsInclude=array("../js_lib/popUp.js");
  
	require("../code/output.inc.php");

}

catch (Exception $e)
{
	// exception handling funtions 
	include("../php_lib/myExceptionHandling.inc.php"); 
	echo myExceptionHandling($_e,"../logs/error_log.csv");
}



?>