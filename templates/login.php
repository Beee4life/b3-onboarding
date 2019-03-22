<?php
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h2>
            <?php _e( 'Log In', 'b3-onboarding' ); ?>
        </h2>
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
                                __( 'You have successfully requested access to <strong>%1$s</strong>. You\'ll be notified by email about the result.', 'b3-onboarding' ),
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
                                __( 'You have successfully registered to <strong>%1$s</strong>. You can set your password when you <a href="%2$s">reset it</a>.', 'b3-onboarding' ),
                                get_bloginfo( 'name' ),
                                $password_reset_url
                            );
                            $show_form = false;
                        }
                    }
                ?>
            </p>
        <?php } ?>
        
        <?php /* Show message if user reset password */ ?>
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

        <table class="b3_table b3_table--login" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <label for="user_login"><?php esc_html_e( 'Email address', 'b3-onboarding' ); ?></label>
                </td>
                <td>
                    <input type="text" name="log" id="user_login" class="input" value="" size="20">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="user_pass"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
                </td>
                <td>
                    <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> <?php esc_html_e( 'Remember Me', 'b3-onboarding' ); ?></label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In">
                    <input type="hidden" name="redirect_to" value="">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo b3_form_links( 'login' ); ?>
                </td>
            </tr>
        </table>
    </form>

</div>
