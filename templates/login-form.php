<div class="login-form-container">
    <?php if ( $attributes[ 'show_title' ] ) : ?>
        <h2><?php _e( 'Sign In', 'sd-login' ); ?></h2>
    <?php endif; ?>

    <!-- Show errors if there are any -->
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) : ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Show success message if user successfully registered -->
    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                printf(
                    __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'sd-login' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
    <?php endif; ?>

    <!-- Show message if user reset password -->
    <?php if ( $attributes['lost_password_sent'] ) : ?>
        <p class="login-info">
            <?php _e( 'Check your email for a link to reset your password.', 'personalize-login' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
    <?php if ( $attributes[ 'logged_out' ] ) : ?>
        <p class="login-info">
            <?php _e( 'You have signed out. Would you like to sign in again?', 'sd-login' ); ?>
        </p>
    <?php endif; ?>

    <!-- Show message if user just reset password -->
    <?php if ( $attributes['password_updated'] ) : ?>
        <p class="login-info">
            <?php _e( 'Your password has been changed. You can sign in now.', 'personalize-login' ); ?>
        </p>
    <?php endif; ?>

    <?php
        wp_login_form(
            array(
                'label_username' => __( 'Email', 'sd-login' ),
                'label_log_in'   => __( 'Sign In', 'sd-login' ),
                'redirect'       => $attributes[ 'redirect' ],
            )
        );
    ?>

    <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
        <?php _e( 'Forgot your password?', 'sd-login' ); ?>
    </a>
</div>
