<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/admin
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class Breaking_News_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Breaking_News_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Breaking_News_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/breaking-news-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/breaking-news-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the Settings Page in Admin Dashboard
	 *
	 * @since 0.0.1
	 */
	public function add_admin_page() {
		add_menu_page( __( 'Breaking News', 'breaking-news' ),
			'Breaking News',
			'manage_options',
			'breaking-news',
			[ $this, 'settings_page_callback' ],
			'dashicons-welcome-widgets-menus',
			90
		);
	}

	/**
	 * Settings Page Callback method
	 *
	 * @since 0.0.1
	 */
	public function settings_page_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'templates/breaking-news-admin-display.php';
	}
}
