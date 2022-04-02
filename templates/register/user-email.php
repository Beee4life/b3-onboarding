<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element b3_form-element--email">
    <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
    <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo apply_filters( 'b3_localhost_email', false ); ?>" required>
</div>
