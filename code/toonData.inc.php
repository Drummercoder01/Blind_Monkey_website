<?php
  $_inhoud.= "<br>". 
        $_row['d_voornaam']." ".
        $_row['d_naam'].
        "<br><br>".
        $_row['d_gender_mnem'].
        " / " .
        $_row['d_soortlid_mnem']. 
        "<br><br>".
        $_row['d_straat']."&nbsp;&nbsp;".
        $_row['d_nr']."&nbsp;&nbsp;".
        $_row['d_xtr'].
        "<br>".
        $_row['d_postnummer']."&nbsp;&nbsp;". 
        $_row['d_gemeenteNaam']. 
        "<br><br>". 
        "Tel : ".$_row['d_tel'].
        "<br>".
        "Mob : ".$_row['d_mob'].
        "<br><br>". 
        "Mail: ".$_row['d_mail'].
        "<br><br>";
?>