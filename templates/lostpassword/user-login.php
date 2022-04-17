<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $label = get_option( 'b3_register_email_only' ) ? esc_html__( 'Email address', 'b3-onboarding' ) : esc_html__( 'User name or email address', 'b3-onboarding' )
?>
<div class="b3_form-element b3_form-element--email">
    <label class="b3_form-label b3_form-label--email" for="b3_user_email"><?php echo $label; ?></label>
    <input type="text" name="user_login" id="b3_user_email" value="<?php echo apply_filters( 'b3_localhost_email', false ); ?>" required>
</div>
