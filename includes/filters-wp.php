<?php

    /**
     * Disable admin notification on password change
     */
    if ( 1 == get_option( 'b3_disable_admin_notification_password_change', false ) ) {
        add_filter( 'wp_password_change_notification_email', '__return_false' );
    } else {
        add_filter( 'wp_password_change_notification_email', 'b3_password_changed_email', 10, 3 );
    }

    /**
     * Filter password change notification mail (admin)
     *
     * @since 2.0.0
     *
     * @param $wp_password_change_notification_email
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_password_changed_email( $wp_password_change_notification_email, $user, $blogname ) {
        $message = sprintf( esc_html__( 'Password changed for user: %s', 'b3-onboarding' ), $user->user_login ); // default: Password changed for user: {username}
        $message = b3_replace_template_styling( $message );
        $message = strtr( $message, b3_replace_email_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );
        $subject = 'User changed password'; // default: [blog name] Password changed

        $wp_password_change_notification_email[ 'subject' ] = $subject;
        $wp_password_change_notification_email[ 'message' ] = $message;

        return $wp_password_change_notification_email;
    }

    /**
     * Override new user notification for admin
     *
     * @since 1.0.6
     *
     * @param $wp_new_user_notification_email_admin
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_new_user_notification_email_admin( $wp_new_user_notification_email_admin, $user, $blogname ) {

        if ( isset( $_POST[ 'action' ] ) && 'createuser' == $_POST[ 'action' ] ) {
            // user is manually added
            return false;
        } else {

            if ( false != get_option( 'b3_disable_admin_notification_new_user', false ) ) {
                return false;
            }

            $registration_type = get_option( 'b3_registration_type', false );

            if ( 'request_access' == $registration_type ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_request_access_subject_admin', b3_get_request_access_subject_admin() );

                $admin_email = apply_filters( 'b3_request_access_message_admin', b3_get_request_access_message_admin() );
                $admin_email = b3_replace_template_styling( $admin_email );
                $admin_email = strtr( $admin_email, b3_replace_email_vars() );
                $admin_email = htmlspecialchars_decode( stripslashes( $admin_email ) );

                $wp_new_user_notification_email_admin[ 'message' ] = $admin_email;

            } elseif ( in_array( $registration_type, [ 'email_activation' ] ) ) {
                // we don't want the email when a user registers, but only when he activates
                return false;

            } elseif ( in_array( $registration_type, [ 'open' ] ) ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject() );

                $admin_email = apply_filters( 'b3_new_user_mesage', b3_get_new_user_message() );
                $admin_email = b3_replace_template_styling( $admin_email );
                $admin_email = strtr( $admin_email, b3_replace_email_vars( array( 'user_data' => $user ) ) );
                $admin_email = htmlspecialchars_decode( stripslashes( $admin_email ) );

                $wp_new_user_notification_email_admin[ 'message' ] = $admin_email;

            }
        }

        return $wp_new_user_notification_email_admin;

    }
    add_filter( 'wp_new_user_notification_email_admin', 'b3_new_user_notification_email_admin', 9, 3 );


    /**
     * Override new user notification email for user
     *
     * @since 1.0.6
     *
     * @param $wp_new_user_notification_email
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {

        // check if use of own styling/templates
        $send_custom_mail = true;
        $send_manual_mail = false;

        if ( isset( $_POST[ 'action' ] ) && 'createuser' == $_POST[ 'action' ] ) {
            // user is manually added
            if ( isset( $_POST[ 'send_user_notification' ] ) && 1 == $_POST[ 'send_user_notification' ] ) {
                // user must get AN email, from WP or custom
                $send_custom_mail               = false;
                $send_manual_mail               = true;
                $wp_new_user_notification_email = false;
            } else {
                $send_custom_mail = false;
            }
        }

        if ( true == $send_custom_mail ) {
            // $wp_new_user_notification_email[ 'to' ] = $user->user_email;
            if ( 'request_access' == get_option( 'b3_registration_type', false ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_request_access_subject_user', b3_get_request_access_subject_user() );

                $user_email = apply_filters( 'b3_request_access_message_user', b3_get_request_access_message_user() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars() );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;

            } elseif ( 'email_activation' == get_option( 'b3_registration_type', false ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_email_activation_subject_user', b3_get_email_activation_subject_user() );

                $user_email = apply_filters( 'b3_email_activation_message_user', b3_get_email_activation_message_user() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars( array( 'user_data' => $user ), true ) );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;

            } elseif ( 'open' == get_option( 'b3_registration_type', false ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );

                $user_email = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars( array( 'user_data' => $user ) ) );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;

            } else {
                error_log( 'OOPS' );
            }

        }
        if ( true == $send_manual_mail ) {
            // @TODO: create email message for manual adding of user
            $wp_new_user_notification_email[ 'to' ]      = $user->user_email;
            $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
            $wp_new_user_notification_email[ 'message' ] = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );
        }

        return $wp_new_user_notification_email;

    }
    add_filter( 'wp_new_user_notification_email', 'b3_new_user_notification_email', 10, 3 );


    /**
     * Returns the message subject for the password reset mail.
     *
     * @since 1.0.6
     *
     * @param $subject
     * @param $user_login
     * @param $user_data
     *
     * @return mixed
     */
    function b3_replace_retrieve_password_subject( $subject, $user_login, $user_data ) {

        $b3_forgot_password_subject = get_option( 'b3_forgot_password_subject', false );
        if ( false != $b3_forgot_password_subject ) {
            $subject = $b3_forgot_password_subject;
        } else {
            $subject = b3_default_forgot_password_subject();
        }

        return $subject;

    }
    add_filter( 'retrieve_password_title', 'b3_replace_retrieve_password_subject', 10, 3 );


    /**
     * Returns the message body for the password reset mail.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    function b3_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {

        $b3_forgot_password_message = get_option( 'b3_forgot_password_message', false );
        if ( false != $b3_forgot_password_message ) {
            $message = $b3_forgot_password_message;
        } else {
            $message = b3_default_forgot_password_message();
        }

        $vars = [
            'reset_url' => network_site_url( "wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode( $user_data->user_login ), 'login' ) . "\r\n\r\n",
        ];
        $message = b3_replace_template_styling( $message );
        $message = strtr( $message, b3_replace_email_vars( $vars ) );

        return $message;

    }
    add_filter( 'retrieve_password_message', 'b3_replace_retrieve_password_message', 10, 4 );


    /**
     * Check for errors on Wordpress' own registration form
     *
     * @param $errors
     * @param $sanitized_user_login
     * @param $user_email
     *
     * @return mixed
     */
    function b3_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( 1 == get_option( 'b3_first_last_required', false ) ) {
            if ( empty( $_POST[ 'first_name' ] ) || ! empty( $_POST[ 'first_name' ] ) && trim( $_POST[ 'first_name' ] ) == '' ) {
                $errors->add( 'first_name_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'You must include a first name.', 'b3-onboarding' ) ) );
            }

            if ( empty( $_POST[ 'last_name' ] ) || ! empty( $_POST[ 'last_name' ] ) && trim( $_POST[ 'last_name' ] ) == '' ) {
                $errors->add( 'last_name_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'You must include a last name.', 'b3-onboarding' ) ) );
            }
        }
        if ( 1 == get_option( 'b3_recaptcha', false ) ) {
            $b3ob = new B3Onboarding();
            if ( ! $b3ob->b3_verify_recaptcha() ) {
                $errors->add( 'recaptcha_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'Recaptcha failed.', 'b3-onboarding' ) ) );
            }
        }

        $privacy_error = b3_verify_privacy();
        if ( true == $privacy_error ) {
            $errors->add( 'no_privacy', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'You have to accept the privacy statement.', 'b3-onboarding' ) ) );

            return $errors;
        }

        return $errors;
    }
    add_filter( 'registration_errors', 'b3_registration_errors', 10, 3 );


    /**
     * Add post states for B3 pages
     *
     * @param $post_states
     * @param $post
     *
     * @return mixed
     */
    function b3_add_post_state( $post_states, $post ) {

        $title_suffix = false;
        if ( $post->ID == get_option( 'b3_account_page_id', false ) ) {
            if ( $post->post_title == 'Account' ) {
                $title_suffix = ': Account';
            }
            $post_states[] = 'B3' . $title_suffix;
        } elseif ( $post->ID == get_option( 'b3_register_page_id', false ) ) {
            if ( $post->post_title == 'Register' ) {
                $title_suffix = ': Register';
            }
            $post_states[] = 'B3' . $title_suffix;
        } elseif ( $post->ID == get_option( 'b3_login_page_id', false ) ) {
            if ( $post->post_title == 'Login' ) {
                $title_suffix = ': Login';
            }
            $post_states[] = 'B3' . $title_suffix;
        } elseif ( $post->ID == get_option( 'b3_logout_page_id', false ) ) {
            if ( $post->post_title == 'Log Out' ) {
                $title_suffix = ': Log out';
            }
            $post_states[] = 'B3' . $title_suffix;
        } elseif ( $post->ID == get_option( 'b3_forgotpass_page_id', false ) ) {
            if ( $post->post_title == 'Forgot password' ) {
                $title_suffix = ': Forgot password';
            }
            $post_states[] = 'B3' . $title_suffix;
        } elseif ( $post->ID == get_option( 'b3_resetpass_page_id', false ) ) {
            if ( $post->post_title == 'Reset Password' ) {
                $title_suffix = ': Reset password';
            }
            $post_states[] = 'B3' . $title_suffix;
        } elseif ( $post->ID == get_option( 'b3_approval_page_id', false ) ) {
            if ( $post->post_title == 'User approval' ) {
                $title_suffix = ': User approval';
            }
            $post_states[] = 'B3' . $title_suffix;
        }

        return $post_states;
    }
    add_filter( 'display_post_states', 'b3_add_post_state', 10, 2 );


    /**
     * Adds nonce to log out page link
     *
     * @param $permalink
     * @param $post_id
     *
     * @return string
     */
    function b3_logout_link( $permalink, $post_id ) {
        if ( b3_get_logout_url( true ) == $post_id ) {
            $permalink = add_query_arg( '_wpnonce', wp_create_nonce( 'log-out' ), $permalink );
        }

        return $permalink;
    }
    add_filter( 'page_link', 'b3_logout_link', 10, 2 );

    /**
     * Style recovery mail (not in use yet)
     *
     * @since 2.0.0
     *
     * @param $email
     * @param $url
     *
     * @return string
     */
    function b3_recovery_mail( $email, $url ) {

        $email = b3_replace_template_styling( $email );
        $email = strtr( $email, b3_replace_email_vars() );
        $email = htmlspecialchars_decode( stripslashes( $email ) );

        return $email;
    }
    // add_filter( 'recovery_mode_email', 'b3_recovery_mail', 5, 2 );
