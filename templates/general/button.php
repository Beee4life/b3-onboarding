<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    switch( $attributes[ 'template' ] ) {
        case 'account':
            $button_modifier = false;
            $button_value    = esc_attr__( 'Update profile', 'b3-onboarding' );
            break;
        case 'getpass':
            $button_modifier = false;
            $button_value    = get_option( 'b3_use_one_time_password' ) ? esc_attr__( 'Get password', 'b3-onboarding' ) : esc_attr__( 'Log in', 'b3-onboarding' );
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
            $button_modifier = 'register';
            $button_value    = esc_attr__( 'Register', 'b3-onboarding' );
            if ( in_array( $attributes[ 'registration_type' ], [ 'request_access', 'request_access_subdomain' ] ) ) {
                $button_value = esc_attr__( 'Request access', 'b3-onboarding' );
            }
            break;
        case 'resetpass':
            $button_modifier = false;
            $button_value    = esc_attr__( 'Set password', 'b3-onboarding' );
            break;
        default:
            $button_modifier = false;
            $button_value    = esc_attr__( 'Save', 'b3-onboarding' );
    }
?>
<div class="b3_form-element b3_form-element--button">
    <?php b3_get_submit_button( $button_value, false, $attributes ); ?>
</div>
