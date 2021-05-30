<?php
    /**
     * Ouptuts fields for login form
     *
     * @since 1.0.0
     */
    
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $label = esc_attr__( 'Username or Email address', 'b3-onboarding' );

    if ( 1 == get_site_option( 'b3_register_email_only' ) ) {
        $label = esc_attr__( 'Email address', 'b3-onboarding' );
    }

    do_action( 'b3_add_form_messages', $attributes );
?>

<div id="b3-login" class="b3_page b3_page--login">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>

    <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
        <input name="b3_form" value="login" type="hidden" />

        <?php // Output of fields starts here ?>
        <div class="b3_form-element">
            <label class="b3_form-label b3_form-label--userlogin" for="user_login"><?php echo $label; ?></label>
            <input type="text" name="log" id="user_login" class="input" value="" size="20" autocomplete="username">
        </div>

        <div class="b3_form-element">
            <label class="b3_form-label" for="user_pass"><?php esc_attr_e( 'Password', 'b3-onboarding' ); ?></label>
            <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" autocomplete="current-password">
        </div>

        <?php do_action( 'b3_add_recaptcha_fields', $attributes[ 'template' ] ); ?>

        <div class="b3_form-element">
            <p class="rememberme">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                <label for="rememberme"><?php esc_attr_e( 'Remember Me', 'b3-onboarding' ); ?></label>
            </p>
        </div>

        <div class="b3_form-element b3_form-element--submit">
            <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="<?php esc_attr_e( 'Log in', 'b3-onboarding' ); ?>">
            <?php if ( false !== $attributes[ 'redirect' ] ) { ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $attributes[ 'redirect' ] ); ?>">
            <?php } ?>
        </div>

        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>

    </form>

</div>
