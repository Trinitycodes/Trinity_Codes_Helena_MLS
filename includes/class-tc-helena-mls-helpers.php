<?php

/**
 *
 * Useful functions for rets downloads
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 */

/**
 * Completes minor tasks to aid in the download of properties from RETS
 *
 * @since      1.0.0
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/includes
 * @author     Trinity Codes <trinity@trinitycodes.com>
 */
class TC_Helena_MLS_Helpers {


	/**
	 * Returns a timestring dependant which type is defined in 
	 * the parameter
	 *
	 * If $type is 'download', then set timestamp to six months ago.
	 *
	 * If $type is 'update', set timestamp to 24 hours ago.
	 *
	 * @since    1.0.0
	 */
	public function set_search_date( $type ) {

		switch( $type ) {

			case 'download':
				// set the time to six months ago (15552000)
				$timestamp = strtotime( date('c') ) - 15552000;
				$time = date('Y-m-d', $timestamp);
				break;

			case 'update':
				// set the time string to 24 hours ago
				$timestamp = strtotime( date('c') ) - 86400;
				$time = date('Y-m-d', $timestamp);
				break;

		}

		$time_string = $time . 'T02:00:00';

		return $time_string;

	}

	/**
	 * Determines whether a post exists using the 
	 * parameters below.
	 * @param  (string) $meta_value Post meta value
	 * @param  (string) $meta_key   Post meta key
	 * @param  (string) $post_type  (optional) Post Type
	 * @return (int)             Post ID if found, or false if not found
	 */
	public function does_post_exist( $meta_value, $meta_key, $post_type='' ) {

		wp_reset_postdata();
		$args = array(
				'post_type' => $post_type,
				'meta_key' => $meta_key,
				'meta_value' => $meta_value,
			);
		$my_query = new WP_Query( $args );

		if( $my_query->have_posts() ) {

		  	while( $my_query->have_posts() ) {
				$my_query->the_post();
				$post_id = get_the_ID();

				return $post_id;
			} // end while


		} else {
			return false;
		}
		wp_reset_postdata();

	}

