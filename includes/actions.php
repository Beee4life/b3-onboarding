<?php
    /**
     * Add custom fields to Wordpress' default register form
     */
    function b3_add_registration_fields() {

        // Get and set any values already sent
        $first_name = ( isset( $_POST[ 'first_name' ] ) ) ? $_POST[ 'first_name' ] : '';
        $last_name  = ( isset( $_POST[ 'last_name' ] ) ) ? $_POST[ 'last_name' ] : '';

        $activate_first_last = get_option( 'b3_activate_first_last' );
        $first_last_required = get_option( 'b3_first_last_required' );

        // @TODO: check for recaptch
        // @TODO: check for privacy

        if ( true == $activate_first_last ) {
        ?>

        <p>
            <label for="first_name"><?php _e( 'First name', 'b3-onboarding' ) ?>
            <br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( stripslashes( $first_name ) ); ?>" size="25" /></label>
        </p>

        <p>
            <label for="last_name"><?php _e( 'Last name', 'b3-onboarding' ) ?>
            <br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( stripslashes( $last_name ) ); ?>" size="25" /></label>
        </p>

        <?php
        }
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
