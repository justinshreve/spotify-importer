<?php
/**
 * Spotify service definition for Keyring.
 * https://developer.spotify.com/web-api/
 */

class Keyring_Service_Spotify extends Keyring_Service_OAuth2 {
	const NAME  = 'spotify';
	const LABEL = 'Spotify';

	function __construct() {
		parent::__construct();

		// Enable "basic" UI for entering key/secret
		if ( ! KEYRING__HEADLESS_MODE ) {
			add_action( 'keyring_spotify_manage_ui', array( $this, 'basic_ui' ) );
			add_filter( 'keyring_spotify_basic_ui_intro', array( $this, 'basic_ui_intro' ) );
		}

		$this->set_endpoint( 'authorize',    'https://accounts.spotify.com/authorize', 'GET'  );
		$this->set_endpoint( 'access_token', 'https://accounts.spotify.com/api/token', 'POST' );
		$this->set_endpoint( 'self',         'https://api.spotify.com/v1/me',          'GET'  );

		$creds = $this->get_credentials();
		$this->app_id  = $creds['app_id'];
		$this->key     = $creds['key'];
		$this->secret  = $creds['secret'];

		$this->consumer = new OAuthConsumer( $this->key, $this->secret, $this->callback_url );
		$this->signature_method = new OAuthSignatureMethod_HMAC_SHA1;

		$this->authorization_header    = 'Bearer';
		$this->authorization_parameter = false;
	}

	function basic_ui_intro() {
		echo '<p>' . sprintf( __( 'To get started, <a href="%1$s">register an OAuth client on Spotify</a>. The most important setting is the <strong>Redirect URIs</strong>, which should be set to <code>%2$s</code>. You can set the other values to whatever you like.', 'keyring' ), 'https://developer.spotify.com/my-applications/', Keyring_Util::admin_url( 'spotify', array( 'action' => 'verify' ) ) ) . '</p>';
		echo '<p>' . __( "Once you've saved those changes, copy the <strong>CLIENT ID</strong> value into the <strong>API Key</strong> field, and the <strong>CLIENT SECRET</strong> value into the <strong>API Secret</strong> field and click save (you don't need an App ID value for Spotify).", 'keyring' ) . '</p>';
	}

	function build_token_meta( $token ) {
		if ( empty( $token['user'] ) ) {
			$meta = array();
		} else {
			$meta = array(
			/*	'user_id'  => $token['user']->id,
				'username' => $token['user']->username,
				'name'     => $token['user']->full_name,
				'picture'  => $token['user']->profile_picture,
			*/
			);
		}

		return apply_filters( 'keyring_access_token_meta', $meta, 'spotify', $token, null, $this );
	}

	function get_display( Keyring_Access_Token $token ) {
		return '';
		//return $token->get_meta( 'name' );
	}
}

add_action( 'keyring_load_services', array( 'Keyring_Service_Spotify', 'init' ) );