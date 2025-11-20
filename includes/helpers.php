<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function elhp_get_option( $key ) {
    return get_option( 'elhp_' . $key );
}

function elhp_log( $message ) {
    if ( WP_DEBUG === true ) {
        error_log( '[ExtraLogin for HP] ' . $message );
    }
}
