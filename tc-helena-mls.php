<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           TC_Helena_MLS
 *
 * @wordpress-plugin
 * Plugin Name:       Trinity Codes Helena MLS
 * Plugin URI:        http://trinitycodes.com/helena-mls-plugin/
 * Description:       TC Helena MLS uses PHRETS to connect to the Helena MLS and store MLS data in the database for use with plugins and themes.
 * Version:           1.0.0
 * Author:            Trinity Codes
 * Author URI:        http://trinitycodes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tc-helena-mls
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tc-helena-mls-activator.php
 */
function activate_tc_helena_mls() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tc-helena-mls-activator.php';
	TC_Helena_MLS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_tc_helena_mls() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tc-helena-mls-deactivator.php';
	TC_Helena_MLS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tc_helena_mls' );
register_deactivation_hook( __FILE__, 'deactivate_tc_helena_mls' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tc-helena-mls.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tc_helena_mls() {

	$plugin = new TC_Helena_MLS();
	$plugin->run();

}
run_tc_helena_mls();
