<?php
    /**
     * Ouptuts fields for reset pass form
     *
     * @since 1.0.0
     */
    
    if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php do_action( 'b3_add_form_messages', $attributes ); ?>
<div id="b3-resetpass" class="b3 b3_page b3_page--resetpass">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>

    <form name="resetpassform" id="resetpassform" action="<?php echo b3_get_reset_password_url(); ?>" method="post" autocomplete="off">
        <input name="b3_form" value="resetpass" type="hidden" />
        <input name="rp_login" type="hidden" value="<?php esc_attr_e( $attributes[ 'login' ] ); ?>" autocomplete="off"/>
        <input name="rp_key" type="hidden" value="<?php esc_attr_e( $attributes[ 'key' ] ); ?>"/>

        <div class="b3_form-element">
            <label class="b3_form-label" for="pass1"><?php esc_attr_e( 'New password', 'b3-onboarding' ) ?></label>
            <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
        </div>
        <div class="b3_form-element">
            <label class="b3_form-label" for="pass2"><?php esc_attr_e( 'Repeat new password', 'b3-onboarding' ) ?></label>
            <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
        </div>

        <p class="password-hint"><?php echo wp_get_password_hint(); ?></p>

        <div class="b3_form-element b3_form-element--submit">
            <input type="submit" id="resetpass-button" class="button" value="<?php esc_attr_e( 'Set password', 'b3-onboarding' ); ?>" />
        </div>
    </form>
</div>
