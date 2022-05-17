<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Disable/filter password change notification mail (admin)
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
        $message = strtr( $message, b3_get_replacement_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );
        $subject = __( 'User changed password', 'b3-onboarding' ); // default: [blog name] Password changed

        $wp_password_change_notification_email[ 'subject' ] = $subject;
        $wp_password_change_notification_email[ 'message' ] = $message;

        if ( 1 == get_option( 'b3_disable_admin_notification_password_change' ) ) {
            $wp_password_change_notification_email = [
                'to'      => false,
                'subject' => false,
                'message' => false,
                'headers' => false,
            ];
        }

        return $wp_password_change_notification_email;
    }
    add_filter( 'wp_password_change_notification_email', 'b3_password_changed_email_admin', 10, 3 );


    /**
     * Filter email change notification mail (user)
     *
     * @since 2.3.0
     *
     * @param $change_email
     * @param $user
     * @param $userdata
     *
     * @return mixed
     */
    function b3_email_changed_email_user( $change_email, $user, $userdata ) {
        if ( true == get_option( 'b3_register_email_only' ) ) {
            $new_message = 'Hi,';
        } else {
            $new_message = 'Hi ###USERNAME###,';
        }
        $new_message               .= '<br><br>';
        $new_message               .= 'This notice confirms that your email address on ###SITENAME### was changed to ###NEW_EMAIL### from ###EMAIL###.';
        $new_message               .= '<br><br>';
        $new_message               .= 'If you did not change your email, please contact the site administrator at ###ADMIN_EMAIL###.';
        $new_message               .= b3_default_greetings();
        $new_message               = b3_replace_template_styling( $new_message );
        $new_message               = strtr( $new_message, b3_get_replacement_vars() );
        $change_email[ 'message' ] = $new_message;

        return $change_email;
    }
    add_filter( 'email_change_email', 'b3_email_changed_email_user', 5, 3 );


    /**
     * Override new user notification for admin
     *
     * Filter: wp_new_user_notification_email_admin
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
        if ( isset( $_POST[ '_wp_http_referer' ] ) && ( strpos( $_POST[ '_wp_http_referer' ], 'user-new.php' ) !== false || strpos( $_POST[ '_wp_http_referer' ], 'site-new.php' ) !== false ) ) {
            $wp_new_user_notification_email_admin[ 'to' ] = '';

        } else {
            $admin_email       = false;
            $registration_type = get_option( 'b3_registration_type' );

            if ( false != get_option( 'b3_disable_admin_notification_new_user' ) || in_array( $registration_type, [ 'email_activation' ] ) ) {
                // we don't want the email when a user registers, but only when he/she activates
                $wp_new_user_notification_email_admin[ 'to' ] = '';

            } elseif ( 'request_access' == $registration_type ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_request_access_subject_admin', b3_get_request_access_subject_admin() );
                $admin_email = apply_filters( 'b3_request_access_message_admin', b3_get_request_access_message_admin() );

            } elseif ( in_array( $registration_type, array( 'open' ) ) ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject() );
                $admin_email = apply_filters( 'b3_new_user_message', b3_get_new_user_message() );

            } elseif ( in_array( $registration_type, array( 'blog' ) ) ) {
                $wp_new_user_notification_email_admin[ 'to' ]      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( $registration_type ) );
                $wp_new_user_notification_email_admin[ 'subject' ] = apply_filters( 'b3_new_wpmu_user_subject_admin', b3_get_new_wpmu_user_subject_admin() );
                $admin_email = apply_filters( 'b3_new_wpmu_user_message_admin', b3_get_new_wpmu_user_message_admin() );

            }
            if ( false != $admin_email ) {
                $admin_email = b3_replace_template_styling( $admin_email );
                $admin_email = strtr( $admin_email, b3_get_replacement_vars( 'message', [ 'user_data' => $user ] ) );
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
     * Filter: wp_new_user_notification_email
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
        $registration_type = get_option( 'b3_registration_type' );

        if ( isset( $_POST[ '_wp_http_referer' ] ) ) {
            // user is manually added
            if ( strpos( $_POST[ '_wp_http_referer' ], 'user-new.php' ) !== false ) {
                if ( isset( $_POST[ 'send_user_notification' ] ) && 1 == $_POST[ 'send_user_notification' ] ) {
                    // user must get AN email, from WP or custom
                    $wp_new_user_notification_email[ 'to' ]      = $user->user_email;
                    $wp_new_user_notification_email[ 'headers' ] = array();
                    $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                    $user_email = apply_filters( 'b3_welcome_user_message_manual', b3_get_manual_welcome_user_message() );
                }
            } elseif ( strpos( $_POST[ '_wp_http_referer' ], 'site-new.php' ) !== false ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                $user_email = apply_filters( 'b3_welcome_user_message_manual', b3_get_manual_welcome_user_message() );
            }

        } else {
            $wp_new_user_notification_email[ 'to' ]      = $user->user_email;
            $wp_new_user_notification_email[ 'headers' ] = array();

            if ( 'request_access' == $registration_type ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_request_access_subject_user', b3_get_request_access_subject_user() );
                $user_email = apply_filters( 'b3_request_access_message_user', b3_get_request_access_message_user() );

            } elseif ( 'email_activation' == $registration_type ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_email_activation_subject_user', b3_get_email_activation_subject_user() );
                $user_email = apply_filters( 'b3_email_activation_message_user', b3_get_email_activation_message_user() );

            } elseif ( 'open' == $registration_type ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                $user_email = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );

            } elseif ( 'blog' == $registration_type ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                $user_email = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );

            } elseif ( 'none' == $registration_type ) {
                $wp_new_user_notification_email[ 'subject' ] = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                $user_email = apply_filters( 'b3_welcome_user_message_manual', b3_get_manual_welcome_user_message() );
            }
        }

        if ( isset( $user_email ) ) {
            $user_email = b3_replace_template_styling( $user_email );
            if ( 'email_activation' == $registration_type ) {
                $user_email = strtr( $user_email, b3_get_replacement_vars( 'message', array( 'user_data' => $user ), true ) );
            } else {
                $user_email = strtr( $user_email, b3_get_replacement_vars( 'message', array( 'user_data' => $user ) ) );
            }

            $user_email = htmlspecialchars_decode( stripslashes( $user_email ) );
            $wp_new_user_notification_email[ 'message' ] = $user_email;
        }

        return $wp_new_user_notification_email;

    }
    add_filter( 'wp_new_user_notification_email', 'b3_new_user_notification_email', 10, 3 );


    /**
     * Disable admin email when registration is closed
     *
     * @param $status
     * @param $site
     * @param $user
     *
     * @since 3.1.0
     *
     * @return false|mixed
     */
    function b3_disable_admin_email( $status, $site, $user ) {
        if ( 'none' == get_option( 'b3_registration_type' ) ) {
            return false;
        }

        return $status;
    }
    add_filter( 'send_new_site_email', 'b3_disable_admin_email', 10, 3 );


    /**
     * Filter to override new site email (New Site Created)
     *
     * @param $new_site_email
     * @param $site
     * @param $user
     *
     * @since 3.1.0
     *
     * @return mixed
     */
    function b3_new_site_email( $new_site_email, $site, $user ) {
        // @TODO: add filter + (maybe) user input for message
        $user_email                  = apply_filters( 'b3_new_site_created_message', b3_get_new_site_created_message() );
        $user_email                  = b3_replace_template_styling( $user_email );
        $user_email                  = strtr( $user_email, b3_get_replacement_vars( 'message', array( 'user_data' => $user, 'site' => $site ) ) );
        $user_email                  = htmlspecialchars_decode( stripslashes( $user_email ) );
        $new_site_email[ 'message' ] = $user_email;

        return $new_site_email;
    }
    add_filter( 'new_site_email', 'b3_new_site_email', 10, 3 );


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

        $reset_pass_url      = b3_get_reset_password_url();
        $vars[ 'reset_url' ] = $reset_pass_url . '?action=rp&key=' . $key . '&login=' . rawurlencode( $user_data->user_login ) . "\r\n\r\n";
        $vars[ 'user_data' ] = $user_data;
        $message             = b3_replace_template_styling( $message );
        $message             = htmlspecialchars_decode( stripslashes( strtr( $message, b3_get_replacement_vars( 'message', $vars ) ) ) );

        return $message;
    }
    add_filter( 'retrieve_password_message', 'b3_replace_retrieve_password_message', 10, 4 );


    /**
     * Change content of password changed email (when user changed, when logged in)
     *
     * @param $pass_change_email
     * @param $user
     * @param $userdata
     *
     * @return array|bool
     */
    function b3_content_password_change_notification( $pass_change_email, $user, $userdata ) {
        // if admin disabled user notification option
        if ( true == get_option( 'b3_disable_user_notification_password_change' ) ) {
            $pass_change_email = array(
                'to'      => false,
                'subject' => false,
                'message' => false,
                'headers' => false,
            );
            return $pass_change_email;
        }

        $salutation = ( true == get_option( 'b3_register_email_only' ) ) ? false : '###USERNAME###';

        $pass_change_text = sprintf( __(
            'Hi %s,
            <br><br>
            This notice confirms that your password was changed on ###SITENAME###.
            <br><br>
            If you did not change your password, please contact the Site Administrator at ###ADMIN_EMAIL###
            <br><br>
            This email has been sent to ###EMAIL###.
            <br><br>
            Regards,
            <br>
            All at ###SITENAME###
            <br>
            ###SITEURL###', 'b3-onboarding'
        ), $salutation );

        $message = b3_replace_template_styling( $pass_change_text );
        $message = strtr( $message, b3_get_replacement_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );

        $pass_change_email = array(
            'to'      => $user[ 'user_email' ],
            /* translators: Password change notification email subject. %s: Site title. */
            'subject' => __( '[%s] Password Changed' ),
            'message' => $message,
            'headers' => '',
        );

        return $pass_change_email;
    }
    add_filter( 'password_change_email', 'b3_content_password_change_notification', 10, 3 );


    /**
     * Disable WPMU user signup email to take it over
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


    /**
     * Disable new user mail with login credentials
     *
     * @param $blog_id
     * @param $user_id
     * @param $password
     * @param $title
     * @param $meta
     *
     * @return false
     */
    function b3_disable_welcome_mu_user_blog_email( $blog_id, $user_id, $password, $title, $meta ) {
        return false;
    }
    add_filter( 'wpmu_welcome_notification', 'b3_disable_welcome_mu_user_blog_email', 10, 5 );


    /**
     * For filter 'wp_mail_from', returns a proper from-address when sending e-mails
     *
     * @param   string $original_email_address
     * @return  string
     */
    function b3_email_from( $original_email_address ) {
        // Make sure the email adress is from the same domain as your website to avoid being marked as spam.
        $from_email = apply_filters( 'b3_notification_sender_email', b3_get_notification_sender_email() );
        if ( false != $from_email ) {
            return $from_email;
        }

        return $original_email_address;
    }
    add_filter( 'wp_mail_from', 'b3_email_from' );


    /**
     * For filter 'wp_mail_from_name', returns a proper from-name when sending e-mails
     *
     * @param   string $original_email_from
     * @return  string
     */
    function b3_email_from_name( $original_from_name ) {
        $sender_name = apply_filters( 'b3_notification_sender_name', b3_get_notification_sender_name() );
        if ( false != $sender_name ) {
            return $sender_name;
        }

        return $original_from_name;
    }
    add_filter( 'wp_mail_from_name', 'b3_email_from_name' );


    /**
     * For filter 'wp_mail_content_type', overrides content-type
     * Always return HTML
     *
     * @return  string
     */
    function b3_email_content_type( $content_type ) {
        return 'text/html';
    }
    add_filter( 'wp_mail_content_type', 'b3_email_content_type' );


    /**
     * Filter to change styling for multiple emails
     *
     * @since 3.7.0
     *
     * @param $email_content
     * @param $new_email
     *
     * @return string
     */
    function b3_confirm_change_email( $email_content, $new_email ) {
        $search  = 'If this is correct, please click on the following link to change it:';
        $replace = 'If this is correct, please click ###HERE### to change it.';
        $email_content = str_replace( $search, $replace, $email_content );
        $email_content = str_replace( '###EMAIL###', '###EMAIL###.', $email_content );
        $email_content = str_replace( '###HERE###', '<a href="###ADMIN_URL###">here</a>', $email_content );
        $email_content = str_replace( "Regards,\n", '', $email_content );
        $email_content = str_replace( "All at ###SITENAME###\n", '', $email_content );
        $email_content = str_replace( "###ADMIN_URL###\n", '', $email_content );
        $email_content = str_replace( "\n###SITEURL###", '', $email_content );
        $email_content = str_replace( "\n", '<br>', $email_content );
        $email_content .= b3_default_greetings();
        $email_content = b3_replace_template_styling( $email_content );
        $email_content = strtr( $email_content, b3_get_replacement_vars() );
        $email_content = htmlspecialchars_decode( stripslashes( $email_content ) );

        return $email_content;
    }
    add_filter( 'new_user_email_content', 'b3_confirm_change_email', 10, 2 ); // attempt change email
    add_filter( 'new_admin_email_content', 'b3_confirm_change_email', 10, 2 ); // attempt change site admin email
    add_filter( 'new_network_admin_email_content', 'b3_confirm_change_email', 10, 2 ); // attempt change network admin email
    add_filter( 'site_admin_email_change_email', 'b3_confirm_change_email', 10, 3 ); // after site admin email change
    add_filter( 'network_admin_email_change_email', 'b3_confirm_change_email', 10, 2 ); // after network admin email change
