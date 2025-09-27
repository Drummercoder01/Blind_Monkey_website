<?php
/*
The MIT License (MIT)

Copyright (c) Fri Apr 08 2016 Micky De Pauw

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
 * Deze functie logt de beschikbare security informatie bij het 
 * aan- en uit-loggen en bij authorisation checks en plaatst deze 
 * in "../log/securityLog.csv"
 * @param [[Type]] $_logon  [de logon vande gebruiker]
 * @param [[Type]] $_action [de actie die de gebruiker uitvoerde]
 */
function logSecurityInfo($_logon,$_action)
{
  //log de security info
	$_error_log[1] = strftime("%d-%m-%Y %H:%M:%S");
  $_error_log[2] = $_logon;
  $_error_log[4] = $_action;

  $_pointer = fopen("../logs/securityLog.csv","ab");
  fputcsv($_pointer,$_error_log);
  fclose($_pointer);
}
?>