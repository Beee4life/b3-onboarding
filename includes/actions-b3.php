<?php
    // This file contains functions hooked to the plugin's own hooks

    /**
     * Ouptuts default login/email field
     *
     * @since 1.0.0
     *
     */
    function b3_login_email_fields() {
        ob_start();
        ?>
            <div class="b3_form-element b3_form-element--login">
                <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="text" name="user_login" id="b3_user_login" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) ) ? 'username' : ''; ?>" required>
            </div>

            <div class="b3_form-element b3_form-element--email">
                <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) ) ? 'test@xxx.com' : ''; ?>" required>
            </div>
        <?php
        $output = ob_get_clean();
        echo $output;
    }
    add_action( 'b3_login_email_fields', 'b3_login_email_fields' );

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
        $from_name   = 'Dummy';
        $from_email  = 'Dummy';
        $to          = $user_object->user_email;
        $subject     = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
        $message     = apply_filters( 'b3_account_approved_message', b3_get_account_approved_message() );
        $message     = b3_replace_template_styling( $message );
        $message     = strtr( $message, b3_replace_email_vars() );
        $message     = htmlspecialchars_decode( stripslashes( $message ) );
        $headers     = array(
            'From: ' . $from_name . ' <' . $from_email . '>',
            'Content-Type: text/html; charset=UTF-8',
        );
        wp_mail( $to, $subject, $message, $headers );
    }
    add_action( 'b3_new_user_activated_by_admin', 'b3_do_stuff_after_new_user_activated_by_admin' );


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
    function b3_after_user_activated( $user_id ) {
        if ( 1 != get_option( 'b3_disable_admin_notification_new_user', false ) ) {
            wp_new_user_notification( $user_id, null, 'admin' );
        }
        // send activate email to user
        if ( 'email_activation' == get_option( 'b3_registration_type', false ) ) {
            $from_name  = 'Dummy';
            $from_email = 'Dummy';
            $user       = get_userdata( $user_id );
            $to         = $user->user_email;
            $subject    = apply_filters( 'b3_account_activated_subject_user', b3_get_account_activated_subject_user() );
            $message    = apply_filters( 'b3_account_activated_message_user', b3_get_account_activated_message_user() );
            $message    = b3_replace_template_styling( $message );
            $message    = strtr( $message, b3_replace_email_vars( [ 'user_data' => $user ] ) );
            $message    = htmlspecialchars_decode( stripslashes( $message ) );
            $headers    = array(
                'From: ' . $from_name . ' <' . $from_email . '>',
                'Content-Type: text/html; charset=UTF-8',
            );
            wp_mail( $to, $subject, $message, $headers );
        }
    }
    add_action( 'b3_new_user_activated', 'b3_after_user_activated' );


    /**
     * Add reCAPTCHA check
     *
     * @since 2.0.0
     *
     * @param $recaptcha_public
     * @param string $form_type
     */
    function b3_add_recaptcha_fields( $form_type = 'register' ) {
        $recaptcha_public  = get_option( 'b3_recaptcha_public', false );
        $recaptcha_version = get_option( 'b3_recaptcha_version', '2' );
        $show_recaptcha    = get_option( 'b3_recaptcha', false );

        if ( false != $show_recaptcha ) {
            do_action( 'b3_before_recaptcha_' . $form_type );
            if ( '3' != $recaptcha_version ) {
                ?>
                <div class="b3_form-element b3_form-element--recaptcha">
                    <div class="recaptcha-container">
                        <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
                    </div>
                </div>
                <?php
            }
            do_action( 'b3_after_recaptcha_' . $form_type );
        }
    }
    add_action( 'b3_add_recaptcha_fields', 'b3_add_recaptcha_fields' );

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
                <?php // @TODO: add more fields for Multisite (MS) ?>
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
     * Output for first/last name fields
     *
     * @since 0.8-beta
     */
    function b3_first_last_name_fields() {
        $show_first_last_name = get_option( 'b3_activate_first_last', false );
        if ( $show_first_last_name ) {
            ob_start();
            ?>
            <?php $required = ( true == get_option( 'b3_first_last_required', false ) ) ? ' required="required"' : false; ?>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="first_name" id="b3_first_name" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) ) ? 'First name' : false; ?>"<?php echo $required; ?>>
            </div>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="last_name" id="b3_last_name" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) ) ? 'Last name' : false; ?>"<?php echo $required; ?>>
            </div>
            <?php
            $output = ob_get_clean();
            echo $output;
        }
    }
    add_action( 'b3_add_first_last_name', 'b3_first_last_name_fields' );

    /**
     * Output the password fields (not in use yet)
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
     * Function to output any custom fields
     *
     * @since 2.0.0
     */
    function b3_add_custom_fields_registration() {
        $extra_field_values = apply_filters( 'b3_extra_fields', array() );
        if ( is_array( $extra_field_values ) && ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $extra_field ) {
                echo b3_render_extra_field( $extra_field );
            }
        }
    }
    add_action( 'b3_add_custom_fields_registration', 'b3_add_custom_fields_registration' );

    /**
     * Function to output a privacy checkbox
     */
    function b3_add_privacy_checkbox() {
        $show_privacy = get_option( 'b3_privacy', false );
        if ( true == $show_privacy ) { ?>
            <div class="b3_form-element b3_form-element--privacy">
                <label>
                    <input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1" /> <?php echo htmlspecialchars_decode( apply_filters( 'b3_add_privacy_text', b3_get_privacy_text() ) ); ?>
                </label>
            </div>
        <?php }
    }
    add_action( 'b3_add_privacy_checkbox', 'b3_add_privacy_checkbox' );

    /**
     * Output any hidden fields
     *
     * @since 2.0.0
     */
    function b3_hidden_fields_registration_form() {
        $hidden_field_values = apply_filters( 'b3_hidden_fields', array() );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            $hidden_fields = '';
            foreach( $hidden_field_values as $key => $value ) {
                $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
            }
            echo $hidden_fields;
        }
    }
    add_action( 'b3_hidden_fields_registration_form', 'b3_hidden_fields_registration_form' );


    /**
     * Echo error/info message above a (custom) form
     *
     * @since 2.0.0
     *
     * @param bool $attributes
     *
     * @return bool
     */
    function b3_show_form_messages( $attributes = false ) {

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
                    $messages[] = esc_html__( "Enter your email address and we'll send you a link to reset your password.", 'b3-onboarding' );
                } elseif ( isset( $attributes[ 'template' ] ) && 'register' == $attributes[ 'template' ] ) {
                    if ( 'request_access' == $registration_type ) {
                        $show_errors = true;
                        $messages[] = apply_filters( 'b3_filter_before_request_access', esc_html__( 'You have to request access for this website.', 'b3-onboarding' ) );
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
    add_action( 'b3_show_form_messages', 'b3_show_form_messages' );
