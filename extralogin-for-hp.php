<?php
/**
 * Plugin Name: ExtraLogin for HP
 * Description: Enhance your HivePress site with social authentication. Allow users to sign in seamlessly using their Facebook or Google accounts, reducing registration friction and improving user experience.
 * Version: 1.0.0
 * Author: Brixhab
 * Text Domain: extralogin-for-hp
 * Domain Path: /languages/
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.0
 *
 * @package ExtraLogin_For_HP
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Register extension directory with HivePress.
add_filter(
	'hivepress/v1/extensions',
	function( $extensions ) {
		$extensions['authentication'] = __DIR__;
		return $extensions;
	}
);
