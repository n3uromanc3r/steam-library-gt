<?php

require_once( __DIR__ . '/../classes/SteamLibraryGTLoader.php' );

$steam_config = array(
	'steam_web_api_key' => $_REQUEST['steam_web_api_key'],
	'steam_profile_id' => $_REQUEST['steam_profile_id']
);

$steam_library_gt_loader = new SteamLibraryGTLoader($steam_config);
$sorted_games = $steam_library_gt_loader->sorted_games;
$sorted_games_json = json_encode($sorted_games);

echo $sorted_games_json;
exit;

?>