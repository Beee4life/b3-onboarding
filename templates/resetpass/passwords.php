<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element">
    <label class="b3_form-label" for="pass1"><?php esc_html_e( 'New password', 'b3-onboarding' ) ?></label>
    <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
</div>

<div class="b3_form-element">
    <label class="b3_form-label" for="pass2"><?php esc_html_e( 'Repeat new password', 'b3-onboarding' ) ?></label>
    <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
</div>

<p class="password-hint"><?php echo wp_get_password_hint(); ?></p>
