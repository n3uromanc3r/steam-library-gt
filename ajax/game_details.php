<?php

$game_name = $_REQUEST['name'];

$game_info = simplexml_load_file('http://thegamesdb.net/api/GetGame.php?name=' . urlencode($game_name));
$game_info_json = json_encode($game_info);

echo $game_info_json;
exit;

?>