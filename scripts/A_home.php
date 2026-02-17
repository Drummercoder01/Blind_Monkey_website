<?php
try {
  require_once("../code/initialisatie.inc.php");
  require_once("../php_lib/inlezen.inc.php");
  require_once("../php_lib/mainPoints.php");


  /*******************************************
   *    (Input en) verwerking
   ********************************************/
  include("../code/0-nav-bar.php");
  include("../code/1-section_home.php");
  include("../code/2-section_about.php");
  include("../code/3-section_music.php");
  include("../code/4-section_press.php");
  include("../code/5-section_events.php");
  include("../code/6-section_videos.php");
  include("../code/7-section_photos.php");
  include("../code/8-section_footer.php");


  /*******************************************
   *    output
   ********************************************/
  // menu definieren  
  $_menu = 0;
  // comentario file definieren  
  $_commentaar = 'A_home_C.html';

  $_jsInclude = array(
    "../js/scroll.js",
    "../js/more_about.js",
    "../js/videos_handler.js",
    "../js/music_handler.js",
    "../js/press_handler.js", // Ahora actualizado
    "../js/event_handler.js",
    "../js/photos_handler.js",
    "../js/background-load.js",
    "../js/collapse_nav_bar.js",
    "../js/navbar_active.js",
    "../js/newsletter_handler.js",
    "../js/youtube-background.js"
  );

  require_once("../code/output.inc.php");
} catch (Exception $_e) {
  include("../php_lib/myExceptionHandling.inc.php");
  echo myExceptionHandling($_e, "../logs/error_log.csv");
}
