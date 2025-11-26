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

// Define plugin constants.
define( 'EXTRALOGIN_VERSION', '1.0.0' );
define( 'EXTRALOGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EXTRALOGIN_URL', plugin_dir_url( __FILE__ ) );

// Register extension directory with HivePress.
add_filter(
	'hivepress/v1/extensions',
	function( $extensions ) {
		$extensions[] = __DIR__;
		return $extensions;
	}
);

// Check if HivePress is active.
add_action(
	'plugins_loaded',
	function() {
		if ( ! function_exists( 'hivepress' ) ) {
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-error">
						<p>
							<?php
							echo wp_kses_post(
								sprintf(
									/* translators: %s: plugin name */
									__( '<strong>%s</strong> requires HivePress plugin to be installed and activated.', 'extralogin-for-hp' ),
									'ExtraLogin for HP'
								)
							);
							?>
						</p>
					</div>
					<?php
				}
			);
		}
	}
);
