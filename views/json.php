<?php
	require_once('classes/Wormhole.php');
	require_once('classes/IGB.php');
	function jsonWormholes () {
		$charInfo=CharacterInformation::getInstance();
		if ($_SERVER['REQUEST_METHOD'] == "GET") {
    		
    		if ($GLOBALS['homePassword'] != "") {
	    		if (!isset($_COOKIE['pwd'])) {
	      			echo("{}");
	      			return;
	      		} else if ($_COOKIE['pwd'] != $GLOBALS['homePassword']) {
	    			echo("{}");
	    			return;
	    		}
    		}

			$whs = Wormhole::getObjects();
			echo(json_encode($whs));
		} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
			if (!$charInfo->trusted && !$charInfo->authed) return;
			$wh = new Wormhole(-1, $_POST['systemName'], $_POST['wormholeName'], array(CharacterInformation::getInstance()->charID,CharacterInformation::getInstance()->charName,CharacterInformation::getInstance()->corpID), time(), Wormhole::parseScan($_POST['sites']), "", $_POST['sig']);
			$whid = $wh->save();
			if ($whid != -1) {
				if ($_POST['comment'] != "") {
					Wormhole::addComment($charInfo->charID, $whid, $_POST['comment']);
				}
			}
			echo($whid);
		}

	}

	function jsonInformation($args) {
		if (!$args[1]) {
			echo "{}";
			return;
		} 
		$holes = explode(",",$args[1]);
		
		$after = 0;
		if (isset ($_POST['after'])) $after = $_POST['after'];
		
		$result = array(
			"comments" => jsonGetComments($holes, $after),
			"sites" => jsonGetSites($holes, $after),
			"igbheaders" => $charInfo=CharacterInformation::getInstance()
		);

		echo( json_encode( $result ) ) ;
	}

	function jsonUpdateSites ($args) {
		$hole = $args[1];
		if (!$charInfo->trusted && !$charInfo->authed) return;
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$sites = Wormhole::parseScan($_POST['sites']);
			Wormhole::saveSites($hole, $sites);
		}
	}

	function jsonDelete ($args) {
		$charInfo=CharacterInformation::getInstance();
		if ($charInfo->officer) {
			Wormhole::removeHoleById($_POST['id']);
		}
	}



	function jsonComments($args) {
		if (!isset($_POST['text'])) return;
		$hole = $args[1];
		$charInfo=CharacterInformation::getInstance();
		if (!$charInfo->trusted && !$charInfo->authed) return;
		Wormhole::addComment($charInfo->charID, $hole, $_POST['text']);
	}

	function jsonGetComments($systems, $after) {
		$comments = array();
		foreach ($systems as $system) {
			$comment = Wormhole::getComments($system, $after);
			if (count($comment) != 0) $comments[$system] = $comment;
		}
		return $comments;
	}

	function jsonGetSites($systems, $after) {
		$sites = array();
		foreach ($systems as $system) {
			$sites[$system] = Wormhole::getSites($system, $after);
		}
		return $sites;
	}

