<div class="b3__page b3__page--lostpass">
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>
    
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3><?php esc_html_e( 'Lost password', 'b3-onboarding' ); ?></h3>
    <?php } ?>
    
    <p>
        <?php esc_html_e( "Enter your email address and we'll send you a link you can use to pick a new password.", 'b3-onboarding' ); ?>
    </p>

    <form id="lostpasswordform" class="b3__form b3__form--register" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <input name="b3_forgot_pass" value="<?php echo wp_create_nonce( 'b3-forgot-pass' ); ?>" type="hidden" />

        <p class="form-row">
            <label for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
            <input type="text" name="user_login" id="b3_user_email" value="info@xxx.com" required>
        </p>

        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button" value="<?php esc_html_e( 'Reset Password', 'b3-onboarding' ); ?>"/>
        </p>
    </form>

    <?php echo b3_form_links( 'lostpassword' ); ?>

</div>
