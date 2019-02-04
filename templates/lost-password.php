<div id="password-lost-form" class="widecolumn">
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Forgot Your Password?', 'sd-login' ); ?></h3>
    <?php endif; ?>

    <p>
        <?php
            _e(
                "Enter your email address and we'll send you a link you can use to pick a new password.",
                'sd-login'
            );
        ?>
    </p>

    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e( 'Email', 'sd-login' ); ?>
                <input type="text" name="user_login" id="user_login">
        </p>

        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button"
                   value="<?php _e( 'Reset Password', 'sd-login' ); ?>"/>
        </p>
    </form>
</div>
