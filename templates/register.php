<?php
    $send_password_by_mail    = get_option( 'b3_send_pass_mail' );
    $show_custom_passwords    = false;
    $show_first_last_name     = get_option( 'b3_activate_first_last' );
    $first_last_name_required = get_option( 'b3_first_last_required' );
    $show_privacy             = get_option( 'b3_privacy' );
    $show_recaptcha           = get_option( 'b3_recaptcha' );
    $recaptcha_public         = get_option( 'b3_recaptcha_public' );
    $registration_type        = get_option( 'b3_registration_type' );
?>
<div id="b3-register" class="b3__page b3__page--register">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3><?php esc_html_e( 'Register', 'b3-onboarding' ); ?></h3>
    <?php } ?>

    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>
    
    <form id="b3-register-form" class="b3__form b3__form--register" action="<?php echo wp_registration_url(); ?>" method="post">
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />
        <?php b3_hidden_fields_registration_form(); ?>
        
        <?php if ( 'closed' == $registration_type ) { ?>
            
            <p><?php echo apply_filters( 'b3_filter_closed_message', __( 'Sorry, registration is closed.', 'b3-onboarding' ) ); ?></p>
        
        <?php } else { ?>

            <?php if ( 'request_access' == $registration_type ) { ?>
                <?php do_action( 'b3_do_before_request_access' ); ?>
            <?php } ?>
    
            <div class="b3__form-element b3__form-element--login">
                <label class="b3__form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="text" name="user_login" id="b3_user_login" class="b3__form--input" value="username" required>
            </div>

            <div class="b3__form-element b3__form-element--email">
                <label class="b3__form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="email" name="user_email" id="b3_user_email" class="b3__form--input" value="test@xxx.com" required>
            </div>

            <?php if ( $show_first_last_name == true ) { b3_first_last_name_fields(); } ?>
    
            <?php // this function is not in use yet ?>
            <?php if ( $show_custom_passwords == true ) { b3_show_password_fields(); } ?>
    
            <?php if ( is_multisite() ) { b3_add_subdomain_field(); } ?>
    
            <?php do_action( 'b3_add_custom_fields_registration' ); ?>
            <?php b3_extra_fields_registration(); ?>
    
            <?php // this function is not in use yet ?>
            <?php if ( $show_recaptcha == true && $recaptcha_public ) { ?>
                <?php if ( function_exists( 'b3_add_captcha_registration' ) ) { b3_add_captcha_registration( $recaptcha_public ); } ?>
            <?php } ?>
    
            <?php // this function is not in use yet ?>
            <?php if ( $show_privacy == true ) { ?>
                <div class="b3__form-element b3__form-element--register">
                    <label>
                        <input name="b3_privacy" type="checkbox" id="b3_privacy" value="accept"> <?php esc_html_e( 'Accept privacy settings', 'b3-onboarding' ); ?>
                    </label>
                </div>
            <?php } ?>

            <?php do_action( 'b3_do_before_submit_registration_form' ); ?>
            <div class="b3__form-element b3__form-element--submit">
                <?php if ( 'request_access' == $registration_type ) { ?>
                    <input type="submit" name="submit" class="button" value="<?php esc_html_e( 'Request access', 'b3-onboarding' ); ?>"/>
                <?php } else { ?>
                    <input type="submit" name="submit" class="button" value="<?php esc_html_e( 'Register', 'b3-onboarding' ); ?>"/>
                <?php } ?>
            </div>
            <?php do_action( 'b3_do_after_submit_registration_form' ); ?>
    
            <?php if ( 'request_access' == $registration_type ) { ?>
                <?php do_action( 'b3_do_after_request_access' ); ?>
            <?php } ?>

        <?php } ?>
    
    </form>

</div>
