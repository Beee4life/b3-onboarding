<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Copied from wp-login.php since we bypass it and can't hook in/piggyback on the function in this file.
     *
     * @return bool|int|string|WP_Error
     */
    function b3_retrieve_password() {
        $errors    = new WP_Error();
        $user_data = false;

        if ( empty( $_POST[ 'user_login' ] ) || ! is_string( $_POST[ 'user_login' ] ) ) {
            $errors->add( 'empty_username', sprintf( '<strong>%s</strong>: %s.', esc_html__( 'Error', 'b3-onboarding' ), esc_html__( 'Enter a username or email address', 'b3-onboarding' ) ) );
        } elseif ( strpos( $_POST['user_login'], '@' ) ) {
            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
            if ( empty( $user_data ) ) {
                $errors->add( 'invalid_email', sprintf( '<strong>%s</strong>: %s.', esc_html__( 'Error', 'b3-onboarding' ), esc_html__( 'There is no account with that username or email address', 'b3-onboarding' ) ) );
            }
        } else {
            $login     = trim( wp_unslash( $_POST['user_login'] ) );
            $user_data = get_user_by( 'login', $login );
        }

        /**
         * Fires before errors are returned from a password reset request.
         *
         * @since 2.1.0
         * @since 4.4.0 Added the `$errors` parameter.
         * @since 5.4.0 Added the `$user_data` parameter.
         *
         * @param WP_Error $errors A WP_Error object containing any errors generated
         *                         by using invalid credentials.
         * @param WP_User|false    WP_User object if found, false if the user does not exist.
         */
        do_action( 'lostpassword_post', $errors, $user_data );

        if ( $errors->has_errors() ) {
            return $errors;
        }

        if ( ! $user_data ) {
            $errors->add( 'invalidcombo', sprintf( '<strong>%s</strong>: %s.', esc_html__( 'Error', 'b3-onboarding' ), esc_html__( 'There is no account with that username or email address', 'b3-onboarding' ) ) );
            return $errors;
        }

        // Redefining user_login ensures we return the right case in the email.
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key        = get_password_reset_key( $user_data );

        if ( is_wp_error( $key ) ) {
            return $key;
        }

        if ( is_multisite() ) {
            $site_name = get_network()->site_name;
        } else {
            /*
             * The blogname option is escaped with esc_html on the way into the database
             * in sanitize_option we want to reverse this for the plain text arena of emails.
             */
            $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        }

        $message = esc_html__( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
        /* translators: %s: Site name. */
        $message .= sprintf( esc_html__( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
        /* translators: %s: User login. */
        $message .= sprintf( esc_html__( 'Username: %s' ), $user_login ) . "\r\n\r\n";
        $message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
        $message .= esc_html__( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
        $message .= network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n";

        /* translators: Password reset notification email subject. %s: Site title. */
        $title = sprintf( __( '[%s] Password Reset' ), $site_name );


        /**
         * Filters the subject of the password reset email.
         *
         * @since 2.8.0
         * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
         *
         * @param string  $title      Default email title.
         * @param string  $user_login The username for the user.
         * @param WP_User $user_data  WP_User object.
         */
        $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

        /**
         * Filters the message body of the password reset mail.
         *
         * If the filtered message is empty, the password reset email will not be sent.
         *
         * @since 2.8.0
         * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
         *
         * @param string  $message    Default mail message.
         * @param string  $key        The activation key.
         * @param string  $user_login The username for the user.
         * @param WP_User $user_data  WP_User object.
         */
        $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

        if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
            $errors->add(
                'retrieve_password_email_failure',
                sprintf( '<strong>%s</strong>: %s',
                    esc_html__( 'Error', 'b3-onboarding' ),
                    sprintf( __( 'The email could not be sent. Your site may not be correctly configured to send emails. %s.' ),
                        sprintf( '<a href="%s">%s</a>',
                            esc_url( 'https://wordpress.org/support/article/resetting-your-password/' ),
                            esc_html__( 'Get support for resetting your password', 'b3-onboarding' ) ) )
                )
            );
            
            return $errors;
        }

        return true;
    }
