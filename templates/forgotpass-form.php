<?php $show_custom_passwords = get_option( 'b3_custom_passwords' ); ?>

<div id="b3-forgotpass" class="b3">
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) : ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) : ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if ( $attributes[ 'show_title' ] ) : ?>
        <h3><?php esc_html__( 'Forgot Your Password?', 'b3-user-register' ); ?></h3>
    <?php endif; ?>

    <p>
        <?php
            if ( true == $show_custom_passwords ) {
                esc_html__( "Enter your email address and a new password.", 'b3-user-register' );
            } else {
                esc_html__( "Enter your email address and we'll send you a link you can use to pick a new password.", 'b3-user-register' );
            }
        ?>
    </p>

    <form id="b3-register-form" class="b3__form b3__form--register" action="" method="post">
        <input name="b3_forgot_pass" value="<?php echo wp_create_nonce( 'b3-forgot-pass' ); ?>" type="hidden" />

        <p class="form-row">
            <label for="b3_user_email"><?php esc_html__( 'Email', 'b3-user-register' ); ?>
            <input type="text" name="b3_user_email" id="b3_user_email">
        </p>
        <?php if ( true == $show_custom_passwords ) { ?>
            <?php b3_show_password_fields(); ?>
        <?php } ?>
        
        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button" value="<?php esc_html__( 'Reset Password', 'b3-user-register' ); ?>"/>
        </p>
    </form>
</div>
