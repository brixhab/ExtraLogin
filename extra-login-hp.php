<?php
/**
 * Plugin Name: ExtraLogin for HP
 * Description: Adds Google Sign-In to HivePress login & registration forms.
 * Version: 1.0.0
 * Author: Brixhab
 * Text Domain: extralogin-hp
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ELHP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELHP_URL', plugin_dir_url( __FILE__ ) );

require_once ELHP_PATH . 'includes/helpers.php';
require_once ELHP_PATH . 'includes/class-google-button.php';
require_once ELHP_PATH . 'includes/class-google-login.php';

add_action( 'init', function() {
    // Start session for OAuth callback
    if ( ! session_id() ) {
        session_start();
    }
});