	/**
	 * Find and return an array of category
	 * id's from the name of the categories
	 * @param  (array) $info  Contains an array of property information including ( class, main_area, sub_area, price, bedrooms, bathrooms, acres, property_type )
	 * 
	 * @return (array)        Array will contain at least 1 category id (mostly 2)
	 */
	public function get_property_taxonomies( $info ) {

		// Create an array
		$my_terms = array();

		$classes = array( 'RESIDENTIAL' => 'single-family', 'COMMERCIAL' => 'commercial', 'MULTIFAMILY' => 'multi-family', 'LAND' => 'land' );

		$property_types = array(
				'Residential w/Rental' => 'single-family-wrental',
				'Apartment Building' => 'apartment-building',
				'Condo' => 'condo',
				'Townhouse' => 'condo',
				'To Be Built' => 'to-be-built',
				'Farm/Ranch Agric.' => 'farmranch-agriculture',
			);

		$main_areas = array(
				'INTWN-LW' => 'helena-area',
				'INTWN-NW' => 'helena-area',
				'INTWN-NC' => 'helena-area',
				'INTWN-NE' => 'helena-area',
				'INTWN-LE' => 'helena-area',
				'INTWN-UE' => 'helena-area',
				'INTWN-SC' => 'helena-area',
				'INTWN-UW' => 'helena-area',
				'INTWN-SE' => 'helena-area',
				'CENTRAL VALLEY' => 'helena-area',
				'WEST VALLEY' => 'helena-area',
				'NORTH VALLEY' => 'helena-area',
				'EAST HELENA' => 'helena-area',
				'Northwest' => 'helena-area',
				'Southwest' => 'helena-area',
				'Augusta 59410' => 'other',
				'Avon 59713' => 'elliston-avon-area',
				'Basin 59631' => 'boulder-area',
				'Boulder 59632' => 'boulder-area',
				'Canyon creek 59633' => 'helena-area',
				'Cardwell 59721' => 'whitehall-area',
				'Cascade 59421' => 'wolf-creek-dearborn-area',
				'Clancy C.59634' => 'helena-area',
				'Craig C.59648' => 'wolf-creek-dearborn-area',
				'Dearborn D.59648' => 'wolf-creek-dearborn-area',
				'Deer Lodge 59722' => 'elliston-avon-area',
				'Elliston 59728' => 'elliston-avon-area',
				'Garrison 59731' => 'elliston-avon-area',
				'Helmville 59843' => 'other',
				'Jefferson City 59638' => 'helena-area',
				'Lincoln 59639' => 'other',
				'Marysville 59640' => 'helena-area',
				'Montana City M.C.59634' => 'helena-area',
				'Nelson 59602' => 'helena-area',
				'Ovando 59854' => 'other',
				'Radersburg 59641' => 'townsend-area',
				'Toston 59643' => 'townsend-area',
				'Townsend 59644' => 'townsend-area',
				'Whitehall 59759' => 'whitehall-area',
				'Winston 59647' => 'townsend-area',
				'Wolf Creek W.C. 59648' => 'wolf-creek-dearborn-area',
				'Outside area northwest' => 'elliston-avon-area',
				'Outside area northeast' => 'wolf-creek-dearborn-area',
				'Outside area southeast' => 'townsend-area',
				'Outside area southwest' => 'boulder-area',
				'Other' => 'other',
			);

		$sub_areas = array(
				'INTWN-LW' => 'helena-city-limits',
				'INTWN-NW' => 'helena-city-limits',
				'INTWN-NC' => 'helena-city-limits',
				'INTWN-NE' => 'helena-city-limits',
				'INTWN-LE' => 'helena-city-limits',
				'INTWN-UE' => 'helena-city-limits',
				'INTWN-SC' => 'helena-city-limits',
				'INTWN-UW' => 'helena-city-limits',
				'INTWN-SE' => 'helena-city-limits',
				'CENTRAL VALLEY' => 'helena-valley',
				'WEST VALLEY' => 'helena-valley',
				'NORTH VALLEY' => 'helena-valley',
				'EAST HELENA' => 'east-helena',
				'Northwest' => 'helena-valley',
				'Augusta 59410' => 'augusta',
				'Avon 59713' => 'avon',
				'Basin 59631' => 'basin',
				'Boulder 59632' => 'boulder',
				'Canyon creek 59633' => 'helena-valley',
				'Cardwell 59721' => 'whitehall',
				'Cascade 59421' => 'cascade',
				'Clancy C.59634' => 'clancy',
				'Craig C.59648' => 'craig',
				'Dearborn D.59648' => 'dearborn',
				'Deer Lodge 59722' => 'deer-lodge',
				'Elliston 59728' => 'elliston',
				'Garrison 59731' => 'avon',
				'Helmville 59843' => 'helmville',
				'Jefferson City 59638' => 'jefferson-city',
				'Lincoln 59639' => 'lincoln',
				'Marysville 59640' => 'marysville',
				'Montana City M.C.59634' => 'montana-city',
				'Nelson 59602' => 'helena-city-limits',
				'Ovando 59854' => 'ovando',
				'Radersburg 59641' => 'radersburg',
				'Toston 59643' => 'toston',
				'Townsend 59644' => 'townsend',
				'Whitehall 59759' => 'whitehall',
				'Winston 59647' => 'winston',
				'Wolf Creek W.C. 59648' => 'wolf-creek',
				'Outside area northwest' => 'avon',
				'Outside area northeast' => 'wolf-creek',
				'Outside area southeast' => 'townsend',
				'Outside area southwest' => 'boulder',
				'Other' => 'outside-area',
			);

		// find the term info
		$class_term = get_term_by( 'slug', $classes[$info['class']], 'types' );
		$term = get_term_by( 'slug', $property_types[$info['types']], 'types' );
		$area_term = get_term_by( 'slug', $main_areas[$info['main_area']], 'area' );
		$sub_area_term = get_term_by( 'slug', $sub_areas[$info['sub_area']], 'locations' );

		/**
		 * Find price terms ids
		 * 
		 * @var [array]
		 */
		$price_term_ids = array();
		$price_terms = $this->tc_find_terms( $info['price'], 'price' );
		if( $price_terms ) {
			foreach( $price_terms as $key => $value ) {
				array_push( $price_term_ids, $value );
			}
		}
		/**
		 * Load the price ids into $my_terms
		 */
		if( !empty( $price_term_ids) )
			$my_terms['price'] = $price_term_ids;

		/**
		 * Find bedroom terms ids
		 * 
		 * @var [array]
		 */
		$bed_terms = array();
		$bedroom_terms = $this->tc_find_terms( $info['bedrooms'], 'bedrooms' );
		if( $bedroom_terms ) {
			foreach( $bedroom_terms as $key => $value ) {
				array_push( $bed_terms, $value );
			}
		}
		/**
		 * Load the bedroom term ids into $my_terms
		 */
		if( !empty( $bed_terms ) )
			$my_terms['bedrooms'] = $bed_terms;

		/**
		 * Find bathroom terms ids
		 * 
		 * @var [array]
		 */
		$bath_terms = array();
		$bathroom_terms = $this->tc_find_terms( $info['bathrooms'], 'bathrooms' );
		if( $bathroom_terms ) {
			foreach( $bathroom_terms as $key => $value ) {
				array_push( $bath_terms, $value );
			}
		}
		/**
		 * Load the bath term ids into $my_terms
		 */
		if( !empty( $bath_terms ) )
			$my_terms['bathrooms'] = $bath_terms;

		/**
		 * Find sqfeet terms ids
		 * 
		 * @var [array]
		 */
		$feet_terms = array();
		$sqfeet_terms = $this->tc_find_terms( $info['sqfeet'], 'sqfeet' );
		if( $sqfeet_terms ) {
			foreach( $sqfeet_terms as $key => $value ) {
				array_push( $feet_terms, $value );
			}
		}
		/**
		 * Load feet term ids into $my_terms
		 */
		if( !empty( $feet_terms ) )
			$my_terms['sqfeet'] = $feet_terms;

		/**
		 * Find acres terms ids
		 * 
		 * @var [array]
		 */
		$acre_term_ids = array();
		$acre_terms = $this->tc_find_terms( $info['acres'], 'acres' );
		if( $acre_terms ) {
			foreach( $acre_terms as $key => $value ) {
				array_push( $acre_term_ids, $value );
			}
		}
		/**
		 * Load feet term ids into $my_terms
		 */
		if( !empty( $acre_term_ids ) )
			$my_terms['acres'] = $acre_term_ids;

		/**
		 * Find label terms ids
		 * 
		 * @var [array]
		 */
		$label_term_ids = array();
		$label_terms = $this->tc_find_terms( $info['labels'], 'labels' );
		if( $label_terms ) {
			foreach( $label_terms as $key => $value ) {
				array_push( $label_term_ids, $value );
			}
		}
		/**
		 * Load feet term ids into $my_terms
		 */
		if( !empty( $label_term_ids ) )
			$my_terms['labels'] = $label_term_ids;

		// Add the ids to the $my_terms array
		/**
		 * Array needs to be associative array.
		 *
		 * Index needs to be the taxomony (types, price, area, location, acres, bedrooms, bathrooms, sqfeet)
		 * ids should then be added to each of the taxonomies as an array
		 */
		$term_array = array();

		if( $class_term ) 
			array_push( $term_array, $class_term->term_id );

		if( $term )
			array_push( $term_array, $term->term_id );

		$my_terms['types'] = $term_array;

		if( $area_term )
			$my_terms[$area_term->taxonomy] = array( $area_term->term_id );

		if( $sub_area_term )
			$my_terms['locations'] = array( $sub_area_term->term_id );

		return $my_terms;

	}

