<?php
    /**
     * Ouptuts fields for register form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_add_form_messages', $attributes );

    $activate_recaptcha = ( isset( $attributes[ 'recaptcha' ] ) ) ? true : false;
    $recaptcha_version  = ( false != $activate_recaptcha ) ? $attributes[ 'recaptcha' ][ 'version' ] : false;
    if ( ! isset( $_REQUEST[ 'registered' ] ) || isset( $_REQUEST[ 'registered' ] ) && 'access_requested' != $_REQUEST[ 'registered' ] ) {
?>
<div id="b3-register" class="b3_page b3_page--register">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <?php echo sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ); ?>
    <?php } ?>

    <form name="registerform" id="registerform" class="b3_form b3_form--register" action="<?php echo b3_get_current_url(); ?>" method="post">
        <?php
            do_action( 'b3_add_hidden_fields_registration' );

            // add vars for single site
            if ( 'blog' != $attributes[ 'registration_type' ] ) {
                do_action( 'b3_add_username_email_fields', $attributes[ 'registration_type' ] );
            }

            if ( ! is_multisite() ) {
                do_action( 'b3_add_password_fields' );
            } elseif ( is_main_site() ) {
                do_action( 'b3_add_site_fields', $attributes[ 'registration_type' ] );
            }
            do_action( 'b3_register_form' );
            do_action( 'b3_do_before_submit_registration_form' );
        ?>

        <div class="b3_form-element b3_form-element--submit">
            <?php if ( ! is_multisite() && 'request_access' == $attributes[ 'registration_type' ] ) { ?>
                <?php $submit_label = esc_attr__( 'Request access', 'b3-onboarding' ); ?>
            <?php } else { ?>
                <?php $submit_label = esc_attr__( 'Register', 'b3-onboarding' ); ?>
            <?php } ?>

            <?php if ( $activate_recaptcha && 3 == $recaptcha_version ) { ?>
                <?php echo sprintf( '<input type="submit" class="button g-recaptcha" data-sitekey="%s" data-callback="onSubmit" data-action="submit" value="%s" />', $attributes[ 'recaptcha' ][ 'public' ], $submit_label ); ?>
            <?php } else { ?>
                <?php echo sprintf( '<input type="submit" class="button" value="%s" />', $submit_label ); ?>
            <?php } ?>
        </div>

        <?php do_action( 'b3_do_after_submit_registration_form' ); ?>
        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
    </form>

</div>
<?php } ?>
