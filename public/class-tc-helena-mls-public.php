<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/public
 * @author     Trinity Codes <trinity@trinitycodes.com>
 */
class TC_Helena_MLS_Public {

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

	protected $helpers;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $rets_url, $rets_login, $rets_pass ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->rets_url = $rets_url;
		$this->rets_login = $rets_login;
		$this->rets_pass = $rets_pass;
		$this->helpers = new TC_Helena_MLS_Helpers();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tc-helena-mls-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tc-helena-mls-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Display the property thumbnail 
	 *
	 * This is fired by a shortcode [tc_display_thumbnail]
	 * 
	 * @return  (html) The image and link output.
	 */
	public function tc_load_property_thumbnail () {

		$post_id = get_the_ID();

		if( has_post_thumbnail( $post_id ) ) {
			ob_start();
				include_once( dirname( __FILE__ ) . '/partials/tc-display-thumbnail.php' );
			return ob_get_clean();
		}

	}

	/**
	 * Display the property gallery from the post_meta
	 *
	 * This is fired by a shortcode [tc_display_thumbnail]
	 * 
	 * @return  (html) The image gallery.
	 */
	public function tc_load_property_gallery () {

		if( has_post_thumbnail( $post_id ) ) {
			ob_start();
				include_once( dirname( __FILE__ ) . '/partials/tc-display-property-gallery.php' );
			return ob_get_clean();
		}

	}

	/**
	 * Display the agent and office section on the property detail page.
	 *
	 * This is fired by a shortcode [tc_property_agent]
	 * 
	 * @return  (html) The agent display section.
	 */
	public function tc_display_agent_section () {

		if( has_post_thumbnail( $post_id ) ) {
			ob_start();
				include_once( dirname( __FILE__ ) . '/partials/tc-display-agent-section.php' );
			return ob_get_clean();
		}

	}

}
