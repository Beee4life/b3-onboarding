<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    switch( $attributes[ 'template' ] ) {
        case 'account':
            $button_modifier = false;
            $button_value    = esc_attr__( 'Update profile', 'b3-onboarding' );
            break;
        case 'login':
            $button_modifier = false;
            $button_value    = esc_attr__( 'Log in', 'b3-onboarding' );
            break;
        case 'lostpassword':
            $button_modifier = false;
            $button_value    = esc_attr__( 'Reset password', 'b3-onboarding' );
            break;
        case 'register':
            $button_modifier = false;
            $button_value    = esc_attr__( 'Register', 'b3-onboarding' );
            break;
        default:
            $button_modifier = false;
            $button_value    = esc_attr__( 'Save', 'b3-onboarding' );
    }
?>

<div class="b3_form-element b3_form-element--button">
    <?php b3_get_submit_button( $button_value, false, $attributes ); ?>
</div>
