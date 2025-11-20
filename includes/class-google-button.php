<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ELHP_Google_Button {

    public function __construct() {

        // Add button under login form
        add_filter( 'hivepress/v1/templates/user_login_form', [ $this, 'inject_button' ], 20 );

        // Add button under register form
        add_filter( 'hivepress/v1/templates/user_register_form', [ $this, 'inject_button' ], 20 );

        // Login page template
        add_filter( 'hivepress/v1/templates/page_user_login', [ $this, 'inject_button_page' ], 20 );

        // Register page template
        add_filter( 'hivepress/v1/templates/page_user_register', [ $this, 'inject_button_page' ], 20 );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'elhp-style', ELHP_URL . 'assets/google-button.css' );
    }

    /* Inject into form templates (modal + blocks) */
    public function inject_button( $template ) {

        $google_auth_url = site_url( '/?elhp_google_login=1' );

        $template['blocks']['elhp_google_button'] = [
            'type'    => 'html',
            'content' => '<a href="'.esc_url($google_auth_url).'" class="elhp-google-btn">Continue with Google</a>',
            'priority'=> 200,
        ];

        return $template;
    }

    /* Inject into page templates (/account/login & /account/register) */
    public function inject_button_page( $template ) {
        $google_auth_url = site_url( '/?elhp_google_login=1' );

        $template['blocks']['elhp_google_button_page'] = [
            'type'    => 'html',
            'content' => '<div style="margin-top:20px;"><a href="'.esc_url($google_auth_url).'" class="elhp-google-btn">Continue with Google</a></div>',
            'priority'=> 200,
        ];

        return $template;
    }
}

new ELHP_Google_Button();
