
<?
function loot () {
	$pageTitle = "Loot Sheet";

	$roles = array (
		"FC" => 1.25,
		"Salvager" => 1.0,
		"Logi" => 1,
		"DPS" => 1,
		"Scout" => 1,
		"BADSCOUT" => 0.5,
		"Newbiescout" => 0.5,
		"Finder" => 0.25
	);

	include('templates/loot.html');
}

?>