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
 * Deze functie verstuurd een email naar php.mail onder het AJAX protocol.
 * Eerst wordt er nagezien of al de velden ingevuld zijn en 
 * worden er aanwijzingen gegeven indien dit niet het geval is.
 * let op; deze functie werkt enkel met de gegeven id's.
 * Deze functie werkt samen met "clearUsermsg()"
 */
function contactForm() 
{

	if (document.getElementById('naam').value == "") 
	{
		document.getElementById('userMSG').innerHTML = '<h4>Gelieve je naam in te vullen</h4>';
	} 
	
	else if (document.getElementById('mail').value == "") 
	{
		document.getElementById('userMSG').innerHTML = '<h4>Gelieve je email in te vullen</h4>';
	} 
	
	else if (document.getElementById('opm').value == "") 
	{
		document.getElementById('userMSG').innerHTML = '<h4>Heb je geen vraag of opmerking</h4>';
	} 
	
	else 
	{
	
    
    
		var boodschap = "Boodschap via de website" + "<br>" + "van -->" + document.getElementById('naam').value + "<br>" + "mail -->" + document.getElementById('mail').value + "<br>" + "inhoud-->" + document.getElementById('opm').innerHTML;

		xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = function () 
		{
			
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				document.getElementById('userMSG').innerHTML = '<h3>We hebben jouw vraag goed ontvangen !</h3>';
				document.getElementById('naam').value = "";
				document.getElementById('mail').value = "";
				document.getElementById('opm').value = "";
			}
		}	

			xmlhttp.open("GET", "phpmail.php?b=" + boodschap, true);
			xmlhttp.send();
		
	}
}