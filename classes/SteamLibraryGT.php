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
	}

	/**
	 * Shortcode function. [steam_library_gt]
	 */
	public function display_steam_library_gt() {		
		$html = '<div id="container"></div>';		
		return $html;
	}

	public function load_javascript() {
		wp_register_script( 
			'steam', 
			plugins_url('/steam-library-gt/js/steam.js'), 
			array('jquery')
		);
		wp_localize_script('steam', 'steam_data', array( 'plugin_url' => plugins_url(), 'steam_profile_id' => $this->steam_config['steam_profile_id'], 'cache_exists' => file_exists(__DIR__ . '/../cache/.cached')));
		wp_enqueue_script('steam');
	}
}

$steam_library_gt = new SteamLibraryGT();

?>