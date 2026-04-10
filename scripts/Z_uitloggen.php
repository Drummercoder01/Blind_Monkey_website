<?php
try
{
	require_once("../code/initialisatie_admin.inc.php");

	If (!isset($_POST['submit']))
	{
		$_inhoud= "
		<h1>End session</h1>
		<div class='alert alert-info my-4' role='alert'>
			End session
		</div>
		<div class='row'>
			<div class='col-12 mb-4'>
				<div class='card'>
					<div id='logoff'>
						<form  method='post' action='$_srv' class='card-body'>
							<div class='mb-3 form-check'>
								<input type='checkbox' name='persist' class='form-check-input' id='desloguearse'>
								<label class='form-check-label' for='desloguearse'>Forget my login</label>						
							</div>
							<input type='submit' name='submit' value='log out' class='btn btn-primary'>
						</form>
					</div>			
					<div class='card-body text-center'>
						
					</div>
				</div>
			</div>
		</div>";		
	}
	else
	{
		if (isset ($_POST['persist'])) // persistente  log-out 
		{
			// alle persistentie velden op hun default waarde       
			$_user_id = $_SESSION['user_id'];
			$_query = "UPDATE  ts_authentication 
                        SET 
                        d_token = ' ',
                        d_identifier = ' ',
                        d_expire= 0
                     WHERE d_user ='".$_SESSION['user_id']."'";
			$_PDO->query($_query);
			$_action=" Persistent uitgelogd";
		}
		else
		{
			$_action=" Uitgelogd";
		}
		
		require_once('../php_lib/logSecurityInfo.inc.php');

		logSecurityInfo($_SESSION['logon'], $_action);
	
		session_destroy(); // vernietig de sessie
		header('Location:../totZiens/totZiens.html'); // keer terug naar de logon-pagina
		exit;
	}

	$_commentaar = "C_home_C.html";
	$_menu = 0;

	require("../code/output_admin.inc.php");
}

catch (Exception $_e)
{
	// exception handling funtions 
	include("../php_lib/myExceptionHandling.inc.php"); 
	echo myExceptionHandling($_e,"../logs/error_log.csv");
}



?>