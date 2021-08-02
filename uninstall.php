<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://github.com/abdullahsajjad
 * @since      0.0.3
 *
 * @package    Breaking_News
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


if ( ! function_exists( 'bn_delete_all_options' ) ) {

	/**
	 * Deletes plugin data in options table
	 *
	 * @since 0.0.3
	 */
	function bn_delete_all_options() {
		$options_arr = [
			'bn_settings',
		];
		foreach ( $options_arr as $option ) {
			delete_option( $option );
		}
	}

} // endif

if ( ! function_exists( 'bn_is_data_removal_allowed' ) ) {

	/**
	 * checks if allowed removing data on plugin uninstall
	 *
	 * @return bool
	 * @since 0.0.3
	 */
	function bn_is_data_removal_allowed() {
		$general_options = get_option( 'bn_settings' );
		if ( ! empty( $general_options['remove_data'] ) ) {
			return true;
		}

		return false;
	}

} // endif

if ( ! function_exists( 'bn_uninstall_driver' ) ) {

	/**
	 * Driver Function
	 *
	 * @since 0.0.3
	 */
	function bn_uninstall_driver() {
		if ( bn_is_data_removal_allowed() === false ) { //if not allowed return
			return;
		}

		bn_delete_all_options();
	}

} // endif


bn_uninstall_driver();
