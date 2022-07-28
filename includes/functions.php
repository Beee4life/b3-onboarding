<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    include 'download.php';
    include 'functions-email-general.php';
    include 'functions-email-ms.php';
    include 'functions-meta.php';

    /**
     * Create an array of available email 'boxes'
     *
     * @since 1.0.0
     *
     * @return array
     */
    function b3_get_email_boxes() {
        $registration_type = get_option( 'b3_registration_type' );
        $email_boxes       = array();

        $email_boxes[] = array(
            'id'    => 'email_settings',
            'title' => esc_html__( 'Global email settings', 'b3-onboarding' ),
        );
        if ( is_main_site() ) {
            if ( in_array( $registration_type, array( 'request_access', 'request_access_subdomain' ) ) ) {
                $email_boxes[] = array(
                    'id'    => 'request_access_user',
                    'title' => esc_html__( 'Request access email (user)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'request_access_admin',
                    'title' => esc_html__( 'Request access email (admin)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'account_approved',
                    'title' => esc_html__( 'Account approved email (user)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'account_rejected',
                    'title' => esc_html__( 'Account rejected email (user)', 'b3-onboarding' ),
                );
            }
            if ( in_array( $registration_type, array( 'email_activation' ) ) ) {
                $email_boxes[] = array(
                    'id'    => 'email_activation',
                    'title' => esc_html__( 'Email activation (user)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'account_activated',
                    'title' => esc_html__( 'Account activated (user)', 'b3-onboarding' ),
                );
            }
        }
        if ( in_array( $registration_type, array( 'open', 'blog', 'all', 'site' ) ) ) {
            $email_boxes[] = array(
                'id'    => 'welcome_user',
                'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            );
        }
        if ( in_array( $registration_type, array( 'open', 'blog', 'all', 'site', 'none' ) ) ) {
            $email_boxes[] = array(
                'id'    => 'welcome_user_manual',
                'title' => esc_html__( 'Welcome email (user) - added by admin', 'b3-onboarding' ),
            );
        }
        if ( is_main_site() ) {
            if ( is_multisite() ) {
                if ( in_array( $registration_type, array( 'user' ) ) ) {
                    // @TODO: test this
                    // $email_boxes[] = array(
                    //     'id'    => 'email_activation',
                    //     'title' => esc_html__( 'Email activation (user)', 'b3-onboarding' ),
                    // );
                    // $email_boxes[] = array(
                    //     'id'    => 'account_activated',
                    //     'title' => esc_html__( 'Account activated (user)', 'b3-onboarding' ),
                    // );
                    $email_boxes[] = array(
                        'id'    => 'confirm_user_email',
                        'title' => esc_html__( 'Confirm email (user only)', 'b3-onboarding' ),
                    );
                    $email_boxes[] = array(
                        'id'    => 'activated_user_email',
                        'title' => esc_html__( 'User activated (user only)', 'b3-onboarding' ),
                    );
                } elseif ( in_array( $registration_type, array( 'site' ) ) ) {
                    $email_boxes[] = array(
                        'id'    => 'confirm_user_site_email',
                        'title' => esc_html__( 'Confirm email (user + site)', 'b3-onboarding' ),
                    );
                    $email_boxes[] = array(
                        'id'    => 'activated_user_site_email',
                        'title' => esc_html__( 'User activated (user + site)', 'b3-onboarding' ),
                    );
                }
                if ( ! in_array( $registration_type, array( 'none' ) ) ) {
                    $email_boxes[] = array(
                        'id'    => 'new_wpmu_user_admin',
                        'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                    );
                }
            }
            if ( in_array( $registration_type, array( 'open', 'email_activation' ) ) ) {
                $email_boxes[] = array(
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                );
            }
        }
        $email_boxes[] = array(
            'id'    => 'lost_password',
            'title' => esc_html__( 'Lost password email', 'b3-onboarding' ),
        );

        return $email_boxes;
    }


    /**
     * Return registration options
     *
     * @since 1.0.0
     *
     * @return array
     */
    function b3_get_registration_types() {
        $registration_options = array();
        $closed_option        = array(
            array(
                'value' => 'none',
                'label' => esc_html__( 'Closed (for everyone)', 'b3-onboarding' ),
            ),
        );

        if ( ! is_multisite() ) {
            $normal_options = array(
                array(
                    'value' => 'request_access',
                    'label' => esc_html__( 'Request access (requires admin approval)', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'email_activation',
                    'label' => esc_html__( 'Email activation (user needs to confirm email)', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'open',
                    'label' => esc_html__( 'Open (user is instantly active)', 'b3-onboarding' ),
                ),
            );
        }

        if ( is_multisite() ) {
            $multisite_options = array(
                array(
                    'value' => 'user',
                    'label' => esc_html__( 'Visitor may register user', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'blog',
                    'label' => esc_html__( 'Logged in user may register a site (no public new user registration)', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'all',
                    'label' => esc_html__( 'Visitor may register user and/or site', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'site',
                    'label' => esc_html__( "Visitor may register user + site (must register site)", 'b3-onboarding' ),
                ),
                array(
                    'value' => 'request_access_subdomain',
                    'label' => esc_html__( 'Request access (admin approval + user domain request)', 'b3-onboarding' ),
                ),
            );
        }

        if ( ! is_multisite() ) {
            $registration_options = array_merge( $closed_option, $registration_options, $normal_options );
        } else {
            if ( is_main_site() ) {
                $registration_options = array_merge( $closed_option, $multisite_options );
            }

        }

        return $registration_options;
    }

    /**
     * Return user email logo and default logo if false
     *
     * @since 2.0.0
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_main_logo() {
        $custom_logo = get_option( 'b3_main_logo' );

        if ( false != $custom_logo ) {
            $main_logo = $custom_logo;
        } else {
            $main_logo = b3_default_main_logo();
        }

        return $main_logo;
    }


    /**
     * Get the 'registration closed' message
     *
     * @since 2.0.0
     *
     * @param $registration_closed_message
     *
     * @return string
     */
    function b3_get_registration_closed_message() {
        $user_input = get_option( 'b3_registration_closed_message' );
        if ( false != $user_input ) {
            $registration_closed_message = htmlspecialchars_decode( $user_input );
        } else {
            $registration_closed_message = b3_default_registration_closed_message();
        }

        return $registration_closed_message;
    }


    /**
     * Message to let user know they need to login first to register a site
     *
     * @return string
     */
    function b3_get_logged_in_registration_only_message() {
        $user_input = get_option( 'b3_logged_in_registration_only' );
        if ( false != $user_input ) {
            $logged_in_registration_only_message = htmlspecialchars_decode( $user_input );
        } else {
            $logged_in_registration_only_message = b3_default_logged_in_registration_only_message();
        }

        return $logged_in_registration_only_message;
    }


    /**
     * Get the privacy text
     *
     * @since 1.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_privacy_text() {
        $privacy_text = get_option( 'b3_privacy_text' );
        if ( false != $privacy_text ) {
            $message = stripslashes( $privacy_text );
        } else {
            $message = b3_default_privacy_text();
        }

        return $message;
    }


    /**
     * Get a unique activation url for a user
     *
     * @since 1.0.0
     *
     * @param $user_data
     *
     * @return string
     */
    function b3_get_activation_url( $user_data ) {
        // Generate an activation key
        $key = wp_generate_password( 20, false );

        global $wpdb;
        // Set the activation key for the user
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_data->user_login ) );

        $login_url      = b3_get_login_url();
        $activation_url = add_query_arg( array( 'action' => 'activate', 'key' => $key, 'user_login' => rawurlencode( $user_data->user_login ) ), $login_url );

        return $activation_url;
    }


    /**
     * General opening of settings field
     *
     * @since 2.0.0
     *
     * @param bool $hide
     */
    function b3_get_settings_field_open( $no_render = false, $hide = false, $modifier = false ) {
        if ( false == $no_render ) {
            $hide_class = ( $hide != false ) ? ' hidden' : false;
            $modifier   = ( $modifier != false ) ? ' b3_settings-field--' . $modifier : false;
            echo sprintf( '<div class="b3_settings-field%s%s">', $hide_class, $modifier );
        }
    }


    /**
     * General opening of settings label
     *
     * @since 2.0.0
     *
     * @param bool $hide
     */
    function b3_get_label_field_open( $hide = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        echo sprintf( '<div class="b3_settings-label%s">', $hide_class );
    }


    /**
     * Close a div.
     * This function is not really needed, but it prevents PhpStorm from throwing a ton of errors.
     *
     * @since 2.0.0
     */
    function b3_get_close() {
        echo '</div>';
    }


    /**
     * Return submit button
     *
     * @since 2.0.0
     *
     * @param false $submit_value
     * @param false $button_modifier
     */
    function b3_get_submit_button( $submit_value = false, $button_modifier = false, $attributes = [] ) {
        $button_class = false;
        if ( false == $submit_value || ! is_string( $submit_value ) ) {
            $submit_value = esc_attr__( 'Save settings', 'b3-onboarding' );
        }

        if ( false != $button_modifier ) {
            if ( is_string( $button_modifier ) ) {
                $button_class = ' button-submit--' . esc_attr( $button_modifier );
            }
        }

        $button = sprintf( '<input class="button button-primary button--submit%s" type="submit" value="%s" />', $button_class, $submit_value );

        if ( 'register' == $button_modifier && isset( $attributes[ 'recaptcha' ][ 'public' ] ) && ! empty( $attributes[ 'recaptcha' ][ 'public' ] ) ) {
            $activate_recaptcha = get_option( 'b3_activate_recaptcha' );
            $recaptcha_version  = get_option( 'b3_recaptcha_version' );
            if ( $activate_recaptcha && 3 == $recaptcha_version ) {
                $button = sprintf( '<input type="submit" class="button g-recaptcha" data-sitekey="%s" data-callback="onSubmit" data-action="submit" value="%s" />', esc_attr( $attributes[ 'recaptcha' ][ 'public' ] ), esc_attr( $submit_value ) );
            }
        }

        echo $button;
    }

    /**
     * Get register page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_register_url( $return_id = false ) {
        $register_page_id = get_option( 'b3_register_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $register_page_id = apply_filters( 'wpml_object_id', $register_page_id, 'page', true );
        }
        if ( false != $register_page_id ) {
            if ( false != $return_id ) {
                return $register_page_id;
            }
            if ( get_post( $register_page_id ) ) {
                return get_the_permalink( $register_page_id );
            }
        }

        return wp_registration_url();
    }


    /**
     * Get login page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_login_url( $return_id = false, $blog_id = false ) {

        if ( false != $blog_id && is_multisite() ) {
            switch_to_blog($blog_id);
        }

        $login_page_id = get_option( 'b3_login_page_id' );

        if ( class_exists( 'Sitepress' ) ) {
            $login_page_id = apply_filters( 'wpml_object_id', $login_page_id, 'page', true );
        }

        if ( false != $login_page_id ) {
            if ( false != $return_id ) {
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }
                return $login_page_id;
            }

            if ( get_post( $login_page_id ) ) {
                $login_url = get_the_permalink( $login_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }

                return $login_url;
            }
        }

        return wp_login_url();
    }


    /**
     * Get logout page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_logout_url( $return_id = false ) {
        $id = get_option( 'b3_logout_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $id = apply_filters( 'wpml_object_id', $id, 'page', true );
        }
        if ( false != $id ) {
            if ( false != $return_id ) {
                return $id;
            }
            if ( get_post( $id ) ) {
                return get_the_permalink( $id );
            }
        }

        return wp_logout_url();

    }


    /**
     * Get account page page id/link
     *
     * @since 1.0.6
     *
     * @return bool|mixed
     */
    function b3_get_account_url( $return_id = false, $language = false ) {
        $account_page_id = get_option( 'b3_account_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $account_page_id = apply_filters( 'wpml_object_id', $account_page_id, 'page', true, $language );
        }
        if ( false != $account_page_id ) {
            if ( false != $return_id ) {
                return $account_page_id;
            }
            if ( get_post( $account_page_id ) ) {
                return get_the_permalink( $account_page_id );
            }
        } else {
            return admin_url( 'profile.php' );
        }

        return false;
    }


    /**
     * Get lost password page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_lostpassword_url() {
        $lost_password_page_id = get_option( 'b3_lost_password_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $lost_password_page_id = apply_filters( 'wpml_object_id', $lost_password_page_id, 'page', true );
        }
        if ( false != $lost_password_page_id && get_post( $lost_password_page_id ) ) {
            return get_the_permalink( $lost_password_page_id );
        }

        return wp_lostpassword_url();
    }


    /**
     * Get reset pass page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_reset_password_url( $return_id = false ) {
        $reset_pass_page_id = get_option( 'b3_reset_password_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $reset_pass_page_id = apply_filters( 'wpml_object_id', $reset_pass_page_id, 'page', true );
        }
        if ( false != $reset_pass_page_id ) {
            if ( true == $return_id ) {
                return $reset_pass_page_id;
            }
            $reset_post = get_post( $reset_pass_page_id );
            if ( $reset_post ) {
                $link = get_the_permalink( $reset_pass_page_id );
            }
            if ( isset( $link ) ) {
                return $link;
            }
        }

        return network_site_url( 'wp-login.php', 'login' ) . '?action=rp';
    }


    /**
     * Get account page id/link
     *
     * @since 1.0.6
     *
     * @param bool $return_link
     *
     * @return bool|mixed|void
     */
    function b3_get_user_approval_link( $return_id = false ) {
        if ( true == get_option( 'b3_front_end_approval' ) ) {
            $user_approval_page_id = get_option( 'b3_approval_page_id' );
            if ( class_exists( 'Sitepress' ) ) {
                $user_approval_page_id = apply_filters( 'wpml_object_id', $user_approval_page_id, 'page', true );
            }
            if ( false != $user_approval_page_id ) {
                if ( true == $return_id ) {
                    return $user_approval_page_id;
                }
                if ( get_post( $user_approval_page_id ) ) {
                    return get_the_permalink( $user_approval_page_id );
                } else {
                    return admin_url( 'admin.php?page=b3-user-approval' );
                }
            }
        } else {
            return admin_url( 'admin.php?page=b3-user-approval' );
        }

        return false;
    }


    /**
     * Convert a GMT date/time to local, in system defined date/time format
     *
     * @param $date_time_gmt
     *
     * @return false|mixed|string|null
     * @throws Exception
     */
    function b3_get_local_date_time( $date_time_gmt = false ) {
        if ( false != $date_time_gmt ) {
            $date_time = new DateTime( $date_time_gmt );
            $date_time->setTimezone( new DateTimeZone( wp_timezone_string() ) );
            $registration_date = $date_time->format( get_option( 'date_format' ) ) . ' @ ' . $date_time->format( get_option( 'time_format' ) );

            return $registration_date;
        }

        return $date_time_gmt;
    }


    /**
     * Get the message above registration form
     *
     * @TODO: create user input option
     *
     * @param $message
     *
     * @return bool|mixed|string|void
     */
    function b3_get_message_above_registration() {
        $register_message = get_option( 'b3_register_message' );
        if ( false != $register_message ) {
            $message = $register_message;
        } else {
            $message = b3_default_message_above_registration();
        }

        return $message;
    }


    /**
     * Get the message above login form
     *
     * @TODO: create user input option
     *
     * @param $message
     *
     * @return bool|mixed|string|void
     */
    function b3_get_message_above_login() {
        $login_message = get_option( 'b3_message_above_login' );
        if ( false != $login_message ) {
            return $login_message;
        }

        return false;
    }


    /**
     * Get the message above lost password form
     *
     * @TODO: create user input option
     *
     * @param $message
     *
     * @return bool|mixed|string|void
     */
    function b3_get_message_above_lost_password() {
        $password_message = get_option( 'b3_message_above_lost_password' );
        if ( false != $password_message ) {
            $message = $password_message;
        } else {
            $message = b3_default_message_above_lost_password();
        }

        return $message;
    }


    /**
     * Get the message above request access form
     *
     * @TODO: create user input option
     *
     * @param $message
     *
     * @return bool|mixed|string|void
     */
    function b3_get_message_above_request_access() {
        $password_message = get_option( 'b3_message_above_request_access' );
        if ( false != $password_message ) {
            $message = $password_message;
        } else {
            $message = b3_default_message_above_request_access();
        }

        return $message;
    }


    /**
     * Reserved usernames
     *
     * @since 2.0.4
     *
     * @return array
     */
    function b3_get_disallowed_usernames() {
        // @TODO: apply user input
        $filtered_names = apply_filters( 'b3_reserved_usernames', b3_get_default_reserved_user_names() );

        return $filtered_names;
    }


    /**
     * Get 'easy' passwords
     *
     * @since 3.5.0
     *
     * @return mixed|void
     */
    function b3_get_easy_passwords() {
        $passwords = apply_filters( 'b3_easy_passwords', b3_get_default_easy_passwords() );

        return $passwords;
    }


    /**
     * Get blocked domain names
     *
     * @since 3.5.0
     *
     * @return mixed|void
     */
    function b3_get_blocked_domain_names() {
        $domain_names = apply_filters( '', get_option( 'b3_disallowed_domains' ) );
    }


    /**
     * Get protocol
     *
     * @return string
     */
    function b3_get_protocol() {
        return ( isset( $_SERVER[ 'HTTPS' ] ) && 'off' != $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http';
    }


    /**
     * Get current URL
     *
     * @param false $include_query
     *
     * @return string
     */
    function b3_get_current_url( $include_query = false ) {
        $url        = b3_get_protocol() . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
        $url_array  = parse_url( $url );
        $port       = ( isset( $url_array[ 'port' ] ) && ! empty( $url_array[ 'port' ] ) ) ? ':' . $url_array[ 'port' ] : false;
        $path       = ( isset( $url_array[ 'path' ] ) && ! empty( $url_array[ 'path' ] ) ) ? $url_array[ 'path' ] : false;
        $return_url = $url_array[ 'scheme' ] . '://' . $url_array[ 'host' ] . $port . $path;

        if ( false !== $include_query ) {
            if ( isset( $url_array[ 'query' ] ) ) {
                $query_string = $url_array[ 'query' ];
                $return_url   .= '?' . $query_string;
            }

        }

        return $return_url;
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


    /**
     * For email override in new user + blog
     *
     * @param $domain
     *
     * @return false|int
     */
    function b3_get_signup_id( $domain ) {
        if ( $domain ) {
            $blog_id = get_blog_id_from_url( $domain );
            if ( false != $blog_id ) {
                return $blog_id;
            }
        }

        return false;
    }


    /**
     * Get admin tabs
     *
     * @return array[]
     */
    function b3_get_admin_tabs() {
        $tabs = array(
            array(
                'id'      => 'registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => b3_render_tab_content( 'registration' ),
                'icon'    => 'shield',
            ),
        );

        $tabs[] = array(
            'id'      => 'pages',
            'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'pages' ),
            'icon'    => 'admin-page',
        );

        $tabs[] = array(
            'id'      => 'emails',
            'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'emails' ),
            'icon'    => 'email',
        );
        if ( get_option( 'b3_activate_custom_emails' ) ) {
            $tabs[] = array(
                'id'      => 'template',
                'title'   => esc_html__( 'Template', 'b3-onboarding' ),
                'content' => b3_render_tab_content( 'template' ),
                'icon'    => 'admin-customizer',
            );
        }

        if ( is_main_site() ) {
            if ( ! is_multisite() ) {
                $tabs[] = array(
                    'id'      => 'users',
                    'title'   => esc_html__( 'Users', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'users' ),
                    'icon'    => 'admin-users',
                );
            }

            if ( true == get_option( 'b3_activate_recaptcha' ) ) {
                $tabs[] = array(
                    'id'      => 'recaptcha',
                    'title'   => esc_html__( 'reCaptcha', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'recaptcha' ),
                    'icon'    => 'plus-alt',
                );
            }
        }

        $tabs[] = array(
            'id'      => 'settings',
            'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'settings' ),
            'icon'    => 'admin-generic',
        );

        return $tabs;
    }


    /**
     * Checks that the reCAPTCHA parameter (both versions) sent with the registration
     * request is valid.
     *
     * @return bool True if the CAPTCHA is OK, otherwise false.
     */
    function b3_verify_recaptcha() {
        if ( isset ( $_POST[ 'g-recaptcha-response' ] ) ) {
            $recaptcha_response = $_POST[ 'g-recaptcha-response' ];
        } else {
            return false;
        }

        $recaptcha_secret = apply_filters( 'b3_recaptcha_secret', get_option( 'b3_recaptcha_secret' ) );
        $success          = false;
        if ( false != $recaptcha_secret ) {
            $response = wp_remote_post(
                'https://www.google.com/recaptcha/api/siteverify',
                array(
                    'body' => array(
                        'secret' => $recaptcha_secret,
                        'response' => $recaptcha_response
                    )
                )
            );

            $response_body = wp_remote_retrieve_body( $response );
            $response_code = wp_remote_retrieve_response_code( $response );

            if ( 200 == $response_code && $response && is_array( $response ) ) {
                $decoded_response = json_decode( $response_body );
                $success          = $decoded_response->success;
            }
        }

        return $success;
    }


    /**
     * Get email preview link
     *
     * @param $id
     *
     * @return false|string
     */
    function b3_get_preview_link( $id ) {
        if ( $id ) {
            return sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( B3OB_PLUGIN_SETTINGS . '&preview=' . $id ), esc_html__( 'Preview', 'b3-onboarding' ) );
        }

        return false;
    }


    /**
     * Get plugin file (from name)
     *
     * @param $plugin_name
     *
     * @return int|string|null
     */
    function b3_get_plugin_file( $plugin_name ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        $plugins = get_plugins();
        foreach( $plugins as $plugin_file => $plugin_info ) {
            if ( $plugin_info[ 'Name' ] == $plugin_name ) {
                return $plugin_file;
            }
        }

        return null;
    }


    /**
     * Set default settings
     *
     * @since 2.0.0
     */
    function b3_set_default_settings( $blog_id = false ) {
        if ( false != $blog_id ) {
            switch_to_blog( $blog_id );
        }
        $plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . b3_get_plugin_file( 'B3 OnBoarding' ) );
        update_option( 'b3ob_version', $plugin_data[ 'Version' ], false );
        update_option( 'b3_disable_admin_notification_password_change', 1, false );

        if ( ! in_array( get_option( 'b3_activate_custom_emails' ), [ '0', '1' ] ) ) {
            update_option( 'b3_activate_custom_emails', 1, false );
        }

        $stored_styling = get_option( 'b3_email_styling' );
        if ( ! $stored_styling ) {
            $email_styling = stripslashes( b3_default_email_styling() );
            update_option( 'b3_email_styling', $email_styling, false );
        }
        $stored_template = get_option( 'b3_email_template' );
        if ( ! $stored_template ) {
            $email_template = stripslashes( b3_default_email_template() );
            update_option( 'b3_email_template', $email_template, false );
        }

        if ( ! is_multisite() ) {
            update_option( 'b3_dashboard_widget', 1, false );
            update_option( 'b3_hide_admin_bar', 1, false );
            update_option( 'users_can_register', 0 );

            $restrict_admin = get_option( 'b3_restrict_admin' );
            if ( false == $restrict_admin || is_array( $restrict_admin ) && empty( $restrict_admin ) ) {
                update_option( 'b3_restrict_admin', array( 'subscriber', 'b3_activation', 'b3_approval' ), false );
            }

        } else {
            if ( is_main_site() && false == $blog_id ) {
                update_option( 'b3_dashboard_widget', 1, false );
                update_site_option( 'registrationnotification', 'no' );
            }
        }

		if ( false == get_option( 'b3_registration_type' ) ) {
			if ( ! is_multisite() ) {
	    		update_option( 'b3_registration_type', 'none', false );
    		} else {
				if ( is_main_site() && false == $blog_id ) {
                    update_option( 'b3_registration_type', get_site_option( 'registration' ), false );
        		}
    		}
		}

		if ( false != get_option( 'wp_page_for_privacy_policy' ) ) {
            update_option( 'b3_privacy_page_id', get_option( 'wp_page_for_privacy_policy' ), false );
        }

        if ( false != $blog_id ) {
            restore_current_blog();
        }
    }


    /**
     * Get all possible template locations
     *
     * @since 3.2.0
     *
     * @return string[]
     */
    function b3_get_template_paths() {
        $template_paths = array(
            get_stylesheet_directory() . '/b3-onboarding/',
            get_stylesheet_directory() . '/plugins/b3-onboarding/',
            get_template_directory() . '/b3-onboarding/',
            get_template_directory() . '/plugins/b3-onboarding/',
            B3OB_PLUGIN_PATH . '/templates/',
        );

        return $template_paths;
    }


    /**
     * Locate file in possible template locations
     *
     * @since 3.2.0
     *
     * @param $template_name
     *
     * @return false|string
     */
    function b3_locate_template( $template_name ) {
        foreach( b3_get_template_paths() as $location ) {
            if ( file_exists( $location . $template_name . '.php' )) {
                return $location . $template_name . '.php';
            }
        }

        return false;
    }


    /**
     * Render template
     *
     * @since 3.2.0
     *
     * @param $template_name
     * @param array $attributes
     * @param false $current_user
     */
    function b3_get_template( $template_name, $attributes = [], $current_user = false ) {
        if ( $template_name ) {
            $template = b3_locate_template( $template_name );

            do_action( 'b3_do_before_template', $template_name );
            if ( file_exists( $template ) ) {
                include $template;
            }
            do_action( 'b3_do_after_template', $template_name );
        }
    }


    /**
     * New function to do all replacements in 1 function
     *
     * @since 3.8.0
     *
     * @param $type
     * @param $vars
     * @param $activation
     *
     * @return array
     * @throws Exception
     */
    function b3_get_replacement_vars( $type = 'message', $vars = array(), $activation = false ) {
        $replacements = [];
        $user_data    = false;

        if ( isset( $vars[ 'user_data' ] ) ) {
            $user_data = $vars[ 'user_data' ];
        } elseif ( is_user_logged_in() ) {
            $user_data = get_userdata( get_current_user_id() );
            if ( false != $user_data ) {
                $vars[ 'user_data' ] = $user_data;
            }
        }
        $blog_id = ( isset( $vars[ 'site' ]->blog_id ) ) ? $vars[ 'site' ]->blog_id : get_current_blog_id();
        $user_login = ( true != get_option( 'b3_register_email_only' ) && isset( $user_data->user_login ) ) ? $user_data->user_login : false;

        if ( isset( $vars[ 'registration_date' ] ) ) {
            $registration_date_gmt = $vars[ 'registration_date' ];
        } elseif ( isset( $vars[ 'user_data' ]->user_registered ) ) {
            $registration_date_gmt = $vars[ 'user_data' ]->user_registered;
        } else {
            $registration_date_gmt = false;
        }
        $local_registration_date = b3_get_local_date_time( $registration_date_gmt );

        // More info: http://itman.in/en/how-to-get-client-ip-address-in-php/
        if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
            // check ip from share internet
            $user_ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
        } elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
            // to check ip is pass from proxy
            $user_ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        } else {
            $user_ip = $_SERVER[ 'REMOTE_ADDR' ];
        }

        if ( isset( $vars[ 'blog_id' ] ) ) {
            switch_to_blog( $vars[ 'blog_id' ] );
            $replacements[ '%site_name%' ] = get_option( 'blogname' );
            restore_current_blog();
        }

        switch($type) {
            case 'message':
                $replacements = array(
                    '%account_page%'      => esc_url( b3_get_account_url() ),
                    '%blog_name%'         => ( is_multisite() ) ? get_blog_option( $blog_id, 'blogname' ) : get_option( 'blogname' ), // check in single site
                    '%email_footer%'      => apply_filters( 'b3_email_footer_text', b3_get_email_footer() ),
                    '%home_url%'          => get_home_url( $blog_id, '/' ),
                    '%login_url%'         => esc_url( b3_get_login_url() ),
                    '%logo%'              => apply_filters( 'b3_main_logo', esc_url( b3_get_main_logo() ) ),
                    '%lostpass_url%'      => b3_get_lostpassword_url(),
                    '%network_name%'      => get_site_option( 'site_name' ),
                    '%registration_date%' => $local_registration_date,
                    '%reset_url%'         => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
                    '%user_ip%'           => $user_ip,
                    '%user_login%'        => $user_login,
                );
                break;
            case 'subject':
                $replacements = array(
                    '%blog_name%'    => get_option( 'blogname' ),
                    '%network_name%' => get_site_option( 'site_name' ),
                    '%user_login%'   => $user_login,
                    '%first_name%'   => ( false != $user_data ) ? $user_data->first_name : false,
                );
                break;
            default:
                $replacements = [];
        }

        if ( is_multisite() ) {
            $options_site_url = esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) );
            $replacements[ '%settings_url%' ] = $options_site_url;

            if ( isset( $vars[ 'blog_id' ] )  ) {
                $replacements[ '%home_url%' ]  = get_home_url( $vars[ 'blog_id' ] );
            }
            if ( isset( $vars[ 'domain' ] ) && isset( $vars[ 'path' ] )  ) {
                $replacements[ '%home_url%' ] = b3_get_protocol() . '://' . $vars[ 'domain' ] . $vars[ 'path' ];
            }
            if ( isset( $vars[ 'user_password' ] ) ) {
                $replacements[ '%user_password%' ] = $vars[ 'user_password' ];
            }
            $replacements[ 'network_name' ] = get_option( 'name' );
        }

        if ( false != $activation ) {
            if ( is_multisite() ) {
                if ( isset( $vars[ 'key' ] ) ) {
                    $activate_url                       = b3_get_login_url() . "?activate=user&key={$vars[ 'key' ]}";
                    $replacements[ '%activation_url%' ] = esc_url( $activate_url );
                }
            } else {
                $replacements[ '%activation_url%' ] = b3_get_activation_url( $user_data );
            }
        }

        return $replacements;
    }


	/**
     * Get approvement table headers
     *
	 * @param $attributes
	 *
	 * @return array
	 */
	function b3_get_approvement_table_headers( $attributes ) {
		$headers[] = ( is_multisite() ) ? esc_html__( 'Signup ID', 'b3-onboarding' ) : esc_html__( 'User ID', 'b3-onboarding' );
		if ( $attributes ) {
			if ( false == $attributes[ 'register_email_only' ] ) {
				$headers[] = esc_html__( 'User name', 'b3-onboarding' );
			}
			if ( false != $attributes[ 'show_first_last_name' ] ) {
				$headers[] = esc_html__( 'First name', 'b3-onboarding' );
				$headers[] = esc_html__( 'Last name', 'b3-onboarding' );
			}
		}
		$headers[] = esc_html__( 'Email', 'b3-onboarding' );

		if ( is_multisite() ) {
			$headers[] = esc_html__( 'Domain', 'b3-onboarding' );
			$headers[] = esc_html__( 'Site name', 'b3-onboarding' );
		}
		$headers[] = esc_html__( 'Actions', 'b3-onboarding' );

		return $headers;
	}


	/**
     * Render approvement table row
     *
	 * @param $user
	 * @param $attributes
	 *
	 * @return false|string
	 */
	function b3_render_approvement_table_row( $user, $attributes ) {
		ob_start();
		echo '<tr>';
		echo sprintf( '<td>%s</td>', ( is_multisite() ) ? $user->signup_id : $user->ID );
		if ( false == $attributes[ 'register_email_only' ] ) {
			echo sprintf( '<td>%s</td>', $user->user_login );
		}
		if ( false != $attributes[ 'show_first_last_name' ] ) {
            if ( is_multisite() ) {
                $meta = unserialize($user->meta);
                $first_name = ( isset( $meta[ 'first_name' ] ) ) ? $meta[ 'first_name' ] : '';
                $last_name = ( isset( $meta[ 'last_name' ] ) ) ? $meta[ 'last_name' ] : '';
                echo sprintf( '<td>%s</td>', $first_name );
                echo sprintf( '<td>%s</td>', $last_name );
            } else {
                echo sprintf( '<td>%s</td>', $user->first_name );
                echo sprintf( '<td>%s</td>', $user->last_name );
            }
		}
		echo sprintf( '<td>%s</td>', $user->user_email );
		if ( is_multisite() ) {
			echo sprintf( '<td>%s</td>', $user->domain );
			echo sprintf( '<td>%s</td>', $user->title );
		}
		echo '<td>';
		?>
		<form name="b3_user_management" method="post">
			<input name="b3_manage_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-manage-users-nonce' ); ?>" />
			<input name="b3_approve_user" class="button" type="submit" value="<?php echo esc_attr__( 'Approve', 'b3-onboarding' ); ?>" />
			<input name="b3_reject_user" class="button" type="submit" value="<?php echo esc_attr__( 'Reject', 'b3-onboarding' ); ?>" />
			<?php if ( is_multisite() ) { ?>
				<input name="b3_signup_id" type="hidden" value="<?php echo esc_attr( $user->signup_id ); ?>" />
			<?php } else { ?>
				<input name="b3_user_id" type="hidden" value="<?php echo esc_attr( $user->ID ); ?>" />
			<?php } ?>
		</form>
		<?php
		echo '</td>';
		echo '</tr>';
		$output = ob_get_clean();

		return $output;
	}
