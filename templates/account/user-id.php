<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! get_option( 'b3_register_email_only' ) ) {
?>

<div class="b3_form-element b3_form-element--login">
    <label class="b3_form-label" for="user_login"><?php esc_attr_e( 'Username', 'b3-onboarding' ); ?></label>
    <input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $current_user->user_login ); ?>" disabled="disabled" />
</div>
<?php }
