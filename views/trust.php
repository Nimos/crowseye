<?	
	require_once('classes/IGB.php');
	function trust() {
		$pageTitle = "Trust";
		$IGB=IGB::getInstance();
  		if (!isset($_GET['from'])) {
			$_GET['from'] = "/";
		}
		if (!$IGB->used || $IGB->trusted) {
    		#header("location: " . $_GET['from']);
  		}

  		include('templates/trust.html');
  	}
	