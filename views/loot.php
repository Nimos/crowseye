
<?php

function getSheetPermissions($sheet) {
	$charInfo = CharacterInformation::getInstance();

	if ($sheet['fc'] == $charInfo->dbID) {
		return 2; #rw
	}
	$members = Database::filterBy("lootentries", "sheet = ".SQLite3::escapeString($sheet['rowid']));
	
	foreach ($members as $member) {
		if ($member['name'] == $charInfo->charName) return 1; #r
	}

	if ($charInfo->officer || $charInfo->director) return 1;

	return 0;
}
function viewLootSheet ($args) {
	$id = $args[1];
	
	$sheet = Database::filterBy("loots", "rowid = ".SQLite3::escapeString($id));
	if (count($sheet) == 0) requestError(404);
	$sheet = $sheet[0];

	$paid = $sheet["status"];

	$permissions = getSheetPermissions($sheet);
	$charInfo = CharacterInformation::getInstance();

	if ($permissions == 0) {
		requestError(403);
	}

	$pageTitle = "Loot Sheet";

	$readonly = ($permissions == 2) ? 0 : 1;
	
	$entries = getSheetJson($sheet);

	$roles = array (
		"FC" => 1.25,
		"Logi" => 1.0,
		"DPS 125%" => 1.25,
		"DPS 75%" => 0.75,
		"DPS" => 1.0,
		"Salvager" => 1.0,
		"Scout" => 0.5,
		"Finder" => 0
	);

	include('templates/loot/sheet.html');
}

function addLootSheetMember ($args) {
	$id = $args[1];

	$sheet = Database::filterBy("loots", "rowid = ".SQLite3::escapeString($id));
	if (count($sheet) == 0) requestError(404);
	$sheet = $sheet[0];
	
	$permissions = getSheetPermissions($sheet);

	if ($permissions != 2) 	requestError(403);

	Database::putObject("lootentries", "(sheet, name, sites, role) VALUES (".$sheet['rowid'].",'',0, 'DPS')");

	print getSheetJson($sheet);
}

function getSheetJson ($sheet) {
	$data = array();
	$data["entries"] = Database::filterBy("lootentries", "sheet = ".SQLite3::escapeString($sheet['rowid']));
	$data["totalIsk"] = $sheet['isk'];
	$data["totalSites"] = $sheet['sites'];
	return json_encode($data);
}

function updateLootSheet ($args) {
	$id = $args[1];
	$sheet = Database::filterBy("loots", "rowid = ".SQLite3::escapeString($id));
	$sheet = $sheet[0];
	$charInfo = CharacterInformation::getInstance();

	$permissions = getSheetPermissions($sheet);

	if ($permissions == 0) {
		requestError(403);
	}

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['action']) &&  ($permissions == 2 || $charInfo->director)) {

			if ($_POST['action'] == "togglePaid") {
				if ($_POST['status'] == 0 || $_POST['status'] == 1 || $_POST['status'] == 2 || $_POST['status'] == 4) {
					if ($_POST['mode'] == 'set') {
						Database::exec("UPDATE loots SET proof='".Sqlite3::escapeString($_POST['proof'])."', status= status |".$_POST['status']." WHERE rowid=".Sqlite3::escapeString($id).";");
					} else {
						Database::exec("UPDATE loots SET proof='".Sqlite3::escapeString($_POST['proof'])."', status= status & ~".$_POST['status']." WHERE rowid=".Sqlite3::escapeString($id).';');
					}
				}
			}
		} elseif ($permissions == 2) {
			$data = $_POST['data'];
			Database::exec('UPDATE loots SET isk='.Sqlite3::escapeString($data['totalIsk']).', sites='.Sqlite3::escapeString($data['totalSites']).' WHERE rowid='.Sqlite3::escapeString($id).';');

			foreach ($data['entries'] as $entry) {
				Database::exec("UPDATE lootentries SET name='".Sqlite3::escapeString($entry['name'])."', role='".Sqlite3::escapeString($entry['role'])."', sites=".Sqlite3::escapeString($entry['sites']).", isk=".Sqlite3::escapeString($entry['isk'])." WHERE rowid=".Sqlite3::escapeString($entry['rowid'])." AND sheet='".Sqlite3::escapeString($sheet['rowid'])."';");
			}

			$sheet = Database::filterBy("loots", "rowid = ".SQLite3::escapeString($id));
			$sheet = $sheet[0];
		}
	}


	print getSheetJson($sheet);
}

function createLootSheet() {
	$charInfo = CharacterInformation::getInstance();
	$pageTitle = "New Loot Sheet";
	if (!$charInfo->officer) {
		requestError(403);
	}

	if (isset($_POST['holeName'])) {
		$sheetid = Database::putObject("loots", "(hole, fc, date, sites, isk, status) VALUES ('".SQLite3::escapeString($_POST['holeName']) ."',". $charInfo->dbID .",". time().", 0, 0, 0);");
		$hole = Wormhole::getObjects('holes.name = "' . Sqlite3::escapeString($_POST['holeName']) .'"');
		Database::putObject("lootentries", "(sheet, name, sites, role) VALUES (".$sheetid.",'".SQLite3::escapeString($charInfo->charName)."',0, 'FC')");
		$fleet = explode("\n", $_POST['initialFleet']);
		foreach ($fleet as $member) {
			$member = explode("\t", $member);
			$member = $member[0];
			$member = trim($member);
			if ($member != $charInfo->charName && $member != "") {
				Database::putObject("lootentries", "(sheet, name, sites, role) VALUES (".$sheetid.",'".SQLite3::escapeString($member)."',0, 'DPS')");
			}
		}
		if (count($hole) > 0) {
			$finder = $hole[0]->reporter[0];
			Database::putObject("lootentries", "(sheet, name, sites, role) VALUES (".$sheetid.",'".SQLite3::escapeString($finder)."',0, 'Finder')");
		}
		header("Location: /loot/".$sheetid);
	}

	include('templates/loot/new.html');
}

function userLootList() {
	$charInfo = CharacterInformation::getInstance();
	$pageTitle = "My Loot Sheets";
	date_default_timezone_set('UTC');

	$sheets = Database::getObjects("SELECT DISTINCT players._rowid_, lootentries.isk AS myisk, lootentries.sites AS mysites, role,loots.*,loots.rowid,loots.sites, players.charName AS fc FROM lootentries JOIN players ON loots.fc=players._rowid_ JOIN loots ON loots.rowid=lootentries.sheet WHERE lootentries.name ='".Sqlite3::escapeString($charInfo->charName)."' GROUP BY lootentries.sheet ORDER BY loots.rowid DESC;");
	
	include('templates/loot/list.html');

}

function adminLootList() {
	$charInfo = CharacterInformation::getInstance();
	$pageTitle = "All Loot Sheets";
	date_default_timezone_set('UTC');

	if (!$charInfo->officer) {
		requestError(403);
	}

	$sheets = Database::getObjects('SELECT DISTINCT players._rowid_, lootentries.isk AS myisk, lootentries.sites AS mysites, role,loots.*,loots.rowid,loots.sites, players.charName AS fc FROM lootentries JOIN players ON loots.fc=players._rowid_ JOIN loots ON loots.rowid=lootentries.sheet GROUP BY lootentries.sheet ORDER BY loots.rowid DESC;');
	
	include('templates/loot/adminlist.html');
}

