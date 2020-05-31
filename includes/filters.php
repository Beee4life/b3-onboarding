<?php

    /**
     * Filter lost password URL
     *
     * @param $lostpassword_url
     * @param $redirect
     *
     * @return false|mixed|string
     */
    function b3_lost_password_page_url( $lostpassword_url, $redirect ) {

        $lost_password_page_id = b3_get_forgotpass_id();
        if ( false != $lost_password_page_id ) {
            $lost_pass_url = esc_url( get_permalink( $lost_password_page_id ) );
            if ( class_exists( 'SitePress' ) ) {
                $lost_pass_url = esc_url( get_permalink( apply_filters( 'wpml_object_id', $lost_password_page_id, 'page', true ) ) );
            }
            if ( false != $redirect ) {
                return $lost_pass_url . '?redirect_to=' . $redirect;
            }

            return $lost_pass_url;

        }

        return $lostpassword_url;
    }
    // add_filter( 'lostpassword_url', 'b3_lost_password_page_url', 10, 2 );


    /**
     * Override new user notification for admin
     *
     * @param $wp_new_user_notification_email_admin
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_new_user_notification_email_admin( $wp_new_user_notification_email_admin, $user, $blogname ) {

        if ( isset( $_POST[ 'action' ] ) && 'createuser' == $_POST[ 'action' ] ) {
            // manually added
            $wp_new_user_notification_email_admin = false;
        } else {
            if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( get_option( 'b3_registration_type' ) ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_request_access_subject_admin', b3_request_access_subject_admin() );
                $wp_new_user_notification_email_admin[ 'message' ] = apply_filters( 'b3_request_access_message_admin', b3_request_access_message_admin() );

            } elseif ( 'open' == get_option( 'b3_registration_type' ) ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( get_option( 'b3_registration_type' ) ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject( $blogname ) );
                $wp_new_user_notification_email_admin[ 'message' ] = apply_filters( 'b3_new_user_mesage', b3_get_new_user_message( $blogname, $user ) );
            }
        }

        return $wp_new_user_notification_email_admin;

    }
    add_filter( 'wp_new_user_notification_email_admin', 'b3_new_user_notification_email_admin', 9, 3 );


    /**
     * Override new user notification email for user
     *
     * @param $wp_new_user_notification_email
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {

        $send_custom_mail = true;
        if ( isset( $_POST[ 'action' ] ) && 'createuser' == $_POST[ 'action' ] ) {
            // manually added
            if ( isset( $_POST[ 'send_user_notification' ] ) && 1 == $_POST[ 'send_user_notification' ] ) {
                // user must get AN email
                $send_custom_mail               = false;
                $send_manual_mail               = true;
                $wp_new_user_notification_email = false;
            } else {
                $send_custom_mail = false;
            }
        }
        if ( true == $send_custom_mail ) {
            $wp_new_user_notification_email[ 'to' ] = $user->user_email;
            if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_request_access_subject', b3_request_access_subject_user() );
                $wp_new_user_notification_email[ 'message' ] = apply_filters( 'b3_request_access_message', b3_request_access_message_user() );

            } elseif ( 'email_activation' == get_option( 'b3_registration_type' ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_email_activation_subject', b3_get_email_activation_subject( $blogname ) );
                $wp_new_user_notification_email[ 'message' ] = apply_filters( 'b3_email_activation_message', b3_get_email_activation_message( $blogname, $user ) );

            } elseif ( 'open' == get_option( 'b3_registration_type' ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject( $blogname ) );
                $wp_new_user_notification_email[ 'message' ] = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message( $blogname, $user ) );

            } else {
                error_log( 'OOPS' );
            }

        }
        if ( true == $send_manual_mail ) {
            // @TODO: create email template for manual adding of user
        }

        return $wp_new_user_notification_email;

    }
    add_filter( 'wp_new_user_notification_email', 'b3_new_user_notification_email', 10, 3 );


    /**
     * Returns the message subject for the password reset mail.
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
            return $b3_forgot_password_subject;
        }

        return b3_default_forgot_password_subject();

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
            $message = b3_default_forgot_password_message( $key, $user_login );
        }

        if ( false != get_option( 'b3_custom_emails', false ) ) {
            $message = b3_replace_template_styling( $message );
        }

        // replace email variables
        $vars = [
            'reset_url' => network_site_url( "wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode( $user_data->user_login ), 'login' ) . "\r\n\r\n",
        ];
        $message = strtr( $message, b3_replace_email_vars( $vars ) );

        return $message;
    }
    add_filter( 'retrieve_password_message', 'b3_replace_retrieve_password_message', 10, 4 );

    /**
     * Redirect user after successful login.
     *
     * @param string $redirect_to URL to redirect to.
     * @param string $request URL the user is coming from.
     * @param object $user Logged user's data.
     * @return string
     */
    function b3_login_redirect( $redirect_to, $request, $user ) {
        // is there a user to check?
        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            // check for users who are not allowed
            $stored_roles = ( is_array( get_option( 'b3_restrict_admin' ) ) ) ? get_option( 'b3_restrict_admin' ) : [ 'subscriber' ];

            if ( in_array( $stored_roles, $user->roles ) ) {
                // redirect them to another URL
                $login_id = b3_get_login_id();
                if ( false != $login_id ) {
                    $redirect_to = get_permalink( $login_id );
                }
            }
        }

        return $redirect_to;
    }
    add_filter( 'login_redirect', 'b3_login_redirect', 10, 3 );

    /**
     * Redirect after logout
     *
     * @param $redirect_to
     * @param $request
     * @param $user
     *
     * @return string
     */
    function b3_logout_redirect( $redirect_to, $request, $user ) {

        // Make sure we're not trying to redirect to an admin URL
        if ( false !== strpos( $redirect_to, 'wp-admin' ) ) {
            $redirect_to = add_query_arg( 'loggedout', 'true', wp_login_url() );
        }

        // Return the redirect URL for the user
        return $redirect_to;
    }
    add_filter( 'logout_redirect', 'b3_logout_redirect', 10, 3 );


    /**
     * Return account approved subject
     *
     * @return string|void
     */
    function b3_account_approved_subject() {

        $subject = __( 'Account approved', 'b3-onboarding' );
        // get from db
        // if false return default

        return $subject;
    }
    add_filter( 'b3_account_approved_subject', 'b3_account_approved_subject' );

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

        if ( 1 == get_option( 'b3_first_last_required' ) ) {
            if ( empty( $_POST[ 'first_name' ] ) || ! empty( $_POST[ 'first_name' ] ) && trim( $_POST[ 'first_name' ] ) == '' ) {
                $errors->add( 'first_name_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onbaording' ), __( 'You must include a first name.', 'b3-onbaording' ) ) );
            }

            if ( empty( $_POST[ 'last_name' ] ) || ! empty( $_POST[ 'last_name' ] ) && trim( $_POST[ 'last_name' ] ) == '' ) {
                $errors->add( 'last_name_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onbaording' ), __( 'You must include a last name.', 'b3-onbaording' ) ) );
            }
        }

        return $errors;
    }
    add_filter( 'registration_errors', 'b3_registration_errors', 10, 3 );
