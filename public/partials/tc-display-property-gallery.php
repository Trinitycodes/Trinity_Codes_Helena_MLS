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

// get the cdn urls from the post meta
$urls = get_post_meta( $post_id, '_gallery_image_urls', true );
$urls = explode(',', $urls);
$num_urls = count( $urls );
$width = $num_urls * 161;
?>

<?php if( $num_urls > 1 ): ?>

<div style="position: relative;">
	<div style="width: 100%; height: auto; overflow: scroll; position: absolute;">
		<div id='tc-gallery-thumbs' style="width: <?php echo $width; ?>px;">
			<?php foreach( $urls as $url ): ?>
				<div style="width: 150px; float: left; margin-right: 10px;">
						<a id="<?php echo $url; ?>" class="tc-gallery-image" href='<?php echo $url; ?>'>
							<img width="300" height="200" src="<?php echo $url; ?>" class="attachment-medium size-medium" alt="bathroom_sink_web" srcset="" sizes="(max-width: 300px) 100vw, 300px" />
						</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<div style="position: relative; clear: both; margin-top: 350px;"></div>

<?php endif; ?>