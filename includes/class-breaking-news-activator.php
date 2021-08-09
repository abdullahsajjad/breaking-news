<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Breaking_News
 * @subpackage Breaking_News/includes
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class Breaking_News_Activator {

	/**
	 * Runs When Plugin is activated
	 *
	 * @since    0.0.2
	 */
	public static function activate() {
		self::set_default_options();
	}

	/**
	 * Sets Default options for the plugin
	 *
	 * @since 0.0.2
	 */
	protected static function set_default_options() {
		$bn_settings = get_option( 'bn_settings' );

		$bn_defaults = [
			'news_title'       => 'Breaking News :',
			'background_color' => '#fb0404',
			'text_color'       => '#ffffff',
			'bn_position'      => 'after-header',
		];

		self::bn_save_default_options( $bn_settings, $bn_defaults, 'bn_settings' );
	}


	/**
	 * Save default options in options
	 *
	 * checks for the existing options if not set then
	 * add the default value's in options table
	 *
	 * @param $bn_settings      array   db options array
	 * @param $default_settings array   default options array
	 * @param $option           string  option name
	 *
	 * @since 0.0.2
	 */
	protected static function bn_save_default_options( $bn_settings, $default_settings, $option ) {
		if( $bn_settings === FALSE ) {
			$bn_settings = $default_settings;
		} else {
			foreach ( $default_settings as $key => $value ) {
				if ( !isset( $bn_settings[ $key ] ) ) {
					$bn_settings[ $key ] = $value;
				}
			}
		}

		update_option( $option, $bn_settings );
	}

}
