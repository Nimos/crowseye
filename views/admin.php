<?php
	function users() {
		require_once('classes/IGB.php');
		require_once('classes/Database.php');

		$charInfo = CharacterInformation::getInstance();

		if (!$charInfo->director) {
			requestError(403);
		}

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$action = $_POST['action'];
			$target = $_POST['character'];

			if ($action == "giveDirector") {
				Database::exec('UPDATE players SET director=1 WHERE rowid='.SQLite3::escapeString($target).';');
			} else if ($action == "removeDirector") {
				Database::exec('UPDATE players SET director=0 WHERE rowid='.SQLite3::escapeString($target).';');
			} else if ($action == "giveOfficer") {
				Database::exec('UPDATE players SET fc=1 WHERE rowid='.SQLite3::escapeString($target).';');
			} else if ($action == "removeOfficer") {
				Database::exec('UPDATE players SET fc=0 WHERE rowid='.SQLite3::escapeString($target).';');
			}
			exit();
		}

		$pageTitle = "Users";

		$directors = Database::getObjects('SELECT rowid,* FROM players WHERE director=1;');
		$officers = Database::getObjects('SELECT rowid,* FROM players WHERE fc=1 and director=0;');
		$scrubs = Database::getObjects('SELECT rowid,* FROM players WHERE fc=0 AND director=0;');

		include('templates/admin/users.html');
	}
	
	function WHScanLog() {
		require_once('classes/IGB.php');
		require_once('classes/Database.php');

		$charInfo = CharacterInformation::getInstance();

		if (!$charInfo->director) {
			requestError(403);
		}

		$pageTitle = "WHScanLog";

		$directors = Database::getObjects('Select players.rowid,* from holes Inner join players on holes.reporter=players.rowid order by reported desc;');

		include('templates/admin/WHScanLog.html');
	}
