<?php
session_start();
/******************
*Initialisatie
*******************/
try {
	//$_srv gebruiken we als "action" in onze formulieren
	$_srv = $_SERVER['PHP_SELF'];

	// instantiering van $_PDO (connectie met dbms en selectie van de datbase)
	include("../connections/pdo.inc.php");
	// functie om strings te encrypten
	require("../php_lib/encrypt.inc.php");


	if (isset($_GET['k']))   //via mail
	{ 
		$_resetKey = addslashes($_GET['k']);
		$_nu = time();

		// is de ontvangen reset key ok
		// correct en niet uit-getimed
		$_query= "SELECT d_user , d_logon
              FROM ts_authentication 
		      WHERE	(d_resetKey = '$_resetKey'
		      AND d_resetTimer >= '$_nu')"; 
		
		$_result = $_PDO -> query("$_query"); 
		
		if (($_result -> rowCount() > 0)) 
		{
			while($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
			{
				$_SESSION['user'] = $_row['d_user']; // voor verdere selecties
				$_SESSION['logon'] = $_row['d_logon']; // voor reporting en encrypting
			}
		}
		else
		{

			require_once("../php_lib/logSecurityInfo.inc.php");
			
			logSecurityInfo("onbekende User","Onbekende reset-key of reset niet meer geldig");

			header("Location:../fatal/fatalError.html"); 

		}


		// formulier om nieuw  paswoord in te geven  
		$_inhoud = "<div id=container>
					<h2>Nieuw paswoord</h2>
					<form action='$_srv' method='post'>
					<label>Paswoord</label>
					<input type='password' id='paswoord' name= 'paswoord' 
					onKeyUp='vergelijk()'>
					<label>Confirmeer</label>
					<input type='password' id='confirmeer' name= 'confirmeer'
 					onKeyUp='vergelijk()'>
					<div id='boodschap'> 
					&nbsp;
					</div>
					<div id='verstuur'>
					&nbsp;
					</div>
					</form>
                    </div>";   
	}

	elseif (isset($_POST['submit']))
	{
		// verwerk ingegeven paswoord    
		$_paswoord =encrypt($_POST['paswoord'],$_SESSION['logon']); 

		$_user = $_SESSION['user'];

		$_query= "UPDATE ts_authentication
				  SET d_paswoord = '$_paswoord',
				  d_identifier = '',
				  d_token = '',
				  d_expire = 0,
                  d_faultCntr = 0,
                  d_timeout = 0,
                  d_resetKey = '',
                  d_resetTimer = 0
				WHERE d_user = $_user;";	

		$_result = $_PDO -> query("$_query"); 

		header("Location:../scripts/A_logon.php"); 
	}

	else
	{
		throw new Exception("illegal access");
	}

	/*******************************************
*    output
********************************************/  
	//$_smarty instantieren en initialiseren  
	require_once("../smarty/mySmarty.inc.php");

	$_smarty->assign('inhoud',$_inhoud);
	// display it
	$_smarty->display('reset.tpl');


}

catch (Exception $_e)
{
	// exception handling funtions 
	include("../php_lib/myExceptionHandling.inc.php"); 
	echo myExceptionHandling($_e,"../logs/error_log.csv");
}


?>