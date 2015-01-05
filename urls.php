<?php

  $urls = array(
    "^/$" => 'home',
    "^/trust(.*)$" => 'trust',
    "^/top$" => 'top',
    "^/loot/new$" => 'createLootSheet',
    "^/loot/list$" => 'userLootList',
    "^/loot/all$" => 'adminLootList',
    "^/loot/(.*)/update" => "updateLootSheet",
    "^/loot/(.*)/addMember" => "addLootSheetMember",
    "^/loot/(.*)$" => 'viewLootSheet',
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
    "^/logout$" => "logout",
    "^/ssologin" => "ssologin",
    "^/admin/users$" => "users",
    "^/api/toggleRun$" => "toggleRun",
    "^/admin/WHScanLog" => "WHScanLog"
    
  );

  $staticUrl = "/static/";
