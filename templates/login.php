<?php
    $has_reset_page        = true;
    $show_form             = true;
    $send_password_by_mail = get_option( 'b3_send_pass_mail' );
    $request_access        = get_option( 'b3_registration_type' );
?>
<div id="b3-login" class="b3">
    <?php if ( $attributes[ 'show_title' ] ) { ?>
        <h2>
            <?php _e( 'Log In', 'b3-user-register' ); ?>
        </h2>
    <?php } ?>

    <?php /* Show errors if there are any */ ?>
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>

    <?php /* Show success message if user successfully activated */ ?>
    <?php if ( isset( $attributes[ 'user_activate' ] ) ) { ?>
        <p class="b3__message">
            <?php esc_html_e( 'You have successfully activated your account. You can now log in.', 'b3-user-register' ); ?>
        </p>
    <?php } ?>

    <?php /* Show success message if user successfully registered */ ?>
    <?php if ( $attributes[ 'registered' ] ) { ?>
        <p class="login-info">
            <?php
                if ( is_multisite() ) {
                    echo sprintf(
                        __( 'You have successfully registered to <strong>%s</strong>. We have emailed you an activation link.', 'b3-user-register' ),
                        get_bloginfo( 'name' )
                    );
                } else {
                    if ( true == $send_password_by_mail ) {
                        echo sprintf(
                            __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'b3-user-register' ),
                            get_bloginfo( 'name' )
                        );
                    } else {

                        if ( 'request_access' == $request_access && ! empty( $_GET[ 'registered' ] ) && 'access_requested' == $_GET[ 'registered' ] ) {
    
                            echo sprintf(
                                __( 'You have successfully requested access to <strong>%1$s</strong>. You\'ll be notified by email about the result.', 'b3-user-register' ),
                                get_bloginfo( 'name' )
                            );
                            $show_form = false;
    
                        } elseif ( ! empty( $_GET[ 'registered' ] ) && 'confirm_email' == $_GET[ 'registered' ] ) {
    
                            echo sprintf(
                                __( 'You have successfully registered to <strong>%1$s</strong>. Please click the confirmation link in your email.', 'b3-user-register' ),
                                get_bloginfo( 'name' )
                            );
                            $show_form = false;
    
                        } else {
    
                            if ( true == $has_reset_page ) {
                                $password_reset_url = esc_url( wp_lostpassword_url() );
                            } else {
                                if ( false != b3_get_forgotpass_id() ) {
                                    $password_reset_url = esc_url( get_permalink( b3_get_forgotpass_id() ) ); // make dynamic/filterable
                                } else {
                                    $password_reset_url = esc_url( wp_lostpassword_url() );
                                }
                            }
                            echo sprintf(
                                __( 'You have successfully registered to <strong>%1$s</strong>. You can set your password when you <a href="%2$s">reset it</a>.', 'b3-user-register' ),
                                get_bloginfo( 'name' ),
                                $password_reset_url
                            );
                            $show_form = false;
                        }
                    }
                }
            ?>
        </p>
    <?php } ?>

    <?php /* Show message if user reset password */ ?>
    <?php if ( $attributes[ 'lost_password_sent' ] ) { ?>
        <p class="login-info">
            <?php esc_html_e( 'Check your email for a link to reset your password.', 'b3-user-register' ); ?>
        </p>
        <?php $show_form = false; ?>
    <?php } ?>

    <?php /* Show logged out message if user just logged out */ ?>
    <?php if ( $attributes[ 'logged_out' ] ) { ?>
        <p class="login-info">
            <?php esc_html_e( 'You have signed out.', 'b3-user-register' ); ?>
        </p>
    <?php } ?>

    <?php /* Show message if user just reset password */ ?>
    <?php if ( $attributes[ 'password_updated' ] ) { ?>
        <p class="login-info">
            <?php esc_html_e( 'Your password has been changed. You can login now.', 'b3-user-register' ); ?>
        </p>
    <?php } ?>

    <?php if ( false != $show_form ) { ?>
        <?php
            wp_login_form(
                array(
                    'label_username' => esc_html__( 'Username or email address', 'b3-user-register' ),
                    'label_log_in'   => esc_html__( 'Log In', 'b3-user-register' ),
                    'redirect'       => $attributes[ 'redirect' ],
                ) );
        ?>
        <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
            <?php esc_html_e( 'Forgot your password?', 'b3-user-register' ); ?>
        </a>
    <?php } ?>

</div>
