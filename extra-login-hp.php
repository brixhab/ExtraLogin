<?php
/**
 * Plugin Name: ExtraLogin for HP
 * Description: Google Sign-In for HivePress (ListingHive). Publisher: Brixhab. Version: 1.0.0
 * Version: 1.0.0
 * Author: Brixhab
 * Text Domain: extra-login-hp
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ELHP_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELHP_URL',  plugin_dir_url( __FILE__ ) );

/* includes */
require_once ELHP_PATH . 'includes/helpers.php';
require_once ELHP_PATH . 'includes/admin-settings.php';
require_once ELHP_PATH . 'includes/class-google-button.php';
require_once ELHP_PATH . 'includes/class-google-login.php';

add_action( 'init', function() {
    if ( ! session_id() ) {
        @session_start();
    }
});
