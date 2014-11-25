<?php
	require_once('classes/IGB.php');

	function requestError ($code, $request="") {
		$charInfo = CharacterInformation::getInstance();
		if ($code == 404) {
			$errorTitle = "404 - Page not Found";
			$errorText = "Sorry, we could not find the page you were looking for :(";
		} else if ($code = 403) {
			$errorTitle ="403 - Forbidden";
			$errorText = "You are not allowed to view this page. Please log in using the link at the top.";
		} else {
			$errorTitle = "Something terrible happened";
			$errorText = "Apparently there isn't even an error text to describe this situation!";
		}

		$pageTitle = $errorTitle;

		header("HTTP/1.0 ".$code);
		
		include("templates/error.html");
		exit();
	}

?>