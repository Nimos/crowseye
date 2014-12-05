<?php	
	require_once('classes/IGB.php');

	function addModal() {
		$charInfo = CharacterInformation::getInstance();
		if ($charInfo->trusted) {
		$inWSpace = false;
			if (preg_match('/J[0-9][0-9][0-9][0-9][0-9][0-9]/i', $charInfo->solarSystemName)) {
				$inWSpace = true;
			}
		}

		include('templates/add.html');
	}


