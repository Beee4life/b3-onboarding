<?php $show_custom_passwords = get_option( 'b3-custom-passwords' ); ?>
<div id="b3-register" class="b3">
    <?php if ( $attributes[ 'show_title' ] ) : ?>
        <h3><?php _e( 'Register', 'b3-login' ); ?></h3>
    <?php endif; ?>
    <?php //echo '<pre>'; var_dump($attributes[ 'errors' ]); echo '</pre>'; exit; ?>
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) : ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form id="b3-register-form" class="b3__form b3__form--register" action="<?php echo wp_registration_url(); ?>" method="post">
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="user_login"><?php _e( 'User name', 'b3-login' ); ?> <strong>*</strong></label>
            <input type="text" name="user_login" id="user_login" class="b3__form--input">
        </div>
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="email"><?php _e( 'Email', 'b3-login' ); ?> <strong>*</strong></label>
            <input type="text" name="email" id="email" class="b3__form--input">
        </div>

        <?php if ( is_multisite() ) { ?>
            <?php // @TODO: add fields for Multisite ?>
        <?php } ?>
        
        <?php if ( $show_custom_passwords == true ) { ?>
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass1"><?php _e( 'Password', 'b3-login' ); ?></label>
            <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3__form--input" />
        </div>

        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass2"><?php _e( 'Confirm Password', 'b3-login' ); ?></label>
            <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3__form--input" />
        </div>
        <?php } ?>
        
        <?php
            // @TODO: Create default output for this hook
            do_action( 'b3_add_custom_fields_' . $template_name );
        ?>

        <?php if ( $attributes[ 'recaptcha_site_key' ] ) : ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $attributes[ 'recaptcha_site_key' ]; ?>"></div>
            </div>
            <p></p>
        <?php endif; ?>

        <?php if ( $show_custom_passwords != true ) : ?>
        <div class="b3__form-element b3__form-element--register">
            <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'b3-login' ); ?>
        </div>
        <?php endif; ?>

        <div class="b3__form-element b3__form-element--submit">
            <input type="submit" name="submit" class="button" value="<?php _e( 'Register', 'b3-login' ); ?>"/>
        </div>
    </form>
</div>
