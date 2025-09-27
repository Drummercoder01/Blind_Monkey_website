<?php

try{
require("../code/initialisatie.inc.php");
/*******************************************
*    Input en verwerking
********************************************/
if (! isset($_POST["submit"]))  // geen formulier 
{
// toon  formulier
  
// default - waarde voor datumveld  
// let op datum formaat voor formulier 
// --> "jaar-maand-dag" 
  $_vandaag =strftime("%Y-%m-%d");

//formulier  
  $_inhoud=
		"<h1>Exception-log</h1>  
    <form  method=post action='$_srv'>
      Verwijder Exceptionstot en met <br>
      <input type=date name=einde value='$_vandaag'>
      <div class=clearfix></div>
      <br>
		    <input type=submit name=submit value=Verwijder>
    </form>";
}
  
else // inhoud formulier verwerken
{
// input uitpakken 
// let op datum-formaat uit formulier --> "jaar-maand-dag"  
  

// 1: jaar, dag, maand splitsen 
  $_datum = $_POST['einde'];
  list($_jaar, $_maand, $_dag) = explode("-",$_datum,3);
  
// 2: datum voor in scherm output
  $_datum = $_dag."-".$_maand."-".$_jaar;

// 3: om te kunnen gebruiken invergelijkingen -> 
//    einde-dag omzetten naar timestamp (23 uur 59 min 59 sec) 
//    omzetten naar naar timestamp -->
//    mktime(uur,min,sec,maand,dag,jaar)
  $_einde= mktime(23,59,59,$_maand,$_dag,$_jaar);

  
  
/****************************************
 *    CSV file ../logs/error_log.csv    *
 *    verwerken                         *
 ****************************************/    
  
// actuele csv file openen  A
  $_pointerA = fopen("../logs/error_log.csv","rb");
  
  if (! $_pointerA)
  {
    throw new Exception("Opening actuele error_log failed");
  }
// tijdelijke csv file openen T  
  $_pointerT = fopen("../logs/tijdelijke_error_log.csv","w+b");
  
  if (! $_pointerT)
  {
    throw new Exception("Opening tijdelijke error_log failed");
  }
  $_output=""; 
  
  $_exceptionCounter=0;
  
// Actuele error-log (csv file) uitlezen  --> $_pointerA
  while(! feof($_pointerA))
  {
	   $_error_log =fgetcsv($_pointerA);	 
       
    if (! feof($_pointerA))
	   {
// inhoud CSV splitsen     
      list($_tijd, $_msg, $_script, $_lijn) = $_error_log;
      
// $_tijd bestaat uit 'dag-maand-jaar uur:min:sec'     
// 1: inhoud $_tijd eerst splitsen in 2 delen $_d en $_t
      list($_d, $_t) = explode(" ",$_tijd,2);
   
// 2: $_d exploderen op basis van '-' 
//    let op formaat in $_d is 'dag-maand-jaar' daarom
//    geeft deze actie 3 delen $_jaar $_maand $_dag     
      list($_dag, $_maand, $_jaar) = explode("-",$_d,3);
      
// 3: om te kunnen vergelijken met de ingegeven 
//    zetten we de gelezen datum omnaar een timestamp
      $_errorDatum= mktime(0,0,0,$_maand,$_dag,$_jaar);

      
// vergelijk de gelezen datum (error-log) 
// met de gegeven eind-datum  (formulier)     
      if ($_errorDatum <= $_einde )        
      {   
// indien de gelezen datum kleiner is dan de gegeven datum 
// moet de exception verwijderd worden.  
// In dit geval wordt hij niet gecopieerd naar 
// de tijdelijke file
// we tellen wel de "verwijderde" exceptions
        
        $_exceptionCounter++; 
      }
      else
      {
// indien de gelezen datum groter is dan de gegeven datum 
// moet de exception bijgehouden worden.  
// In dit geval wordt hij wel gecopieerd naar 
// de tijdelijke file 
        
        fputcsv($_pointerT,$_error_log);
      }
     }
	 }

  
//einde copieren/verwijderen
// sluit beide csv files  A & T  
  fclose($_pointerA);
  fclose($_pointerT);
  
// verijder de actuele error-log (error_log.csv)  
  unlink("../logs/error_log.csv");
  
// hernoem de tijdelijke_error-log  naar de 
// actuele error-log error_log.csv 
  rename("../logs/tijdelijke_error_log.csv","../logs/error_log.csv");
  
  // maak output aan  
  $_output="<h1>Exceptions</h1>
            <br>
            <p> Alle exceptions ($_exceptionCounter) tot en met $_datum zijn verwijderd"; 
}

/*****************
 *   output      *
 *****************/
// menu definieren  
  $_menu =  112;
// commentaar file definieren  
  $_commentaar = 'Y_verwijderen_C.html';

  require("../code/output.inc.php");

}

catch (Exception $_e)
{
  // exception handling funtions 
  include("../php_lib/myExceptionHandling.inc.php"); 
  echo myExceptionHandling($_e,"../logs/error_log.csv");
}
?>