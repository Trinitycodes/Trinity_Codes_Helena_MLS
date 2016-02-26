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
    <h2><?php _e( 'Helena MLS RETS Settings', 'tc-helena-mls' ) ?></h2>
    <form action="options.php" method="post">
        <?php settings_fields( 'tc-helena-mls-admin' ); ?>
        <?php do_settings_sections( 'tc-helena-mls-admin' ); ?>
         
        <input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'tc-helena-mls' ); ?>" class="button button-primary" />
    </form>
</div>