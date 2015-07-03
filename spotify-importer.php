<?php
/*
Plugin Name: Spotify Importer
Plugin URI: https://github.com/justinshreve/spotify-importer
Description: Automatically publish songs from a Spotify Playlist.
Version: 1.0.0
Author: Justin Shreve
Author URI: http://justin.gs
License: GPL v2 or newer <https://www.gnu.org/licenses/gpl.txt>
*/

class Spotify_Importer {

	public function __construct() {
		add_filter( 'keyring_services', array( $this, 'register_service' ) );
	}

	public function register_service( $services ) {
		$services[] = plugin_dir_path( __FILE__ ) . 'includes/keyring-service-spotify.php';
		return $services;
	}
}

new Spotify_Importer;
