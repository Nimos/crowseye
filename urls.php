<?php

  $urls = array(
    "^/$" => 'home',
    "^/trust(.*)$" => 'trust',
    "^/top$" => 'top',
    "^/loot(.*)$" => 'loot',
    "^/api/wh$" => "jsonWormholes",
    "^/api/comments/([0-9]*)$" => "jsonComments",
    "^/api/sites/([0-9]*)$" => "jsonUpdateSites",
    "^/api/information/(.*)$" => "jsonInformation",
    "^/api/delete$" => "jsonDelete",
    "^/dojo$" => "dojoParser",
    "^/help/fittings$" => "fittingHelp",
    "^/help/(.*)$" => "embeddedGdoc",
    "^/help$" => "helpLanding",
    "^/login$" => "login",
    "^/ssologin" => "ssologin",
    "^/admin/users" => "users"
    
  );

  $staticUrl = "/static/"

?>
