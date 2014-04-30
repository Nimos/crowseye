<?
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
		$IGB = IGB::getInstance();

		$home = "Chidah";
  	if (isset($_COOKIE['home'])) $home = $_COOKIE['home'];
    if (!isset($_COOKIE['pwd'])) {
      header("location: login");
      return;
    }
    if ($_COOKIE['pwd'] != $GLOBALS['homePassword']) {
      header("location: login");
      return;
    }

		if ($IGB->used && !$IGB->trusted) {
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
        $query = $db->query('SELECT players._rowid_,players.charname,players.charID,players.corpID,COUNT(*) FROM holes JOIN players ON holes.reporter=players._rowid_ 
        					 WHERE reported > '.(time()-30*24*3600).'
        					 GROUP BY players.charID 
        					 ORDER BY COUNT(*) DESC, players._rowid_ ASC
        					 LIMIT 10' 
        					 );
		$topScoutsMonth = array();
        while ($row = $query->fetchArray()) {
        	array_push($topScoutsMonth, $row);
        }

		include('templates/top.html');
	}
	?>