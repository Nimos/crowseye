<?php	
	require_once('classes/IGB.php');
	function trust() {
		$pageTitle = "Trust";
		$charInfo=CharacterInformation::getInstance();
  		if (!isset($_GET['from'])) {
			$_GET['from'] = "/";
		}
		if (!$charInfo->used || $charInfo->trusted) {
    		#header("location: " . $_GET['from']);
  		}

  		include('templates/trust.html');
  	}
	
