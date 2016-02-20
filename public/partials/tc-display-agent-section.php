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

// get the agent and office from the post meta
$agent = get_post_meta( $post_id, '_tc_listing_agent', true );
$office = get_post_meta( $post_id, '_tc_listing_office', true );
?>

<div style="padding: 15px 25px; text-align: center;">
	<p><strong>Listing Office: </strong> <?php echo $office; ?></p>
	<p><strong>Listing Agent: </strong> <?php echo $agent; ?></p>
</div>