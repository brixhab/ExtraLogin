<?php
/**
 * Scripts configuration.
 *
 * @package ExtraLogin_For_HP\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'extralogin-authentication' => [
		'handle'  => 'extralogin-authentication',
		'src'     => plugin_dir_url( __FILE__ ) . '../../assets/js/common.min.js',
		'version' => '1.0.0',
		'deps'    => [ 'hivepress-core' ],
		'data'    => [
			'apiURL' => hivepress()->router->get_url(
				'user_authenticate_action',
				[
					'authenticator' => '',
				]
			),
		],
	],
];
