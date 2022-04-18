<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    // These fields should be left untouched, they're needed.
?>
<?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
<input type="hidden" name="admin_bar_front" id="admin_bar_front" value="<?php echo get_user_meta( $current_user->ID, 'show_admin_bar_front', true ); ?>" />
<input type="hidden" name="from" value="profile" />
<input type="hidden" name="instance" value="1" />
<input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>" />
<input type="hidden" name="action" value="profile" />
<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
<input type="hidden" name="nickname" id="nickname" value="<?php echo ( isset( $current_user->nickname ) ) ? esc_attr( $current_user->nickname ) : esc_attr( $current_user->user_login ); ?>" class="regular-text" />
