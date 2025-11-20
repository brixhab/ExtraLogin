<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ELHP_Google_Login {

    private $client_id;
    private $client_secret;

    public function __construct() {

        $this->client_id     = elhp_get_option('google_client_id');
        $this->client_secret = elhp_get_option('google_client_secret');

        add_action( 'init', [ $this, 'handle_google_redirect' ] );
    }

    public function handle_google_redirect() {

        if ( ! isset($_GET['elhp_google_login']) ) {
            return;
        }

        // Google OAuth link
        $redirect_uri = site_url( '/?elhp_google_callback=1' );

        $auth_url = "https://accounts.google.com/o/oauth2/auth?" . http_build_query([
            'client_id'     => $this->client_id,
            'redirect_uri'  => $redirect_uri,
            'response_type' => 'code',
            'scope'         => 'email profile'
        ]);

        wp_redirect( $auth_url );
        exit;
    }

}

new ELHP_Google_Login();
