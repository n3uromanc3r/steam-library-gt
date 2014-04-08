<?php

class SteamLibraryGTSettingsPage {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	public function __construct() {
		add_action('admin_menu', array($this, 'steam_library_gt_menu'));
		add_action('admin_init', array($this, 'page_init'));		
	}

	/**
	 * Add options page
	 */
	public function steam_library_gt_menu()	{
		add_options_page(
			'Steam Library GT Settings', 
			'Steam Library GT', 
			'manage_options', 
			'steam_library-setting-admin', 
			array($this, 'create_admin_page')
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()	{
		// Set class property
		$this->options = get_option('steam_library_gt_config');
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Steam Library GT Settings</h2>           
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'steam_library_option_group' );   
				do_settings_sections('steam_library-setting-admin');
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()	{        
		register_setting(
			'steam_library_option_group', // Option group
			'steam_library_gt_config', // Option name
			array($this, 'sanitize') // Sanitize
		);

		add_settings_section(
			'steam_web_api_key_section', // ID
			'Steam Web API Key Configuration', // Title
			array($this, 'print_steam_web_api_key_section_info'), // Callback
			'steam_library-setting-admin' // Page
		);

		add_settings_field(
			'steam_web_api_key', // ID
			'Steam Web API Key', // Title 
			array($this, 'steam_web_api_key_callback'), // Callback
			'steam_library-setting-admin', // Page
			'steam_web_api_key_section' // Section           
		);

		add_settings_section(
			'steam_profile_id_section', // ID
			'Steam Profile ID Configuration', // Title
			array($this, 'print_steam_profile_id_section_info'), // Callback
			'steam_library-setting-admin' // Page
		);  

		add_settings_field(
			'steam_profile_id', 
			'Steam Profile ID', 
			array($this, 'steam_profile_id_callback'), 
			'steam_library-setting-admin', 
			'steam_profile_id_section'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize($input) {
		$new_input = array();
		if(isset($input['steam_web_api_key'])) {
			$new_input['steam_web_api_key'] = sanitize_text_field($input['steam_web_api_key']);
		}
		if(isset($input['steam_profile_id'])) {
			$new_input['steam_profile_id'] = sanitize_text_field($input['steam_profile_id']);
		}
		return $new_input;
	}

	/** 
	 * Print the Section text
	 */
	public function print_steam_web_api_key_section_info() {
		print 'You will need a Steam Web API Key, in order to access the Steam Web API service and use this plugin.  <br>Navigate to <a href="http://steamcommunity.com/dev/apikey">http://steamcommunity.com/dev/apikey</a> to generate and retrieve your unique key, then enter it in the field below.';
	}

	/** 
	 * Print the Section text
	 */
	public function print_steam_profile_id_section_info() {
		print 'You will need to provide your Steam Profile ID, in order to use this plugin.  <br>Navigate to the following page (be sure to replace <i>ChetFaliszek</i> with your own Steam user name or ID) <a href="http://steamcommunity.com/id/ChetFaliszek/?xml=1">http://steamcommunity.com/id/ChetFaliszek/?xml=1</a>.<br/>Copy the <b>steamID64</b> value into the field below. In Chet\'s case, the value would be <b>76561197968575517</b>';
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function steam_web_api_key_callback() {
		printf(
			'<input type="text" class="regular-text code" id="steam_web_api_key" name="steam_library_gt_config[steam_web_api_key]" value="%s" />',
			isset($this->options['steam_web_api_key']) ? esc_attr($this->options['steam_web_api_key']) : ''
		);
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function steam_profile_id_callback() {
		printf(
			'<input type="text" id="steam_profile_id" name="steam_library_gt_config[steam_profile_id]" value="%s" />',
			isset($this->options['steam_profile_id']) ? esc_attr($this->options['steam_profile_id']) : ''
		);
	}
}

if (is_admin()) {
	$steam_library_gt_settings_page = new SteamLibraryGTSettingsPage();
}

?>