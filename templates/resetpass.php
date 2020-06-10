<?php
    /**
     * Ouptuts fields for reset pass form
     *
     * @since 1.0.0
     */
?>
<div id="b3-resetpass" class="b3 b3_page b3_page--login">
    <?php if ( $attributes[ 'title' ] ) : ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php endif; ?>

    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
        <input name="rp_login" type="hidden" value="<?php echo esc_attr( $attributes[ 'login' ] ); ?>" autocomplete="off"/>
        <input name="rp_key" type="hidden" value="<?php echo esc_attr( $attributes[ 'key' ] ); ?>"/>
        <input name="b3_form" value="custom" type="hidden" />

        <?php do_action( 'b3_add_form_messages', $attributes ); ?>

        <div class="b3_form-element">
            <label class="b3_form-label" for="pass1"><?php esc_html_e( 'New password', 'b3-onboarding' ) ?></label>
            <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
        </div>
        <div class="b3_form-element">
            <label class="b3_form-label" for="pass2"><?php esc_html_e( 'Repeat new password', 'b3-onboarding' ) ?></label>
            <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
        </div>

        <p class="description"><?php echo wp_get_password_hint(); ?></p>

        <p class="resetpass-submit">
            <input type="submit" id="resetpass-button" class="button" value="<?php esc_html_e( 'Reset Password', 'b3-onboarding' ); ?>" />
        </p>
    </form>
</div>
