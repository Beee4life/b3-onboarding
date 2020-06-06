<?php
    /**
     * Ouptuts fields for login form
     *
     * @since 1.0.0
     */
    $request_access = get_option( 'b3_registration_type', false );
    $reset_page     = get_option( 'b3_forgotpass_page_id', false );
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>

    <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
        <input name="b3_form" value="custom" type="hidden" />

        <?php do_action( 'b3_show_form_messages', $attributes ); ?>

        <?php // Output of fields starts here ?>
        <?php // @TODO: maybe hook this to something ? ?>
        <div class="b3_form-element">
            <label class="b3_form-label b3_form-label--userlogin" for="user_login"><?php esc_html_e( 'Username or Email address', 'b3-onboarding' ); ?></label>
            <input type="text" name="log" id="user_login" class="input" value="" size="20">
        </div>

        <div class="b3_form-element">
            <label class="b3_form-label" for="user_pass"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
            <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
        </div>

        <?php do_action( 'b3_add_recaptcha_fields', $attributes[ 'template' ] ); ?>

        <div class="rememberme-wrap">
            <p class="rememberme">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                <label for="rememberme"><?php esc_html_e( 'Remember Me', 'b3-onboarding' ); ?></label>
            </p>
        </div>

        <?php // @TODO: maybe create a hook for this ? ?>
        <p class="">
            <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="<?php esc_html_e( 'Log in', 'b3-onboarding' ); ?>">
            <?php if ( false !== $attributes[ 'redirect' ] ) { ?>
                <input type="hidden" name="redirect_to" value="<?php echo $attributes[ 'redirect' ]; ?>">
            <?php } ?>
        </p>

        <?php // @TODO: maybe create a hook for this ? ?>
        <?php echo b3_get_form_links( 'login' ); ?>

    </form>

</div>
