<?php

    /**
     * Disable admin notification on password change
     *
     * @since 2.0.0
     */
    if ( 1 == get_site_option( 'b3_disable_admin_notification_password_change', false ) ) {
        add_filter( 'wp_password_change_notification_email', '__return_false' );
    } else {
        add_filter( 'wp_password_change_notification_email', 'b3_password_changed_email_admin', 10, 3 );
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
    function b3_password_changed_email_admin( $wp_password_change_notification_email, $user, $blogname ) {
        $message = sprintf( esc_html__( 'Password changed for user: %s', 'b3-onboarding' ), $user->user_login ); // default: Password changed for user: {username}
        $message = b3_replace_template_styling( $message );
        $message = strtr( $message, b3_replace_email_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );
        $subject = __( 'User changed password', 'b3-onboarding' ); // default: [blog name] Password changed

        $wp_password_change_notification_email[ 'subject' ] = $subject;
        $wp_password_change_notification_email[ 'message' ] = $message;

        return $wp_password_change_notification_email;

    }

    /**
     * Filter password change notification mail (user)
     *
     * @since 2.3.0
     *
     * @param $change_email
     * @param $user
     * @param $userdata
     *
     * @return mixed
     */
    function b3_password_changed_email_user( $change_email, $user, $userdata ) {
        if ( true == get_site_option( 'b3_register_email_only' ) ) {
            $new_message = 'Hi,';
        } else {
            $new_message = 'Hi ###USERNAME###,';
        }
        $new_message               .= '<br /><br />';
        $new_message               .= 'This notice confirms that your email address on ###SITENAME### was changed to ###NEW_EMAIL### from ###EMAIL###.';
        $new_message               .= '<br /><br />';
        $new_message               .= 'If you did not change your email, please contact the site administrator at ###ADMIN_EMAIL###';
        $new_message               .= '<br /><br />';
        $new_message               .= __( 'Greetings', 'b3-onboarding' ) . ',';
        $new_message               .= '<br /><br />';
        $new_message               .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) );
        $new_message               = b3_replace_template_styling( $new_message );
        $new_message               = strtr( $new_message, b3_replace_email_vars() );
        $change_email[ 'message' ] = $new_message;

        return $change_email;

    }
    add_filter( 'email_change_email', 'b3_password_changed_email_user', 5, 3 );

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

            if ( false != get_site_option( 'b3_disable_admin_notification_new_user', false ) ) {
                return false;
            }

            $registration_type = get_site_option( 'b3_registration_type', false );

            if ( 'request_access' == $registration_type ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_request_access_subject_admin', b3_get_request_access_subject_admin() );

                $admin_email = apply_filters( 'b3_request_access_message_admin', b3_get_request_access_message_admin() );
                $admin_email = b3_replace_template_styling( $admin_email );
                $admin_email = strtr( $admin_email, b3_replace_email_vars() );
                $admin_email = htmlspecialchars_decode( stripslashes( $admin_email ) );

                $wp_new_user_notification_email_admin[ 'message' ] = $admin_email;

            } elseif ( in_array( $registration_type, [ 'email_activation' ] ) ) {
                // we don't want the email when a user registers, but only when he/she activates
                return false;

            } elseif ( in_array( $registration_type, [ 'open' ] ) ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject() );

                $admin_email = apply_filters( 'b3_new_user_message', b3_get_new_user_message() );
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

        if ( isset( $_POST[ 'action' ] ) && 'createuser' == $_POST[ 'action' ] ) {
            // user is manually added
            if ( isset( $_POST[ 'send_user_notification' ] ) && 1 == $_POST[ 'send_user_notification' ] ) {
                // user must get AN email, from WP or custom
                $wp_new_user_notification_email[ 'to' ]      = $user->user_email;
                $wp_new_user_notification_email[ 'headers' ] = [];
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );

                $user_email = apply_filters( 'b3_manual_welcome_user_message', b3_get_manual_welcome_user_message() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars( array( 'user_data' => $user ) ) );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;
            }
        }

        if ( true == $send_custom_mail ) {
            $wp_new_user_notification_email[ 'to' ]      = $user->user_email;
            $wp_new_user_notification_email[ 'headers' ] = [];
            if ( 'request_access' == get_site_option( 'b3_registration_type', false ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_request_access_subject_user', b3_get_request_access_subject_user() );

                $user_email = apply_filters( 'b3_request_access_message_user', b3_get_request_access_message_user() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars() );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;

            } elseif ( 'email_activation' == get_site_option( 'b3_registration_type', false ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_email_activation_subject_user', b3_get_email_activation_subject_user() );

                $user_email = apply_filters( 'b3_email_activation_message_user', b3_get_email_activation_message_user() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars( array( 'user_data' => $user ), true ) );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;

            } elseif ( 'open' == get_site_option( 'b3_registration_type', false ) ) {

                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );

                $user_email = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );
                $user_email = b3_replace_template_styling( $user_email );
                $user_email = strtr( $user_email, b3_replace_email_vars( array( 'user_data' => $user ) ) );
                $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );

                $wp_new_user_notification_email[ 'message' ] = $user_email;

            }

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

        $b3_lost_password_subject = apply_filters( 'b3_lost_password_subject', b3_get_lost_password_subject() );
        if ( false != $b3_lost_password_subject ) {
            $subject = $b3_lost_password_subject;
        }

        return $subject;

    }
    add_filter( 'retrieve_password_title', 'b3_replace_retrieve_password_subject', 10, 3 );


    /**
     * Returns the message body for the password reset mail.
     *
     * @since 1.0.6
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    function b3_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {

        $lost_password_message = apply_filters( 'b3_lost_password_message', b3_get_lost_password_message() );
        if ( false != $lost_password_message ) {
            $message = $lost_password_message;
        }

        // get reset pass id
        $reset_pass_url      = b3_get_reset_password_url();
        $vars[ 'reset_url' ] = $reset_pass_url . "?action=rp&key=" . $key . "&login=" . rawurlencode( $user_data->user_login ) . "\r\n\r\n";
        $message             = b3_replace_template_styling( $message );
        $message             = htmlspecialchars_decode( stripslashes( strtr( $message, b3_replace_email_vars( $vars ) ) ) );

        return $message;

    }
    add_filter( 'retrieve_password_message', 'b3_replace_retrieve_password_message', 10, 4 );


    /**
     * Check for errors on WordPress' own registration form
     *
     * @since 1.0.0
     *
     * @param $errors
     * @param $sanitized_user_login
     * @param $user_email
     *
     * @return mixed
     */
    function b3_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( 1 == get_site_option( 'b3_first_last_required', false ) ) {
            if ( empty( $_POST[ 'first_name' ] ) || ! empty( $_POST[ 'first_name' ] ) && trim( $_POST[ 'first_name' ] ) == '' ) {
                $errors->add( 'first_name_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'You must include a first name.', 'b3-onboarding' ) ) );
            }

            if ( empty( $_POST[ 'last_name' ] ) || ! empty( $_POST[ 'last_name' ] ) && trim( $_POST[ 'last_name' ] ) == '' ) {
                $errors->add( 'last_name_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'You must include a last name.', 'b3-onboarding' ) ) );
            }
        }
        if ( 1 == get_site_option( 'b3_activate_recaptcha', false ) ) {
            $b3ob = new B3Onboarding();
            if ( ! $b3ob->b3_verify_recaptcha() ) {
                $errors->add( 'recaptcha_error', sprintf( '<strong>%s</strong>: %s', __( 'ERROR', 'b3-onboarding' ), __( 'Recaptcha failed.', 'b3-onboarding' ) ) );
            }
        }

        $extra_field_errors = apply_filters( 'b3_extra_fields_validation', [] );
        if ( ! empty( $extra_field_errors ) ) {
            $errors->add( $extra_field_errors[ 'error_code' ], $extra_field_errors[ 'error_message' ] );
            $errors->add( 'field_' . $extra_field_errors[ 'id' ], '' );

            return $errors;
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
     * @since 1.0.6
     *
     * @param $post_states
     * @param $post
     *
     * @return mixed
     */
    function b3_add_post_state( $post_states, $post ) {

        if ( $post->ID == get_site_option( 'b3_account_page_id', false ) ) {
            $post_states[] = 'B3 : Account';
        } elseif ( $post->ID == get_site_option( 'b3_register_page_id', false ) ) {
            $post_states[] = 'B3 : Register';
        } elseif ( $post->ID == get_site_option( 'b3_login_page_id', false ) ) {
            $post_states[] = 'B3 : Login';
        } elseif ( $post->ID == get_site_option( 'b3_logout_page_id', false ) ) {
            $post_states[] = 'B3 : Log out';
        } elseif ( $post->ID == get_site_option( 'b3_lost_password_page_id', false ) ) {
            $post_states[] = 'B3 : Lost password';
        } elseif ( $post->ID == get_site_option( 'b3_reset_password_page_id', false ) ) {
            $post_states[] = 'B3 : Reset password';
        } elseif ( $post->ID == get_site_option( 'b3_approval_page_id', false ) ) {
            $post_states[] = 'B3 : User approval';
        }

        return $post_states;

    }
    add_filter( 'display_post_states', 'b3_add_post_state', 10, 2 );


    /**
     * Adds nonce to log out page link
     *
     * @since 1.0.0
     *
     * @param $permalink
     * @param $post_id
     *
     * @return string
     */
    function b3_logout_link( $permalink, $post_id ) {
        if ( b3_get_logout_url( true ) == $post_id ) {
            $permalink = add_query_arg( '_wpnonce', wp_create_nonce( 'logout' ), $permalink );
        }

        return $permalink;

    }
    add_filter( 'page_link', 'b3_logout_link', 10, 2 );


    /**
     * Filters message on default register form
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function wp_login_message( $message ) {

        if ( isset( $_GET[ 'action' ] ) ) {
            $action = $_GET[ 'action' ];
            if ( 'register' == $action ) {
                $message = apply_filters( 'b3_message_above_registration', b3_get_message_above_registration() );
            } elseif ( 'lostpassword' == $action ) {
                $message = apply_filters( 'b3_message_above_lost_password', b3_get_message_above_lost_password() );
            }
        } else {
            $message = apply_filters( 'b3_message_above_login', b3_get_message_above_login() );
        }

        if ( ! empty( $message ) ) {
            $message = '<p class="message">' . $message . '</p>';
        }

        return $message;

    }
    add_filter( 'login_message', 'wp_login_message' );


    /**
     * Check if user may login, if he/she has a custom role.
     *
     * @since 2.4.0
     *
     * @param $user
     * @param $password
     *
     * @return WP_Error
     */
    function b3_login_errors( $user, $password ) {
        if ( $user ) {
            if ( in_array( 'b3_activation', $user->roles ) ) {
                return new WP_Error( 'wait_confirmation', '' );
            } elseif ( in_array( 'b3_approval', $user->roles ) ) {
                return new WP_Error( 'wait_approval', '' );
            }
        }

        return $user;

    }
    add_filter( 'wp_authenticate_user', 'b3_login_errors', 20, 2 );

    /**
     * Change content of password changed email
     * Not in use yet, prepare for coming setting/filter
     *
     * @param $pass_change_email
     * @param $user
     * @param $userdata
     *
     * @return array|bool
     */
    function b3_content_password_change_notification( $pass_change_email, $user, $userdata ) {

        // if admin disabled notification option
        if ( true == get_site_option( 'b3_disable_password_change_email' ) ) {
            return false;
        }

        // @TODO: add if for email only registration

        $pass_change_text = __(
            'Hi ###USERNAME###,

This notice confirms that your password was changed on ###SITENAME###.

If you did not change your password, please contact the Site Administrator at
###ADMIN_EMAIL###

This email has been sent to ###EMAIL###.

Regards,
All at ###SITENAME###
###SITEURL###', 'b3-onboarding'
        );

        $pass_change_email = array(
            'to'      => $user[ 'user_email' ],
            /* translators: Password change notification email subject. %s: Site title. */
            'subject' => __( '[%s] Password Changed' ),
            'message' => $pass_change_text,
            'headers' => '',
        );

        return $pass_change_email;
    }
    add_filter( 'password_change_email', 'b3_content_password_change_notification', 10, 3 );


    /**
     * Change content of email address changed email
     * Not in use yet, prepare for coming setting/filter
     *
     * @param $email_change_email
     * @param $user
     * @param $userdata
     *
     * @return array|bool
     */
    function b3_content_email_change_notification( $email_change_email, $user, $userdata ) {

        // if admin disabled notification option
        // option doesn't exist in admin (yet)

        // @TODO: add if for no email setting
        // @TODO: add if for email only registration

        $pass_change_text = __(
            'Hi ###USERNAME###,

This notice confirms that your email address on ###SITENAME### was changed to ###NEW_EMAIL###.

If you did not change your email, please contact the Site Administrator at
###ADMIN_EMAIL###

This email has been sent to ###EMAIL###.

Regards,
All at ###SITENAME###
###SITEURL###', 'b3-onboarding'
        );

        $email_change_email = array(
            'to'      => $user[ 'user_email' ],
            /* translators: Password change notification email subject. %s: Site title. */
            'subject' => __( '[%s] Password Changed' ),
            'message' => $pass_change_text,
            'headers' => '',
        );

        return $email_change_email;
    }
    add_filter( 'email_change_email', 'b3_content_email_change_notification', 10, 3 );


    /**
     * Prevent update registration option
     *
     * @param $new_value
     * @param $old_value
     *
     * @return false|mixed|string|void
     */
    function b3_prevent_update_registration_option( $new_value, $old_value ) {
        $b3_setting = get_site_option( 'b3_registration_type' );
        if ( is_multisite() && is_main_site() ) {
            if ( 'closed' == $b3_setting ) {
                $b3_setting = 'none';
            } elseif ( 'ms_register_user' == $b3_setting ) {
                $b3_setting = 'user';
            } elseif ( 'ms_loggedin_register' == $b3_setting ) {
                $b3_setting = 'blog';
            } elseif ( 'ms_register_site_user' == $b3_setting ) {
                $b3_setting = 'all';
            } else {
                $b3_setting = 'all';
            }
        } elseif ( ! is_multisite() ) {
            if ( 'closed' == $b3_setting ) {
                $b3_setting = '0';
            } else {
                $b3_setting = '1';
            }
        }

        $new_value = $b3_setting;

        return $new_value;

    }
    add_filter( 'pre_update_option_users_can_register', 'b3_prevent_update_registration_option', 10, 2 ); // non-multissite
    add_filter( 'pre_update_site_option_registration', 'b3_prevent_update_registration_option', 10, 2 ); // multisite


    /**
     * Prevent update setting to inform admins for new users
     *
     * @param $new_value
     * @param $old_value
     *
     * @return string
     */
    function b3_prevent_update_registration_notification_option( $new_value, $old_value ) {
        return 'no';
    }
    add_filter( 'pre_update_site_option_registrationnotification', 'b3_prevent_update_registration_notification_option', 10, 2 );


    /**
     * Disable WPMU user signup email to take it over
     *
     * @TODO: check if vars can be removed
     *
     * @param       $user_login
     * @param       $user_email
     * @param       $key
     * @param array $meta
     *
     * @return false
     */
    function b3_disable_wpmu_user_signup_notification( $user_login, $user_email, $key, $meta = array() ) {
        return false;
    }
    add_filter( 'wpmu_signup_user_notification', 'b3_disable_wpmu_user_signup_notification', 10, 5 );


    /**
     * Disable WPMU user welcome email to take it over
     *
     * @TODO: check if vars can be removed
     *
     * @param $user_id
     * @param $password
     * @param $meta
     *
     * @return false
     */
    function b3_disable_welcome_mu_user_email( $user_id, $password, $meta ) {
        return false;
    }
    add_filter( 'wpmu_welcome_user_notification', 'b3_disable_welcome_mu_user_email', 10, 3 );


    /**
     * Disable email for register site + user
     *
     * @return false
     */
    function b3_disable_signup_mu_user_blog_email() {
        return false;
    }
    add_filter( 'wpmu_signup_blog_notification', 'b3_disable_signup_mu_user_blog_email' );


    function b3_disable_welcome_mu_user_blog_email( $blog_id, $user_id, $password, $title, $meta ) {
        return false;
    }
    add_filter( 'wpmu_welcome_notification', 'b3_disable_welcome_mu_user_blog_email', 10, 5 );
