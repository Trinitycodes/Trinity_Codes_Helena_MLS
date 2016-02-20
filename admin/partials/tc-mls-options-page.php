<?php

/**
 * Displays form for Helena MLS Options page
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    TC_Helena_MLS
 * @subpackage TC_Helena_MLS/admin/partials
 */
?>

<div class="wrap">
	<h2>Helena MLS Settings</h2>
	<p>The information below needs to be obtained from Helena MLS and must be connected with a RETS account.</p>

	<form method="post" action="options.php"> 

		<?php echo settings_fields( 'tc-helena-mls-admin' ); ?>
		<?php echo do_settings_sections( 'tc-helena-mls-admin' ); ?>

		<table class="form-table">
	        <tr valign="top">
	        <th scope="row">MLS RETS Url</th>
	        <td><input size="75" type="text" name="_tc_mls_url" value="<?php echo esc_attr( get_option('_tc_mls_url') ); ?>" /></td>
	        </tr>
	         
	        <tr valign="top">
	        <th scope="row">MLS RETS Username</th>
	        <td><input type="text" name="_tc_mls_user_login" value="<?php echo esc_attr( get_option('_tc_mls_user_login') ); ?>" /></td>
	        </tr>
	        
	        <tr valign="top">
	        <th scope="row">MLS RETS Password</th>
	        <td><input type="password" name="_tc_mls_pass" value="<?php echo esc_attr( get_option('_tc_mls_pass') ); ?>" /></td>
	        </tr>
	    </table>

		<?php submit_button(); ?>
	</form>

</div>