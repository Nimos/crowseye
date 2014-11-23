<?php	
	require_once('classes/IGB.php');

	function addModal() {
		$igb = IGB::getInstance();
		if (!$igb->trusted) return;

		$inWSpace = false;
		if (preg_match('/J[0-9][0-9][0-9][0-9][0-9][0-9]/i', $igb->solarSystemName)) {
			$inWSpace = true;
		}

		include('templates/add.html');
	}



?>
