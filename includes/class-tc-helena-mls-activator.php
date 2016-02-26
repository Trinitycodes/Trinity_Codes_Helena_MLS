<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 * @author     Trinity Codes <trinity@trinitycodes.com>
 */
class TC_Helena_MLS_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// create scheduled events here.
		if( !wp_next_scheduled( 'tc_download_properties' ) ) {
			wp_schedule_event( 1434963600, 'daily', 'tc_download_properties' );
		}
		if( !wp_next_scheduled( 'tc_update_property_images' ) ) {
			wp_schedule_event( time(), '15minutes', 'tc_update_property_images' );
		}

	}

}
