<div class="b3_form-element b3_form-element--login">
    <?php if ( false == get_option( 'b3_register_email_only' ) ) { ?>
        <label class="b3_form-label" for="user_login"><?php esc_attr_e( 'Username', 'b3-onboarding' ); ?></label>
        <input type="text" name="user_login" id="user_login" value="<?php esc_attr_e( $current_user_object->user_login ); ?>" disabled="disabled" />
    <?php } else { ?>
        <label class="b3_form-label" for="b3_user_login"><?php esc_attr_e( 'User ID', 'b3-onboarding' ); ?></label>
        <div class="user-login"><?php esc_html_e( $current_user_object->user_login ); ?></div>
    <?php } ?>
</div>