	/**
	 * Specifies whether or not a video url is accepted for an embeded video
	 * @param  (string) $url  The full url.
	 * @return boolean      Returns true if url is accepted or false if url is not accepted.
	 */
	public function is_accepted_url( $url=0 ) {

		if( $url ) {

			// create an array of accepted url strings
			$accepted_urls = array(
					'youtube.com',
					'vimeo.com',
					'vine.co',
					'flikr.com',
				);

			$found = false;
			for( $i=0;$i<count($accepted_urls);$i++ ) {

				$pos = strpos( $url, $accepted_urls[$i] );
				if( $pos !== false ) {

					$found = true;

				}

			}

			if( $found ) {
				return true;
			} else {
				return false;
			}

		}

	}

	/**
	 * Retrieve geocodes by address from google maps
	 * @param  (string) $address Full address of property
	 * @return (array)          contains geocodes for address givin
	 */
	public function tc_get_geocode( $address ) {

		// url encode the address
	    $address = urlencode($address);
	     
	    // google map geocode api url
	    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
	 
	    // get the json response
	    $resp_json = file_get_contents($url);
	     
	    // decode the json
	    $resp = json_decode($resp_json, true);
	 
	    // response status will be 'OK', if able to geocode given address 
	    if($resp['status']=='OK'){
	 		// echo '<pre>'; print_r( $resp['results'] ); exit;
	        // get the important data
	        $lati = $resp['results'][0]['geometry']['location']['lat'];
	        $longi = $resp['results'][0]['geometry']['location']['lng'];
	        $place_id = $resp['results'][0]['place_id'];
	        
	        // verify if data is complete
	        if($lati && $longi && $place_id){
	         
	            // put the data in the array
	            $data_arr = array();            
	             
	            array_push(
	                $data_arr, 
	                    $lati, 
	                    $longi, 
	                    $place_id
	                );
	             
	            return $data_arr;
	             
	        }else{
	            return false;
	        }
	         
	    }else{
	        return false;
	    }

	}

