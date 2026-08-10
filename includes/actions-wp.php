<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    /*
     * This file contains functions hooked to WordPress' hooks
     */

    // Update usermeta after user register
    function b3_update_user_meta_after_register( $user_id ) {
        if ( isset( $_POST[ 'first_name' ] ) && ! empty( $_POST[ 'first_name' ] ) ) {
            update_user_meta( $user_id, 'first_name', sanitize_text_field( wp_unslash( $_POST[ 'first_name' ] ) ) );
        }
        if ( isset( $_POST[ 'last_name' ] ) && ! empty( $_POST[ 'last_name' ] ) ) {
            update_user_meta( $user_id, 'last_name', sanitize_text_field( wp_unslash( $_POST[ 'last_name' ] ) ) );
        }

        $extra_field_values = apply_filters( 'b3_extra_fields', [] );
        if ( ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $field ) {
                if ( isset( $field[ 'id' ] ) ) {
                    if ( ! empty( $_POST[ $field[ 'id' ] ] ) ) {
                        update_user_meta( $user_id, $field[ 'id' ], sanitize_text_field( wp_unslash( $_POST[ $field[ 'id' ] ] ) ) );
                    }
                }
            }
        }

        $hidden_field_values = apply_filters( 'b3_hidden_fields', [] );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            foreach( $hidden_field_values as $meta_key => $meta_value ) {
                update_user_meta( $user_id, $meta_key, $meta_value );
            }
        }

        if ( isset( $_POST[ 'b3_privacy_accept' ] ) && 1 == $_POST[ 'b3_privacy_accept' ] ) {
            update_user_meta( $user_id, 'privacy_accept', true );
        }
    }
    add_action( 'user_register', 'b3_update_user_meta_after_register' );

    // Do stuff after user registers (single site).
    function b3_set_role_after_register( int $user_id ) {
        global $pagenow;
        error_log('Hit');
        // Check if the user is being manually created via WP Admin
        $is_manual_admin_add = is_admin() && 'user-new.php' === $pagenow;

        if ( $is_manual_admin_add ) {
            update_user_meta( $user_id, 'manually_added', true );

        } elseif ( $user_id > 0 ) {
            $admin_approval    = get_option( 'b3_needs_admin_approval' );
            $registration_type = get_option( 'b3_registration_type' );
            $user              = new WP_User( $user_id );

            if ( $user instanceof WP_User && 'email_activation' === $registration_type ) {
                $user->set_role( 'b3_activation' );
            }
        }
    }
    add_action( 'user_register', 'b3_set_role_after_register' );

    // Add approval to admin bar
    function b3_change_admin_bar( $wp_admin_bar ) {
        // @TODO: check in multisite
        if ( current_user_can( 'promote_users' ) ) {
            $approval_users = [];
            $registration_type = get_option( 'b3_registration_type' );
            $admin_approval    = get_option( 'b3_needs_admin_approval' );

            if ( $admin_approval ) {
                if ( is_multisite() ) {
                    $site_id = ! is_main_site() ? get_current_blog_id() : 0;

                    $meta_query = [
                        [
                            'key'   => 'pending',
                            'value' => '1',
                        ],
                    ];
                    $approval_args  = [ 'blog_id' => 0, 'meta_query' => $meta_query ];

                } else {
                    $approval_args  = [ 'role' => 'b3_approval' ];
                }
                $approval_users = get_users( $approval_args );
            }

            if ( 0 < count( $approval_users ) ) {
                $page_link     = admin_url( 'admin.php?page=b3-user-approval' );
                $approval_args = [
                    'id'    => 'approval',
                    'title' => '&rarr; ' . esc_attr__( 'Approve', 'b3-onboarding' ) . ' (' . count( $approval_users ) . ')',
                    'href'  => $page_link,
                    'meta'  => [ 'class' => 'topbar_approve_user' ],
                ];
                $wp_admin_bar->add_node( $approval_args );
            }
        }

        if ( current_user_can( 'manage_options' ) ) {
            if ( get_option( 'b3_activate_filter_validation' ) ) {
                $page_link     = admin_url( 'admin.php?page=b3-onboarding&tab=settings' );
                $approval_args = [
                    'id'    => 'verify_filters',
                    'title' => '&rarr; ' . esc_attr__( 'Filter verification active', 'b3-onboarding' ),
                    'href'  => $page_link,
                    'meta'  => [ 'class' => 'topbar_verify_filters' ],
                ];
                $wp_admin_bar->add_node( $approval_args );
            }
        }
    }
    add_action( 'admin_bar_menu', 'b3_change_admin_bar', 80 );

    // Do stuff after signup WPMU user (only)
    function b3_after_signup_user( $user_login, $user_email, $key, $meta = [] ) {
        if ( ! is_admin() ) {
            $current_network = get_network();
            $subject         = sprintf( b3_get_wpmu_activate_user_subject(), $current_network->site_name );
            $message         = sprintf( b3_get_wpmu_activate_user_message(), $user_login, b3_get_login_url() . "?activate=user&key={$key}" );
            $message         = b3_replace_template_styling( $message );
            $message         = strtr( $message, b3_get_replacement_vars() );
            $message         = htmlspecialchars_decode( stripslashes( $message ) );
            wp_mail( $user_email, $subject, $message, [] );
        }
    }
    add_action( 'after_signup_user', 'b3_after_signup_user', 11, 4 );

    // Do stuff after activate wpmu user only
    function b3_after_activate_user( $user_id, $password, $meta = [] ) {
        $current_network = get_network();
        $user            = get_userdata( $user_id );

        if ( get_option( 'b3_needs_admin_approval' ) ) {
            $subject = sprintf( esc_html__( 'Account activated for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
            $message = b3_get_default_request_access_message_user( true );

            global $wpdb;
            $meta[ 'pending' ] = 1;
            $data[ 'meta' ]    = serialize( $meta );
            $table             = $wpdb->signups;
            $where             = [ 'user_login' => $user->user_login ];
            $wpdb->update( $table, $data, $where );

        } else {
            $subject = sprintf( b3_get_wpmu_user_activated_subject(), $current_network->site_name, $user->user_login );
        }

        if ( ! empty( $meta ) ) {
            foreach( $meta as $meta_key => $meta_value ) {
                update_user_meta( $user_id, $meta_key, $meta_value );
            }
        }

        if ( ! isset( $message ) ) {
            $message = sprintf( b3_get_wpmu_user_activated_message( $user->user_email ), $user->user_login, $user->user_login, $password, b3_get_login_url(), $current_network->site_name );
        }

        $message = b3_replace_template_styling( $message );
        $message = strtr( $message, b3_get_replacement_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user->user_email, $subject, $message, [] );
    }
    add_action( 'wpmu_activate_user', 'b3_after_activate_user', 10, 3 );

    // Override activate new wpmu user + blog message
    function b3_override_new_mu_user_blog_email( $domain, $path, $title, $user_login, $user_email, $key, $meta ) {
        $current_network   = get_network();
        $registration_type = get_option( 'b3_registration_type' );
        $blog_id           = b3_get_signup_id( $domain );
        $subject           = strtr( b3_get_wpmu_activate_user_blog_subject(), b3_get_replacement_vars( 'message', [ 'blog_id' => $blog_id ] ) );
        $message           = b3_get_wpmu_activate_user_blog_message();
        $message           = b3_replace_template_styling( $message );
        $message           = strtr( $message, b3_get_replacement_vars( 'message', [
            'domain' => $domain,
            'key'    => $key,
            'path'   => $path,
        ], true ) );
        $message           = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user_email, $subject, $message, [] );
    }
    add_action( 'after_signup_site', 'b3_override_new_mu_user_blog_email', 10, 7 );

    // Override welcome mu user email message
    function b3_override_welcome_mu_user_blog_message( $blog_id, $user_id, $password, $title, $meta ) {
        $user_data = get_userdata( $user_id );
        $subject   = strtr( b3_get_wpmu_activated_user_blog_subject(), b3_get_replacement_vars( 'message', [ 'blog_id' => $blog_id ] ) );
        $message   = b3_get_wpmu_activated_user_blog_message( $user_data->user_login, $user_data->user_email );
        $message   = b3_replace_template_styling( $message );
        $message   = strtr( $message, b3_get_replacement_vars( 'message', [
            'blog_id'       => $blog_id,
            'user_data'     => $user_data,
            'user_password' => $password,
        ] ) );
        $message   = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user_data->user_email, $subject, $message, [] );
    }
    add_action( 'wpmu_activate_blog', 'b3_override_welcome_mu_user_blog_message', 10, 5 );

    // add network admin notices
    function b3_network_admin_notices() {
        if ( 'settings-network' === get_current_screen()->id ) {
            // translators: 1. plugin name, 2. link to tab registration, 3. link to tab emails
            echo sprintf( '<div class="notice notice-info"><p>'. esc_html__( '%1$s overrides the \'Registration\' option and the \'Registration notification\'. You can change the registration type %2$s and the registration notification %3$s.', 'b3-onboarding' ) . '</p></div>',
                'B3 OnBoarding',
                sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=registration' ) ), esc_html__( 'here', 'b3-onboarding' ) ),
                sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) ), esc_html__( 'here', 'b3-onboarding' ) )
            );
        }

        $plugin = get_plugin_data( B3OB_PLUGIN_PATH . '/B3Onboarding.php' );
        if ( strpos( $plugin[ 'Version' ], 'dev' ) !== false || strpos( $plugin[ 'Version' ], 'beta' ) !== false ) {
            // translators: plugin name
            $warning_message = sprintf( esc_html__( "You're using a development version of %s, which has not been released yet and can give some unexpected results.", 'b3-onboarding' ), 'B3 OnBoarding' );
            $notice          = sprintf( '<div class="notice notice-warning"><p>%s</p></div>', $warning_message );
            if ( false === apply_filters( 'b3_hide_development_notice', false ) ) {
                echo wp_kses_post( $notice );
            }
        }
    }
    add_action( 'network_admin_notices', 'b3_network_admin_notices' );

    // ajax-handling of file download
    function b3_handle_file_download() {
        if ( ! isset( $_GET[ 'file' ] ) ) {
            wp_die( 'Missing file parameter.' );
        }

        $allowed_files = [
            'default-email-styling.css'   => trailingslashit( B3OB_PLUGIN_PATH ) . 'includes/default-email-styling.css',
            'default-email-template.html' => trailingslashit( B3OB_PLUGIN_PATH ) . 'includes/default-email-template.html',
        ];

        $file_key = sanitize_text_field( $_GET[ 'file' ] );

        if ( ! isset( $allowed_files[ $file_key ] ) ) {
            wp_die( 'Invalid file selection.' );
        }

        $file_path = $allowed_files[ $file_key ];

        if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
            if ( ob_get_level() ) {
                ob_end_clean();
            }

            header( 'Content-Description: File Transfer' );
            header( 'Content-Type: application/octet-stream' );
            header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
            header( 'Expires: 0' );
            header( 'Cache-Control: must-revalidate' );
            header( 'Pragma: public' );
            header( 'Content-Length: ' . filesize( $file_path ) );

            readfile( $file_path );
            exit;
        }

        wp_die( 'File not found.' );
    }
    add_action( 'admin_post_b3_download', 'b3_handle_file_download' );

    // Initiates email activation
    function b3_do_user_activate() {
        if ( is_multisite() ) {
            if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && 'GET' === $_SERVER[ 'REQUEST_METHOD' ] && isset( $_GET[ 'activate' ] ) && 'user' === $_GET[ 'activate' ] ) {
                $redirect_url      = b3_get_login_url();
                $valid_error_codes = [ 'already_active', 'blog_taken' ];
                $request_uri       = isset( $_SERVER[ 'REQUEST_URI' ] ) ? sanitize_text_field( wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) ) : '';
                [ $activate_path ] = explode( '?', $request_uri );
                $activate_cookie   = 'wp-activate-' . COOKIEHASH;
                $key               = '';
                $result            = null;

                if ( isset( $_GET[ 'key' ] ) && isset( $_POST[ 'key' ] ) && $_GET[ 'key' ] !== $_POST[ 'key' ] ) {
                    wp_die( esc_html__( 'A key value mismatch has been detected. Please follow the link provided in your activation email.','b3-onboarding' ), esc_html__( 'An error occurred during the activation', 'b3-onboarding' ), 400 );
                } elseif ( ! empty( $_GET[ 'key' ] ) ) {
                    $key = sanitize_key( $_GET[ 'key' ] );
                } elseif ( ! empty( $_POST[ 'key' ] ) ) {
                    $key = sanitize_key( $_POST[ 'key' ] );
                }

                if ( $key ) {
                    $redirect_url = remove_query_arg( 'key' );

                    if ( remove_query_arg( false ) !== $redirect_url ) {
                        setcookie( $activate_cookie, $key, 0, $activate_path, COOKIE_DOMAIN, is_ssl(), true );
                        wp_safe_redirect( $redirect_url );
                        exit;
                    } else {
                        $result = wpmu_activate_signup( $key );
                    }
                }

                if ( null === $result && isset( $_COOKIE[ $activate_cookie ] ) ) {
                    $key    = sanitize_key( $_COOKIE[ $activate_cookie ] );
                    $result = wpmu_activate_signup( $key );
                    setcookie( $activate_cookie, ' ', time() - YEAR_IN_SECONDS, $activate_path, COOKIE_DOMAIN, is_ssl(), true );
                }

                if ( null === $result || ( is_wp_error( $result ) && 'invalid_key' === $result->get_error_code() ) ) {
                    status_header( 404 );
                } elseif ( is_wp_error( $result ) ) {
                    $error_code = $result->get_error_code();

                    if ( ! in_array( $error_code, $valid_error_codes, true ) ) {
                        status_header( 400 );
                    }
                }

                if ( ! is_wp_error( $result ) ) {
                    if ( get_option( 'b3_needs_admin_approval' ) ) {
                        do_action( 'b3_set_approval_status', $result );
                        do_action( 'b3_inform_admin', 'request_access', $result[ 'user_id' ] );
                        $redirect_url = add_query_arg( [ 'message' => 'activate_approval_needed' ], $redirect_url );

                    } elseif ( get_option( 'b3_use_magic_link' ) ) {
                        do_action( 'b3_inform_admin', 'new_user', $result[ 'user_id' ] );
                        $redirect_url = add_query_arg( [ 'message' => 'activate_success_magic' ], $redirect_url );
                    } else {
                        do_action( 'b3_inform_admin', 'new_user', $result[ 'user_id' ] );
                        $redirect_url = add_query_arg( [ 'mu-activate' => 'success' ], $redirect_url );
                    }
                    wp_safe_redirect( $redirect_url );
                    exit;
                }
            }

        } elseif ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && 'GET' === $_SERVER[ 'REQUEST_METHOD' ] && ! empty( $_GET[ 'action' ] ) && 'activate' === sanitize_text_field( wp_unslash( $_GET[ 'action' ] ) ) && ! empty( $_GET[ 'key' ] ) && ! empty( $_GET[ 'user_login' ] ) ) {
            global $wpdb;
            $errors     = false;
            $key        = preg_replace( '/[^a-zA-Z0-9]/i', '', sanitize_key( $_GET[ 'key' ] ) );
            $user_login = sanitize_user( wp_unslash( $_GET[ 'user_login' ] ) );

            if ( empty( $key ) || ! is_string( $key ) ) {
                $errors = new WP_Error( 'invalid_key', esc_attr__( 'Invalid key', 'b3-onboarding' ) );
            }

            if ( empty( $user_login ) || ! is_string( $user_login ) ) {
                $errors = new WP_Error( 'invalid_key', esc_attr__( 'Invalid key', 'b3-onboarding' ) );
            }

            // Validate activation key
            $cache_group = 'b3ob';
            $cache_key   = 'user_info_' . md5( $user_login );
            // @TODO: test
            $results     = wp_cache_get( $cache_key, $cache_group );

            if ( false === $results ) {
                $user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE user_activation_key = %s AND user_login = %s', $wpdb->users, $key, $user_login ) );
                wp_cache_set( $cache_key, $user, $cache_group );
            }

            if ( empty( $user ) ) {
                $errors = new WP_Error( 'invalid_user', esc_attr__( 'Invalid user', 'b3-onboarding' ) );
            }

            if ( is_wp_error( $errors ) ) {
                // errors found
                $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), b3_get_login_url() );

            } else {
                // remove user_activation_key
                $wpdb->update( $wpdb->users, [ 'user_activation_key' => '' ], [ 'user_login' => $user_login ] );
                // @TODO: test
                wp_cache_delete( $cache_key, $cache_group );

                $admin_approval = get_option( 'b3_needs_admin_approval' ) && ! get_user_meta( $user->ID, 'manually_added', true );
                $use_magic_link = get_option( 'b3_use_magic_link' );

                // activate user, change user role
                $user_object = new WP_User( $user->ID );
                if ( $admin_approval ) {
                    $user_object->set_role( 'b3_approval' );
                } else {
                    $user_object->set_role( get_option( 'default_role' ) );
                }

                if ( $admin_approval || $use_magic_link ) {
                    $redirect_url = b3_get_login_url();
                } elseif ( false == get_option( 'b3_activate_custom_passwords' ) ) {
                    $redirect_url = b3_get_lostpassword_url();
                } else {
                    $redirect_url = b3_get_login_url();
                }

                if ( $admin_approval ) {
                    $redirect_url = add_query_arg( [ 'activate' => 'success_approval' ], $redirect_url );
                } elseif ( $use_magic_link ) {
                    $redirect_url = add_query_arg( [ 'activate' => 'magic' ], $redirect_url );
                } else {
                    $redirect_url = add_query_arg( [ 'activate' => 'success' ], $redirect_url );
                }

                do_action( 'b3_after_user_activated', $user->ID );
            }

            wp_safe_redirect( $redirect_url );
            exit;
        }
    }
    add_action( 'init', 'b3_do_user_activate' );
