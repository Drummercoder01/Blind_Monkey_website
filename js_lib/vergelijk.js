/*

The MIT License (MIT)

Copyright (c) Mon May 11 2020 Micky De Pauw Micky.depauw@webontwikkeling.info

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
 * Deze functie vergelijkt het paswoord en de confirmatie en geeft een boodschap
 * let op, dit werkt enkel met de gegeven id's
 */
function vergelijk() {
	if ((document.getElementById("paswoord").value != 				document.getElementById("confirmeer").value)
		|| (document.getElementById("paswoord").value ==""))
	{
		document.getElementById("boodschap").innerHTML = '<p>Paswoord en Confirmatie moeten gelijk zijn</p>';
		document.getElementById("verstuur").innerHTML = '';
	} else if (document.getElementById("paswoord").value != "") {
		document.getElementById("boodschap").innerHTML = '';
		document.getElementById("verstuur").innerHTML = '<input type=submit id=submit name=submit>';
	}
}
