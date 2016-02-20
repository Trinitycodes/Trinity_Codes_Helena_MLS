<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/public/partials
 */

$post_id = get_the_ID();
?>

<p>
	<a id="tc-thumbnail-holder" href="<?php echo the_permalink( $post_id ); ?>">
		<?php echo get_the_post_thumbnail( $post_id, 'full', array( 'class' => 'tc-gallery-target', 'style' => 'max-width: 100%; height: auto;' ) ); ?>
	</a>
</p>