<?php
/*
The MIT License (MIT)

Copyright (c) Thu Apr 07 2016 Micky De Pauw

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORTOR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
-->

*/

/**
 * Deze functie kijkt of er een persistent aanlog cookie 
 * gezet is en of het valabel is.
 * @return [bool] [true   --> persistent aangelogged
 *                 false  --> niet persistent aangelogged]
 */

function persistentLogon()
{

	global $_PDO;
	$_time = time();
	
	//***************** persistentie cookie 
	if (isset($_COOKIE["auth"]))
	{
		list($_identifier, $_token) = explode(':', $_COOKIE["auth"]);

		if (ctype_xdigit ($_identifier) && ctype_xdigit ($_token)) 
		{ 
			// token en identifier correct aanwezig 
			// kijk nu of deze ook in de database aanwezig zijn 

			$_query="SELECT d_user, d_rol, d_logon 
											FROM ts_authentication 
		 												WHERE (d_token = '$_token' 
		      										 AND d_identifier = '$_identifier'
															 AND d_expire > $_time)";

			$_result = $_PDO->query($_query);

			if ($_result -> rowCount() == 1) // logon gekend
			{

				While ($_row = $_result -> fetch(PDO::FETCH_ASSOC))

					// logon gegevens ophalen
				{  
					// cookie gezet binnen de vorige x uur 
					// m.a.w. cookie nog niet afgelopen

					$_SESSION['user_id'] = $_row['d_user'];
					$_SESSION['rol'] = $_row['d_rol'];
					$_SESSION['authenticated'] = true;
					$_SESSION['logon'] = $_row['d_logon'];

					require_once("../php_lib/logSecurityInfo.inc.php");
					logSecurityInfo($_SESSION["logon"], "Persistent aangelogd");

					return (true); 
					// persistent ingelogged
				}
			} 
			else
			{
				return (false); 
				// combinatie identifier & token niet gevonden
			}
		}
		else
		{
			return (false);
			// aanwezig cookie niet correct
		}
	}
	else
	{
		return (false);
		// geen cookie aanwezig
	}
}
?>