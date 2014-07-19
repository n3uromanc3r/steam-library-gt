<?php
/**
 * Plugin Name: Steam Library GT
 * Plugin URI: https://github.com/n3uromanc3r/steam-library-gt
 * Description: Provides an overview of a users Steam Library.
 * Version: 1.6
 * Author: Keito
 * Author URI: https://github.com/n3uromanc3r
 * License: GPL2
 */

/*  Copyright 2014  Keito  (email : keito@virusav.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once(__DIR__ . '/classes/SteamLibraryGTSettingsPage.php');
require_once(__DIR__ . '/classes/SteamLibraryGT.php');

wp_enqueue_script('jquery');

?>
