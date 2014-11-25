<?php
	require_once('classes/IGB.php');

	function requestError ($code, $request) {
		$charInfo = CharacterInformation::getInstance();
		if ($code == 404) {
			$errorTitle = "404 - Page not Found";
			$errorText = "Sorry, we could not find the page you were looking for :(";
		} else {
			$errorTitle = "Something terrible happened";
			$errorText = "Apparently there isn't even an error text to describe this situation!";
		}

		$pageTitle = $errorTitle;

		
		include("templates/error.html");
		exit();
	}

?>