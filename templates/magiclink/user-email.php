<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $label = esc_html__( 'Email address', 'b3-onboarding' );
?>
<div class="b3_form-element b3_form-element--magiclink-email">
    <label class="b3_form-label b3_form-label--magiclink-email" for="user_email"><?php echo esc_attr( $label ); ?></label>
    <input type="email" name="email" id="user_email" class="input" value="<?php echo esc_attr( apply_filters( 'b3_localhost_email', false ) ); ?>" size="20" autocomplete="username">
</div>
