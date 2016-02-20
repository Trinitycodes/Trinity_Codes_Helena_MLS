<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
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
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 * @author     Trinity Codes <trinity@trinitycodes.com>
 */
class TC_Helena_MLS {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      TC_Helena_MLS_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The widgets variable
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      TC_Helena_MLS_Loader    $widgets   Loads the widgets capabilities
	 */
	protected $widgets;

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
	 * The rets_url is the url for the rets login, given by Helena MLS
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $rets_url    The current rets url for logging in to rets.
	 */
	protected $rets_url;

	/**
	 * The rets_login is the username for the rets login, given by Helena MLS
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $rets_login    The current rets username for logging in to rets.
	 */
	protected $rets_login;

	/**
	 * The rets_pass is the password for the rets login, given by Helena MLS
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $rets_pass    The current rets password for logging in to rets.
	 */
	protected $rets_pass;

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

		$this->plugin_name = 'tc-helena-mls';
		$this->version = '1.0.0';

		// get the url, username, and password for the RETS from the wp_options
		$this->rets_url = get_option( '_tc_mls_url' );
		$this->rets_login = get_option( '_tc_mls_user_login' );
		$this->rets_pass = get_option( '_tc_mls_pass' );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_widgets();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tc-helena-mls-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tc-helena-mls-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tc-helena-mls-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tc-helena-mls-public.php';

		/**
		 * The class responsible for handling actual connection and upload from the mls.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/phrets/vendor/autoload.php';

		/**
		 * The class responsible for making helper functions available to the other classes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tc-helena-mls-helpers.php';

		/**
		 * The class responsible for creating and displaying widgets.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tc-helena-mls-widgets.php';

		$this->loader = new TC_Helena_MLS_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the TC_Helena_MLS_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new TC_Helena_MLS_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Load the widgets
	 *
	 * Uses the TC_Helena_MLS_Widgets class to implement the widgets
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_widgets() {

		$this->widgets = new TC_Featured_Listings_Widget();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new TC_Helena_MLS_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'tc_add_cron_interval' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'tc_add_mls_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'tc_register_mls_options' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new TC_Helena_MLS_Public( $this->get_plugin_name(), $this->get_version(), $this->get_rets_url(), $this->get_rets_login(), $this->get_rets_pass() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode( 'tc_download_listings', $plugin_public, 'download_listings' );
		$this->loader->add_shortcode( 'tc_delete_properties', $plugin_public, 'delete_all_properties' );
		$this->loader->add_shortcode( 'tc_update_listings', $plugin_public, 'update_property_listings' );
		$this->loader->add_shortcode( 'tc_set_images', $plugin_public, 'tc_load_initial_images' );
		$this->loader->add_shortcode( 'tc_display_thumbnail', $plugin_public, 'tc_load_property_thumbnail' );
		$this->loader->add_shortcode( 'tc_display_gallery', $plugin_public, 'tc_load_property_gallery' );
		$this->loader->add_shortcode( 'tc_property_agent', $plugin_public, 'tc_display_agent_section' );

		// run by cron
		$this->loader->add_action( 'tc_download_properties', $plugin_public, 'update_property_listings' );
		$this->loader->add_action( 'tc_update_property_images', $plugin_public, 'tc_load_initial_images' );

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
	 * @return    TC_Helena_MLS_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Runthe widgets class
	 *
	 * @since     1.0.0
	 * @return    string    The widgets class.
	 */
	public function get_widgets() {
		return $this->widgets;
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

	/**
	 * Retrieve the rets_url for rets login.
	 *
	 * @since     1.0.0
	 * @return    string    The url for rets login.
	 */
	public function get_rets_url() {
		return $this->rets_url;
	}

	/**
	 * Retrieve the username for rets login.
	 *
	 * @since     1.0.0
	 * @return    string    The username for rets login.
	 */
	public function get_rets_login() {
		return $this->rets_login;
	}

	/**
	 * Retrieve the password for rets login.
	 *
	 * @since     1.0.0
	 * @return    string    The password for rets login.
	 */
	public function get_rets_pass() {
		return $this->rets_pass;
	}

}
