<?php

	$ssoEnabled = ($GLOBALS['crest_clientID'] != '' && $GLOBALS['crest_secretKey'] != '');
		


	function ssologin () {

		if (!$GLOBALS['ssoEnabled']) {
			requestError(404, $_SERVER);
		}

		if (isset($_GET['code'])) {
			ssostep2();
		} else {
			ssostep1();
		}
	}

	function logout() {
		if (!$GLOBALS['ssoEnabled']) {
			requestError(404, $_SERVER);
		}

		unset($_SESSION['loggedIn']);
		unset($_SESSION['charID']);
		unset($_SESSION['charName']);

		header("Location: /");

	}

	function ssostep1() {
		$ssobase = "https://login.eveonline.com/oauth/authorize/?response_type=code";
		$redirect = "http://" . $_SERVER['HTTP_HOST'] . '/ssologin';
		$clientid = $GLOBALS['crest_clientID'];
		$scope = "";

		$url = $ssobase."&redirect_uri=" . $redirect . "&client_id=" . $clientid . "&scope=" . $scope;

		header("Location:" . $url);
	}

	function ssostep2() {
		$url = 'https://login.eveonline.com/oauth/token';
		$auth = 'Basic ' . base64_encode($GLOBALS['crest_clientID'] .':'. $GLOBALS['crest_secretKey']);

		$postdata = array('grant_type' => 'authorization_code', 'code' => $_GET['code']);


		$options = array(
    		'http' => array(
        		'header'  => 'Authorization: '.$auth."\r\n" . "Content-Type: application/x-www-form-urlencoded\r\n",
        		'method'  => 'POST',
        		'content' => http_build_query($postdata)
    		),
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		
		if (!$result) {
			print "nope";
			exit;
		}

		$result =  json_decode($result);
		$token = $result->access_token;

		$url = "https://login.eveonline.com/oauth/verify";

		$options = array(
    		'http' => array(
        		'header'  => 'Authorization: Bearer '.$token."\r\n"
    		)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		if (!$result) {
			print "nope";
			exit;
		}

		$result =  json_decode($result);

		$_SESSION['loggedIn'] = true;
		$_SESSION['charID'] = $result->CharacterID;
		$_SESSION['charName'] = $result->CharacterName;

		header("Location: /");

	}
