<?php
    $send_password_by_mail = get_option( 'b3_send_pass_mail' );
    $show_custom_passwords = get_option( 'b3_custom_passwords' );
    $show_first_last_name  = get_option( 'b3_first_last_name' );
    $show_privacy          = get_option( 'b3_privacy' );
    $show_recaptcha        = get_option( 'b3_recaptcha' );
    $recaptcha_public      = get_option( 'b3_recaptcha_public' );
    $registration_type     = get_option( 'b3_registration_type' );
?>
<div id="b3-register" class="b3">
    <?php if ( $attributes[ 'show_title' ] ) { ?>
        <h3><?php esc_html_e( 'Register', 'b3-user-register' ); ?></h3>
    <?php } ?>

    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>
    
    <?php do_action( 'b3_do_before_registration_form' ); ?>

    <form id="b3-register-form" class="b3__form b3__form--register" action="<?php echo wp_registration_url(); ?>" method="post">
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />
        <?php b3_hidden_fields_registration_form(); ?>
        
        <?php if ( 'request_access' == $registration_type ) { ?>

            <?php do_action( 'b3_do_before_request_access' ); ?>

            <div class="b3__form-element b3__form-element--email">
                <label class="b3__form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-user-register' ); ?> <strong>*</strong></label>
                <input type="email" name="b3_user_email" id="b3_user_email" class="b3__form--input" value="info@xxx.com" required>
            </div>

            <?php do_action( 'b3_add_custom_fields_registration' ); ?>
            <?php b3_extra_fields_registration(); ?>

            <?php do_action( 'b3_do_before_submit_registration_form' ); ?>
            <div class="b3__form-element b3__form-element--submit">
                <input type="submit" name="submit" class="button" value="<?php esc_html_e( 'Request access', 'b3-user-register' ); ?>"/>
            </div>
            <?php do_action( 'b3_do_after_submit_registration_form' ); ?>

            <?php do_action( 'b3_do_after_request_access' ); ?>
    
        <?php } else { ?>

            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-user-register' ); ?> <strong>*</strong></label>
                <input type="text" name="b3_user_login" id="b3_user_login" class="b3__form--input" value="xxx" required>
            </div>
    
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-user-register' ); ?> <strong>*</strong></label>
                <input type="text" name="b3_user_email" id="b3_user_email" class="b3__form--input" value="info@xxx.com" required>
            </div>
    
            <?php if ( is_multisite() ) { ?>
                <?php // @TODO: add more fields for Multisite ?>
                <div class="b3__form-element b3__form-element--register">
                    <label class="b3__form-label" for="b3_subdomain"><?php esc_html_e( 'Desired (sub) domain', 'b3-user-register' ); ?></label>
                    <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3__form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-user-register' ); ?>        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
                </div>
            <?php } ?>
            
            <?php // this function is not in use yet ?>
            <?php if ( $show_custom_passwords == true ) { ?>
                <?php if ( function_exists( 'b3_show_password_fields' ) ) { b3_show_password_fields(); } ?>
            <?php } ?>
        
            <?php // this function is not in use yet ?>
            <?php if ( $show_first_last_name == true ) { ?>
                <div class="b3__form-element b3__form-element--register">
                    <label class="b3__form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-user-register' ); ?></label>
                    <input type="text" name="b3_first_name" id="b3_first_name" class="b3__form--input">
                </div>
                <div class="b3__form-element b3__form-element--register">
                    <label class="b3__form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-user-register' ); ?></label>
                    <input type="text" name="b3_last_name" id="b3_last_name" class="b3__form--input">
                </div>
            <?php } ?>
    
            <?php do_action( 'b3_do_add_custom_fields_registration' ); ?>
            <?php b3_extra_fields_registration(); ?>
        
            <?php // this function is not in use yet ?>
            <?php if ( $show_recaptcha == true && $recaptcha_public ) { ?>
                <?php if ( function_exists( 'b3_add_captcha_registration' ) ) { b3_add_captcha_registration( $recaptcha_public ); } ?>
            <?php } ?>
    
            <?php if ( $show_privacy == true ) { ?>
                <div class="b3__form-element b3__form-element--register">
                    <label>
                        <input name="b3_privacy" type="checkbox" id="b3_privacy" value="accept"> <?php esc_html_e( 'Accept privacy settings', 'b3-user-register' ); ?>
                    </label>
                </div>
            <?php } ?>
    
            <?php if ( $show_custom_passwords != true ) { ?>
                <div class="b3__form-element b3__form-element--register">
                    <?php if ( $send_password_by_mail == true ) { ?>
                        <?php esc_html_e( 'Note: Your password will be generated automatically and sent to your email address.', 'b3-user-register' ); ?>
                    <?php } elseif ( 'email_activation' == $registration_type )  { ?>
                        <?php esc_html_e( 'Note: You can set your own password after you have confirmed your email.', 'b3-user-register' ); ?>
                    <?php } else { ?>
                        <?php esc_html_e( 'Note: You can set your own password after registering.', 'b3-user-register' ); ?>
                    <?php } ?>
                </div>
            <?php } ?>
        
            <?php do_action( 'b3_do_before_submit_registration_form' ); ?>
            <div class="b3__form-element b3__form-element--submit">
                <input type="submit" name="submit" class="button" value="<?php esc_html_e( 'Register', 'b3-user-register' ); ?>"/>
            </div>
            <?php do_action( 'b3_do_after_submit_registration_form' ); ?>
            
        <?php } ?>
    
    </form>

    <?php do_action( 'b3_do_after_registration_form' ); ?>
    
</div>
