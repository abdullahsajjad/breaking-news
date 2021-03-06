<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Breaking_News
 * @subpackage Breaking_News/includes
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class Breaking_News {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Breaking_News_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BREAKING_NEWS_VERSION' ) ) {
			$this->version = BREAKING_NEWS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'breaking-news';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Breaking_News_Loader. Orchestrates the hooks of the plugin.
	 * - Breaking_News_i18n. Defines internationalization functionality.
	 * - Breaking_News_Admin. Defines all hooks for the admin area.
	 * - Breaking_News_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-breaking-news-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-breaking-news-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-breaking-news-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-breaking-news-public.php';

		/**
		 * The class responsible for defining all actions that occur in the settings page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/class-breaking-news-settings.php';

		$this->loader = new Breaking_News_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Breaking_News_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Breaking_News_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Define and register all the functionality related to admin area
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 */
	protected function define_admin() {
		$this->define_admin_hooks();

		$this->define_settings_hooks();
	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 */
	protected function define_admin_hooks() {

		$plugin_admin = new Breaking_News_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_page' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_custom_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_data' );

	}


	/**
	 * Register all the hooks related to the settings page
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 */
	protected function define_settings_hooks () {
		$plugin_settings = new Breaking_News_Settings;

		$this->loader->add_action( 'admin_init', $plugin_settings, 'bn_register_settings' );
		$this->loader->add_action( 'bn_settings_content', $plugin_settings, 'do_settings_fields' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Breaking_News_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'display_breaking_news',9999 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Breaking_News_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
