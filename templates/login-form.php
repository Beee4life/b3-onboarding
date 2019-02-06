<?php
    $has_reset_page        = false;
    $send_password_by_mail = get_option( 'b3_send_pass_mail' );
?>
<div id="b3-login" class="b3">
    <?php if ( $attributes[ 'show_title' ] ) : ?>
        <h2>
            <?php _e( 'Sign In', 'b3-user-register' ); ?>
        </h2>
    <?php endif; ?>

    <!-- Show errors if there are any -->
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) : ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) : ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Show success message if user successfully registered -->
    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                if ( is_multisite() ) {
                    echo sprintf(
                        esc_html__( 'You have successfully registered to <strong>%s</strong>. We have emailed you an activation link.', 'b3-user-register' ),
                        get_bloginfo( 'name' )
                    );
                } else {
                    if ( true == $send_password_by_mail ) {
                        echo sprintf(
                            esc_html__( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'b3-user-register' ),
                            get_bloginfo( 'name' )
                        );
                    } else {
                        // if no reset pw page is set
                        if ( true == $has_reset_page ) {
                            $password_reset_url = esc_url( wp_lostpassword_url() );
                        } else {
                            $password_reset_url = esc_url( home_url( '/reset-password/' ) ); // make dynamic/filterable
                        }
                        echo sprintf(
                            esc_html__( 'You have successfully registered to <strong>%1$s</strong>. You can set your password when you <a href="%2$s">reset it</a>.', 'b3-user-register' ),
                            get_bloginfo( 'name' ),
                            $password_reset_url
                        );
                    }
                }
            ?>
        </p>
    <?php endif; ?>

    <!-- Show message if user reset password -->
    <?php if ( $attributes[ 'lost_password_sent' ] ) : ?>
        <p class="login-info">
            <?php esc_html_e( 'Check your email for a link to reset your password.', 'b3-user-register' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes[ 'logged_out' ] ) : ?>
        <p class="login-info">
            <?php esc_html_e( 'You have signed out. Would you like to sign in again?', 'b3-user-register' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show message if user just reset password -->
    <?php if ( $attributes[ 'password_updated' ] ) : ?>
        <p class="login-info">
            <?php esc_html_e( 'Your password has been changed. You can sign in now.', 'b3-user-register' ); ?>
        </p>
    <?php endif; ?>
    
    <?php
        wp_login_form(
            array(
                'label_username' => esc_html__( 'Email', 'b3-user-register' ),
                'label_log_in'   => esc_html__( 'Login', 'b3-user-register' ),
                'redirect'       => $attributes[ 'redirect' ],
            ) );
    ?>

    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php esc_html_e( 'Forgot your password?', 'b3-user-register' ); ?>
    </a>
</div>
