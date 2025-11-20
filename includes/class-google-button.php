<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ELHP_Google_Button {

    public function __construct() {
        add_filter( 'hivepress/v1/templates/user_login_block', [ $this, 'add_google_button' ], 20 );
        add_filter( 'hivepress/v1/templates/user_register_block', [ $this, 'add_google_button' ], 20 );
    }

    public function add_google_button( $template ) {

        $google_auth_url = site_url( '/?elhp_google_login=1' );

        $template['blocks']['google_login'] = [
            'type'    => 'part',
            'path'    => ELHP_PATH . 'templates/google-button.php',
            'context' => [
                'google_url' => $google_auth_url
            ]
        ];

        return $template;
    }
}

new ELHP_Google_Button();
