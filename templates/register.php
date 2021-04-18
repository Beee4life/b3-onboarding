<?php
    /**
     * Ouptuts fields for register form
     *
     * @since 1.0.0
     */

    do_action( 'b3_add_form_messages', $attributes );
    $activate_recaptcha = ( isset( $attributes[ 'recaptcha' ] ) ) ? true : false;
    $recaptcha_version  = ( false != $activate_recaptcha ) ? $attributes[ 'recaptcha' ][ 'version' ] : false;
    if ( ! isset( $_REQUEST[ 'registered' ] ) || isset( $_REQUEST[ 'registered' ] ) && 'access_requested' != $_REQUEST[ 'registered' ] ) {
?>

<div id="b3-register" class="b3_page b3_page--register">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>

    <form name="registerform" id="registerform" class="b3_form b3_form--register" action="<?php echo b3_get_current_url(); ?>" method="post">
        <input name="b3_form" value="register" type="hidden" />
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />

        <?php do_action( 'b3_add_hidden_fields_registration' ); ?>

        <?php
            // add vars for single site
            if ( 'blog' != $attributes[ 'registration_type' ] ) {
                do_action( 'b3_add_username_email_fields', $attributes[ 'registration_type' ] );
            }
        ?>

        <?php if ( ! is_multisite() ) { ?>
            <?php do_action( 'b3_add_password_fields' ); ?>
        <?php } else { ?>
            <?php if ( is_main_site() ) { ?>
                <?php do_action( 'b3_add_site_fields', $attributes[ 'registration_type' ] ); ?>
            <?php } ?>
        <?php } ?>
        <?php do_action( 'b3_register_form' ); ?>
        <?php do_action( 'b3_do_before_submit_registration_form' ); ?>

        <div class="b3_form-element b3_form-element--submit">
            <?php if ( ! is_multisite() && 'request_access' == $attributes[ 'registration_type' ] ) { ?>
                <?php $submit_label = esc_attr__( 'Request access', 'b3-onboarding' ); ?>
            <?php } else { ?>
                <?php $submit_label = esc_attr__( 'Register', 'b3-onboarding' ); ?>
            <?php } ?>
            <?php if ( $activate_recaptcha && 3 == $recaptcha_version ) { ?>
                <input type="submit" class="button g-recaptcha" data-sitekey="<?php echo $attributes[ 'recaptcha' ][ 'public' ]; ?>" data-callback="onSubmit" data-action="submit" value="<?php echo $submit_label; ?>" />
            <?php } else { ?>
                <input type="submit" class="button" value="<?php echo $submit_label; ?>" />
            <?php } ?>
        </div>
        <?php do_action( 'b3_do_after_submit_registration_form' ); ?>

        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>

    </form>

</div>
<?php } ?>
