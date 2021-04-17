<?php
    include 'download.php';
    include 'functions-email-general.php';
    include 'functions-email-single.php';
    include 'functions-email-ms.php';

    /**
     * Return all custom meta keys
     *
     * @since 1.0.0
     *
     * @return array
     */
    function b3_get_all_custom_meta_keys() {

        // @TODO: keep this list updated
        $meta_keys = array(
            'b3_account_activated_message',
            'b3_account_activated_subject',
            'b3_account_approved_message',
            'b3_account_approved_subject',
            'b3_account_page_id', // set on activate
            'b3_account_rejected_message',
            'b3_account_rejected_subject',
            'b3_activate_custom_emails',
            'b3_activate_custom_passwords',
            'b3_activate_first_last',
            'b3_activate_recaptcha',
            'b3_activated_wpmu_user_message',
            'b3_activated_wpmu_user_subject',
            'b3_activated_wpmu_user_site_message',
            'b3_activated_wpmu_user_site_subject',
            'b3_approval_page_id',
            'b3_confirm_wpmu_user_subject',
            'b3_confirm_wpmu_user_message',
            'b3_confirm_wpmu_user_site_subject',
            'b3_confirm_wpmu_user_site_message',
            'b3_custom_emails',
            'b3_dashboard_widget',
            'b3_debug_info',
            'b3_disable_action_links',
            'b3_disable_admin_notification_new_user', // @TODO: check
            'b3_disable_admin_notification_password_change',
            'b3_disable_delete_user_email',
            'b3_disable_wordpress_forms',
            'b3_email_activation_message',
            'b3_email_activation_subject',
            'b3_email_styling',
            'b3_email_template',
            'b3_first_last_required',
            'b3_forgot_password_message',
            'b3_forgot_password_subject',
            'b3_forgotpass_page_id',
            'b3_front_end_approval',
            'b3_hide_admin_bar',
            'b3_link_color',
            'b3_login_page_id',
            'b3_loginpage_bg_color', // @TODO: change
            'b3_loginpage_font_family', // @TODO: change
            'b3_loginpage_font_size', // @TODO: change
            'b3_loginpage_logo_height', // @TODO: change
            'b3_loginpage_logo_width', // @TODO: change
            'b3_logo_in_email',
            'b3_logout_page_id',
            'b3_lost_password_message',
            'b3_lost_password_subject',
            'b3_lost_password_page_id',
            'b3_main_logo',
            'b3_new_user_message',
            'b3_new_user_notification_addresses', // @TODO: check
            'b3_new_user_subject',
            'b3_notification_sender_email',
            'b3_notification_sender_name',
            'b3_privacy',
            'b3_privacy_page',
            'b3_privacy_text',
            'b3_recaptcha_on',
            'b3_recaptcha_public',
            'b3_recaptcha_secret',
            'b3_recaptcha_version',
            'b3_register_page_id', // set on activate
            'b3_registration_closed_message', // @TODO: check
            'b3_registration_type', // set on activate
            'b3_request_access_message_admin',
            'b3_request_access_message_user',
            'b3_request_access_notification_addresses',
            'b3_request_access_subject_admin',
            'b3_request_access_subject_user',
            'b3_reset_password_page_id', // set on activate
            'b3_restrict_admin', // set on activate
            'b3_sidebar_widget', // set on activate
            'b3_style_wordpress_forms',
            'b3_users_may_delete',
            'b3_version',
            'b3_welcome_user_message',
            'b3_welcome_user_subject',
        );

        return $meta_keys;
    }


    /**
     * Create an array of available email 'boxes'
     *
     * @since 1.0.0
     *
     * @return array
     */
    function b3_get_email_boxes() {

        $registration_type = get_site_option( 'b3_registration_type' );
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
            if ( in_array( $registration_type, array( 'open' ) ) ) {
                $email_boxes[] = array(
                    'id'    => 'welcome_email_user',
                    'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
                );
            }
            if ( is_multisite() && is_main_site() ) {
                $email_boxes[] = array(
                    'id'    => 'confirm_user_email',
                    'title' => esc_html__( 'Confirm email (user only)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'activated_user_email',
                    'title' => esc_html__( 'User activated (user only)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'confirm_user_site_email',
                    'title' => esc_html__( 'Confirm email (user + site)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'activated_user_site_email',
                    'title' => esc_html__( 'User activated (user + site)', 'b3-onboarding' ),
                );
                $email_boxes[] = array(
                    'id'    => 'new_wpmu_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                );
            }
            if ( in_array( $registration_type, array( 'open', 'email_activation' ) ) ) {
                $email_boxes[] = array(
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                );
            }
            $email_boxes[] = array(
                'id'    => 'lost_password',
                'title' => esc_html__( 'Lost password email', 'b3-onboarding' ),
            );
            $email_boxes[] = array(
                'id'    => 'email_styling',
                'title' => esc_html__( 'Email styling', 'b3-onboarding' ),
            );
            $email_boxes[] = array(
                'id'    => 'email_template',
                'title' => esc_html__( 'Email template', 'b3-onboarding' ),
            );
        }

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
                'value' => 'closed', // @TODO: maybe change to none
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
                    'value' => 'user', // @TODO: maybe change to user
                    'label' => esc_html__( 'Visitor may register user', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'blog', // @TODO: maybe change to blog
                    'label' => esc_html__( 'Logged in user may register a site', 'b3-onboarding' ),
                ),
                array(
                    'value' => 'all', // @TODO: maybe change to all
                    'label' => esc_html__( 'Visitor may register user + site', 'b3-onboarding' ),
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
        $custom_logo = get_site_option( 'b3_main_logo' );

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

        $user_input = get_site_option( 'b3_registration_closed_message' );
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

        $user_input = get_site_option( 'b3_logged_in_registration_only' );
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

        $privacy_text = get_site_option( 'b3_privacy_text' );
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

        // Set the activation key for the user
        global $wpdb;
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
    function b3_get_settings_field_open( $hide = false, $modifier = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        $modifier   = ( $modifier != false ) ? ' b3_settings-field--' . $modifier : false;
        echo '<div class="b3_settings-field' . $hide_class . $modifier . '">';
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
        echo '<div class="b3_settings-label' . $hide_class . '">';
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
     * @param bool $submit_value
     */
    function b3_get_submit_button( $submit_value = false, $button_modifier = false ) {
        // validate user value
        if ( false != $submit_value ) {
            if ( ! is_string( $submit_value ) ) {
                // throw error
            }
        } else {
            $submit_value = esc_attr__( 'Save settings', 'b3-onboarding' );
        }
        if ( false != $button_modifier ) {
            if ( is_string( $button_modifier ) ) {
                $button_modifier = ' button-submit--' . esc_attr( $button_modifier );
            } else {
                $button_modifier = false;
            }
        }
        echo '<input class="button button-primary button--submit' . $button_modifier . '" type="submit" value="' . $submit_value . '" />';
    }

    /**
     * Get register page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_register_url( $return_id = false ) {
        $id = get_site_option( 'b3_register_page_id' );
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

        $disable_wp_forms = get_site_option( 'b3_disable_wordpress_forms' );
        $login_page_id    = get_site_option( 'b3_login_page_id' );

        if ( class_exists( 'Sitepress' ) ) {
            $login_page_id = apply_filters( 'wpml_object_id', $login_page_id, 'page', true );
        }

        if ( '1' == $disable_wp_forms ) {
            if ( false != $login_page_id ) {
                if ( false != $return_id ) {
                    return $login_page_id;
                }

                if ( get_post( $login_page_id ) ) {
                    if ( is_multisite() ) {
                        switch_to_blog( get_main_site_id() );
                    }
                    $login_url = get_the_permalink( $login_page_id );
                    if ( is_multisite() ) {
                        restore_current_blog();
                    }

                    return $login_url;
                }
            }

            if ( false != $blog_id ) {
                switch_to_blog( get_main_site_id() );
                $login_url = get_the_permalink( $login_page_id );
                restore_current_blog();

                return $login_url;
            }

        } else {
            // @TODO: when forms are not forced
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
        $id = get_site_option( 'b3_logout_page_id' );
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
        $id = get_site_option( 'b3_account_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $id = apply_filters( 'wpml_object_id', $id, 'page', true, $language );
        }
        if ( false != $id ) {
            if ( false != $return_id ) {
                return $id;
            }
            if ( get_post( $id ) ) {
                return get_the_permalink( $id );
            }
        } else {
            // @TODO: return admin profile
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
        // @TODO: check this on single site, if it returns correct ID
        $id = get_site_option( 'b3_lost_password_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $id = apply_filters( 'wpml_object_id', $id, 'page', true );
        }
        if ( false != $id && get_post( $id ) ) {
            return get_the_permalink( $id );
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
        $id = get_option( 'b3_reset_password_page_id' );
        if ( class_exists( 'Sitepress' ) ) {
            $id = apply_filters( 'wpml_object_id', $id, 'page', true );
        }
        if ( false != $id ) {
            if ( true == $return_id ) {
                return $id;
            }
            if ( get_post( $id ) ) {
                return get_the_permalink( $id );
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
        if ( true == get_site_option( 'b3_front_end_approval' ) ) {
            $id = get_site_option( 'b3_approval_page_id' );
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( false != $id ) {
                if ( true == $return_id ) {
                    return $id;
                }
                if ( get_post( $id ) ) {
                    return get_the_permalink( $id );
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
     * Convert a GMT date/time to local
     *
     * @param bool $date_time_gmt
     *
     * @return bool|false|string
     * @throws Exception
     */
    function b3_get_local_date_time( $date_time_gmt = false ) {
        $date_format       = get_option( 'date_format' );
        $gmt_offset        = get_option( 'gmt_offset' );
        $time_format       = get_option( 'time_format' );
        $timezone          = get_option( 'timezone_string' );
        $registration_date = gmdate( $date_format . ' @ ' . $time_format, time() );

        if ( false != $date_time_gmt ) {
            if ( ! empty( $timezone ) ) {
                $new_date = new DateTime( $date_time_gmt, new DateTimeZone( 'UTC' ) );
                $new_date->setTimeZone( new DateTimeZone( $timezone ) );
                $registration_date = $new_date->format( $date_format . ' @ ' . $time_format );
            } elseif ( ! empty( $gmt_offset ) ) {
                $registration_date_gmt_ts = strtotime( $date_time_gmt );
                $registration_date_ts     = $registration_date_gmt_ts + ( $gmt_offset * HOUR_IN_SECONDS );
                $registration_date        = gmdate( $date_format . ' @ ' . $time_format, $registration_date_ts );
            }

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

        $register_message = get_site_option( 'b3_register_message' );
        if ( false != $register_message ) {
            $message = $register_message;
        } else {
            $message = b3_get_default_message_above_registration();
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

        $login_message = get_site_option( 'b3_message_above_login' );
        if ( false != $login_message ) {
            return $login_message;
        }

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

        $password_message = get_site_option( 'b3_message_above_lost_password' );
        if ( false != $password_message ) {
            $message = $password_message;
        } else {
            $message = b3_get_default_message_above_lost_password();
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

        $password_message = get_site_option( 'b3_message_above_request_access' );
        if ( false != $password_message ) {
            $message = $password_message;
        } else {
            $message = b3_get_default_message_above_request_access();
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
    function b3_get_reserved_usernames() {

        $default_reserved_names = array(
            'admin',
            'administrator',
        );

        $filtered_names = apply_filters( 'b3_reserved_usernames', [] );
        if ( ! is_array( $filtered_names ) ) {
            $filtered_names = [ $filtered_names ];
        }

        $reserved_user_names = array_merge( $default_reserved_names, $filtered_names );

        return $reserved_user_names;

    }


    /**
     * Get protocol
     *
     * @return string
     */
    function b3_get_protocol() {
        $protocol = ( isset( $_SERVER[ 'HTTPS' ] ) && 'off' != $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http';

        return $protocol;
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
            $errors->add( 'empty_username', __( '<strong>Error</strong>: Enter a username or email address.' ) );
        } elseif ( strpos( $_POST['user_login'], '@' ) ) {
            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
            if ( empty( $user_data ) ) {
                $errors->add( 'invalid_email', __( '<strong>Error</strong>: There is no account with that username or email address.' ) );
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
            $errors->add( 'invalidcombo', __( '<strong>Error</strong>: There is no account with that username or email address.' ) );
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

        $message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
        /* translators: %s: Site name. */
        $message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
        /* translators: %s: User login. */
        $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
        $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
        $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
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
                sprintf(
                /* translators: %s: Documentation URL. */
                    __( '<strong>Error</strong>: The email could not be sent. Your site may not be correctly configured to send emails. <a href="%s">Get support for resetting your password</a>.' ),
                    esc_url( 'https://wordpress.org/support/article/resetting-your-password/' )
                )
            );
            return $errors;
        }

        return true;
    }

    function b3_get_signup_id( $domain ) {
        if ( $domain ) {
            $blog_id = get_blog_id_from_url( $domain );
            if ( false != $blog_id ) {
                return $blog_id;
            }
        }

        return false;

    }
