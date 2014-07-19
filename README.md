Steam Library GT
================

Display your Steam game library in your Wordpress site.  

Grab related game information from the thegamesdb.net and launch your games.

## Description

Display your Steam game library and grab related game information from the thegamesdb.net.

Simply add the shortcode [steam_library_gt] to a page or post, to embed the library.

## Installation

1. Upload `steam-library-gt` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure plugin in admin setting page
4. Add shortcode `[steam_library_gt]` to your post or page

## Frequently Asked Questions

*	**The plugin doesn't seem to work**

	Check your file/folder permissions.  Make sure the `cache` folder is writable by the server.

*	**The plugin takes a long time to load, the first time I use it**

	The first time you access the plugin it will cache every image from your library.  If your library is large, this could take several minutes... Please, be patient!

## Screenshots

1. The Steam Library GT Settings page
![screenshot](http://i.imgur.com/BuBIDtI.png)
2. Embedded library
![screenshot](http://i.imgur.com/vkdPXL1.png)
3. Popup modal containing game information and steam launcher
![screenshot](http://i.imgur.com/U2AtC0w.png)

## API Usage

This plugin uses 2 separate remote API's:

1. Steam - http://api.steampowered.com
2. thegamesDB - http://thegamesdb.net/api

The Steam API is used to retrieve your game library data (using the GetOwnedGames method).  To successfully interact with this service, we use the Steam Profile ID and Steam Web API Key you provide in the plugin configuration admin page.

ThegamesDB.net API is used to retrieve per game information (using the GetGame method).  No credentials are required to interact with this service.

Further information on both API service methods can be found [here](https://developer.valvesoftware.com/wiki/Steam_Web_API#GetOwnedGames) and [here](http://wiki.thegamesdb.net/index.php?title=GetGame)

## Changelog

*	**1.5*
	Updated information.
*	**1.4**
	Switched to native WP AJAX action hooks.
*	**1.3**
	Updated page load spinner.
*	**1.2**
	Added missing cache folder.
*	**1.1**
	Fixed [bug](https://github.com/n3uromanc3r/steam-library-gt/issues/1) in the way the steam key is handled.
*	**1.0**
	Initial commit.