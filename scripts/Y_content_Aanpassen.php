<?php
try
{
	require("../code/initialisatie_admin.inc.php");
	$_info="";
	$_comment="";



	/*******************************************
*    (Input en) verwerking
********************************************/
	if (! isset($_GET["content"]) &&  ! isset($_POST["submit"]))  // geen formulier 
	{
		throw new exception("illegal access");
	}
	if (isset($_GET["content"]))
	{
		$_file= $_GET['content'];
		require("../php_lib/inlezen.inc.php");
		$_tekst =inlezen($_file);

		$_inhoud = "<h1>$_file edit</h1>
                                   <form action='$_srv' method=POST>
                                       <input type=hidden name=file value=$_file>
                                       <textarea name= tekst cols=200 rows=20>$_tekst</textarea>
                                       <script > 	
                                            CKEDITOR.replace( 'tekst' ); 
                                       </script>
                                   <br>
                                   <input type=submit name=submit value=Save> or ";
	}
	else
	{
		$_file = $_POST['file'];
		$_tekst= $_POST['tekst'];

		// file openen
		$_pointer = fopen("../content/$_file", 'wb');
		if (! $_pointer)
		{
			throw new Exception("file not opened");
		}


		// tekst weg-schrijven
		fwrite($_pointer, $_tekst);
		//file sluiten
		fclose($_pointer);

		$_inhoud= "<h2>Nieuwe inhoud voor &quot;$_file&quot; is weggeschreven</h2>";
	}
	//$_smarty instantieren en initialiseren  
	require_once("../smarty/mySmarty.inc.php");

	$_smarty->assign('inhoud',$_inhoud);
	// display it
	$_smarty->display('content.tpl');
}

catch (Exception $_e)
{
	// exception handling funtions 
	include("../php_lib/myExceptionHandling.inc.php"); 
	echo myExceptionHandling($_e,"../logs/error_log.csv");
}



?>