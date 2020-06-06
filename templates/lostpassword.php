<?php
    /**
     * Ouptuts fields for forgot password form
     *
     * @since 1.0.0
     */
?>
<div class="b3_page b3_page--lostpass">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>

    <form id="forgotpasswordform" class="b3_form b3_form--register" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <input name="b3_forgot_pass" value="<?php echo wp_create_nonce( 'b3-forgot-pass' ); ?>" type="hidden" />

        <?php do_action( 'b3_show_form_messages', $attributes ); ?>

        <div class="b3_form-element">
            <label class="b3_form-label b3_form-label--email" for="b3_user_email"><?php esc_html_e( 'Email address', 'b3-onboarding' ); ?></label>
            <input type="text" name="user_login" id="b3_user_email" value="<?php echo ( defined( 'WP_TESTING' ) && true == WP_TESTING ) ? 'test@xxx.com' : false; ?>" required>
        </div>

        <p class="forgotpassword-submit">
            <input type="submit" class="button button-primary button--forgotpass" value="<?php esc_html_e( 'Reset Password', 'b3-onboarding' ); ?>"/>
        </p>

        <?php echo b3_get_form_links( 'forgotpassword' ); ?>
    </form>

</div>
