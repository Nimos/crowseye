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
		$IGB = IGB::getInstance();
		include("templates/layout.html");
	}

	?>