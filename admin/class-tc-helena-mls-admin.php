<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/admin
 * @author     Trinity Codes <trinity@trinitycodes.com>
 */
class TC_Helena_MLS_Admin {

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
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tc-helena-mls-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tc-helena-mls-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register new cron intervals
	 *
	 * @since    1.0.0
	 */
	public function tc_add_cron_interval( $schedules ) {
		$interval = 15*60;
	    $schedules['15minutes'] = array(
	        'interval' => $interval,
	        'display'  => __( 'Every Fifteen Minutes' ),
	    );
	    $schedules['5minutes'] = array(
	        'interval' => 5*60,
	        'display'  => __( 'Every Five Minutes' ),
	    );
	    $schedules['30minutes'] = array(
	        'interval' => 30*60,
	        'display'  => __( 'Every Thirty Minutes' ),
	    );
	 
	    return $schedules;
	}

	/**
	 * Create a custom Options page
	 *
	 * @since    1.0.0
	 */
	public function tc_add_mls_options_page() {

		add_options_page( 'Helena MLS Options', 'Helena MLS Plugin', 'manage_options', 'tc-helena-mls-admin', array( $this, 'tc_mls_plugin_options' ) );

	}

	/**
	 * Create the html for the options page
	 *
	 * @since    1.0.0
	 */
	public function tc_mls_plugin_options() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		include_once( dirname( __FILE__ ) . '/partials/tc-mls-options-page.php' );
		
	}

	/**
	 * Register the mls options
	 *
	 * @since    1.0.0
	 */
	public function tc_register_mls_options() {

		//register our settings
		register_setting( 'tc-helena-mls-admin', '_tc_mls_url' );
		register_setting( 'tc-helena-mls-admin', '_tc_mls_user_login' );
		register_setting( 'tc-helena-mls-admin', '_tc_mls_pass' );
		
	}



}
