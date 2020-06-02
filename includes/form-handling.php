<?php

    // Admin settings
    function b3_admin_form_handling() {

        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_settings_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ 'b3_settings_nonce' ], 'b3-settings-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    // Custom passwords (not used yet)
                    if ( isset( $_POST[ 'b3_activate_custom_passwords' ] ) && 1 == $_POST[ 'b3_activate_custom_passwords' ] ) {
                        update_option( 'b3_custom_passwords', '1', true );
                    } else {
                        delete_option( 'b3_custom_passwords' );
                    }

                    // Custom login page
                    if ( isset( $_POST[ 'b3_style_default_pages' ] ) && 1 == $_POST[ 'b3_style_default_pages' ] ) {
                        update_option( 'b3_style_default_pages', 1, true );
                    } else {
                        delete_option( 'b3_style_default_pages' );
                    }

                    // Force custom login page
                    if ( isset( $_POST[ 'b3_force_custom_login_page' ] ) && 1 == $_POST[ 'b3_force_custom_login_page' ] ) {
                        update_option( 'b3_force_custom_login_page', 1, true );
                    } else {
                        delete_option( 'b3_force_custom_login_page' );
                    }

                    if ( ( isset( $_POST[ 'b3_style_default_pages' ] ) && 1 == $_POST[ 'b3_style_default_pages' ] ) && ( isset( $_POST[ 'b3_force_custom_login_page' ] ) && 1 == $_POST[ 'b3_force_custom_login_page' ] ) ) {
                        // can't be at same time
                        update_option( 'b3_force_custom_login_page', 1, true );
                        delete_option( 'b3_style_default_pages' );
                    }

                    if ( isset( $_POST[ 'b3_debug_info' ] ) && 1 == $_POST[ 'b3_debug_info' ] ) {
                        update_option( 'b3_debug_info', 1, true );
                    } else {
                        delete_option( 'b3_debug_info' );
                    }

                    // Sidebar widget
                    if ( isset( $_POST[ 'b3_activate_sidebar_widget' ] ) && 1 == $_POST[ 'b3_activate_sidebar_widget' ] ) {
                        update_option( 'b3_sidebar_widget', 1, true );
                    } else {
                        delete_option( 'b3_sidebar_widget' );
                    }

                    // Dashboard widget (not in use yet)
                    if ( isset( $_POST[ 'b3_activate_dashboard_widget' ] ) && 1 == $_POST[ 'b3_activate_dashboard_widget' ] ) {
                        update_option( 'b3_dashboard_widget', 1, true );
                    } else {
                        delete_option( 'b3_dashboard_widget' );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'General settings saved', 'b3-onboarding' ) );

                    return;

                }

            } elseif ( isset( $_POST[ 'b3_pages_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ "b3_pages_nonce" ], 'b3-pages-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    $page_ids = [
                        'b3_account_page_id',
                        'b3_forgotpass_page_id',
                        'b3_login_page_id',
                        'b3_logout_page_id',
                        'b3_register_page_id',
                        'b3_resetpass_page_id',
                    ];
                    if ( isset( $_POST[ 'b3_approval_page_id' ] ) ) {
                        $page_ids[] = 'b3_approval_page_id';
                    }
                    foreach( $page_ids as $page ) {
                        $old_id = get_option( $page );
                        update_option( $page, $_POST[ $page ], true );
                        delete_post_meta( $old_id, '_b3_page' );
                        update_post_meta( $_POST[ $page ], '_b3_page', true );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Pages settings saved', 'b3-onboarding' ) );

                    return;
                }

            } elseif ( isset( $_POST[ 'b3_registration_nonce' ] ) ) {
                if ( ! wp_verify_nonce( $_POST[ "b3_registration_nonce" ], 'b3-registration-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    // Registration options
                    if ( isset( $_POST[ 'b3_registration_type' ] ) ) {
                        if ( is_multisite() ) {
                            if ( is_main_site() ) {
                                $ms_registration_type = $_POST[ 'b3_registration_type' ];
                                if ( 'closed' == $ms_registration_type ) {
                                    $registration_type = 'none';
                                    update_option( 'b3_registration_type', $ms_registration_type );
                                } elseif ( 'request_access_subdomain' == $ms_registration_type ) {
                                    // not in use (yet)
                                    $registration_type = '';
                                    update_option( 'b3_registration_type', $ms_registration_type );
                                } elseif ( 'ms_loggedin_register' == $ms_registration_type ) {
                                    $registration_type = 'blog';
                                    update_option( 'b3_registration_type', $ms_registration_type );
                                } elseif ( 'ms_register_user' == $ms_registration_type ) {
                                    $registration_type = 'user';
                                    update_option( 'b3_registration_type', $ms_registration_type );
                                } elseif ( 'ms_register_site_user' == $ms_registration_type ) {
                                    $registration_type = 'all';
                                    update_option( 'b3_registration_type', $ms_registration_type );
                                }
                                update_site_option( 'registration', $registration_type );
                            }
                        } else {
                            if ( 'closed' == $_POST[ 'b3_registration_type' ] ) {
                                update_option( 'users_can_register', '0', true );
                            } else {
                                update_option( 'users_can_register', '1', true );
                            }
                            update_option( 'b3_registration_type', $_POST[ 'b3_registration_type' ], true );
                        }
                    }

                    // First/last name
                    if ( isset( $_POST[ 'b3_activate_first_last' ] ) && 1 == $_POST[ 'b3_activate_first_last' ] ) {
                        update_option( 'b3_activate_first_last', $_POST[ 'b3_activate_first_last' ], true );
                    } else {
                        delete_option( 'b3_activate_first_last' );
                        delete_option( 'b3_first_last_required' );
                    }

                    // First/last name
                    if ( isset( $_POST[ 'b3_first_last_required' ] ) && 1 == $_POST[ 'b3_first_last_required' ] ) {
                        update_option( 'b3_first_last_required', $_POST[ 'b3_first_last_required' ], true );
                    } else {
                        delete_option( 'b3_first_last_required' );
                    }

                    // reCAPTCHA
                    if ( isset( $_POST[ 'b3_activate_recaptcha' ] ) && 1 == $_POST[ 'b3_activate_recaptcha' ] ) {
                        update_option( 'b3_recaptcha', 1, true );
                    } else {
                        delete_option( 'b3_recaptcha' );
                    }

                    // reCAPTCHA
                    if ( isset( $_POST[ 'b3_recaptcha_login' ] ) && 1 == $_POST[ 'b3_recaptcha_login' ] ) {
                        update_option( 'b3_recaptcha_login', 1, true );
                        if ( ! isset( $_POST[ 'b3_activate_recaptcha' ] ) || 0 == $_POST[ 'b3_activate_recaptcha' ] ) {
                            delete_option( 'b3_recaptcha_login' );
                        }
                    } else {
                        delete_option( 'b3_recaptcha_login' );
                    }

                    // Privacy (not in use yet)
                    if ( isset( $_POST[ 'b3_activate_privacy' ] ) && 1 == $_POST[ 'b3_activate_privacy' ] ) {
                        update_option( 'b3_privacy', 1, true );
                    } else {
                        delete_option( 'b3_privacy' );
                    }

                    if ( isset( $_POST[ 'b3_privacy_page' ] ) ) {
                        if ( filter_var( $_POST[ 'b3_privacy_page' ], FILTER_VALIDATE_URL ) === false ) {
                            // @TODO: do something if url is invalid
                        }
                        update_option( 'b3_privacy_page', $_POST[ 'b3_privacy_page' ], true );
                    } else {
                        delete_option( 'b3_privacy_page' );
                    }

                    if ( isset( $_POST[ 'b3_privacy_text' ] ) ) {
                        update_option( 'b3_privacy_text', $_POST[ 'b3_privacy_text' ], true );
                    }

                    // Action links
                    if ( isset( $_POST[ 'b3_disable_action_links' ] ) && 1 == $_POST[ 'b3_disable_action_links' ] ) {
                        update_option( 'b3_disable_action_links', 1, true );
                    } else {
                        delete_option( 'b3_disable_action_links' );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Registration settings saved', 'b3-onboarding' ) );

                    return;

                }

            } elseif ( isset( $_POST[ 'b3_loginpage_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ "b3_loginpage_nonce" ], 'b3-loginpage-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    if ( ! empty( $_POST[ 'b3_loginpage_bg_color' ] ) ) {
                        $color = $_POST[ 'b3_loginpage_bg_color' ];
                        if ( '#' == substr( $_POST[ 'b3_loginpage_bg_color' ], 0, 1 ) ) {
                            $color = substr( $_POST[ 'b3_loginpage_bg_color' ], 1 );
                        }
                        $length = strlen($color);
                        if ( 3 != $length && 6 != $length ) {
                            B3Onboarding::b3_errors()->add( 'error_wrong_hexlength', esc_html__( 'Then length of your hex code is incorrect.', 'b3-onboarding' ) );

                            return;
                        } else {
                            update_option( 'b3_loginpage_bg_color', $color );
                        }
                    } else {
                        delete_option( 'b3_loginpage_bg_color' );
                    }

                    update_option( 'b3_loginpage_font_family', $_POST[ 'b3_loginpage_font_family' ] );
                    update_option( 'b3_loginpage_font_size', $_POST[ 'b3_loginpage_font_size' ] );
                    update_option( 'b3_login_logo', $_POST[ 'b3_login_logo' ] );

                    $max_width  = 320;
                    $max_height = 150;
                    if ( $_POST[ 'b3_loginpage_logo_width' ] >= $max_width ) {
                        update_option( 'b3_loginpage_logo_width', $max_width );
                    } else {
                        update_option( 'b3_loginpage_logo_width', $_POST[ 'b3_loginpage_logo_width' ] );
                    }
                    if ( $_POST[ 'b3_loginpage_logo_height' ] >= $max_height ) {
                        update_option( 'b3_loginpage_logo_height', $max_height );
                    } else {
                        update_option( 'b3_loginpage_logo_height', $_POST[ 'b3_loginpage_logo_height' ] );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Login page settings saved', 'b3-onboarding' ) );

                    return;

                }

            } elseif ( isset( $_POST[ 'b3_emails_nonce' ] ) ) {
                if ( ! wp_verify_nonce( $_POST[ "b3_emails_nonce" ], 'b3-emails-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    update_option( 'b3_notification_sender_email', $_POST[ 'b3_notification_sender_email' ], true );
                    update_option( 'b3_notification_sender_name', $_POST[ 'b3_notification_sender_name' ], true );
                    update_option( 'b3_forgot_password_message', htmlspecialchars( $_POST[ 'b3_forgot_password_message' ] ), true );
                    update_option( 'b3_forgot_password_subject', $_POST[ 'b3_forgot_password_subject' ], true );

                    if ( isset( $_POST[ 'b3_activate_custom_emails' ] ) && 1 == $_POST[ 'b3_activate_custom_emails' ] ) {
                        update_option( 'b3_custom_emails', 1, true );
                    } else {
                        delete_option( 'b3_custom_emails' );
                    }

                    if ( isset( $_POST[ 'b3_logo_in_email' ] ) && 1 == $_POST[ 'b3_logo_in_email' ] ) {
                        update_option( 'b3_logo_in_email', 1, true );
                    } else {
                        delete_option( 'b3_logo_in_email' );
                    }

                    if ( isset( $_POST[ 'b3_email_logo' ] ) && ! empty( $_POST[ 'b3_email_logo' ] ) ) {
                        update_option( 'b3_email_logo', $_POST[ 'b3_email_logo' ], true );
                    } else {
                        delete_option( 'b3_email_logo' );
                    }

                    if ( isset( $_POST[ 'b3_email_styling' ] ) ) {
                        update_option( 'b3_email_styling', $_POST[ 'b3_email_styling' ], true );
                    }
                    if ( isset( $_POST[ 'b3_email_template' ] ) ) {
                        update_option( 'b3_email_template', stripslashes( $_POST[ 'b3_email_template' ] ), true );
                    }

                    if ( isset( $_POST[ 'b3_disable_admin_notification_password_change' ] ) && 1 == $_POST[ 'b3_disable_admin_notification_password_change' ] ) {
                        update_option( 'b3_disable_admin_notification_password_change', 1, true );
                    } else {
                        delete_option( 'b3_disable_admin_notification_password_change' );
                    }

                    if ( isset( $_POST[ 'b3_disable_admin_notification_new_user' ] ) && 1 == $_POST[ 'b3_disable_admin_notification_new_user' ] ) {
                        update_option( 'b3_disable_admin_notification_new_user', 1, true );
                    } else {
                        delete_option( 'b3_disable_admin_notification_new_user' );
                    }

                    if ( in_array( get_option( 'b3_registration_type', [] ), [ 'open', 'email_activation' ] ) ) {
                        if ( isset( $_POST[ 'b3_account_activated_subject' ] ) ) {
                            update_option( 'b3_account_activated_subject', $_POST[ 'b3_account_activated_subject' ], true );
                        }
                        if ( isset( $_POST[ 'b3_account_activated_message' ] ) ) {
                            update_option( 'b3_account_activated_message', htmlspecialchars( $_POST[ 'b3_account_activated_message' ] ), true );
                        }

                        update_option( 'b3_new_user_message', htmlspecialchars( $_POST[ 'b3_new_user_message' ] ), true );
                        update_option( 'b3_new_user_notification_addresses', $_POST[ 'b3_new_user_notification_addresses' ], true );
                        update_option( 'b3_new_user_subject', $_POST[ 'b3_new_user_subject' ], true );

                        if ( isset( $_POST[ 'b3_welcome_user_message' ] ) ) {
                            update_option( 'b3_welcome_user_message', htmlspecialchars( $_POST[ 'b3_welcome_user_message' ] ), true );
                        }
                        if ( isset( $_POST[ 'b3_welcome_user_subject' ] ) ) {
                            update_option( 'b3_welcome_user_subject', stripslashes( $_POST[ 'b3_welcome_user_subject' ] ), true );
                        }

                        if ( in_array( get_option( 'b3_registration_type' ), [ 'email_activation' ] ) ) {
                            update_option( 'b3_email_activation_subject', stripslashes( $_POST[ 'b3_email_activation_subject' ] ), true );
                            update_option( 'b3_email_activation_message', htmlspecialchars( $_POST[ 'b3_email_activation_message' ] ), true );
                        }

                    }

                    if ( 'request_access' == get_option( 'b3_registration_type', false ) ) {
                        update_option( 'b3_account_approved_message', htmlspecialchars( $_POST[ 'b3_account_approved_message' ], ENT_QUOTES ), true );
                        update_option( 'b3_account_approved_subject', $_POST[ 'b3_account_approved_subject' ], true );
                        update_option( 'b3_request_access_message_admin', htmlspecialchars( $_POST[ 'b3_request_access_message_admin' ] ), true );
                        update_option( 'b3_request_access_message_user', htmlspecialchars( $_POST[ 'b3_request_access_message_user' ] ), true );
                        update_option( 'b3_request_access_notification_addresses', $_POST[ 'b3_request_access_notification_addresses' ], true );
                        update_option( 'b3_request_access_subject_admin', $_POST[ 'b3_request_access_subject_admin' ], true );
                        update_option( 'b3_request_access_subject_user', $_POST[ 'b3_request_access_subject_user' ], true );
                        update_option( 'b3_account_rejected_message', htmlspecialchars( $_POST[ 'b3_account_rejected_message' ], ENT_QUOTES ), true );
                        update_option( 'b3_account_rejected_subject', $_POST[ 'b3_account_rejected_subject' ], true );

                        if ( isset( $_POST[ 'b3_disable_delete_user_email' ] ) && 1 == $_POST[ 'b3_disable_delete_user_email' ] ) {
                            update_option( 'b3_disable_delete_user_email', 1, true );
                        } else {
                            delete_option( 'b3_disable_delete_user_email' );
                        }

                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Email settings saved', 'b3-onboarding' ) );

                    return;
                }

            } elseif ( isset( $_POST[ 'b3_users_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ "b3_users_nonce" ], 'b3-users-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    // Front-end approval
                    if ( isset( $_POST[ 'b3_activate_frontend_approval' ] ) && 1 == $_POST[ 'b3_activate_frontend_approval' ] ) {
                        update_option( 'b3_front_end_approval', 1, true );
                    } else {
                        delete_option( 'b3_front_end_approval' );
                        delete_option( 'b3_approval_page_id' );
                    }

                    // Restrict admin
                    if ( isset( $_POST[ 'b3_restrict_admin' ] ) ) {
                        update_option( 'b3_restrict_admin', $_POST[ 'b3_restrict_admin' ], true );
                    } else {
                        delete_option( 'b3_restrict_admin' );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'User settings saved', 'b3-onboarding' ) );

                    return;
                }

            } elseif ( isset( $_POST[ 'b3_recaptcha_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ "b3_recaptcha_nonce" ], 'b3-recaptcha-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    update_option( 'b3_recaptcha_public', $_POST[ 'b3_recaptcha_public' ], true );
                    update_option( 'b3_recaptcha_secret', $_POST[ 'b3_recaptcha_secret' ], true );
                    update_option( 'b3_recaptcha_version', $_POST[ 'b3_recaptcha_version' ], true );

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'reCaptcha settings saved', 'b3-onboarding' ) );

                    return;
                }

            }
        }
    }
    add_action( 'admin_init', 'b3_admin_form_handling' );


    // Admin settings
    function b3_approve_deny_users() {

        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_manage_users_nonce' ] ) ) {

                // @TODO: check for real errors here
                $redirect_url = network_admin_url( 'admin.php?page=b3-user-approval' );
                if ( ! is_admin() ) {
                    $approval_link = b3_get_user_approval_id( true );
                    if ( false != $approval_link ) {
                        $redirect_url = $approval_link;
                    }
                }

                if ( ! wp_verify_nonce( $_POST[ 'b3_manage_users_nonce' ], 'b3-manage-users-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    $approve     = ( isset( $_POST[ 'b3_approve_user' ] ) ) ? $_POST[ 'b3_approve_user' ] : false;
                    $reject      = ( isset( $_POST[ 'b3_reject_user' ] ) ) ? $_POST[ 'b3_reject_user' ] : false;
                    $user_id     = ( isset( $_POST[ 'b3_user_id' ] ) ) ? $_POST[ 'b3_user_id' ] : false;
                    $user_object = ( isset( $_POST[ 'b3_user_id' ] ) ) ? new WP_User( $user_id ) : false;

                    if ( false != $approve && isset( $user_object->ID ) ) {
                        // activate user
                        do_action( 'b3_new_user_activated_by_admin', $user_id );
                        $redirect_url = add_query_arg( 'user', 'approved', $redirect_url );

                    } elseif ( false != $reject && isset( $user_object->ID ) ) {

                        require_once( ABSPATH . 'wp-admin/includes/user.php' );
                        // reject user
                        if ( true == wp_delete_user( $user_id ) ) {
                            $redirect_url = add_query_arg( 'user', 'rejected', $redirect_url );
                        } else {
                            // @TODO: add error
                            // $redirect_url = add_query_arg( 'user', 'rejected', $redirect_url );
                        }

                    }
                }

                wp_safe_redirect( $redirect_url );
                exit;
            }
        }
    }
    add_action( 'init', 'b3_approve_deny_users' );


    /**
     * Profile form handling (front-end)
     */
    function b3_profile_form_handling() {

        require_once( ABSPATH . 'wp-admin/includes/user.php' );
        require_once( ABSPATH . 'wp-admin/includes/misc.php' );

        define( 'IS_PROFILE_PAGE', true );
        load_textdomain( 'default', WP_LANG_DIR . '/admin-' . get_locale() . '.mo' );
        register_admin_color_schemes();
        wp_enqueue_script( 'user-profile' );
        $current_user = wp_get_current_user();

        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] && ! empty( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'profile' ) {
            check_admin_referer( 'update-user_' . $current_user->ID );

            if ( ! current_user_can( 'edit_user', $current_user->ID ) ) {
                wp_die( __( 'You do not have permission to edit this user.', 'b3-onboarding' ) );
            }

            $errors = edit_user( $current_user->ID );

            if ( ! is_wp_error( $errors ) ) {
                wp_safe_redirect( add_query_arg( 'updated', 'true' ) );
                exit;
            } else {
                // @TODO: add proper error
                error_log('error in profile form handling');
            }
        }
    }
    add_action( 'init', 'b3_profile_form_handling' );
