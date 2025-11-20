<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* Option getters/setters */
function elhp_get_opt( $k ) {
    return get_option( 'elhp_' . $k );
}
function elhp_set_opt( $k, $v ) {
    return update_option( 'elhp_' . $k, $v );
}

/* Logging helper (visible only when WP_DEBUG true) */
function elhp_log( $msg ) {
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
        error_log( '[ExtraLogin for HP] ' . $msg );
    }
}

/* Get HivePress redirect URL fallback */
function elhp_get_redirect_url() {
    if ( function_exists('hivepress') && method_exists(hivepress(), 'router') ) {
        try {
            return hivepress()->router->get_redirect_url();
        } catch (Exception $e) {}
    }
    return home_url('/');
}
