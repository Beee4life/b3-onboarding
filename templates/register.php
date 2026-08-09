<?php
    /**
     * Ouptuts fields for register form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( ! isset( $_REQUEST[ 'registered' ] ) || isset( $_REQUEST[ 'registered' ] ) && 'access_requested' != sanitize_text_field( wp_unslash( $_REQUEST[ 'registered' ] ) ) ) {
?>
    <div id="b3-register" class="b3_page b3_page--register">
        <?php do_action( 'b3_add_form_messages', $attributes ); ?>

        <?php if ( ! empty( $attributes[ 'title' ] ) ) { ?>
            <?php echo sprintf( '<h3>%s</h3>', esc_html( $attributes[ 'title' ] ) ); ?>
        <?php } ?>

        <form name="registerform" id="registerform" class="b3_form b3_form--register" action="<?php echo esc_url( b3_get_register_url() ); ?>" method="post">
            <?php
                // Output of fields starts here
                do_action( 'b3_add_hidden_fields_registration', $attributes );
                do_action( 'b3_add_username_email_fields', $attributes[ 'registration_type' ] );
                do_action( 'b3_add_first_last_name_fields', $attributes[ 'registration_type' ] );
                do_action( 'b3_add_password_fields' );
                do_action( 'b3_add_site_fields', $attributes[ 'registration_type' ] ); // MS
                do_action( 'b3_add_extra_fields_registration' );
                do_action( 'b3_add_terms_checkbox' );
                do_action( 'b3_add_privacy_checkbox' );
                do_action( 'b3_add_recaptcha_fields' );
                do_action( 'b3_render_form_element', 'general/button', $attributes );
                do_action( 'b3_add_action_links', $attributes[ 'template' ] );
            ?>
        </form>
    </div>
<?php } ?>
