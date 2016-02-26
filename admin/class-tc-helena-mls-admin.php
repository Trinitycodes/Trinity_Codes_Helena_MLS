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

	protected $helpers;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
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

		add_options_page(
	        'Helena MLS Settings',
	        'MLS Settings',
	        'manage_options',
	        'tc-helena-mls-admin',
	        array( $this, 'tc_mls_plugin_options' )
	    );

	}

	/**
	 * Create a custom Admin page for loading properties and images
	 *
	 * @since    1.0.0
	 */
	public function tc_add_mls_admin_page() {

		$page_title = 'MLS Admin';
	    $menu_title = 'MLS Admin';
	    $capability = 'manage_options';
	    $menu_slug = 'tc_mls_admin';
	    $function = array( $this, 'tc_mls_admin_page' );
	    $icon_url = '';
	    $position = 99;

	    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

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
	 * Create the html for the mls admin page
	 *
	 * @since    1.0.0
	 */
	public function tc_mls_admin_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if( $_POST['_tc_action'] ) {

			switch( $_POST['_tc_action'] ) {

				case 'download':
					do_action( 'tc_execute_download_listings' );
					break;

				case 'update':
					do_action( 'tc_execute_update_listings' );
					break;

				case 'images':
					do_action( 'tc_execute_load_images' );
					break;

				case 'delete':
					do_action( 'tc_execute_delete_listings' );

			}
			
		}
		
		include_once( dirname( __FILE__ ) . '/partials/tc-mls-admin-page.php' );
		
	}

	/**
	 * Register the mls options
	 *
	 * @since    1.0.0
	 */
	public function tc_register_mls_options() {

		//register our settings
		$section_group = 'tc-helena-mls-admin';

		 // Create section of Page
		 $settings_section = 'tc_main';
		 $page = $section_group;
		 add_settings_section( 
			 $settings_section,
			 __( 'Rets Login Settings', 'tc-helena-mls' ),
			 array( $this, 'tc_main_section_description' ),
			 $page
		 );
		 
		 // Add fields to that section
		add_settings_field(
			 '_tc_mls_url',
			 __( 'MLS RETS Url', 'tc-helena-mls' ),
			 array( $this, 'tc_render_rets_login_field' ),
			 $page,
			 $settings_section,
			 array(
			 		'type' => 'url',
			 		'field_name' => '_tc_mls_url',
			 	)
		);

		add_settings_field(
			 '_tc_mls_user_login',
			 __('RETS UserLogin', 'tc-helena-mls' ),
			 array( $this, 'tc_render_rets_login_field' ),
			 $page,
			 $settings_section,
			 array( 
			 		'type' => 'text',
			 		'field_name' => '_tc_mls_user_login',
			 	)
		);

		add_settings_field(
			 '_tc_mls_pass',
			 __('RETS Pass', 'tc-helena-mls' ),
			 array( $this, 'tc_render_rets_login_field' ),
			 $page,
			 $settings_section,
			 array(
			 		'type' => 'password',
			 		'field_name' => '_tc_mls_pass',
			 	)
		);

		register_setting( $section_group, '_tc_mls_url', array( $this, 'tc_sanitize_string' ) );
		register_setting( $section_group, '_tc_mls_user_login', array( $this, 'tc_sanitize_string') );
		register_setting( $section_group, '_tc_mls_pass', array( $this, 'tc_sanitize_string') );
		
	}

	/**
	 * Render the inputs for the main section on options page
	 * 
	 * @param  (associative array) $args Contains field name and type for type of content that should be in the field.
	 * @return echo       form input
	 */
	public function tc_render_rets_login_field( $args ) {
		$size = '30';
		$type = 'text';

		if( $args['type'] == 'url' ) {
			$size = '90';
		}

		if( $args['type'] == 'password' ) {
			$type = 'password';
		}

    	echo '<input type="' . $type . '" size="' . $size . '" id="' . $args['field_name'] . '" name="' . $args['field_name'] . '" value="'. get_option( $args['field_name'], '').'">';

	}

	/**
	 * Validate input text field
	 * @param  string $input Value of text input.
	 * @return string        Sanitized string value.
	 */
	public function tc_sanitize_string( $input ) {
		$validated = sanitize_text_field( $input );
		return $validated;
	}

	/**
	 * Description to display above the main section on the settings page.
	 * @return echo [description]
	 */
	public function tc_main_section_description() {

		echo 'The RETS login information will need to be obtained from the Helena MLS and requires a valid realtor account.';

	}

	/**
	 * Download the Listings on the site.
	 * 
	 */
	public function download_listings(){

		$no_image = get_page_by_title( 'no_image', 'OBJECT', 'attachment' );
		if( !$no_image ) {
			print_r( "Error: There is no Placeholder Image.<br><br>Please upload an image for a placeholder and give it a title of 'no_image'.  Then try again." );
			exit;
		}

		include_once( dirname( __FILE__ ) . '/../includes/phrets/vendor/autoload.php' );

		$config = new \PHRETS\Configuration;
		$config->setLoginUrl( $this->rets_url )
		        ->setUsername( $this->rets_login )
		        ->setPassword( $this->rets_pass )
		        ->setRetsVersion('1.7.2');
		//$rets->setLogger($log);
		$rets = new \PHRETS\Session($config);
		$connect = $rets->Login();

		$timestamp_field = 'L_UpdateDate';
		$property_classes = array('RE_1', 'LD_2', 'CI_3', 'MF_4');

		// Set the property download counter
		$property_count = 0;

		// Set the time string for the $query
		$time_set = $this->helpers->set_search_date('download');

		foreach ($property_classes as $pc) {
		    // generate the DMQL query
		    $query = "({$timestamp_field}=$time_set+)";
		    
		    // Create a select string depending on which class is iterating
		    switch( $pc ) {

		    	case 'RE_1':
		    		$select_string = 'L_PictureCount,VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_Keyword1,L_Keyword2,L_Keyword3,L_Keyword4,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LM_Char25_1,LM_Int4_2,LM_Int4_8,LMD_MP_Latitude,LMD_MP_Longitude,LFD_GARAGE_5,LFD_STYLE_8,LFD_BASEMENT_11,LFD_COOLING_14,LFD_HEATING_15,LFD_ZONING_27,LFD_OUTBUILDINGS_32,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    	case 'LD_2':
		    		$select_string = 'L_PictureCount,VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_Keyword3,L_Keyword10,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LMD_MP_Latitude,LMD_MP_Longitude,LMD_MP_Subdivision,LFD_OUTBUILDINGS_40,LFD_LIVESTOCKALLOWED_42,LFD_IRRIGATION_55,LFD_MINERALRIGHTS_56,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    	case 'CI_3':
		    		$select_string = 'L_PictureCount,VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LM_Char25_13,LMD_MP_Latitude,LMD_MP_Longitude,LFD_COOLING_72,LFD_HEATING_73,LFD_ZONING_76,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    	case 'MF_4':
		    		$select_string = 'L_PictureCount,VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_Keyword7,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LM_Char25_1,LM_Int4_2,LM_Int4_8,LMD_MP_Latitude,LMD_MP_Longitude,LFD_STYLE_93,LFD_BASEMENT_94,LFD_COOLING_95,LFD_HEATING_96,LFD_ZONING_107,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    }
		    // make the request and get the results
		    $results = $rets->Search(
		    	'Property', 
		    	$pc, 
		    	$query, 
		    	array(
		    	'QueryType' => 'DMQL2',
		        'Count' => 1, // count and records
		        'Format' => 'COMPACT-DECODED',
		        'Limit' => 9999,
		        'StandardNames' => 0, // give system names
		        'Select' => $select_string,
		        )
		    );

		    $results_count = count( $results );
		   //  print_r( "$pc Properties Found: $results_count<br><br>" ); $rets->Disconnect(); exit;

		    set_time_limit(120);

		    foreach( $results as $result ) {

		    	/** 
		    	 * First we need to make sure the property 
		    	 * does not already exist.  Test that using 
		    	 * the MLS Number
		    	 */
		    	$post_check = false;

		    	$post_check = $this->helpers->does_post_exist( $result->get('L_ListingID'), '_listing_mls', 'listing' );

		    	// if $post_check is false, then post does 
		    	// not exist, so create a new one.
		    	if( !$post_check && $result->get('L_Status') == 'Active' ) {

		    		$property_count = $property_count + 1;

		    		if( $result->get( 'L_Class' ) == 'RESIDENTIAL' ) {
		    			// Figure the number of bathrooms
						$full_baths = intval( $result->get('L_Keyword2') );
						$half_baths = intval( $result->get('L_Keyword3') );
						$total_baths = $full_baths + $half_baths;
					}

		    		/**
		    		 * Get all the taxanomy ids for this property
		    		 */
		    		$categories = $this->helpers->get_property_taxonomies( 
		    			array(
		    				'class' => $result->get('L_Class'), 
		    				'types' => $result->get('L_Type_'), 
		    				'main_area' => $result->get('L_Area'), 
		    				'sub_area' => $result->get('L_Area'),
		    				'bedrooms' => $result->get('L_Keyword1'),
		    				'bathrooms' => $total_baths,
		    				'price' => $result->get('L_AskingPrice'),
		    				'acres' => $result->get('L_NumAcres'),
		    				'sqfeet' => $result->get('LM_Int4_8'),
		    				'labels' => $result->get('L_ListAgent1'),
		    			)
		    		);

		    		wp_set_current_user( 1 );

		    		$content = '';
		    		$content .= '[tc_display_thumbnail]';
		    		$content .= '&nbsp; [tc_display_gallery]';
		    		$content .= '&nbsp;&nbsp;' . $result->get('L_Remarks');
		    		$content .= '&nbsp;&nbsp;<br><br><br>[property_details]';
		    		$content .= '&nbsp;&nbsp;[tc_property_agent]';
		    		$content .= '&nbsp;&nbsp;[property_map]';

			    	// Send results to be handled by method
			    	// Create custom post_type "noo_property"
					$property = array(
					  'post_title'    => $result->get('L_Address'),
	  			      'post_content'  => $content,
					  'post_status'           => 'publish', 
					  'post_type'             => 'listing',
					  'tax_input' => $categories,
					  'post_author'           => 1,
					  'ping_status'           => get_option('default_ping_status'), 
					  'guid'                  => '',
					  'post_content_filtered' => '',
					  'post_excerpt'          => '',
					  'import_id'             => 0
					);

					// Insert the post into the database
					$post_id = wp_insert_post( $property );
					
					// Now we can insert the post meta

					$mls_num = strval( $result->get('L_ListingID') );
					add_post_meta( $post_id, '_listing_mls', $result->get('L_ListingID') );
					add_post_meta( $post_id, '_listing_text', 'MLS # ' . $result->get('L_ListingID') );

					add_post_meta( $post_id, '_listing_address', $result->get('L_Address') );
					add_post_meta( $post_id, '_listing_city', $result->get('L_City') );
					add_post_meta( $post_id, '_listing_state', $result->get('L_State') );
					add_post_meta( $post_id, '_listing_zip', $result->get('L_Zip') );

					add_post_meta( $post_id, '_listing_price', '$' . number_format( $result->get('L_AskingPrice') ) );
					add_post_meta( $post_id, '_listing_price_sortable', $result->get('L_AskingPrice') );
					add_post_meta( $post_id, '_listing_sqft', $result->get('LM_Int4_8') );
					add_post_meta( $post_id, '_tc_picture_count', $result->get('L_PictureCount') );

					// get the place id
					$address_string = $result->get('L_Address') . ',' . $result->get('L_State') . ' ' . $result->get('L_Zip');
					$place_id = $this->helpers->tc_get_geocode( $address_string );

					if( $place_id[2] ) {

						// Add the google map to the post_meta
						$map_embed_code = '<iframe
  										width="800"
  										height="400"
  										frameborder="0" style="border:0"
  										src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBpzASGwLzl6AVIbeatVrtwN6MbyliOVhc&q=place_id:' . $place_id[2] . '" allowfullscreen>
										</iframe>';
						add_post_meta( $post_id, '_listing_map', $map_embed_code );

					}					


					if( $this->helpers->is_accepted_url( $result->get('VT_VTourURL') ) )
						add_post_meta( $post_id, '_listing_video', $result->get('VT_VTourURL') );

					add_post_meta( $post_id, '_tc_listing_office', $result->get('LO1_OrganizationName') );
					add_post_meta( $post_id, '_tc_listing_agent', $result->get('LA1_UserFirstName') . ' ' . $result->get('LA1_UserLastName') . ' ' . $result->get('LA1_PhoneNumber1') );

					/**
					 * Only add thes if Class is Residential
					 */
					if( $result->get('L_Class') == 'RESIDENTIAL' ) {

						add_post_meta( $post_id, '_listing_bedrooms', $result->get('L_Keyword1') );
						add_post_meta( $post_id, '_listing_bathrooms', $total_baths );
						add_post_meta( $post_id, '_listing_basement', $result->get('LFD_BASEMENT_11') );
						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('LFD_ZONING_27') );
						add_post_meta( $post_id, '_noo_property_field_heating', $result->get('LFD_HEATING_15') );
						add_post_meta( $post_id, '_noo_property_field_style', $result->get('LFD_STYLE_8') );
						
						add_post_meta( $post_id, '_noo_property_field_year-built', $result->get('LM_Int4_2') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', $this->is_this_amenity( $result->get('LFD_OUTBUILDINGS_32') ) );
						add_post_meta( $post_id, '_noo_property_feature_cooling', $this->is_this_amenity( $result->get('LFD_COOLING_14') ) );*/

					}

					/**
					 * Only add thes if Class is Commercial
					 */
					if( $result->get('L_Class') == 'COMMERCIAL' ) {

						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('LFD_ZONING_76') );
						add_post_meta( $post_id, '_noo_property_field_heating', $result->get('LFD_HEATING_73') );
						add_post_meta( $post_id, '_noo_property_field_business-name', $result->get('LM_Char25_13') );
						add_post_meta( $post_id, '_noo_property_field_year-built', $result->get('LM_Int4_2') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', '0' );
						add_post_meta( $post_id, '_noo_property_feature_cooling', $this->is_this_amenity( $result->get('LFD_COOLING_72') ) );*/

					}

					/**
					 * Only add thes if Class is Land
					 */
					if( $result->get('L_Class') == 'LAND' ) {

						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('L_Keyword10') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', $this->is_this_amenity( $result->get('LFD_OUTBUILDINGS_40') ) );
						add_post_meta( $post_id, '_noo_property_feature_cooling', '0' );*/

					}

					/**
					 * Only add thes if Class is Multifamily
					 */
					if( $result->get('L_Class') == 'MULTIFAMILY' ) {

						add_post_meta( $post_id, '_listing_basement', $result->get('LFD_BASEMENT_94') );
						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('LFD_ZONING_107') );
						add_post_meta( $post_id, '_noo_property_field_heating', $result->get('LFD_HEATING_96') );
						add_post_meta( $post_id, '_noo_property_field_style', $result->get('LFD_STYLE_93') );
						
						add_post_meta( $post_id, '_noo_property_field_year-built', $result->get('LM_Int4_2') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', '0' );
						add_post_meta( $post_id, '_noo_property_feature_cooling', $this->is_this_amenity( $result->get('LFD_COOLING_95') ) );*/


					}

					/**
					 * Add the no_image image as thumbnail
					 */
					$no_image = get_page_by_title( 'no_image', 'OBJECT', 'attachment' );
					add_post_meta( $post_id, '_thumbnail_id', $no_image->ID );

				}

		    	
		    	// $string = '<br><br>Virtual Tour Address => ' . $result->get('VT_VTourURL') . '<br> String Date: ' . strtotime( $result->get('L_ListingDate') ) . '<br> Days on Market => ' . $this->figure_days_on_market( $result->get('L_ListingDate') ) . '<br> String Date Today: ' . strtotime( date('c') ) . '<br><br>'; 
		    			    	
		    }

		}

		/**
		 * Create _tc_full_download_complete setting
		 *
		 * Set to date of completion.
		 */
		if( get_option( '_tc_full_download_complete' ) ) {
			$full_download = update_option( '_tc_full_download_complete', date( 'Ymd' ) );
		} else {
			$full_download = add_option( '_tc_full_download_complete', date( 'Ymd' ) );
		}

		if( $full_download ) {
			print_r( 'Full Download completed date was saved successfully!' );
		} else {
			print_r( 'The Update option was not updated.<br><br>' );
		}
		print_r( $property_count . ' Properties were Downloaded by the downloader' );


		// logout
		$rets->Disconnect();

	}

	/**
	 *  Update the Listings on the site.
	 * 
	 */
	public function update_property_listings(){

		$no_image = get_page_by_title( 'no_image', 'OBJECT', 'attachment' );
		if( !$no_image ) {
			print_r( "Error: There is no Placeholder Image.<br><br>Please upload an image for a placeholder and give it a title of 'no_image'.  Then try again." );
			exit;
		}

		$args = array(
				'post_type' => 'listing',
				'cache_results' => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'nopaging' => true,
			);
		$my_query = new WP_Query( $args );

		// if _tc_full_download_complete option is not set then 
		// run download listings instead of update listings
		$full_download = get_option( '_tc_full_download_complete' );
		if( !$full_download ) {
			do_action( 'tc_execute_download_listings' );
			die();
		}

		include_once( dirname( __FILE__ ) . '/../includes/phrets/vendor/autoload.php' );

		$config = new \PHRETS\Configuration;
		$config->setLoginUrl( $this->rets_url )
		        ->setUsername( $this->rets_login )
		        ->setPassword( $this->rets_pass )
		        ->setRetsVersion('1.7.2');
		//$rets->setLogger($log);
		$rets = new \PHRETS\Session($config);
		$connect = $rets->Login();

		$timestamp_field = 'L_UpdateDate';
		$property_classes = array('RE_1', 'LD_2', 'CI_3', 'MF_4');

		// Set the property download counter
		$property_count = 0;

		// Set the time string for the $query
		$time_set = $this->helpers->set_search_date('update');

		foreach ($property_classes as $pc) {
		    // generate the DMQL query
		    $query = "({$timestamp_field}=$time_set+)";
		    
		    // Create a select string depending on which class is iterating
		    switch( $pc ) {

		    	case 'RE_1':
		    		$select_string = 'VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_Keyword1,L_Keyword2,L_Keyword3,L_Keyword4,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LM_Char25_1,LM_Int4_2,LM_Int4_8,LMD_MP_Latitude,LMD_MP_Longitude,LFD_GARAGE_5,LFD_STYLE_8,LFD_BASEMENT_11,LFD_COOLING_14,LFD_HEATING_15,LFD_ZONING_27,LFD_OUTBUILDINGS_32,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    	case 'LD_2':
		    		$select_string = 'VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_Keyword3,L_Keyword10,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LMD_MP_Latitude,LMD_MP_Longitude,LMD_MP_Subdivision,LFD_OUTBUILDINGS_40,LFD_LIVESTOCKALLOWED_42,LFD_IRRIGATION_55,LFD_MINERALRIGHTS_56,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    	case 'CI_3':
		    		$select_string = 'VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LM_Char25_13,LMD_MP_Latitude,LMD_MP_Longitude,LFD_COOLING_72,LFD_HEATING_73,LFD_ZONING_76,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    	case 'MF_4':
		    		$select_string = 'VT_VTourURL,L_ListingID,L_Class,L_Type_,L_Area,L_AskingPrice,L_City,L_State,L_Zip,L_Keyword7,L_NumAcres,LO1_OrganizationName,L_ListingDate,L_Remarks,L_UpdateDate,L_Last_Photo_updt,L_DisplayId,L_Address,L_Status,LM_Char25_1,LM_Int4_2,LM_Int4_8,LMD_MP_Latitude,LMD_MP_Longitude,LFD_STYLE_93,LFD_BASEMENT_94,LFD_COOLING_95,LFD_HEATING_96,LFD_ZONING_107,LA1_LoginName,LA1_UserFirstName,LA1_UserLastName,LA1_PhoneNumber1,L_ListAgent1';
		    		break;

		    }
		    // make the request and get the results
		    $results = $rets->Search(
		    	'Property', 
		    	$pc, 
		    	$query, 
		    	array(
		    	'QueryType' => 'DMQL2',
		        'Count' => 1, // count and records
		        'Format' => 'COMPACT-DECODED',
		        'Limit' => 9999,
		        'StandardNames' => 0, // give system names
		        'Select' => $select_string,
		        )
		    );

		    $results_count = $results->getTotalResultsCount();
		    print_r( "$results_count<br><br>" );

		    set_time_limit(120);

		    foreach( $results as $result ) {

		    	/** 
		    	 * First we need to make sure the property 
		    	 * does not already exist.  Test that using 
		    	 * the MLS Number
		    	 */
		    	$post_check = false;

		    	$post_check = $this->helpers->does_post_exist( $result->get('L_ListingID'), '_listing_mls', 'listing' );
		    	

		    	/**
		    	 * The only properties included in the above
		    	 * query will be those either created that day
		    	 * or those updated that day.  
		    	 * 
		    	 * If they were created that day, then 
		    	 * $post_check will be false and a new one 
		    	 * will be created.
		    	 *
		    	 * If the property has been updated then it will 
		    	 * exist.  In that case we need to delete it 
		    	 * and then set $post_check to false so that a 
		    	 * new one will be created.
		    	 * 
		    	 * Now we need to determine whether we need to 
		    	 * update the post or delete it.
		    	 */
		    	if( $post_check ) {

		    		if( $result->get('L_Status') != 'Active' ) {

			    		// Delete all the attachments associated 
			    		// with this property ($post_check contains the property ID)
			    		$args = array(
							'post_type' => 'attachment',
							'numberposts' => -1,
							'post_status' => null,
							'post_parent' => $post_check
						);

						$attachments = get_posts( $args );
						if ( $attachments ) {
							foreach ( $attachments as $attachment ) {

							    wp_delete_attachment( $attachment->ID, true );

							}
						}

			    		// Delete the property 
			    		wp_delete_post( $post_check, true );

			    	} else {

			    		/**
			    		 * Update the post content
			    		 */
			    		
			    		// create the content
			    		$content = '';
			    		$content .= '[tc_display_thumbnail]';
			    		$content .= '&nbsp; [tc_display_gallery]';
			    		$content .= '&nbsp;&nbsp;' . $result->get('L_Remarks');
			    		$content .= '&nbsp;&nbsp;[property_details]';
			    		$content .= '&nbsp;&nbsp;[tc_property_agent]';
			    		$content .= '&nbsp;&nbsp;[property_map]';

						$my_post = array(
						    'ID'           => $post_check,
						    'post_content' => $content,
						);

						// Update the post into the database
						wp_update_post( $my_post );

						/**
			    		 * Update all of the post meta for the property
			    		 */
						update_post_meta( $post_check, '_listing_price', '$' . number_format( $result->get('L_AskingPrice') ) );
						update_post_meta( $post_check, '_listing_sqft', $result->get('LM_Int4_8') );
						/*update_post_meta( $post_check, '_listing_acres', $result->get('L_NumAcres') );
						update_post_meta( $post_check, '_noo_property_feature_livestock-allowed', $this->is_this_amenity( $result->get('LFD_LIVESTOCKALLOWED_42') ) );
						update_post_meta( $post_check, '_noo_property_feature_irrigation', $this->is_this_amenity( $result->get('LFD_IRRIGATION_55') ) );
						update_post_meta( $post_check, '_noo_property_feature_mineral-rights', $this->is_this_amenity( $result->get('LFD_MINERALRIGHTS_56') ) ); */

						if( $this->helpers->is_accepted_url( $result->get('VT_VTourURL') ) )
							update_post_meta( $post_check, '_listing_video', $result->get('VT_VTourURL') );

						update_post_meta( $post_check, '_tc_listing_office', $result->get('LO1_OrganizationName') );
						update_post_meta( $post_check, '_tc_listing_agent', $result->get('LA1_UserFirstName') . ' ' . $result->get('LA1_UserLastName') . ' ' . $result->get('LA1_PhoneNumber1') );

			    	}

		    	}

		    	// if $post_check is false, then post does 
		    	// not exist, so create a new one.
		    	if( !$post_check && $result->get('L_Status') == 'Active' ) {

		    		$property_count = $property_count + 1;

		    		/**
		    		 * Get all the taxanomy ids for this property
		    		 */
		    		$categories = $this->helpers->get_property_taxonomies( 
		    			array(
		    				'class' => $result->get('L_Class'), 
		    				'types' => $result->get('L_Type_'), 
		    				'main_area' => $result->get('L_Area'), 
		    				'sub_area' => $result->get('L_Area'),
		    				'bedrooms' => $result->get('L_Keyword1'),
		    				'bathrooms' => $total_baths,
		    				'price' => $result->get('L_AskingPrice'),
		    				'acres' => $result->get('L_NumAcres'),
		    				'sqfeet' => $result->get('LM_Int4_8'),
		    				'labels' => $result->get('L_ListAgent1'),
		    			)
		    		);
		    		

		    		wp_set_current_user( 1 );

		    		// create the content
			    		$content = '';
			    		$content .= '[tc_display_thumbnail]';
			    		$content .= '&nbsp; [tc_display_gallery]';
			    		$content .= '&nbsp;&nbsp;' . $result->get('L_Remarks');
			    		$content .= '&nbsp;&nbsp;<br><br><br>[property_details]';
			    		$content .= '&nbsp;&nbsp;[tc_property_agent]';
			    		$content .= '&nbsp;&nbsp;[property_map]';

			    	// Send results to be handled by method
			    	// Create custom post_type "noo_property"
					$property = array(
					  'post_title'    => $result->get('L_Address'),
	  			      'post_content'  => $content,
					  'post_status'           => 'publish', 
					  'post_type'             => 'listing',
					  'tax_input' => $categories,
					  'post_author'           => 1,
					  'ping_status'           => get_option('default_ping_status'), 
					  'guid'                  => '',
					  'post_content_filtered' => '',
					  'post_excerpt'          => '',
					  'import_id'             => 0
					);

					// Insert the post into the database
					$post_id = wp_insert_post( $property );

					// Now we can insert the post meta
					$mls_num = strval( $result->get('L_ListingID') );
					add_post_meta( $post_id, '_listing_mls', $result->get('L_ListingID') );
					add_post_meta( $post_id, '_listing_text', 'MLS # ' . $result->get('L_ListingID') );

					add_post_meta( $post_id, '_listing_address', $result->get('L_Address') );
					add_post_meta( $post_id, '_listing_city', $result->get('L_City') );
					add_post_meta( $post_id, '_listing_state', $result->get('L_State') );
					add_post_meta( $post_id, '_listing_zip', $result->get('L_Zip') );

					add_post_meta( $post_id, '_listing_price', '$' . number_format( $result->get('L_AskingPrice') ) );
					add_post_meta( $post_id, '_listing_price_sortable', $result->get('L_AskingPrice') );
					add_post_meta( $post_id, '_listing_sqft', $result->get('LM_Int4_8') );
					add_post_meta( $post_id, '_tc_picture_count', $result->get('L_PictureCount') );

					// get the place id
					$address_string = $result->get('L_Address') . ',' . $result->get('L_State') . ' ' . $result->get('L_Zip');
					$place_id = $this->helpers->tc_get_geocode( $address_string );

					if( $place_id[2] ) {

						// Add the google map to the post_meta
						$map_embed_code = '<iframe
  										width="800"
  										height="400"
  										frameborder="0" style="border:0"
  										src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBpzASGwLzl6AVIbeatVrtwN6MbyliOVhc&q=place_id:' . $place_id[2] . '" allowfullscreen>
										</iframe>';
						add_post_meta( $post_id, '_listing_map', $map_embed_code );

					}	

					if( $this->helpers->is_accepted_url( $result->get('VT_VTourURL') ) )
						add_post_meta( $post_id, '_listing_video', $result->get('VT_VTourURL') );

					add_post_meta( $post_id, '_tc_listing_office', $result->get('LO1_OrganizationName') );
					add_post_meta( $post_id, '_tc_listing_agent', $result->get('LA1_UserFirstName') . ' ' . $result->get('LA1_UserLastName') . ' ' . $result->get('LA1_PhoneNumber1') );

					/**
					 * Only add thes if Class is Residential
					 */
					if( $result->get('L_Class') == 'RESIDENTIAL' ) {

						add_post_meta( $post_id, '_listing_bedrooms', $result->get('L_Keyword1') );
						add_post_meta( $post_id, '_listing_bathrooms', $total_baths );
						add_post_meta( $post_id, '_listing_basement', $result->get('LFD_BASEMENT_11') );
						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('LFD_ZONING_27') );
						add_post_meta( $post_id, '_noo_property_field_heating', $result->get('LFD_HEATING_15') );
						add_post_meta( $post_id, '_noo_property_field_style', $result->get('LFD_STYLE_8') );
						
						add_post_meta( $post_id, '_noo_property_field_year-built', $result->get('LM_Int4_2') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', $this->is_this_amenity( $result->get('LFD_OUTBUILDINGS_32') ) );
						add_post_meta( $post_id, '_noo_property_feature_cooling', $this->is_this_amenity( $result->get('LFD_COOLING_14') ) );*/

					}

					/**
					 * Only add thes if Class is Commercial
					 */
					if( $result->get('L_Class') == 'COMMERCIAL' ) {

						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('LFD_ZONING_76') );
						add_post_meta( $post_id, '_noo_property_field_heating', $result->get('LFD_HEATING_73') );
						add_post_meta( $post_id, '_noo_property_field_business-name', $result->get('LM_Char25_13') );
						add_post_meta( $post_id, '_noo_property_field_year-built', $result->get('LM_Int4_2') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', '0' );
						add_post_meta( $post_id, '_noo_property_feature_cooling', $this->is_this_amenity( $result->get('LFD_COOLING_72') ) );*/

					}

					/**
					 * Only add thes if Class is Land
					 */
					if( $result->get('L_Class') == 'LAND' ) {

						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('L_Keyword10') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', $this->is_this_amenity( $result->get('LFD_OUTBUILDINGS_40') ) );
						add_post_meta( $post_id, '_noo_property_feature_cooling', '0' );*/

					}

					/**
					 * Only add thes if Class is Multifamily
					 */
					if( $result->get('L_Class') == 'MULTIFAMILY' ) {

						add_post_meta( $post_id, '_listing_basement', $result->get('LFD_BASEMENT_94') );
						/*add_post_meta( $post_id, '_noo_property_field_zoning', $result->get('LFD_ZONING_107') );
						add_post_meta( $post_id, '_noo_property_field_heating', $result->get('LFD_HEATING_96') );
						add_post_meta( $post_id, '_noo_property_field_style', $result->get('LFD_STYLE_93') );
						
						add_post_meta( $post_id, '_noo_property_field_year-built', $result->get('LM_Int4_2') );
						add_post_meta( $post_id, '_noo_property_feature_outbuildings', '0' );
						add_post_meta( $post_id, '_noo_property_feature_cooling', $this->is_this_amenity( $result->get('LFD_COOLING_95') ) );*/


					}

					/**
					 * Add the no_image image as thumbnail
					 */
					$no_image = get_page_by_title( 'no_image', 'OBJECT', 'attachment' );
					add_post_meta( $post_id, '_thumbnail_id', $no_image->ID );

				}

		    	
		    	// $string = '<br><br>Virtual Tour Address => ' . $result->get('VT_VTourURL') . '<br> String Date: ' . strtotime( $result->get('L_ListingDate') ) . '<br> Days on Market => ' . $this->figure_days_on_market( $result->get('L_ListingDate') ) . '<br> String Date Today: ' . strtotime( date('c') ) . '<br><br>'; 
		    			    	
		    }

		}
		print_r( $property_count . ' Properties were Downloaded.' );


		// logout
		$rets->Disconnect();

		// run the initial images download
		// $this->tc_load_initial_images();

	}

	/**
	 * Get the urls of the images and save them for each property
	 * 
	 */
	public function tc_load_initial_images() {

		include_once( dirname( __FILE__ ) . '/../includes/phrets/vendor/autoload.php' );

		$config = new \PHRETS\Configuration;
		$config->setLoginUrl( $this->rets_url )
		        ->setUsername( $this->rets_login )
		        ->setPassword( $this->rets_pass )
		        ->setRetsVersion('1.7.2');
		//$rets->setLogger($log);
		$rets = new \PHRETS\Session($config);
		$connect = $rets->Login();


		// set the Image Counter
		$image_count = 0;

		// These files need to be included as dependencies when on the front end.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		/**
		 * Get the setting for uploading or photo url.
		 * @var boolean
		 */
		$upload_option = true; //get_option( 'tchelenamlssettings_tc_listing_photos', 'Not Set' );

		$args = array(
				'post_type' => 'listing',
				'cache_results' => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query' => array( 
						array(
								'key' => '_tc_last_photo_update',
								'value' => 'default',
								'compare' => 'NOT EXISTS',
							),
					),
				'posts_per_page' => 100,
				'order' => 'ASC',
			);
		$my_query = new WP_Query( $args );

		if( $my_query->have_posts() ) {

			set_time_limit(360);
			wp_set_current_user( 1 );

		  	while( $my_query->have_posts() ) {
				$my_query->the_post();
				$post_id = get_the_ID();
				$mls_num = get_post_meta( $post_id, '_listing_mls', true );
				$number_of_images = 0;

				// make sure that this post has images
				$picture_count = get_post_meta( $post_id, '_tc_picture_count', true );

				if( $picture_count )
					$number_of_images = $picture_count;

				/**
				 * Create the gallery string of urls
				 */
				$gallery_urls = '';

				$updated = false; // to get inside the conditional below

				$address = get_post_meta( $post_id, '_listing_address', true );
				$address .= ', ' . get_post_meta( $post_id, '_listing_city', true );
				$address .= ', ' . get_post_meta( $post_id, '_listing_state', true );
				$address .= ' ' . get_post_meta( $post_id, '_listing_zip', true );

				// Upload the main image and set it as the post thumbnail
				$main_image = $rets->GetObject( 'Property', 'Photo', $mls_num, '0' );

				if( $number_of_images > 0 ) {
					foreach( $main_image as $image ):
						
						$filename = "image-thumb-$mls_num.jpg";

						$file_content = file_put_contents( $filename, $image->getContent() );

						$image_count = $image_count + 1;

						if ( ! function_exists( 'wp_handle_upload' ) ) {
							require_once( ABSPATH . 'wp-admin/includes/file.php' );
						}

						$file             = array();
						$file['error']    = '';
						$file['tmp_name'] = $image->getLocation().$filename;
						$file['name']     = $filename;
						$file['type']     = $image->getContentType();
						$file['size']     = $image->getSize();

						if( $image->getContentType() != 'text/xml' ) {
							// upload file to server
							// @new use $file instead of $image_upload
							$file_return      = wp_handle_sideload( $file, array( 'test_form' => false ) );

							$filename = $file_return['file'];
										
							// Check the type of file. We'll use this as the 'post_mime_type'.
							$filetype = wp_check_filetype( basename( $filename ), null );

							// Get the path to the upload directory.
							$wp_upload_dir = wp_upload_dir();

							// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
								'post_content'   => '',
								'post_status'    => 'inherit'
							);

							// Insert the attachment.
							$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

							// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
							require_once( ABSPATH . 'wp-admin/includes/image.php' );

							// Generate the metadata for the attachment, and update the database record.
							$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
							wp_update_attachment_metadata( $attach_id, $attach_data );

							// If no property images
							if ( is_wp_error($attach_id) ) {
								@unlink($file['tmp_name']);
								// set the id to the id of the no-image

									$no_image = get_page_by_title( 'no_image', 'OBJECT', 'attachment' );
									$thumb_exists = get_post_meta( $post_id, '_thumbnail_id', true );
									$thumb_exists ? update_post_meta( $post_id, '_thumbnail_id', $no_image->ID ) : add_post_meta( $post_id, '_thumbnail_id', $no_image->ID );

							}

							if ( $number_of_images == 0 ) {
								@unlink($file['tmp_name']);
								// set the id to the id of the no-image

									$no_image = get_page_by_title( 'no_image', 'OBJECT', 'attachment' );
									$thumb_exists = get_post_meta( $post_id, '_thumbnail_id', true );
									$thumb_exists ? update_post_meta( $post_id, '_thumbnail_id', $no_image->ID ) : add_post_meta( $post_id, '_thumbnail_id', $no_image->ID );

							}

							// if no error, then proceed
							if( !is_wp_error($attach_id) && $number_of_images > 0 ) {

								// take first image and save it as _thumbnail_id
								$thumb_exists = get_post_meta( $post_id, '_thumbnail_id', true );
								$thumb_exists ? update_post_meta( $post_id, '_thumbnail_id', $attach_id ) : add_post_meta( $post_id, '_thumbnail_id', $attach_id );

							}// End of saving main image as thumbnail
						}

					endforeach;
				}

				/**
				* Get the Image Urls from the RETS Database
				* @var (string)
				*/
				$images = $rets->GetObject( 'Property', 'Photo', $mls_num, '*', 1 );

				if( count( $images > 0 ) ) {
					/**
					 * Store the $image urls as post_meta
					 */
					$first = true;
					foreach( $images as $image ) {

						if( $first ) {	
							$gallery_urls .= $image->getLocation();
							$first = false;
						} else {
							$gallery_urls .= ',' . $image->getLocation();
						}
						    	
					} // End foreach( $images as $image )
					add_post_meta( $post_id, '_gallery_image_urls', $gallery_urls );
				}


				// add the updated field only after the property has had all of
				// its images downloaded.
				$updated_date = date('c');
				add_post_meta( $post_id, '_tc_last_photo_update', $updated_date );


			}	

		}
		wp_reset_postdata();
		$image_count = strval($image_count);
		print_r( '<br><br>' . $image_count . ' Images were Loaded.' );

		 // logout
		$rets->Disconnect();
		// $this->download_listing_images();

	}

	public static function delete_all_properties() {

		$args = array(
				'post_type' => 'listing',
				'nopaging' => true,
				'cache_results' => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'posts_per_page' => 20,
			);
		$my_query = new WP_Query( $args );

		if( $my_query->have_posts() ) {

			$counter = 0;

		  	while( $my_query->have_posts() ) {
				$my_query->the_post();
				$post_id = get_the_ID();

				// Delete all the attachments accosiated 
				// with this property ($post_check contains the property ID)
				$args = array(
					'post_type' => 'attachment',
					'numberposts' => -1,
					'post_status' => null,
					'post_parent' => $post_id
				);

				$attachments = get_posts( $args );
				if ( $attachments ) {
					foreach ( $attachments as $attachment ) {

						wp_delete_attachment( $attachment->ID, true );

					}
				}

				// Delete the property 
				wp_delete_post( $post_id, true );
				$counter++;

				
			} // end while


		} else {
			return false;
		}
		print_r( $counter . ' Properties Deleted.' );
		wp_reset_postdata();

	}

}
