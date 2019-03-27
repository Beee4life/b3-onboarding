<div class="b3_page b3_page--lostpass">
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p class="b3_message">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>
    
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3><?php esc_html_e( 'Forgot password', 'b3-onboarding' ); ?></h3>
    <?php } ?>
    
    <form id="forgotpasswordform" class="b3_form b3_form--register" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <input name="b3_forgot_pass" value="<?php echo wp_create_nonce( 'b3-forgot-pass' ); ?>" type="hidden" />

        <p class="b3_message">
            <?php esc_html_e( "Enter your email address and we'll send you a link you can use to pick a new password.", 'b3-onboarding' ); ?>
        </p>

        <p class="form-row">
            <label for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
            <input type="text" name="user_login" id="b3_user_email" value="<?php echo ( defined( 'WP_TESTING' ) && true == WP_TESTING ) ? 'test@xxx.com' : false; ?>" required>
        </p>

        <p class="forgotpassword-submit">
            <input type="submit" name="submit" class="button button-primary button--forgotpass" value="<?php esc_html_e( 'Reset Password', 'b3-onboarding' ); ?>"/>
        </p>
    
        <?php echo b3_form_links( 'forgotpassword' ); ?>
    </form>

</div>
