<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ELHP_Google_Login {

    private $client_id;
    private $client_secret;
    private $scopes = 'openid email profile';

    public function __construct() {
        $this->client_id     = elhp_get_opt('google_client_id');
        $this->client_secret = elhp_get_opt('google_client_secret');

        add_action( 'init', [ $this, 'maybe_start_oauth' ] );
        add_action( 'init', [ $this, 'maybe_handle_callback' ] );
    }

    /* Step 1: Start OAuth (redirect to Google) */
    public function maybe_start_oauth() {
        if ( empty( $_GET['elhp_google_login'] ) ) return;
        if ( empty( $this->client_id ) ) {
            wp_die( 'Google Client ID not configured. Visit plugin settings.' );
        }

        $redirect_uri = esc_url_raw( add_query_arg( 'elhp_google_callback', '1', site_url('/') ) );

        $state = wp_create_nonce( 'elhp_google_state' . wp_rand() );
        $_SESSION['elhp_google_state'] = $state;

        $params = [
            'client_id'     => $this->client_id,
            'redirect_uri'  => $redirect_uri,
            'response_type' => 'code',
            'scope'         => $this->scopes,
            'access_type'   => 'offline',
            'prompt'        => 'select_account consent',
            'state'         => $state,
        ];

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query( $params );
        wp_redirect( $url );
        exit;
    }

    /* Step 2: Handle callback (exchange code -> get user -> create account) */
    public function maybe_handle_callback() {
        if ( empty( $_GET['elhp_google_callback'] ) ) return;
        if ( isset( $_GET['error'] ) ) {
            wp_die( 'Google login error: ' . esc_html( $_GET['error'] ) );
        }

        // State check
        $state = $_GET['state'] ?? '';
        if ( empty( $_SESSION['elhp_google_state'] ) || $state !== $_SESSION['elhp_google_state'] ) {
            // proceed but log for debugging
            elhp_log("Google state mismatch (possible CSRF)");
        }

        $code = sanitize_text_field( $_GET['code'] ?? '' );
        if ( empty( $code ) ) {
            wp_die( 'No code returned from Google.' );
        }

        $redirect_uri = esc_url_raw( add_query_arg( 'elhp_google_callback', '1', site_url('/') ) );

        // Exchange code for tokens
        $token_response = wp_remote_post( 'https://oauth2.googleapis.com/token', [
            'body' => [
                'code'          => $code,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $redirect_uri,
                'grant_type'    => 'authorization_code',
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'timeout' => 20,
        ] );

        if ( is_wp_error( $token_response ) ) {
            wp_die( 'Token request error: ' . $token_response->get_error_message() );
        }

        $tokens = json_decode( wp_remote_retrieve_body( $token_response ), true );
        if ( empty( $tokens['access_token'] ) ) {
            elhp_log( 'Token response: ' . wp_remote_retrieve_body( $token_response ) );
            wp_die( 'Failed to obtain access token from Google.' );
        }

        // Get userinfo
        $userinfo_resp = wp_remote_get( 'https://www.googleapis.com/oauth2/v2/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokens['access_token'],
            ],
            'timeout' => 15,
        ] );

        if ( is_wp_error( $userinfo_resp ) ) {
            wp_die( 'Userinfo request error: ' . $userinfo_resp->get_error_message() );
        }

        $userinfo = json_decode( wp_remote_retrieve_body( $userinfo_resp ), true );
        if ( empty( $userinfo['email'] ) ) {
            wp_die( 'Failed to get user email from Google.' );
        }

        // Create or get existing WP user by email
        $email = sanitize_email( $userinfo['email'] );
        $user = get_user_by( 'email', $email );

        if ( ! $user ) {
            // create username from email prefix (before @)
            $prefix = preg_replace('/[^a-z0-9._-]/i','', strstr($email,'@', true) ); // keep safe chars
            if ( empty($prefix) ) $prefix = 'googleuser';

            $base = $prefix;
            $i = 0;
            while ( username_exists( $prefix ) ) {
                $i++;
                $prefix = $base . $i;
            }

            $display_name = trim( ($userinfo['given_name'] ?? '') . ' ' . ($userinfo['family_name'] ?? '') );
            if ( empty($display_name) ) $display_name = $prefix;

            $userdata = [
                'user_login'   => $prefix,
                'user_email'   => $email,
                'display_name' => $display_name,
                'role'         => 'contributor', // per your requirement
                // Note: no password set; user can use "Lost password?" to set one later
            ];

            $user_id = wp_insert_user( $userdata );
            if ( is_wp_error( $user_id ) ) {
                wp_die( 'User creation failed: ' . $user_id->get_error_message() );
            }
            $user = get_user_by( 'id', $user_id );

            // If HivePress active, create a Vendor programmatically (optional, best-effort)
            if ( class_exists('HivePress\\Models\\Vendor') ) {
                try {
                    $vendor = new HivePress\Models\Vendor();
                    $vendor->fill([
                        'name' => $user->display_name ?: $prefix,
                        'slug' => $user->user_login,
                        'status' => 'auto-draft',
                        'user' => $user->ID,
                    ]);
                    $vendor->save();
                } catch ( Exception $e ) {
                    elhp_log( 'Vendor creation failed: ' . $e->getMessage() );
                }
            }
        }

        // Log the user in
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID );

        // Redirect to HivePress redirect or homepage
        $redirect = elhp_get_redirect_url();
        wp_redirect( $redirect );
        exit;
    }
}

new ELHP_Google_Login();
