<?php
    $show_custom_passwords = get_option( 'b3_custom_passwords' );
    $show_recaptcha        = get_option( 'b3_recaptcha' );
    $show_first_last_name  = get_option( 'b3_first_last_name' );
?>
<div id="b3-register" class="b3">
    <?php if ( $attributes[ 'show_title' ] ) { ?>
        <h3><?php _e( 'Register', 'b3-user-register' ); ?></h3>
    <?php } ?>
    <?php //echo '<pre>'; var_dump($attributes[ 'errors' ]); echo '</pre>'; exit; ?>
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>
    
    <?php do_action( 'b3_before_registration_form' ); ?>

    <form id="b3-register-form" class="b3__form b3__form--register" action="" method="post">
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />
        <?php do_action( 'b3_add_hidden_fields_registration' ); ?>

        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="b3_user_login"><?php _e( 'User name', 'b3-user-register' ); ?> <strong>*</strong></label>
            <input type="text" name="b3_user_login" id="b3_user_login" class="b3__form--input" required>
        </div>

        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="b3_user_email"><?php _e( 'Email', 'b3-user-register' ); ?> <strong>*</strong></label>
            <input type="text" name="b3_user_email" id="b3_user_email" class="b3__form--input" required>
        </div>

        <?php if ( is_multisite() ) { ?>
            <?php // @TODO: add more fields for Multisite ?>
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_subdomain"><?php _e( 'Desired (sub) domain', 'b3-user-register' ); ?></label>
                <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3__form--input" placeholder="customdomain        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
            </div>
        <?php } ?>
        
        <?php // this function is not in use yet ?>
        <?php if ( $show_custom_passwords == true ) { ?>
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="pass1"><?php _e( 'Password', 'b3-user-register' ); ?></label>
                <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3__form--input" />
            </div>
    
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="pass2"><?php _e( 'Confirm Password', 'b3-user-register' ); ?></label>
                <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3__form--input" />
            </div>
        <?php } ?>
    
        <?php // this function is not in use yet ?>
        <?php if ( $show_first_last_name == true ) { ?>
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_first_name"><?php _e( 'First name', 'b3-user-register' ); ?></label>
                <input type="text" name="b3_first_name" id="b3_first_name" class="b3__form--input">
            </div>
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_last_name"><?php _e( 'Last name', 'b3-user-register' ); ?></label>
                <input type="text" name="b3_last_name" id="b3_last_name" class="b3__form--input">
            </div>
        <?php } ?>

        <?php do_action( 'b3_add_custom_fields_registration' ); ?>
    
        <?php // this function is not in use yet ?>
        <?php if ( $show_recaptcha == true && $attributes[ 'recaptcha_site_key' ] ) { ?>
            <?php do_action( 'b3_add_captcha_registration' ); ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $attributes[ 'recaptcha_site_key' ]; ?>"></div>
            </div>
            <p></p>
        <?php } ?>

        <?php if ( $show_custom_passwords != true ) { ?>
            <div class="b3__form-element b3__form-element--register">
                <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'b3-user-register' ); ?>
            </div>
        <?php } ?>
    
        <?php do_action( 'b3_before_submit_registration_form' ); ?>

        <div class="b3__form-element b3__form-element--submit">
            <input type="submit" name="submit" class="button" value="<?php _e( 'Register', 'b3-user-register' ); ?>"/>
        </div>
    
    </form>

    <?php do_action( 'b3_after_registration_form' ); ?>
</div>
