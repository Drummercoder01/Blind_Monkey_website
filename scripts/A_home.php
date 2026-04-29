<?php
try {
  require_once(__DIR__ . "/../code/initialisatie.inc.php");
  require_once(__DIR__ . "/../php_lib/inlezen.inc.php");
  require_once(__DIR__ . "/../php_lib/mainPoints.php");
  require_once(__DIR__ . "/../php_lib/trackVisit.inc.php");
  trackVisit($_PDO);


  /*******************************************
   *    (Input en) verwerking
   ********************************************/
  include(__DIR__ . "/../code/0-nav-bar.php");
  include(__DIR__ . "/../code/1-section_home.php");
  include(__DIR__ . "/../code/2-section_about.php");
  include(__DIR__ . "/../code/3-section_music.php");
  include(__DIR__ . "/../code/4-section_press.php");
  include(__DIR__ . "/../code/5-section_events.php");
  include(__DIR__ . "/../code/6-section_videos.php");
  include(__DIR__ . "/../code/7-section_photos.php");
  include(__DIR__ . "/../code/8-section_footer.php");


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
    "../js/press_handler.js",
    "../js/event_handler.js",
    "../js/photos_handler.js",
    "../js/collapse_nav_bar.js",
    "../js/navbar_active.js",
    "../js/newsletter_handler.js"
  );

  require_once(__DIR__ . "/../code/output.inc.php");
} catch (Exception $_e) {
  include(__DIR__ . "/../php_lib/myExceptionHandling.inc.php");
  echo myExceptionHandling($_e, "../logs/error_log.csv");
}
