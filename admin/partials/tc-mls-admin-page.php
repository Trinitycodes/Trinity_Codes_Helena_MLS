<?php

/**
 * Displays form for Helena MLS Admin page
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'MLS Property Controlls', 'tc-helena-mls' ) ?></h2>
    <p>Download Listings, Update Listings, or Load Listing Images by clicking the appropriate button.</p>
    <form method="post">
        <?php settings_fields( 'tc_mls_admin' ); ?>
        <?php do_settings_sections( 'tc_mls_admin' ); ?>

        <table>
        <tr>
        	<td>Select an Action</td>
        	<td>
		        <select class="widefat" id="_tc_action" name="_tc_action">
					<option value="download"><?php _e('Download Properties', 'tc-helena-mls'); ?></option>
					<option value="update"><?php _e('Update Properties', 'tc-helena-mls'); ?></option>
					<option value="images"><?php _e('Load Images', 'tc-helena-mls'); ?></option>
					<option value="delete"><?php _e('Delete All Listings', 'tc-helena-mls'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
         		<input name="Submit" type="submit" value="<?php esc_attr_e( 'Execute', 'tc-helena-mls' ); ?>" class="button button-primary" />
         	</td>
    </form>
</div>