<?php

class SteamLibraryGTLoader {

	private $steam_config;
	protected $sorted_games;

	public function __construct($steam_config) {
		$this->steam_config = $steam_config;
		$this->sorted_games = $this->retrieve_steam_library_gt();
	}

	/**
	 * cURL function to retrieve steam games library via their API
	 * This also builds a cache of images too
	 */
	public function retrieve_steam_library_gt() {
		$url = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=' . $this->steam_config['steam_web_api_key'] . '&steamid=' . $this->steam_config['steam_profile_id'] . '&include_appinfo=1&include_played_free_games=1&format=json';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$results = curl_exec($ch);

		$results = json_decode($results, true);

		$sorted_games = array();

		foreach ($results['response']['games'] as $game) {
			$sorted_games[$game['name']] = array(
				'app_id' => $game['appid'],
				'image_hash' => $game['img_logo_url'],
				'playtime' => $game['playtime_forever']
			);

			// cache image if available and it doesn't already exist
			if (($game['img_logo_url'] != '') && !file_exists(__DIR__ . '/../cache/' . $game['img_logo_url'] . '.jpg')) {
				$content = file_get_contents('http://media.steampowered.com/steamcommunity/public/images/apps/' . $game['appid'] . '/' . $game['img_logo_url'] . '.jpg'); 
				$this->atomic_put_contents(__DIR__ . '/../cache/' . $game['img_logo_url'] . '.jpg', $content);
			}

			if (file_exists(__DIR__ . '/../cache/' . $game['img_logo_url'] . '.jpg')) {
				$sorted_games[$game['name']]['cached'] = true;
			}
		}

		// leave cached crumb
		if (!file_exists(__DIR__ . '/../cache/.cached')) {
			$this->atomic_put_contents(__DIR__ . '/../cache/.cached', '');
		}		

		ksort($sorted_games);

		return $sorted_games;
	}

	/**
	 * Get sorted games list
	 */
	public function get_sorted_games() {
		return $this->sorted_games;
	}

	/**
	 * Atomic file write
	 * @param string $filename
	 * @param resource $data
	 */
	private function atomic_put_contents($filename, $data) {
		$fp = fopen($filename, "w+");
		if (flock($fp, LOCK_EX)) {
			fwrite($fp, $data);
			flock($fp, LOCK_UN);
		}
		fclose($fp);
	}
}

?>