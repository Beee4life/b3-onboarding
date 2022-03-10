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
    
    if ( ! is_multisite() && 'request_access' == $attributes[ 'registration_type' ] ) {
        $submit_label = esc_attr__( 'Request access', 'b3-onboarding' );
    } else {
        $submit_label = esc_attr__( 'Register', 'b3-onboarding' );
    }

    if ( ! isset( $_REQUEST[ 'registered' ] ) || isset( $_REQUEST[ 'registered' ] ) && 'access_requested' != $_REQUEST[ 'registered' ] ) {
?>
<div id="b3-register" class="b3_page b3_page--register">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false; ?>

    <form name="registerform" id="registerform" class="b3_form b3_form--register" action="<?php echo b3_get_current_url(); ?>" method="post">
        <?php
            do_action( 'b3_add_hidden_fields_registration' );

            if ( 'blog' != $attributes[ 'registration_type' ] ) {
                do_action( 'b3_add_username_email_fields', $attributes[ 'registration_type' ] );
            }

            if ( ! is_multisite() ) {
                do_action( 'b3_add_password_fields' );
            } elseif ( is_main_site() ) {
                do_action( 'b3_add_site_fields', $attributes[ 'registration_type' ] );
            }
            // @TODO: look into adding do_action register_form
            do_action( 'b3_register_form' );
            do_action( 'b3_do_before_submit_registration_form' );
        ?>

        <div class="b3_form-element b3_form-element--submit">
            <?php b3_get_submit_button( $submit_label, 'register', $attributes ); ?>
        </div>

        <?php do_action( 'b3_do_after_submit_registration_form' ); ?>
        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
    </form>

</div>
<?php } ?>
