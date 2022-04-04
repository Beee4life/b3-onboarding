<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element b3_form-element--login">
    <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
    <input type="text" name="user_login" id="b3_user_login" class="b3_form--input" autocapitalize="none" autocomplete="off" spellcheck="false" maxlength="60" value="<?php echo apply_filters( 'b3_localhost_username', false ); ?>" required>
</div>
