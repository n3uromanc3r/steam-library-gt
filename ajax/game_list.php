<?php

define('SHORTINIT', true);
require(__DIR__ . '/../../../../wp-load.php');
require_once(__DIR__ . '/../classes/SteamLibraryGTLoader.php');

$steam_config = get_option('steam_library_gt_config');
$steam_web_api_key = $steam_config['steam_web_api_key'];

$steam_config = array(
	'steam_web_api_key' => $steam_web_api_key,
	'steam_profile_id' => $_REQUEST['steam_profile_id']
);

$steam_library_gt_loader = new SteamLibraryGTLoader($steam_config);
$sorted_games = $steam_library_gt_loader->get_sorted_games();
$sorted_games_json = json_encode($sorted_games);

echo $sorted_games_json;
exit;

?>