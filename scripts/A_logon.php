<?php
try{

	//*************** initalisatie ***************  
	session_start();

	$_SESSION["logon"]="logon";
	// instantiering van $_PDO 
	// (connectie met dbms en selectie van de datbase)
	require("../connections/pdo.inc.php");
	// functie om strings te encrypten
	require("../php_lib/encrypt.inc.php");
	// functie om persitent logon na te kijken
	require("../php_lib/persistentLogon.inc.php");
	// functie om security informatie te loggen
	require_once("../php_lib/logSecurityInfo.inc.php");

	$_srv = $_SERVER['PHP_SELF'];


	//*************** "persistent" aangelogd ??? *************** 
	if (persistentLogon())  
	{   
		header('Location:../scripts/A_home.php'); // ga verder
	} 

	if (!isset($_POST['submit']))
	{
		//***************** formulier (in template) **************
		// is er een boodschap meegestuurd ?
		$_msg = (isset($_GET["msg"]))? $_GET["msg"] : " "; 
		// Smarty initialiseren
		require("../smarty/mySmarty.inc.php");
		// smarty variabelen toevoegen	
		$_smarty->assign('action', $_srv);
		$_smarty->assign('msg', $_msg);
		// display it
		$_smarty->display('logon.tpl');


	}
	else
	{

		//***************** voorbereidingen **************

		// externe niet gecontroleerde data naar interne gecontroleerde data
		$_logon = Htmlspecialchars($_POST['logon']);

		// encrypted paswoord salt is logon
		$_paswoord =encrypt($_POST['paswoord'],$_logon); 
		$_persistent = isset($_POST['persist']); //true indien aangevinkt

		//***************** bestaat het logon ? **************
		$_query = "SELECT * FROM ts_authentication WHERE d_logon = '$_logon';"; 

		$_result = $_PDO->query($_query); 

		if (($_result -> rowCount() != 1)) // logon niet gekend
		{

			logSecurityInfo($_logon, "Logon niet gekend");
			// opnieuw login scherm aanbieden

			header("Location:../scripts/A_logon.php?msg=Logon niet gekend"); 

			//beëindig script;
			exit;
		}

		while($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
		{
			$_user = $_row['d_user'];

			//***************** is het paswoord correct ? **************	
			if ($_paswoord != $_row['d_paswoord']) 
			{
				$_timeOut = 0;  
				$_faultCntr = $_row['d_faultCntr'] + 1; // foutteller incrementeren

				if ($_faultCntr >= 3)
				{
					$_timeOut = time()+(60*60*3); // 3 uur
					$_msg="Gebruiker werd geblokkeerd.";
					$_faultCntr = 0; // na 3 uur mag je opnieuw proberen
				}
				else
				{
					$_timeOut = 0;
					$_pogingen = 3 - $_faultCntr;
					$_msg="Foutief paswoord.(nog $_pogingen pogingen)";
				}	

				$_query = "UPDATE ts_authentication
								SET d_faultCntr = $_faultCntr,
								d_timeOut = $_timeOut
                         WHERE d_user = $_user;";

				$_result = $_PDO -> query($_query);	
				logSecurityInfo($_logon,$_msg);
				// opnieuw login scherm aanbieden
				header("Location:$_srv?msg='$_msg'"); 

				//beëindig script;
				exit;

			}

			if ($_row['d_timeOut'] > time()) 
			{
				// timeout nog niet afgelopen -_> verleng time-out      
				$_timeOut = time()+(60*60*3);

				$_query ="UPDATE ts_authentication
								           SET d_timeOut = '$_timeOut'
								               WHERE d_user = $_user;";

				$_result = $_PDO -> query($_query);	
			} 
// session variables met authentication en authorisation informatie
			$_SESSION['user_id'] = $_row['d_user'];
			$_SESSION['rol'] = $_row['d_rol'];
			$_SESSION['logon'] = $_row['d_logon'];	
			$_SESSION['authenticated'] = TRUE;

		}

		if ($_persistent)
		{       
			$_salt = time(); 
			$_identifier = encrypt ($_logon,$_salt);
			$_token = encrypt (uniqid(rand(), TRUE)); 
			$_expire = time() + (60 * 60 * 8);
			setcookie('auth', "$_identifier:$_token", $_expire); 
			$_action = "Persistent ingelogd";
		}
		else
		{
			$_identifier = "";
			$_token = ""; 
			$_expire = 0;
			$_action = "Ingelogd";
		}


		$_query="UPDATE ts_authentication
					SET d_faultCntr = '0',
					d_timeOut = '0',
                    d_token = '$_token',
                    d_identifier = '$_identifier',
                    d_expire= '$_expire'
					WHERE d_user = '$_user';";

		$_result = $_PDO -> query($_query);	



		logSecurityInfo($_logon,$_action);

		// Alles OK --> ga verder
		header('Location:../scripts/admin_about.php');	
    
		//beëindig script;
		exit;
	}
}

catch (Exception $_e)
{
 // exception handling funtions 
  include("../php_lib/myExceptionHandling.inc.php"); 
  echo myExceptionHandling($_e,"../logs/error_log.csv");
}
?>
