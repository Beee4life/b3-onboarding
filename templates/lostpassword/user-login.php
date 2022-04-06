<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element b3_form-element--email">
    <label class="b3_form-label b3_form-label--email" for="b3_user_email"><?php esc_attr_e( 'Email address', 'b3-onboarding' ); ?></label>
    <input type="text" name="user_login" id="b3_user_email" value="<?php echo apply_filters( 'b3_localhost_email', false ); ?>" required>
</div>
