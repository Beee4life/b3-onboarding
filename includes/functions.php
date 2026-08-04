<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    include 'download.php';
    include 'functions-email-general.php';
    include 'functions-email-ms.php';
    include 'functions-meta.php';
    include 'retrieve-password.php';

    // Create an array of available email 'boxes'
    function b3_get_email_boxes() {
        $admin_approval    = get_option( 'b3_needs_admin_approval' );
        $email_boxes       = [];
        $registration_type = get_option( 'b3_registration_type' );

        $email_boxes[] = [
            'id'    => 'email_settings',
            'title' => esc_html__( 'Global email settings', 'b3-onboarding' ),
        ];
        if ( is_main_site() ) {
            if ( $admin_approval ) {
                $email_boxes[] = [
                    'id'    => 'request_access_user',
                    'title' => esc_html__( 'Request access email (user)', 'b3-onboarding' ),
                ];
                $email_boxes[] = [
                    'id'    => 'request_access_admin',
                    'title' => esc_html__( 'Request access email (admin)', 'b3-onboarding' ),
                ];
                $email_boxes[] = [
                    'id'    => 'account_approved',
                    'title' => esc_html__( 'Account approved email (user)', 'b3-onboarding' ),
                ];
                $email_boxes[] = [
                    'id'    => 'account_rejected',
                    'title' => esc_html__( 'Account rejected email (user)', 'b3-onboarding' ),
                ];
            }
            if ( in_array( $registration_type, [ 'email_activation' ] ) ) {
                $email_boxes[] = [
                    'id'    => 'email_activation',
                    'title' => esc_html__( 'Email activation (user)', 'b3-onboarding' ),
                ];
                $email_boxes[] = [
                    'id'    => 'account_activated',
                    'title' => esc_html__( 'Account activated (user)', 'b3-onboarding' ),
                ];
            }
        }
        if ( in_array( $registration_type, [ 'open', 'blog', 'all', 'site' ] ) ) {
            $email_boxes[] = [
                'id'    => 'welcome_user',
                'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            ];
        }
        if ( in_array( $registration_type, [ 'open', 'blog', 'all', 'site', 'none' ] ) ) {
            $email_boxes[] = [
                'id'    => 'welcome_user_manual',
                'title' => esc_html__( 'Welcome email (user) - added by admin', 'b3-onboarding' ),
            ];
        }
        if ( is_main_site() ) {
            if ( is_multisite() ) {
                if ( in_array( $registration_type, [ 'user' ] ) ) {
                    $email_boxes[] = [
                        'id'    => 'confirm_user_email',
                        'title' => esc_html__( 'Confirm email (user only)', 'b3-onboarding' ),
                    ];
                    $email_boxes[] = [
                        'id'    => 'activated_user_email',
                        'title' => esc_html__( 'User activated (user only)', 'b3-onboarding' ),
                    ];
                } elseif ( in_array( $registration_type, [ 'site' ] ) ) {
                    $email_boxes[] = [
                        'id'    => 'confirm_user_site_email',
                        'title' => esc_html__( 'Confirm email (user + site)', 'b3-onboarding' ),
                    ];
                    $email_boxes[] = [
                        'id'    => 'activated_user_site_email',
                        'title' => esc_html__( 'User activated (user + site)', 'b3-onboarding' ),
                    ];
                }
                if ( ! in_array( $registration_type, [ 'none' ] ) ) {
                    $email_boxes[] = [
                        'id'    => 'new_wpmu_user_admin',
                        'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                    ];
                }
            }
            if ( in_array( $registration_type, [ 'open', 'email_activation' ] ) ) {
                $email_boxes[] = [
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                ];
            }
        }
        $email_boxes[] = [
            'id'    => 'lost_password',
            'title' => esc_html__( 'Lost password email', 'b3-onboarding' ),
        ];
        if ( get_option( 'b3_use_magic_link' ) ) {
            $email_boxes[] = [
                'id'    => 'magiclink',
                'title' => esc_html__( 'Magic link email', 'b3-onboarding' ),
            ];
        }
        $email_boxes[] = [
            'id'    => 'logo',
            'title' => esc_html__( 'Logo', 'b3-onboarding' ),
        ];

        return $email_boxes;
    }

    // Return registration options
    function b3_get_registration_types() {
        $registration_options = [];
        $closed_option        = [
            [
                'value' => 'none',
                'label' => esc_html__( 'Closed (for everyone)', 'b3-onboarding' ),
            ],
        ];

        if ( ! is_multisite() ) {
            $normal_options = [
                [
                    'value' => 'email_activation',
                    'label' => esc_html__( 'Email activation (user needs to confirm email)', 'b3-onboarding' ),
                ],
                [
                    'value' => 'open',
                    'label' => esc_html__( 'Open (user is instantly active)', 'b3-onboarding' ),
                ],
            ];
        }

        if ( is_multisite() ) {
            $multisite_options = [
                [
                    'value' => 'user',
                    'label' => esc_html__( 'Visitor may register user', 'b3-onboarding' ),
                ],
                [
                    'value' => 'blog',
                    'label' => esc_html__( 'Logged in user may register a site (no public new user registration)', 'b3-onboarding' ),
                ],
                [
                    'value' => 'all',
                    'label' => esc_html__( 'Visitor may register user and/or site', 'b3-onboarding' ),
                ],
                [
                    'value' => 'site',
                    'label' => esc_html__( "Visitor may register user + site (must register site)", 'b3-onboarding' ),
                ],
            ];
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

    // Return user email logo and default logo if false
    function b3_get_main_logo() {
        $main_logo = get_option( 'b3_main_logo' );

        if ( ! $main_logo ) {
            $main_logo = b3_default_main_logo();
        }

        return apply_filters( 'b3_main_logo', $main_logo );
    }

    // Get the 'registration closed' message
    function b3_get_registration_closed_message() {
        $user_input = get_option( 'b3_registration_closed_message' );

        if ( $user_input ) {
            $registration_closed_message = htmlspecialchars_decode( $user_input );
        } else {
            $registration_closed_message = b3_default_registration_closed_message();
        }

        return apply_filters( 'b3_registration_closed_message', $registration_closed_message );
    }

    // Message to let user know they need to login first to register a site
    function b3_get_logged_in_registration_only_message() {
        $user_input = get_option( 'b3_logged_in_registration_only' );

        if ( $user_input ) {
            $logged_in_registration_only_message = htmlspecialchars_decode( $user_input );
        } else {
            $logged_in_registration_only_message = b3_default_logged_in_registration_only_message();
        }

        return apply_filters( 'b3_logged_in_registration_only_message', $logged_in_registration_only_message );
    }

    // Get the terms text
    function b3_get_terms_text() {
        $terms_text = get_option( 'b3_terms_text' );

        if ( false != $terms_text ) {
            $message = stripslashes( $terms_text );
        } else {
            $message = b3_default_terms_text();
        }

        return $message;
    }

    // Get the privacy text
    function b3_get_privacy_text() {
        $privacy_text = get_option( 'b3_privacy_text' );

        if ( false != $privacy_text ) {
            $message = stripslashes( $privacy_text );
        } else {
            $message = b3_default_privacy_text();
        }

        return $message;
    }

    // Get a unique activation url for a user
    function b3_get_activation_url( $user_data ) {
        // Generate an activation key
        $key = wp_generate_password( 20, false );

        global $wpdb;
        // Set the activation key for the user
        $data  = [ 'user_activation_key' => $key ];
        $where = [ 'user_login' => $user_data->user_login ];
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
        $wpdb->update( $wpdb->users, $data, $where );

        $query_args     = [
            'action'     => 'activate',
            'key'        => $key,
            'user_login' => rawurlencode( $user_data->user_login ),
        ];
        $login_url      = b3_get_login_url();
        $activation_url = add_query_arg( $query_args, $login_url );

        return $activation_url;
    }

    // General opening of settings field
    function b3_get_settings_field_open( $hide = false, $modifier = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        $modifier   = ( $modifier != false ) ? ' b3_settings-field--' . $modifier : false;
        echo sprintf( '<div class="b3_settings-field%s%s">', esc_attr( $hide_class ), esc_attr( $modifier ) );
    }

    // General opening of settings label
    function b3_get_label_field_open( $hide = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        echo sprintf( '<div class="b3_settings-label%s">', esc_attr( $hide_class ) );
    }

    // Close a div.
    // This function is not really needed, but it prevents PhpStorm from throwing a ton of errors.
    function b3_get_close() {
        echo '</div>';
    }

    // Return submit button
    function b3_get_submit_button( $submit_value = false, $button_modifier = false, $attributes = [], $button_id = false ) {
        $button_class = false;
        $button_id    = $button_id ? esc_attr( $button_id ) : 'b3-submit';

        if ( false === $submit_value || ! is_string( $submit_value ) ) {
            $submit_value = esc_attr__( 'Save settings', 'b3-onboarding' );
        }

        if ( false != $button_modifier ) {
            if ( is_string( $button_modifier ) ) {
                $button_class = ' button-submit--' . esc_attr( $button_modifier );
            }
        }

        $button = sprintf( '<input class="button button-primary button--submit%s" type="submit" id="%s" value="%s" />', $button_class, $button_id, $submit_value );

        if ( 'register' === $button_modifier && isset( $attributes[ 'recaptcha' ][ 'public' ] ) && ! empty( $attributes[ 'recaptcha' ][ 'public' ] ) ) {
            $activate_recaptcha = get_option( 'b3_activate_recaptcha' );
            $recaptcha_version  = get_option( 'b3_recaptcha_version' );

            if ( $activate_recaptcha && 3 == $recaptcha_version ) {
                $button = sprintf( '<input type="submit" class="button g-recaptcha" data-sitekey="%s" data-callback="onSubmit" data-action="submit" value="%s" />', esc_attr( $attributes[ 'recaptcha' ][ 'public' ] ), esc_attr( $submit_value ) );
            }
        }
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $button;
    }

    // Get register page id/link
    function b3_get_register_url( $return_id = false, $blog_id = false ) {

        if ( false != $blog_id && is_multisite() ) {
            switch_to_blog($blog_id);
        }

        $register_page_id = get_option( 'b3_register_page_id' );

        // check to see if stored page still exists
        if ( $register_page_id && get_post( $register_page_id ) instanceof WP_Post ) {
            if ( class_exists( 'SitePress' ) ) {
                $local_page_id = apply_filters( 'wpml_object_id', $register_page_id, 'page', true );
                if ( get_post( $local_page_id ) instanceof WP_Post && (int) $register_page_id !== $local_page_id ) {
                    $register_page_id = $local_page_id;
                }
            }

            if ( get_post( $register_page_id ) ) {
                if ( false != $return_id ) {
                    // @TODO: test this
                    if ( false != $blog_id && is_multisite() ) {
                        restore_current_blog();
                    }

                    return $register_page_id;
                }

                $register_link = get_the_permalink( $register_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    // @TODO: test this
                    restore_current_blog();
                }

                return $register_link;
            }

        } else {
            // stored page doesn't exist (anymore) but it can still be a multisite installation

            if ( is_multisite() ) {
                switch_to_blog( get_main_site_id() );
                $register_url = b3_get_register_url();
                restore_current_blog();

                if ( $register_url ) {
                    return $register_url;
                }
            }
        }

        return wp_registration_url();
    }

    // Get login page id/link
    function b3_get_login_url( $return_id = false, $blog_id = false ) {

        if ( false != $blog_id && is_multisite() ) {
            switch_to_blog($blog_id);
        }

        $login_page_id = get_option( 'b3_login_page_id' );

        // check to see if stored page still exists
        if ( $login_page_id && get_post( $login_page_id ) instanceof WP_Post ) {
            if ( class_exists( 'SitePress' ) ) {
                $local_page_id = apply_filters( 'wpml_object_id', $login_page_id, 'page', true );
                if ( get_post( $local_page_id ) instanceof WP_Post && (int) $login_page_id !== $local_page_id ) {
                    $login_page_id = $local_page_id;
                }
            }

            if ( get_post( $login_page_id ) ) {
                if ( false != $return_id ) {
                    if ( false != $blog_id && is_multisite() ) {
                        restore_current_blog();
                    }
                    return $login_page_id;
                }

                $login_url = get_the_permalink( $login_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }

                return $login_url;
            }

        } else {
            // stored page doesn't exist (anymore) but it can still be a multisite installation

            if ( is_multisite() ) {
                switch_to_blog( get_main_site_id() );
                $login_url = b3_get_login_url();
                restore_current_blog();

                if ( $login_url ) {
                    return $login_url;
                }
            }
        }

        return wp_login_url();
    }

    // Get logout page id/link
    function b3_get_logout_url( $return_id = false, $blog_id = false ) {

        if ( false != $blog_id && is_multisite() ) {
            switch_to_blog( $blog_id );
        }

        $log_out_page_id = get_option( 'b3_logout_page_id' );

        if ( $log_out_page_id && get_post( $log_out_page_id ) instanceof WP_Post ) {
            if ( class_exists( 'SitePress' ) ) {
                $local_page_id = apply_filters( 'wpml_object_id', $log_out_page_id, 'page', true );
                if ( get_post( $local_page_id ) instanceof WP_Post && (int) $log_out_page_id !== $local_page_id ) {
                    $log_out_page_id = $local_page_id;
                }
            }

            if ( get_post( $log_out_page_id ) ) {
                if ( false != $return_id ) {
                    if ( false != $blog_id && is_multisite() ) {
                        restore_current_blog();
                    }
                    return $log_out_page_id;
                }

                $log_out_link = get_the_permalink( $log_out_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }
                return $log_out_link;
            }
        } else {
            // stored page doesn't exist (anymore) but it can still be a multisite installation
            // @TODO: look into this
        }

        return wp_logout_url();
    }

    // Get page id/link for account page
    function b3_get_account_url( $return_id = false, $blog_id = false ) {

        if ( false != $blog_id && is_multisite() ) {
            switch_to_blog($blog_id);
        }

        $account_page_id = get_option( 'b3_account_page_id' );

        if ( $account_page_id && get_post( $account_page_id ) instanceof WP_Post ) {
            if ( class_exists( 'SitePress' ) ) {
                $local_page_id = apply_filters( 'wpml_object_id', $account_page_id, 'page', true );
                if ( get_post( $local_page_id ) instanceof WP_Post && (int) $account_page_id !== $local_page_id ) {
                    $account_page_id = $local_page_id;
                }
            }

            if ( get_post( $account_page_id ) ) {
                if ( false != $return_id ) {
                    if ( false != $blog_id && is_multisite() ) {
                        restore_current_blog();
                    }
                    return $account_page_id;
                }

                $account_link = get_the_permalink( $account_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }
                return $account_link;
            }
        } else {
            // stored page doesn't exist (anymore)
            // @TODO: look into this (also in MS)

            $wp_url = admin_url( 'profile.php' );
            if ( false != $blog_id && is_multisite() ) {
                restore_current_blog();
            }
            return $wp_url;
        }

        return false;
    }

    // Get lost password page id/link
    function b3_get_lostpassword_url( $return_id = false, $blog_id = false ) {

        if ( false != $blog_id && is_multisite() ) {
            switch_to_blog($blog_id);
        }

        $lost_password_page_id = get_option( 'b3_lost_password_page_id' );

        if ( $lost_password_page_id && get_post( $lost_password_page_id ) instanceof WP_Post ) {
            if ( class_exists( 'SitePress' ) ) {
                $local_page_id = apply_filters( 'wpml_object_id', $lost_password_page_id, 'page', true );
                if ( get_post( $local_page_id ) instanceof WP_Post && (int) $lost_password_page_id !== $local_page_id ) {
                    $lost_password_page_id = $local_page_id;
                }
            }

            if ( get_post( $lost_password_page_id ) ) {
                if ( false != $return_id ) {
                    if ( false != $blog_id && is_multisite() ) {
                        restore_current_blog();
                    }
                    return $lost_password_page_id;
                }

                $lost_password_link = get_the_permalink( $lost_password_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }
                return $lost_password_link;
            }
        } else {
            // stored page doesn't exist (anymore)
            // @TODO: look into this (also in MS)
        }

        return wp_lostpassword_url();
    }

    // Get reset pass page id/link
    function b3_get_reset_password_url( $return_id = false, $blog_id = false ) {
        $reset_pass_page_id = get_option( 'b3_reset_password_page_id' );

        if ( $reset_pass_page_id && get_post( $reset_pass_page_id ) instanceof WP_Post ) {
            if ( class_exists( 'SitePress' ) ) {
                $local_page_id = apply_filters( 'wpml_object_id', $reset_pass_page_id, 'page', true );
                if ( get_post( $local_page_id ) instanceof WP_Post && (int) $reset_pass_page_id !== $local_page_id ) {
                    $reset_pass_page_id = $local_page_id;
                }
            }

            if ( false != $return_id ) {
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }
                return $reset_pass_page_id;
            }
            $reset_pass_link = get_the_permalink( $reset_pass_page_id );
            if ( false != $blog_id && is_multisite() ) {
                restore_current_blog();
            }
            return $reset_pass_link;
        }

        return network_site_url( 'wp-login.php', 'login' ) . '?action=rp';
    }

    // Get account page id/link
    function b3_get_user_approval_url( $return_id = false, $blog_id = false ) {
        if ( get_option( 'b3_activate_front_end_approval' ) ) {
            $user_approval_page_id = get_option( 'b3_approval_page_id' );

            if ( $user_approval_page_id && get_post( $user_approval_page_id ) instanceof WP_Post ) {
                if ( class_exists( 'SitePress' ) ) {
                    $local_page_id = apply_filters( 'wpml_object_id', $user_approval_page_id, 'page', true );
                    if ( get_post( $local_page_id ) instanceof WP_Post && (int) $user_approval_page_id !== $local_page_id ) {
                        $user_approval_page_id = $local_page_id;
                    }
                }

                if ( false != $return_id ) {
                    if ( false != $blog_id && is_multisite() ) {
                        restore_current_blog();
                    }
                    return $user_approval_page_id;
                }

                $user_approval_link = get_the_permalink( $user_approval_page_id );
                if ( false != $blog_id && is_multisite() ) {
                    restore_current_blog();
                }
                return $user_approval_link;
            }

        } else {
            return admin_url( 'admin.php?page=b3-user-approval' );
        }

        return false;
    }

    // Convert a GMT date/time to local, in system defined date/time format
    function b3_get_local_date_time( $date_time_gmt ) {
        if ( ! $date_time_gmt ) {
            return false;
        }
        $timestamp   = strtotime( $date_time_gmt );
        $date_format = get_option( 'date_format' ) . ' \@ ' . get_option( 'time_format' );

        return wp_date( $date_format, $timestamp );
    }

    // Get the message above registration form
    function b3_get_message_above_registration() {
        $message = b3_default_message_above_registration();

        return apply_filters( 'b3_message_above_registration', $message );
    }

    // Get the message above login form
    function b3_get_message_above_login() {
        return apply_filters( 'b3_message_above_login', '' );
    }

    // Get the message above lost password form
    function b3_get_message_above_lost_password() {
        $message = b3_default_message_above_lost_password();

        return apply_filters( 'b3_message_above_lost_password', $message );
    }

    // Get the message above request access form
    function b3_get_message_above_request_access() {
        $message = b3_default_message_above_request_access();

        return apply_filters( 'b3_message_above_request_access', $message );
    }

    // Get the message above magic link form
    function b3_get_message_above_magiclink_form() {
        $default_message = b3_default_message_above_magic_link();
        $message         = apply_filters( 'b3_message_above_magic_link', $default_message );

        return $message;
    }

    // Disallowed usernames
    function b3_get_disallowed_usernames( $return = 'array' ) {
        $default_user_names = b3_get_default_reserved_user_names();
        $stored_names       = get_option( 'b3_disallowed_usernames' );

        if ( is_array( $stored_names ) && ! empty( $stored_names ) ) {
            $disallowed_names = array_merge( $default_user_names, $stored_names );
        } else {
            $disallowed_names = $default_user_names;
        }

        $user_name_array = apply_filters( 'b3_disallowed_usernames', $disallowed_names );

        if ( 'string' == $return ) {
            return implode( ',', $user_name_array );
        }

        return $user_name_array;
    }

    // Get 'easy' passwords
    function b3_get_easy_passwords() {
        return apply_filters( 'b3_easy_passwords', b3_get_default_easy_passwords() );
    }

    // Get disallowed domain names
    function b3_get_disallowed_domain_names( $return = 'array' ) {
        $stored_domain_names = get_option( 'b3_disallowed_domains', [] );
        $filtered_domains    = apply_filters( 'b3_disallowed_domains', $stored_domain_names );

        if ( 'string' == $return ) {
            return implode( ',', $filtered_domains );
        }

        return $filtered_domains;
    }

    // Get protocol
    function b3_get_protocol() {
        return ( isset( $_SERVER[ 'HTTPS' ] ) && 'off' != $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http';
    }

    // Get current URL
    function b3_get_current_url( $include_query = false ) {
        $current_url = '';

        if ( isset( $_SERVER[ 'HTTP_HOST' ] ) && isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
            $url         = b3_get_protocol() . '://' . sanitize_text_field( wp_unslash( $_SERVER[ 'HTTP_HOST' ] ) ) . sanitize_text_field( wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) );
            $url_array   = wp_parse_url( $url );
            $port        = ( isset( $url_array[ 'port' ] ) && ! empty( $url_array[ 'port' ] ) ) ? ':' . $url_array[ 'port' ] : false;
            $path        = ( isset( $url_array[ 'path' ] ) && ! empty( $url_array[ 'path' ] ) ) ? $url_array[ 'path' ] : false;
            $current_url = $url_array[ 'scheme' ] . '://' . $url_array[ 'host' ] . $port . $path;

            if ( false !== $include_query && isset( $url_array[ 'query' ] ) ) {
                $query_string = $url_array[ 'query' ];
                $current_url   .= '?' . $query_string;
            }
        }

        return $current_url;
    }

    // For email override in new user + blog
    function b3_get_signup_id( $domain ) {
        if ( $domain ) {
            $blog_id = get_blog_id_from_url( $domain );
            if ( false != $blog_id ) {
                return $blog_id;
            }
        }

        return false;
    }

    // Get admin tabs
    function b3_get_admin_tabs() {
        $tabs = [];

        if ( ! is_multisite() || is_main_site() ) {
            $tabs[] = [
                'id'      => 'registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => b3_render_tab_content( 'registration' ),
                'icon'    => 'shield',
            ];
        }

        $tabs[] = [
            'id'      => 'emails',
            'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'emails' ),
            'icon'    => 'email',
        ];
        if ( get_option( 'b3_activate_custom_emails' ) ) {
            if ( ! apply_filters( 'b3_email_styling', false ) || ! apply_filters( 'b3_email_template', false ) ) {
                $tabs[] = [
                    'id'      => 'styling',
                    'title'   => esc_html__( 'Styling', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'styling' ),
                    'icon'    => 'admin-customizer',
                ];
            }
        }

        if ( is_main_site() ) {
            if ( ! is_multisite() ) {
                $tabs[] = [
                    'id'      => 'users',
                    'title'   => esc_html__( 'Users', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'users' ),
                    'icon'    => 'admin-users',
                ];
            }

            if ( true == get_option( 'b3_activate_recaptcha' ) ) {
                $tabs[] = [
                    'id'      => 'recaptcha',
                    'title'   => esc_html__( 'reCaptcha', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'recaptcha' ),
                    'icon'    => 'plus-alt',
                ];
            }
        }

        $tabs[] = [
            'id'      => 'pages',
            'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'pages' ),
            'icon'    => 'admin-page',
        ];

        if ( ! is_multisite() || is_main_site() ) {
            $tabs[] = [
                'id'      => 'settings',
                'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
                'content' => b3_render_tab_content( 'settings' ),
                'icon'    => 'admin-generic',
            ];
        }

        return $tabs;
    }

    // Checks that the reCAPTCHA parameter (both versions) sent with the registration request is valid.
    function b3_verify_recaptcha() {
        if ( isset( $_POST[ 'g-recaptcha-response' ] ) ) {
            $recaptcha_response = sanitize_text_field( wp_unslash( $_POST[ 'g-recaptcha-response' ] ) );
        } else {
            return false;
        }

        $recaptcha_secret = apply_filters( 'b3_recaptcha_secret', get_option( 'b3_recaptcha_secret' ) );
        $success          = false;

        if ( false != $recaptcha_secret ) {
            $response = wp_remote_post(
                'https://www.google.com/recaptcha/api/siteverify', [
                    'body' => [
                        'secret'   => $recaptcha_secret,
                        'response' => $recaptcha_response,
                    ],
                ] );

            $response_body = wp_remote_retrieve_body( $response );
            $response_code = wp_remote_retrieve_response_code( $response );

            if ( 200 == $response_code && $response && ! is_wp_error( $response ) ) {
                $decoded_response = json_decode( $response_body );

                if ( isset( $decoded_response->success ) && $decoded_response->success ) {
                    $recaptcha_version = (int) get_option( 'b3_recaptcha_version' );

                    if ( 3 === $recaptcha_version ) {
                        // For v3, success isn't enough. We must check the score.
                        // 0.5 is the recommended default threshold (0.0 is bot, 1.0 is human)
                        $score = isset( $decoded_response->score ) ? (float) $decoded_response->score : 0;
                        if ( $score >= 0.5 ) {
                            $success = true;
                        }
                    } else {
                        // For v2, simple success is sufficient
                        $success = true;
                    }
                }
            }
        }

        return $success;
    }

    // Get email preview link
    function b3_get_preview_link( $id ) {
        if ( $id ) {
            return sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( B3OB_PLUGIN_SETTINGS . '&preview=' . $id ), esc_html__( 'Preview', 'b3-onboarding' ) );
        }

        return false;
    }

    // Get plugin file (from name)
    function b3_get_plugin_file( $plugin_name ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        foreach( get_plugins() as $plugin_file => $plugin_info ) {
            if ( $plugin_info[ 'Name' ] === $plugin_name ) {
                return $plugin_file;
            }
        }

        return null;
    }

    // Set default settings
    function b3_set_default_settings( $blog_id = false ) {
        if ( false != $blog_id ) {
            switch_to_blog( $blog_id );
        }

        update_option( 'b3_disable_admin_notification_password_change', 1, false );
        update_option( 'b3_activate_logo_in_email', 1, false );

        if ( ! is_multisite() ) {
            update_option( 'b3_hide_admin_bar', 1, false );
            update_option( 'users_can_register', 0 );

            $restrict_admin = get_option( 'b3_restrict_admin' );
            if ( false == $restrict_admin || is_array( $restrict_admin ) && empty( $restrict_admin ) ) {
                update_option( 'b3_restrict_admin', [ 'subscriber', 'b3_activation', 'b3_approval' ], false );
            }

        } elseif ( is_main_site() && false == $blog_id ) {
            update_site_option( 'registrationnotification', 'no' );
        }

        if ( false == get_option( 'b3_link_color' ) ) {
            update_option( 'b3_link_color', '#e0144b', false );
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

    // Get all possible template locations
    function b3_get_template_paths() {
        $stylesheet_directory = trailingslashit( get_stylesheet_directory() );
        $template_directory   = trailingslashit( get_template_directory() );

        $template_paths = [
            $stylesheet_directory . 'b3-onboarding/',
            $stylesheet_directory . 'plugins/b3-onboarding/',
            $template_directory . 'b3-onboarding/',
            $template_directory . 'plugins/b3-onboarding/',
            trailingslashit( B3OB_PLUGIN_PATH ) . 'templates/',
        ];

        return $template_paths;
    }

    // Locate file in possible template locations
    function b3_locate_template( $template_name ) {
        foreach( b3_get_template_paths() as $location ) {
            if ( file_exists( $location . $template_name . '.php' )) {
                return $location . $template_name . '.php';
            }
        }

        return false;
    }

    // Render template
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

    // New function to do all replacements in 1 function
    function b3_get_replacement_vars( $type = 'message', $vars = [], $activation = false ) {
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

        $blog_id    = ( isset( $vars[ 'site' ]->blog_id ) ) ? $vars[ 'site' ]->blog_id : get_current_blog_id();
        $blog_id    = ( isset( $vars[ 'blog_id' ] ) ) ? $vars[ 'blog_id' ] : $blog_id;
        $user_login = false;

        if ( '1' == get_option( 'b3_register_email_only' ) && isset( $user_data->user_login ) ) {
            $user_login = $user_data->user_login;
        } elseif ( isset( $user_data->data->user_login ) ) {
            $user_login = $user_data->data->user_login;
        }

        if ( isset( $vars[ 'registration_date' ] ) ) {
            $registration_date_gmt = $vars[ 'registration_date' ];
        } elseif ( isset( $vars[ 'user_data' ]->user_registered ) ) {
            $registration_date_gmt = $vars[ 'user_data' ]->user_registered;
        } else {
            $registration_date_gmt = false;
        }
        if ( $registration_date_gmt ) {
            $local_registration_date = b3_get_local_date_time( $registration_date_gmt );
        }

        if ( isset( $vars[ 'blog_id' ] ) ) {
            switch_to_blog( $vars[ 'blog_id' ] );
            $replacements[ '%site_name%' ] = get_option( 'blogname' );
            restore_current_blog();
        }

        // @TODO: maybe merge $replacements (all allowed everywhere)
        switch( $type ) {
            case 'message':
                $add_replacements = [
                    '%account_page%' => esc_url( b3_get_account_url() ),
                    '%blog_name%'    => ( is_multisite() ) ? get_blog_option( $blog_id, 'blogname' ) : get_option( 'blogname' ),
                    '%home_url%'     => get_home_url( $blog_id, '/' ),
                    '%login_url%'    => esc_url( b3_get_login_url() ),
                    '%logo%'         => esc_url( b3_get_main_logo() ),
                    '%lostpass_url%' => b3_get_lostpassword_url(),
                    '%reset_url%'    => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
                    '%user_ip%'      => b3_get_user_ip(),
                    '%user_login%'   => $user_login,
                ];
                $replacements = array_merge( $replacements, $add_replacements );
                if ( isset( $local_registration_date ) ) {
                    $replacements[ '%registration_date%' ] = $local_registration_date;
                }
                break;
            case 'subject':
                $add_replacements = [
                    '%blog_name%'    => ( is_multisite() ) ? get_blog_option( $blog_id, 'blogname' ) : get_option( 'blogname' ),
                    '%network_name%' => get_site_option( 'site_name' ),
                    '%user_login%'   => $user_login,
                    '%first_name%'   => ( false != $user_data ) ? $user_data->first_name : false,
                ];
                $replacements = array_merge( $replacements, $add_replacements );
                break;
            default:
                $replacements = [];
        }

        if ( is_multisite() ) {
            $options_site_url                 = esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) );
            $replacements[ '%network_name%' ] = get_site_option( 'site_name' );
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

        return apply_filters( 'b3_replacement_vars', $replacements, $type, $vars, $activation );
    }

    // Get approvement table headers
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

    // Render approvement table row
    function b3_render_approvement_table_row( $user, $attributes ) {
        ob_start();
        echo '<tr>';
        echo sprintf( '<td>%s</td>', ( is_multisite() ) ? esc_html( $user->signup_id ) : esc_html( $user->ID ) );

        if ( false == $attributes[ 'register_email_only' ] ) {
            echo sprintf( '<td>%s</td>', esc_html( $user->user_login ) );
        }

        if ( false != $attributes[ 'show_first_last_name' ] ) {
            if ( is_multisite() ) {
                $meta       = unserialize( $user->meta );
                $first_name = ( isset( $meta[ 'first_name' ] ) ) ? $meta[ 'first_name' ] : '';
                $last_name  = ( isset( $meta[ 'last_name' ] ) ) ? $meta[ 'last_name' ] : '';
                echo sprintf( '<td>%s</td>', esc_html( $first_name ) );
                echo sprintf( '<td>%s</td>', esc_html( $last_name ) );
            } else {
                echo sprintf( '<td>%s</td>', esc_html( $user->first_name ) );
                echo sprintf( '<td>%s</td>', esc_html( $user->last_name ) );
            }
        }
        echo sprintf( '<td>%s</td>', esc_html( $user->user_email ) );
        if ( is_multisite() ) {
            echo sprintf( '<td>%s</td>', esc_html( $user->domain ) );
            echo sprintf( '<td>%s</td>', esc_html( $user->title ) );
        }
        echo '<td>';
        ?>
        <form name="b3_user_management" method="post">
            <input name="b3_manage_users_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-manage-users-nonce' ) ); ?>"/>
            <input name="b3_approve_user" class="button" type="submit" value="<?php echo esc_attr__( 'Approve', 'b3-onboarding' ); ?>"/>
            <input name="b3_reject_user" class="button" type="submit" value="<?php echo esc_attr__( 'Reject', 'b3-onboarding' ); ?>"/>
            <?php if ( is_multisite() ) { ?>
                <input name="b3_signup_id" type="hidden" value="<?php echo esc_attr( $user->signup_id ); ?>"/>
            <?php } else { ?>
                <input name="b3_user_id" type="hidden" value="<?php echo esc_attr( $user->ID ); ?>"/>
            <?php } ?>
        </form>
        <?php
        echo '</td>';
        echo '</tr>';
        $output = ob_get_clean();

        return $output;
    }

    // Get user IP
    // @src: https://itman.in/en/how-to-get-client-ip-address-in-php/
    function b3_get_user_ip() {
        if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
            // check ip from share internet
            $user_ip = sanitize_text_field( wp_unslash( $_SERVER[ 'HTTP_CLIENT_IP' ] ) );
        } elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
            // to check ip is pass from proxy
            $user_ip = sanitize_text_field( wp_unslash( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) );
        } elseif ( ! empty( $_SERVER[ 'REMOTE_ADDR' ] ) ) {
            $user_ip = sanitize_text_field( wp_unslash( $_SERVER[ 'REMOTE_ADDR' ] ) );
        }

        return $user_ip;
    }

    // Prepares available languages for register form
    function b3_get_languages() {
        $language_array = [ '' => __( 'English', 'b3-onboarding' ) ];
        $languages      = get_available_languages();
        require_once ABSPATH . 'wp-admin/includes/ms.php';

        foreach( $languages as $language_code ) {
            $name = format_code_lang( $language_code );
            $name_array = array_map( 'trim', explode( ';', $name ) );

            if ( ! empty( $name_array[ 0 ] ) ) {
                $language_array[ $language_code ] = $name_array[ 0 ];
            }
        }

        return $language_array;
    }

    function b3_get_subject_message( $preview = '' ) {
        if ( ! empty( $preview ) ) {
            switch( $preview ) {
                case 'template':
                    $lorem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non purus magna. Nam quam est, rutrum non consequat sed, finibus quis mi. Vestibulum eget felis risus. Phasellus nibh ligula, tristique non lorem in, blandit <a href="">iaculis</a> enim. In eleifend fermentum scelerisque. Mauris ultrices tortor non massa lobortis, eget molestie nunc fringilla. Integer fermentum ultrices quam vel scelerisque. Nullam non augue laoreet, sagittis orci ac, eleifend massa.
                    <br><br>
                    Quisque <a href="">quis nibh</a> gravida, condimentum nibh sed, facilisis ligula. Phasellus placerat, metus a ultricies vulputate, arcu massa ullamcorper enim, id iaculis nisl augue eu dolor. Aliquam vel nisi at lacus ultrices fringilla. In cursus mattis lectus, non ultricies orci vulputate nec. Fusce non vestibulum nulla. Cras libero metus, fermentum sit amet venenatis sit amet, vestibulum vitae lectus. Donec interdum volutpat blandit.
                    <br><br>
                    <div class="big-link-container"><div class="big-link"><a href="">BUTTON LABEL</a></div></div>
                    <br>
                    Morbi vehicula metus vestibulum, eleifend arcu quis, rutrum massa. Sed porttitor pellentesque convallis. Suspendisse potenti. Nam dapibus vitae tortor a egestas. Ut at lobortis tortor. Sed tellus sem, pulvinar sit amet posuere non, vulputate vitae mi. Vestibulum ac massa suscipit, placerat risus ut, rutrum turpis. Integer in risus ac turpis dapibus viverra. Nulla facilisi. Nam ut cursus felis. Pellentesque <a href="">congue scelerisque</a> nisl, nec ultricies ex. Vivamus id ex ac dolor porttitor tempus. Maecenas pulvinar porta nunc, in mollis erat egestas et.';
                    $message = $lorem_ipsum;
                    $subject = 'Dummy subject for test mail';
                    break;
                case 'mu-confirm-user-email':
                    $message = b3_get_wpmu_activate_user_message();
                    $subject = b3_get_wpmu_activate_user_subject();
                    break;
                case 'mu-user-activated':
                case 'mu-user-site-activated':
                    $message = b3_get_wpmu_user_activated_message();
                    $subject = b3_get_wpmu_user_activated_subject();
                    break;
                case 'mu-confirm-user-site-email':
                    $message = b3_get_wpmu_activate_user_blog_message();
                    $subject = b3_get_wpmu_activate_user_blog_subject();
                    break;
                case 'mu-new-user-admin':
                    $message = b3_get_new_wpmu_user_message_admin();
                    $subject = b3_get_new_wpmu_user_subject_admin();
                    break;
                case 'account-approved':
                    $message = b3_get_account_approved_message();
                    $subject = b3_get_account_approved_subject();
                    break;
                case 'account-activated':
                    $message = b3_get_account_activated_message_user();
                    $subject = b3_get_account_activated_subject_user();
                    break;
                case 'account-rejected':
                    $message = b3_get_account_rejected_message();
                    $subject = b3_get_account_rejected_subject();
                    break;
                case 'email-activation':
                    $message = b3_get_email_activation_message_user();
                    $subject = b3_get_email_activation_subject_user();
                    break;
                case 'lostpassword':
                    $message = b3_get_lost_password_message();
                    $subject = b3_get_lost_password_subject();
                    break;
                case 'magiclink':
                    $message = b3_get_magic_link_message( '123', '%s' );
                    $subject = b3_get_magic_link_subject();
                    break;
                case 'mu-confirm-email':
                    $message = b3_get_wpmu_activate_user_message();
                    $subject = b3_get_wpmu_activate_user_subject();
                    break;
                case 'new-user-admin':
                    // @TODO: maybe make new one, don't use b3_get_new_user_message
                    $message = b3_get_new_user_message();
                    $subject = b3_get_new_user_subject();
                    break;
                case 'request-access-admin':
                    $message = b3_get_request_access_message_admin();
                    $subject = b3_get_request_access_subject_admin();
                    break;
                case 'request-access-user':
                    $message = b3_get_request_access_message_user();
                    $subject = b3_get_request_access_subject_user();
                    break;
                case 'welcome-user':
                    $message = b3_get_welcome_user_message();
                    $subject = b3_get_welcome_user_subject();
                    break;
                case 'welcome-user-manual':
                    $message = b3_get_manual_welcome_user_message();
                    $subject = b3_get_welcome_user_subject();
                    break;
                case 'styling':
                    $message = b3_get_email_styling( b3_get_link_color() );
                    $subject = '';
                    break;
                default:
                    $message = apply_filters( 'b3_preview_email_message', sprintf( esc_html__( "No email message found for '%s'", 'b3-onboarding' ), $preview ), $preview );
                    $subject = apply_filters( 'b3_preview_email_subject', sprintf( esc_html__( "No email subject found for '%s'", 'b3-onboarding' ), $preview ), $preview );
            }

            return [
                'message' => $message,
                'subject' => $subject,
            ];
        }

        return [];
    }