	/**
	 * Private method to find terms for specific taxonomies
	 *
	 * @param   $value Value of the current property item.
	 * @param   $tax  Taxonomy type to look for terms in.
	 * @return   [array]  Contains any term id's found for that particular property
	 */
	private function tc_find_terms( $term_value, $tax ) {

		$term_ids = array();

		switch( $tax ) {

			case 'price':
				$args = array(
						'hide_empty' => false,
						'fields' => 'id=>slug',
					);
				$terms = get_terms( 'price', $args );
				// loop the terms to check if the values fit
				foreach( $terms as $key => $value ) {
					// create an array useing the slug
					$values = explode( '-', $value );
					
					if( count( $values ) == 2 ) {
						if( ($values[0] <= $term_value) && ($term_value <= $values[1]) ) {
							array_push( $term_ids, $key );
						}
					} else {
						$high_num = str_replace( 'plus', '', $values[0] );
						if( $term_value >= ( $high_num - 1 ) )
							array_push( $term_ids, $key );
					}
				}
				return $term_ids;
				break;

			case 'acres':
				$args = array(
						'hide_empty' => false,
						'fields' => 'id=>slug',
					);
				$terms = get_terms( 'acres', $args );
				// loop the terms to check where the values fit
				foreach( $terms as $key => $value ) {
					// create an array useing the slug
					$values = explode( '-', $value );
					
					if( count( $values ) == 2 ) {
						if( $values[0] == 'zero' ) {
							$values[0] = 1;
						} 
						if( ($values[0] <= $term_value) && ($term_value <= $values[1]) ) {
							array_push( $term_ids, $key );
						}
					} else {
						$high_num = str_replace( 'plus', '', $values[0] );
						if( $term_value >= ( $high_num - 1 ) )
							array_push( $term_ids, $key );
					}
				}
				return $term_ids;
				break;

			case 'bedrooms':
				$args = array(
						'hide_empty' => false,
						'fields' => 'id=>slug',
					);
				$terms = get_terms( 'bedrooms', $args );
				// loop the terms to check where the values fit
				foreach( $terms as $key => $value ) {
					// create an array useing the slug
					$values = explode( '-', $value );

					if( $values[0] == 5 && $term_value > 5 ) {
						array_push( $term_ids, $key );
					}
					if( $values[0] == $term_value ) {
						array_push( $term_ids, $key );

					}
				}
				return $term_ids;
				break;

			case 'bathrooms':
				$args = array(
						'hide_empty' => false,
						'fields' => 'id=>slug',
					);
				$terms = get_terms( 'bathrooms', $args );
				// loop the terms to check where the values fit
				foreach( $terms as $key => $value ) {
					// create an array useing the slug
					$values = explode( '-', $value );

					if( $values[0] == 4 && $term_value > 4 ) {
						array_push( $term_ids, $key );
					}
					if( $values[0] == $term_value ) {
						array_push( $term_ids, $key );

					}
				}
				
				return $term_ids;
				break;

			case 'sqfeet':
				$args = array(
						'hide_empty' => false,
						'fields' => 'id=>slug',
					);
				$terms = get_terms( 'sqfeet', $args );
				// loop the terms to check where the values fit
				foreach( $terms as $key => $value ) {
					// create an array useing the slug
					$values = explode( '-', $value );
					
					if( count( $values ) == 2 ) {
						if( $values[0] == 'zero' ) {
							$values[0] = 1;
						} 
						if( ($values[0] <= $term_value) && ($term_value <= $values[1]) ) {
							array_push( $term_ids, $key );
						}
					} else {
						$high_num = str_replace( 'plus', '', $values[0] );
						if( $term_value >= ( $high_num - 1 ) )
							array_push( $term_ids, $key );
					}
				}
				return $term_ids;
				break;

			case 'labels':
				$args = array(
						'hide_empty' => false,
						'fields' => 'id=>slug',
					);
				$terms = get_terms( 'labels', $args );

				// loop the terms in labels taxonomy
				foreach( $terms as $key => $value ) {
					if( $term_value == '520' && $value == 'featured' ) {
						array_push( $term_ids, $key );
					}
				}
				return $term_ids;
				break;

			default: 
				return array();
		}
	}
}
