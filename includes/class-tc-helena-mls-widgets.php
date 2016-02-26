<?php
/* Full Service Display Widgets */

/** Register Widget **/
function tc_display_featured_listings_widget() {
	register_widget( 'TC_Featured_Listings_Widget' );
}
add_action( 'widgets_init', 'tc_display_featured_listings_widget' );

class TC_Featured_Listings_Widget extends WP_Widget
{
	function TC_Featured_Listings_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'tc_featured_listings_widget', 'description' => 'Displays Featured Listings in Grid.' );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'featured-listings-widget', 'class' => 'on');

		/* Create the widget. */
		$this->WP_Widget( 'featured-listings-widget', 'Featured Listings Widget', $widget_ops, $control_ops );
	}

	// Widget form creation
	function form($instance) {
	 	$title = '';
		$limit = '';

		// Check values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$post_type = esc_attr($instance['post_type']);
			$limit = esc_textarea($instance['limit']);
		} ?>
			 
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Heading', 'tc-helena-mls'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('property_type'); ?>"><?php _e('Property Type', 'tc-helena-mls'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('property_type'); ?>" name="<?php echo $this->get_field_name('property_type'); ?>">
				<option value="all-properties"><?php _e('Multi-Family', 'tc-helena-mls'); ?></option>
				<option value="single-family"><?php _e('Single Family', 'tc-helena-mls'); ?></option>
				<option value="land"><?php _e('Land', 'tc-helena-mls'); ?></option>
				<option value="commercial"><?php _e('Commercial', 'tc-helena-mls'); ?></option>
				<option value="multi-family"><?php _e('Multi-Family', 'tc-helena-mls'); ?></option>
			</select>
		</p>
			 
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Max Number of Listings to Show:', 'tc-helena-mls'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" />
		</p>
			
		<?php 
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['property_type'] = strip_tags($new_instance['property_type']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		return $instance;
	}

	function widget( $args, $instance ) {

		extract( $args );

		echo $before_widget;

		// Display the featured property listings
		$limit = $instance['limit'] ? $instance['limit'] : 100;
		$limit = intval( $limit );

		$params = array(
			'post_type' => 'listing',
			'limit' => $limit,
			'posts_per_page' => $limit,
			'tax_query' => array(
					array(
						'taxonomy' => 'labels',
						'field' => 'slug',
						'terms' => array( 'featured' ),
						'include_children' => true,
						'operator' => 'IN'
					),
				),
		 );
		$properties = new WP_Query( $params );

		if( $properties->have_posts() ) {

			// Set the right left class selector
			$class_selector = 1;

			?>

			<section id="featured-listings-3" class="widget featured-listings">
				<div class="widget-wrap">
					<?php $title = $instance['title']; ?>
					<?php if( $title ): ?>
						<h4><?php _e( $title, 'tc-helena-mls' ); ?></h4>
					<?php endif; ?>
					<?php while( $properties->have_posts() ): ?>
						<?php $properties->the_post(); ?>

						<?php 

						$post_id = get_the_ID(); 

						// Set the align class
						if( $class_selector == 1 ) {
							$align_class = 'left';
							$class_selector = 2;
						} else {
							$align_class = 'right';
							$class_selector = 1;
						}
						?>

						<!-- Display Featured Listings Here -->

							<div class="<?php echo $align_class; ?> post-<?php echo $post_id; ?> listing type-listing status-publish has-post-thumbnail entry">
								<div class="widget-wrap">
									<div class="listing-wrap">
										<a href="<?php echo the_permalink(); ?>"><?php echo get_the_post_thumbnail( $post_id ); ?></a>
										<span class="listing-price"><?php _e( get_post_meta( $post_id, '_listing_price', true ), 'tc-helena-mls' ); ?></span>
										<span class="listing-text"><?php _e( get_post_meta( $post_id, '_listing_text', true ), 'tc-helena-mls' ); ?></span>
										<span class="listing-address"><?php _e( get_post_meta( $post_id, '_listing_address', true ), 'tc-helena-mls' ); ?></span>
										<span class="listing-city-state-zip"><?php _e( get_post_meta( $post_id, '_listing_city', true ), 'tc-helena-mls' ); ?> <?php _e( get_post_meta( $post_id, '_listing_state', true ), 'tc-helena-mls' ); ?>, <?php _e( get_post_meta( $post_id, '_listing_zip', true ), 'tc-helena-mls' ); ?></span>
										<a href="<?php echo the_permalink(); ?>" class="more-link">View Listing</a>
									</div>
								</div>
							</div>

						<!-- End Featured Listings -->

					<?php endwhile; ?>

				</div>
			</section>

			<?php
		}

		echo $after_widget;
	}
}