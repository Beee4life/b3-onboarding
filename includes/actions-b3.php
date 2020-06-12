<?php
    // This file contains functions hooked to the plugin's own hooks

    /**
     * Do stuff afer manual activation by admin
     *
     * @since 1.0.0
     *
     * @param $user_id
     */
    function b3_do_stuff_after_new_user_activated_by_admin( $user_id ) {
        // Do stuff when user is activated by admin
        $user_object = get_userdata( $user_id );
        $user_object->set_role( get_option( 'default_role' ) );
        $to          = $user_object->user_email;
        $subject     = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
        $subject     = strtr( $subject, b3_replace_subject_vars() );
        $message     = apply_filters( 'b3_account_approved_message', b3_get_account_approved_message() );
        $message     = b3_replace_template_styling( $message );
        $message     = strtr( $message, b3_replace_email_vars() );
        $message     = htmlspecialchars_decode( stripslashes( $message ) );
        wp_mail( $to, $subject, $message, array() );
    }
    add_action( 'b3_after_user_activated_by_admin', 'b3_do_stuff_after_new_user_activated_by_admin' );


    /**
     * Do stuff after user clicked activate link
     *
     * @since 1.0.0
     *
     * @TODO: check for a WordPress hook to hook to
     * @TODO: look into filter 'registration_redirect'
     *
     * @param $user_id
     */
    function b3_do_stuff_after_user_activated( $user_id ) {
        if ( 1 != get_option( 'b3_disable_admin_notification_new_user', false ) ) {
            wp_new_user_notification( $user_id, null, 'admin' );
        }
        // send activate email to user
        if ( 'email_activation' == get_option( 'b3_registration_type', false ) ) {
            $user       = get_userdata( $user_id );
            $to         = $user->user_email;
            $subject    = apply_filters( 'b3_account_activated_subject_user', b3_get_account_activated_subject_user() );
            $message    = apply_filters( 'b3_account_activated_message_user', b3_get_account_activated_message_user() );
            $message    = b3_replace_template_styling( $message );
            $message    = strtr( $message, b3_replace_email_vars( [ 'user_data' => $user ] ) );
            $message    = htmlspecialchars_decode( stripslashes( $message ) );
            wp_mail( $to, $subject, $message, array() );
        }
    }
    add_action( 'b3_after_user_activated', 'b3_do_stuff_after_user_activated' );


    /**
     * Ouptuts default login/email field
     *
     * @since 1.0.0
     */
    function b3_add_username_email_fields() {
        ob_start();
        ?>
        <div class="b3_form-element b3_form-element--login">
            <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
            <input type="text" name="user_login" id="b3_user_login" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? apply_filters( 'b3_localhost_username', 'dummy' ) : ''; ?>" required>
        </div>

        <div class="b3_form-element b3_form-element--email">
            <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
            <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? apply_filters( 'b3_localhost_email', get_option( 'admin_email' ) ) : ''; ?>" required>
        </div>
        <?php
        $output = ob_get_clean();
        echo $output;
    }
    add_action( 'b3_add_username_email_fields', 'b3_add_username_email_fields' );


    /**
     * Output for first/last name fields
     *
     * @since 0.8-beta
     */
    function b3_first_last_name_fields() {
        $activate_first_last = get_option( 'xb3_activate_first_last', false );
        if ( $activate_first_last ) {
            $first_last_required = get_option( 'b3_first_last_required', false );
            $first_name          = ( isset( $_POST[ 'first_name' ] ) ) ? $_POST[ 'first_name' ] : ( defined( 'LOCALHOST' ) && true === LOCALHOST ) ? 'First' : false;
            $last_name           = ( isset( $_POST[ 'last_name' ] ) ) ? $_POST[ 'last_name' ] : ( defined( 'LOCALHOST' ) && true === LOCALHOST ) ? 'Last' : false;
            ob_start();
            do_action( 'b3_do_before_first_last_name' );
            ?>
            <?php $required = ( true == get_option( 'b3_first_last_required', false ) ) ? ' required="required"' : false; ?>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="first_name" id="b3_first_name" class="b3_form--input" value="<?php echo $first_name; ?>"<?php echo $required; ?>>
            </div>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="last_name" id="b3_last_name" class="b3_form--input" value="<?php echo $last_name; ?>"<?php echo $required; ?>>
            </div>
            <?php
            do_action( 'b3_do_after_first_last_name' );
            $output = ob_get_clean();
            echo $output;
        }
    }
    add_action( 'b3_add_first_last_name_fields', 'b3_first_last_name_fields' );


    /**
     * Output the password fields (not in use yet, needs more testing)
     *
     * @since 0.8-beta
     */
    function b3_add_password_fields() {
        $show_custom_passwords = get_option( 'b3_use_custom_passwords', false );
        if ( $show_custom_passwords ) {
            ob_start();
            ?>
            <p class="b3_message">
                <?php esc_html_e( "If you triggered this setting manually, be aware that it's not working yet.", "b3-onboarding" ); ?>
            </p>

            <div class="b3_form-element b3_form-element--password">
                <label class="b3_form-label" for="pass1"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
                <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3_form--input" />
            </div>

            <div class="b3_form-element b3_form-element--password">
                <label class="b3_form-label" for="pass2"><?php esc_html_e( 'Confirm Password', 'b3-onboarding' ); ?></label>
                <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3_form--input" />
            </div>
            <?php
            $results = ob_get_clean();
            echo $results;
        }
    }
    add_action( 'b3_add_password_fields', 'b3_add_password_fields' );


    /**
     * Add field for subdomain when WPMU is active (not used yet)
     *
     * @since 1.0.0
     */
    function b3_add_subdomain_field() {
        if ( is_multisite() ) {
            $show_subdomain = get_option( 'b3_show_subdomain_field', false );
            if ( 'all' == get_site_option( 'registration' ) && in_array( get_option( 'b3_registration_type', false ) , array( 'request_access_subdomain', 'ms_register_site_user' ) ) ) {
                ob_start();
                ?>
                <div class="b3_form-element b3_form-element--register">
                    <label class="b3_form-label" for="b3_subdomain"><?php esc_html_e( 'Desired (sub) domain', 'b3-onboarding' ); ?></label>
                    <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-onboarding' ); ?>        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
                </div>
                <?php
                $output = ob_get_clean();
                echo $output;
            }
        }
    }
    add_action( 'b3_add_subdomain_field', 'b3_add_subdomain_field' );


    /**
     * Function to output any custom fields
     *
     * @since 2.0.0
     */
    function b3_add_extra_fields_registration() {
        $extra_field_values = apply_filters( 'b3_extra_fields', array() );
        if ( ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $extra_field ) {
                echo b3_render_extra_field( $extra_field );
            }
        }
    }
    add_action( 'b3_add_extra_fields_registration', 'b3_add_extra_fields_registration' );


    /**
     * Output any hidden fields
     *
     * @since 2.0.0
     */
    function b3_add_hidden_fields_registration() {
        $hidden_field_values = apply_filters( 'b3_hidden_fields', array() );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            $hidden_fields = '';
            foreach( $hidden_field_values as $key => $value ) {
                $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
            }
            echo $hidden_fields;
        }
    }
    add_action( 'b3_add_hidden_fields_registration', 'b3_add_hidden_fields_registration' );


    /**
     * Add reCAPTCHA check
     *
     * @since 2.0.0
     *
     * @param $recaptcha_public
     * @param string $form_type
     */
    function b3_add_recaptcha_fields( $form_type = 'register' ) {
        $recaptcha_public     = get_option( 'b3_recaptcha_public', false );
        $recaptcha_version    = get_option( 'b3_recaptcha_version', '2' );
        $show_recaptcha       = get_option( 'b3_recaptcha', false );

        if ( 'login' == $form_type ) {
            if ( true != get_option( 'b3_recaptcha_login', false ) ) {
                $show_recaptcha = false;
            }
        }

        if ( false != $show_recaptcha ) {
            do_action( 'b3_do_before_recaptcha_' . $form_type );
            if ( '3' != $recaptcha_version ) {
                ?>
                <div class="b3_form-element b3_form-element--recaptcha">
                    <div class="recaptcha-container">
                        <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
                    </div>
                </div>
                <?php
            }
            do_action( 'b3_do_after_recaptcha_' . $form_type );
        }
    }
    add_action( 'b3_add_recaptcha_fields', 'b3_add_recaptcha_fields' );


    /**
     * Function to output a privacy checkbox
     */
    function b3_add_privacy_checkbox() {
        $show_privacy = get_option( 'b3_privacy', false );
        if ( true == $show_privacy ) {
            do_action( 'b3_do_before_privacy_checkbox');
            ?>
            <div class="b3_form-element b3_form-element--privacy">
                <label>
                    <input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1" /> <?php echo htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) ); ?>
                </label>
            </div>
            <?php
            do_action( 'b3_do_after_privacy_checkbox');
        }
    }
    add_action( 'b3_add_privacy_checkbox', 'b3_add_privacy_checkbox' );


    /**
     * Echo error/info message above a (custom) form
     *
     * @since 2.0.0
     *
     * @param bool $attributes
     *
     * @return bool
     */
    function b3_render_form_messages( $attributes = false ) {

        if ( false != $attributes ) {
            $messages          = array();
            $show_errors       = false;
            $registration_type = get_option( 'b3_registration_type', false );

            if ( isset( $attributes[ 'errors' ] ) && 0 < count( $attributes[ 'errors' ] ) ) {
                $show_errors = true;
                foreach ( $attributes[ 'errors' ] as $error ) {
                    $messages[] = $error;
                }
            } elseif ( isset( $attributes[ 'messages' ] ) && 0 < count( $attributes[ 'messages' ] ) ) {
                $show_errors = true;
                foreach ( $attributes[ 'messages' ] as $message ) {
                    $messages[] = $message;
                }
            } else {
                if ( isset( $attributes[ 'template' ] ) && 'lostpassword' == $attributes[ 'template' ] ) {
                    $show_errors = true;
                    $messages[] = esc_html__( apply_filters( 'b3_message_above_lost_password', b3_get_lost_password_message() ) );
                } elseif ( isset( $attributes[ 'template' ] ) && 'register' == $attributes[ 'template' ] ) {
                    if ( 'request_access' == $registration_type ) {
                        $request_access_message = esc_html__( apply_filters( 'b3_message_above_request_access', b3_get_request_access_message() ) );
                        if ( false != $request_access_message ) {
                            $show_errors = true;
                            $messages[]  = $request_access_message;
                        }
                    }
                } elseif ( isset( $attributes[ 'template' ] ) && 'resetpass' == $attributes[ 'template' ] ) {
                    $show_errors = true;
                    $messages[] = esc_html__( 'Enter your new password.', 'b3-onboarding' );
                }
            }

            if ( true == $show_errors && ! empty( $messages ) ) {
                echo '<div class="b3_message">';
                foreach ( $messages as $message ) {
                    echo '<p>';
                    echo $message;
                    echo '</p>';
                }
                echo '</div>';
            }
        }

        return false;
    }
    add_action( 'b3_add_form_messages', 'b3_render_form_messages' );
