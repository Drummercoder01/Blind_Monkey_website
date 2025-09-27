<?php 
/*
The MIT License (MIT)

Copyright (c) Mon May 15 2017 Micky De Pauw

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
*/


/**
 * deze functie gaat na of de gebruiker, op  basis van de rol, toegang heeft 
 * tot het script
 * Deze informatie komt uit de database (model) table --> ts_authorisation
 * in deze tabel vinden we voor elke rol of deze al dan niet toegang heeft 
 * tot het vermelde script (veld d_0 tot d_???).
 * geen authenticated user --> rol=0 --> anonyme surfer
 * @author Micky De Pauw
 **/

function authorised()
{
  global $_PDO; // gebruik global $_PDO ipv locale versie


  if (!isset($_SESSION["authenticated"])) // misschien persistente logon ?
  {
    // functie om persitente logon na te kijken
    require("../php_lib/persistentLogon.inc.php");
    // ophalen van de rol, ...
    persistentLogon();
  }
  // indien niet "authenticated" --> geen $_SESSION["rol"] --> rol=0
  // rol = 0 --> anonyme surfer  
  $_rol = (isset($_SESSION["rol"]))? isset($_SESSION["rol"]) : 0;

  // naam van het script ophalen met ".php"  
  $_script = basename($_SERVER['PHP_SELF']);

  // nagaan of op basis van de rol de user voor dit script authorised is 
  $_query = "SELECT * FROM ts_authorisation 
                       WHERE (d_script = '$_script' 
                              AND 
                              d_$_rol = 1);";
 
  $_result = $_PDO->query("$_query"); 

  if (($_result -> rowCount() == 0))  // not authorised --> fatal error
  {
    header("Location:../fatal/fatalError.html"); 
    exit;
  }
}

?> 