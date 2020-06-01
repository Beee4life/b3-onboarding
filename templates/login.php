<?php
    $redirect = false;
    if ( $attributes[ 'redirect' ] ) {
        $redirect = $attributes[ 'redirect' ];
    }
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>

    <?php
        $reset_page     = get_option( 'b3_forgotpass_page_id' );
        $request_access = get_option( 'b3_registration_type' );
    ?>

    <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
        <?php /* Show errors if there are any */ ?>
        <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
            <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
                <p class="b3_message">
                    <?php echo $error; ?>
                </p>
            <?php } ?>
        <?php } ?>

        <?php /* Show success message if user successfully activated */ ?>
        <?php if ( isset( $attributes[ 'user_activate' ] ) ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'You have successfully activated your account. You can now log in.', 'b3-onboarding' ); ?>
            </p>
        <?php } ?>

        <?php /* Show success message if user successfully registered */ ?>
        <?php if ( $attributes[ 'registered' ] ) { ?>
            <p class="b3_message">
                <?php
                    if ( is_multisite() ) {
                        echo sprintf(
                            __( 'You have successfully registered to <strong>%s</strong>. We have emailed you an activation link.', 'b3-onboarding' ),
                            get_bloginfo( 'name' )
                        );
                    } else {
                        if ( 'request_access' == $request_access && ! empty( $_GET[ 'registered' ] ) && 'access_requested' == $_GET[ 'registered' ] ) {

                            echo sprintf(
                                __( "You have successfully requested access to <strong>%1$s</strong>. You'll be notified by email about the result.", 'b3-onboarding' ),
                                get_bloginfo( 'name' )
                            );
                            $show_form = false;

                        } elseif ( ! empty( $_GET[ 'registered' ] ) && 'confirm_email' == $_GET[ 'registered' ] ) {

                            echo sprintf(
                                __( 'You have successfully registered to <strong>%1$s</strong>. Please click the confirmation link in your email.', 'b3-onboarding' ),
                                get_bloginfo( 'name' )
                            );
                            $show_form = false;

                        } else {

                            if ( false == $reset_page ) {
                                $password_reset_url = esc_url( wp_lostpassword_url() );
                            } else {
                                $password_reset_url = esc_url( get_permalink( $reset_page ) );
                            }
                            echo sprintf(
                                __( 'You have successfully registered to <strong>%1$s</strong>. Set your password <a href="%2$s">here</a>.', 'b3-onboarding' ),
                                get_bloginfo( 'name' ),
                                $password_reset_url
                            );
                            $show_form = false;
                        }
                    }
                ?>
            </p>
        <?php } ?>

        <?php /* Show message if user resets password */ ?>
        <?php if ( $attributes[ 'lost_password_sent' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Check your email for a link to reset your password.', 'b3-onboarding' ); ?>
            </p>
            <?php $show_form = false; ?>
        <?php } ?>

        <?php /* Show logged out message if user just logged out */ ?>
        <?php if ( $attributes[ 'logged_out' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'You are logged out.', 'b3-onboarding' ); ?>
            </p>
        <?php } ?>

        <?php /* Show message if user just reset password */ ?>
        <?php if ( $attributes[ 'password_updated' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Your password has been changed. You can login now.', 'b3-onboarding' ); ?>
            </p>
        <?php } ?>

        <div class="b3_form-element">
            <label class="b3_form-label b3_form-label--userlogin" for="user_login"><?php esc_html_e( 'Username or Email address', 'b3-onboarding' ); ?></label>
            <input type="text" name="log" id="user_login" class="input" value="" size="20">
        </div>

        <div class="b3_form-element">
            <label class="b3_form-label" for="user_pass"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
            <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
        </div>

        <div class="rememberme-wrap">
            <p class="rememberme">
                <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                <label for="rememberme"><?php esc_html_e( 'Remember Me', 'b3-onboarding' ); ?></label>
            </p>
        </div>

        <p class="">
            <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In">
            <input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>">
        </p>

        <?php echo b3_form_links( 'login' ); ?>

    </form>

</div>
