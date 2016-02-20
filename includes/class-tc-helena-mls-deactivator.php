<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 * @author     Trinity Codes <trinity@trinitycodes.com>
 */
class TC_Helena_MLS_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// delete all scheduled events
		wp_clear_scheduled_hook('tc_download_properties');
		wp_clear_scheduled_hook('tc_update_property_images');

	}

}
