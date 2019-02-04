<div class="login-form-container">

    <!-- Show errors if there are any -->
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) : ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) : ?>
            <p class="login-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ( $attributes[ 'logged_out' ] ) : ?>
        <p class="login-info">
            <?php _e( 'You have signed out. Would you like to sign in again?', 'sd-login' ); ?>
        </p>
    <?php endif; ?>

    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                printf(
                    __( 'You have successfully registered to <strong>%s</strong>. We have emailed your password to the email address you entered.', 'personalize-login' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
    <?php endif; ?>

    <form method="post" action="<?php echo wp_login_url(); ?>">
        <p class="login-username">
            <label for="user_login"><?php _e( 'E-mail', 'sd-login' ); ?></label>
            <input type="text" name="user_login" id="user_login">
        </p>
        <p class="login-password">
            <label for="user_pass"><?php _e( 'Password', 'sd-login' ); ?></label>
            <input type="password" name="user_pass" id="user_pass">
        </p>
        <p class="login-submit">
            <label for="rememberme" class="screen-reader-text"><?php _e( 'Remember me', 'sd-login' ); ?></label>
            <input name="rememberme" id="rememberme" value="forever" type="checkbox"> Remember me
        </p>
        <p class="login-submit">
            <input type="submit" value="<?php _e( 'Sign In', 'sd-login' ); ?>">
        </p>
        <p class="login-forget">
            <a class="forgot-password" href="<?php echo wp_lostpassword_url(); ?>">
                <?php _e( 'Forgot your password?', 'sd-login' ); ?>
            </a>
        </p>
    </form>
</div>
