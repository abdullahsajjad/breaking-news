<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/abdullahsajjad
 * @since             0.0.2
 * @package           Breaking_News
 *
 * @wordpress-plugin
 * Plugin Name:       Breaking News
 * Plugin URI:        https://git.toptal.com/screening/Abdullah-Sajjad
 * Description:       Display's breaking news on website.
 * Version:           0.0.3
 * Author:            Abdullah Sajjad
 * Author URI:        https://github.com/abdullahsajjad
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       breaking-news
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die();

/**
 * Currently plugin version.
 */
define( 'BREAKING_NEWS_VERSION', '0.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-breaking-news-activator.php
 */
function activate_breaking_news() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-breaking-news-activator.php';
	Breaking_News_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-breaking-news-deactivator.php
 */
function deactivate_breaking_news() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-breaking-news-deactivator.php';
	Breaking_News_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_breaking_news' );
register_deactivation_hook( __FILE__, 'deactivate_breaking_news' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-breaking-news.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_breaking_news() {

	$plugin = new Breaking_News();
	$plugin->run();

}
run_breaking_news();
