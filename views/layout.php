<?php

	require_once("classes/IGB.php");

	function isAuthenticated() {
   		if (!isset($_COOKIE['pwd'])) {
	      	return false;
    	}
    	if ($_COOKIE['pwd'] != $GLOBALS['homePassword']) {
      		return false;
    	}
    	return true;

	}

	function showLayout() {
		$charInfo = CharacterInformation::getInstance();
		include("templates/layout.html");
	}

	?>