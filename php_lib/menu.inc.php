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
 * Deze functie haalt de nodige info uit de database (t_menu) 
 * om het opgegeven menu te tonen.
 * Let op, deze functie werkt enkel met de correcte "smarty code" in de template
 * @param  [string] $_bestand [identificatei van het menu]
 * @return [associatieve array] [naam en link voor menu items]
 */
/**************************************************************************************/
function menu($_menu)
{
  global $_PDO;
  $_output[0] = "ERROR";
  $_rol = (isset($_SESSION [rol]))? $_SESSION['rol'] : 0;

  $_query = "SELECT d_item, d_link 
                             FROM t_menu 
                             WHERE  d_menu = $_menu    
                                             AND 	
                                              d_$_rol='1' 
                             ORDER BY d_volgorde ASC;";

  $_result = $_PDO->query($_query);
  $_x = 0;
  while($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
  {
    $_output[$_x]= $_row;
    $_x++;
  }
  return($_output);
}
