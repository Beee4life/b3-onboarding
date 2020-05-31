<?php
    /**
     * Add custom fields to Wordpress' default register form
     */
    function b3_add_registration_fields() {

        // Get and set any values already sent
        $activate_first_last = get_option( 'b3_activate_first_last' );
        $first_last_required = get_option( 'b3_first_last_required' );
        $first_name          = ( isset( $_POST[ 'first_name' ] ) ) ? $_POST[ 'first_name' ] : '';
        $last_name           = ( isset( $_POST[ 'last_name' ] ) ) ? $_POST[ 'last_name' ] : '';
        $recaptcha           = get_option( 'b3_recaptcha' );
        $recaptcha_public    = get_option( 'b3_recaptcha_public' );
        $privacy_checkbox    = get_option( 'b3_privacy' );
        $privacy_page        = get_option( 'b3_privacy_page' );
        $privacy_page_wp     = get_option( 'wp_page_for_privacy_policy' );
        if ( false == $privacy_page && false != $privacy_page_wp ) {
            $privacy_page = get_permalink( $privacy_page_wp );
        }

        if ( true == $activate_first_last ) {
        ?>
        <p>
            <label for="first_name"><?php _e( 'First name', 'b3-onboarding' ) ?> <?php if ( 1 == $first_last_required ) { ?>(<?php esc_html_e( 'required', 'b3-onboarding' ); ?>)<?php }?>
            <br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( stripslashes( $first_name ) ); ?>" size="25" /></label>
        </p>

        <p>
            <label for="last_name"><?php _e( 'Last name', 'b3-onboarding' ) ?> <?php if ( 1 == $first_last_required ) { ?>(<?php esc_html_e( 'required', 'b3-onboarding' ); ?>)<?php }?>
            <br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( stripslashes( $last_name ) ); ?>" size="25" /></label>
        </p>
        <?php } ?>

        <?php
            if ( true == $recaptcha ) {
                do_action( 'b3_before_recaptcha_register' );
                ?>
                <div class="recaptcha-container">
                    <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
                </div>
                <p></p>
                <?php
                do_action( 'b3_after_recaptcha_regsiter' );
            }
        ?>

        <?php if ( true == $privacy_checkbox ) { ?>
            <p>
                <label>
                    <input name="accept_privacy" type="checkbox" id="accept_privacy" value="1">
                    <?php
                        if ( false != get_option( 'b3_privacy_text' ) ) {
                            echo get_option( 'b3_privacy_text' );
                        } else {
                            esc_html_e( 'Accept privacy settings', 'b3-onboarding' );
                            if ( true == $privacy_page ) {
                                echo '&nbsp;-&nbsp;';
                                echo sprintf( __( '<a href="%s">Click here</a> for more info.', 'b3-onboarding' ), esc_url( $privacy_page ) );
                            }
                        }
                    ?>
                </label>
            </p>
            <br class="clear">
        <?php } ?>

    <?php
    }
    add_action( 'register_form', 'b3_add_registration_fields' );

    /**
     * Update usermeta after register
     *
     * @param $user_id
     */
    function b3_update_user_register_fields( $user_id ) {
        if ( ! empty( $_POST[ 'first_name' ] ) ) {
            update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST[ 'first_name' ] ) );
        }
        if ( ! empty( $_POST[ 'first_name' ] ) ) {
            update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST[ 'last_name' ] ) );
        }
    }
    add_action( 'user_register', 'b3_update_user_register_fields' );
