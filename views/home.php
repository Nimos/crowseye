<?php
	require_once('classes/Wormhole.php');
	require_once('classes/IGB.php');

  function login() {
    $pageTitle = "Login";
    $nope = false;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      if (isset($_POST['password'])) {
        if ($_POST['password'] == $GLOBALS['homePassword']) {
          setcookie('pwd', $GLOBALS['homePassword'], time()+60*60*24*365);
          header('Location: /');
        } else {
          $nope = true;
        }
      }
    }
    include('templates/login.html');
    
  }


	function home() {
		
		$pageTitle = "Home";
		$charInfo = CharacterInformation::getInstance();

    $home = "GJ0-OJ";

  	if (isset($_COOKIE['home'])) $home = $_COOKIE['home'];
    
    if ($GLOBALS['homePassword'] != "") {
    
      if (!isset($_COOKIE['pwd'])) {
        header("location: login");
        return;
      }

      if ($_COOKIE['pwd'] != $GLOBALS['homePassword']) {
        header("location: login");
        return;
      }
    }

		if ($charInfo->used && !$charInfo->trusted) {
    		header("location: trust?from=" . $_SERVER['REQUEST_URI']);
  		} else {
  		

  			include('templates/home.html');
  		}	

	}
	
	function top() {
		$pageTitle = 'Top Rankings';

		$db = new SQLite3('db/wh.db');
        $db->busyTimeout(1000);
        $query = $db->query('SELECT players._rowid_,players.charname,players.charID,players.corpID,COUNT(*) FROM holes JOIN players ON holes.reporter=players._rowid_ 
        					 GROUP BY players.charID 
        					 ORDER BY COUNT(*) DESC, players._rowid_ ASC
        					 LIMIT 10');
		$topScouts = array();
        while ($row = $query->fetchArray()) {
        	array_push($topScouts, $row);
        }
    $db = new SQLite3('db/wh.db');
        $db->busyTimeout(1000);
        $query = $db->query('SELECT system,COUNT(*) FROM holes
                   GROUP BY system 
                   ORDER BY COUNT(*) DESC
                   LIMIT 10');
    $topSystems = array();
        while ($row = $query->fetchArray()) {
          array_push($topSystems, $row);
        }
    $startOfLastMonth = strtotime('first day of last month');
    $startOfThisMonth = strtotime('first day of this month');

    $query = $db->query('SELECT players._rowid_,players.charname,players.charID,players.corpID,COUNT(*) FROM holes JOIN players ON holes.reporter=players._rowid_ 
        					 WHERE reported > '.$startOfLastMonth.' AND reported < '.$startOfThisMonth.' 
        					 GROUP BY players.charID 
        					 ORDER BY COUNT(*) DESC, players._rowid_ ASC
        					 LIMIT 10' 
    );
		$topScoutsLast = array();
        while ($row = $query->fetchArray()) {
        	array_push($topScoutsLast, $row);
        }
    $query = $db->query('SELECT players._rowid_,players.charname,players.charID,players.corpID,COUNT(*) FROM holes JOIN players ON holes.reporter=players._rowid_ 
                   WHERE reported > '.$startOfThisMonth.' 
                   GROUP BY players.charID 
                   ORDER BY COUNT(*) DESC, players._rowid_ ASC
                   LIMIT 10' 
    );
    $topScoutsThis = array();
        while ($row = $query->fetchArray()) {
          array_push($topScoutsThis, $row);
        }

    $query = $db->query('SELECT players._rowid_,name,players.charID,players.corpID,SUM(isk) FROM lootentries JOIN players ON name=players.charName  
                   GROUP BY name
                   ORDER BY SUM(isk) DESC, players._rowid_ ASC
                   LIMIT 10' 
    );
    $topEarner = array();
        while ($row = $query->fetchArray()) {
          $row[4] = str_split($row[4]);
          $r = array();
          $y = 0;
          for ($x=sizeof($row[4])-1; $x>=0;$x--) {
            if ($y++==3) {
              array_unshift($r, ",");
              $y = 1;
            }
            array_unshift($r, $row[4][$x]);

          }
          $row[4] = implode("", $r);
          array_push($topEarner, $row);
        }

    $query = $db->query('SELECT players._rowid_,players.charName,players.charID,players.corpID,COUNT(*) FROM loots JOIN players ON loots.fc=players._rowid_  
                   GROUP BY loots.fc
                   ORDER BY COUNT(*) DESC, players._rowid_ ASC
                   LIMIT 10' 
    );
    $topFC = array();
        while ($row = $query->fetchArray()) {
          array_push($topFC, $row);
        }



		include('templates/top.html');
	}