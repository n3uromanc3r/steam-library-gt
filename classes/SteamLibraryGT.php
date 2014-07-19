<?php

require_once(__DIR__ . '/SteamLibraryGTLoader.php');

class SteamLibraryGT {

	private $steam_config;	

	public function __construct() {
		$this->steam_config = get_option('steam_library_gt_config');
		add_shortcode('steam_library_gt', array($this, 'display_steam_library_gt'));
		wp_enqueue_style('steam.css', plugins_url('/steam-library-gt/css/steam.css'));
		wp_enqueue_style('font-awesome.min.css', plugins_url('/steam-library-gt/css/font-awesome-4.0.3/css/font-awesome.min.css'));
		add_action('wp_enqueue_scripts', array($this, 'load_javascript'));
		add_action('wp_ajax_load_library', array($this, 'load_library'));
		add_action('wp_ajax_nopriv_load_library', array($this, 'load_library'));	
	}

	/**
	 * Shortcode function. [steam_library_gt]
	 */
	public function display_steam_library_gt() {		
		$html = '<div id="container"></div>';		
		return $html;
	}

	/**
	 * Load necessary scripts
	 */
	public function load_javascript() {		
		wp_register_script( 
			'steam', 
			plugins_url('/steam-library-gt/js/steam.js'), 
			array('jquery')
		);
		wp_localize_script('steam', 'steam_data', array( 'plugin_url' => plugins_url(), 'steam_profile_id' => $this->steam_config['steam_profile_id'], 'ajaxurl' => admin_url('admin-ajax.php'), 'cache_exists' => file_exists(__DIR__ . '/../cache/.cached')));
		wp_enqueue_script('steam');
	}

	/**
	 * Load games library
	 */
	public function load_library() {
		$steam_config = get_option('steam_library_gt_config');
		$steam_web_api_key = $steam_config['steam_web_api_key'];

		$steam_config = array(
			'steam_web_api_key' => $steam_web_api_key,
			'steam_profile_id' => $_POST['steam_profile_id']
		);

		$steam_library_gt_loader = new SteamLibraryGTLoader($steam_config);
		$sorted_games = $steam_library_gt_loader->get_sorted_games();
		$sorted_games_json = json_encode($sorted_games);

		echo $sorted_games_json;
		die();
	}
}

$steam_library_gt = new SteamLibraryGT();

?>