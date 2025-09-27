<?php
function sendMail ($_to,$_onderwerp,$_bericht,$_header="")
{
  if ($_SERVER['SERVER_NAME'] != "localhost")
  {
	   ini_set("SMTP","send.one.com");
	   ini_set ("sendmail_from","msg@mijn_domein.be");
    mail($_to, $_onderwerp, $_bericht,$_header);
  }
  else
  {
    echo("<hr>to: $_to<br><br>onderwerp: $_onderwerp<br><br>Bericht: $_bericht<br><br>header: $_header<hr>");
  } 
  
}
?> 