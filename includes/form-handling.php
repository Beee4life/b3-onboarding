<?php

    // Admin settings
    function b3_admin_form_handling() {
    
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_settings_nonce' ] ) ) {
    
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=settings' );
                
                if ( ! wp_verify_nonce( $_POST[ "b3_settings_nonce" ], 'b3-settings-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {
                    
                    // Custom passwords
                    if ( isset( $_POST[ 'b3_activate_custom_passwords' ] ) ) {
                        update_option( 'b3_custom_passwords', '1', true );
                    } else {
                        delete_option( 'b3_custom_passwords' );
                    }
                    
                    // Registration options
                    if ( isset( $_POST[ 'b3_registration_type' ] ) ) {
                        update_option( 'b3_registration_type', $_POST[ 'b3_registration_type' ], true );
                    }
                    
                    // Custom emails
                    if ( isset( $_POST[ 'b3_activate_custom_emails' ] ) ) {
                        update_option( 'b3_custom_emails', '1', true );
                    } else {
                        delete_option( 'b3_custom_emails' );
                    }
                    
                    // Custom emails
                    if ( isset( $_POST[ 'b3_activate_frontend_approval' ] ) ) {
                        update_option( 'b3_front_end_approval', '1', true );
                    } else {
                        delete_option( 'b3_front_end_approval' );
                        delete_option( 'b3_approval_page_id' );
                    }
                    
                    // Dashboard widget (not in use yet)
                    if ( isset( $_POST[ 'b3_activate_dashboard_widget' ] ) ) {
                        update_option( 'b3_dashboard_widget', '1', true );
                    } else {
                        delete_option( 'b3_dashboard_widget' );
                    }
                    
                    // Sidebar widget
                    if ( isset( $_POST[ 'b3_activate_sidebar_widget' ] ) ) {
                        update_option( 'b3_sidebar_widget', '1', true );
                    } else {
                        delete_option( 'b3_sidebar_widget' );
                    }
                
                    // reCAPTCHA (not in use yet)
                    if ( isset( $_POST[ 'b3_activate_recaptcha' ] ) ) {
                        update_option( 'b3_recaptcha', '1', true );
                    } else {
                        delete_option( 'b3_recaptcha' );
                    }
                
                    // Privacy (not in use yet)
                    if ( isset( $_POST[ 'b3_activate_privacy' ] ) ) {
                        update_option( 'b3_privacy', '1', true );
                    } else {
                        delete_option( 'b3_privacy' );
                    }
    
                    $redirect_url = add_query_arg( 'success', 'settings_saved', $redirect_url );
    
                }
    
                wp_redirect( $redirect_url );
                exit;
    
            } elseif ( isset( $_POST[ 'b3_pages_nonce' ] ) ) {
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=pages' );
                if ( ! wp_verify_nonce( $_POST[ "b3_pages_nonce" ], 'b3-pages-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {
        
                    $loopable_ids = [
                        'b3_account_page_id',
                        'b3_forgotpass_page_id',
                        'b3_login_page_id',
                        'b3_logout_page_id',
                        'b3_register_page_id',
                        'b3_resetpass_page_id',
                    ];
                    if ( isset( $_POST[ 'b3_approval_page_id' ] ) ) {
                        $loopable_ids[] = 'b3_approval_page_id';
                    }
                    foreach( $loopable_ids as $page ) {
                        $old_id = get_option( $page );
                        update_option( $page, $_POST[ $page ], true );
                        delete_post_meta( $old_id, '_b3_page' );
                        update_post_meta( $_POST[ $page ], '_b3_page', true );
                    }
    
                    $redirect_url = add_query_arg( 'success', 'pages_saved', $redirect_url );
                }
    
                wp_redirect( $redirect_url );
                exit;

            } elseif ( isset( $_POST[ 'b3_emails_nonce' ] ) ) {
    
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=emails' );

                if ( ! wp_verify_nonce( $_POST[ "b3_emails_nonce" ], 'b3-emails-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {
    
                    update_option( 'b3_email_styling', $_POST[ 'b3_email_styling' ], true );
                    update_option( 'b3_email_template', stripslashes( $_POST[ 'b3_email_template' ] ), true );
                    update_option( 'b3_forgot_password_message', stripslashes( $_POST[ 'b3_forgot_password_message' ] ), true );
                    update_option( 'b3_forgot_password_subject', $_POST[ 'b3_forgot_password_subject' ], true );
                    update_option( 'b3_new_user_message', stripslashes( $_POST[ 'b3_new_user_message' ] ), true );
                    update_option( 'b3_new_user_notification_addresses', $_POST[ 'b3_new_user_notification_addresses' ], true );
                    update_option( 'b3_new_user_subject', $_POST[ 'b3_new_user_subject' ], true );
                    update_option( 'b3_notification_sender_email', $_POST[ 'b3_notification_sender_email' ], true );
                    update_option( 'b3_notification_sender_name', $_POST[ 'b3_notification_sender_name' ], true );
                    update_option( 'b3_welcome_user_message', stripslashes( $_POST[ 'b3_welcome_user_message' ] ), true );
                    update_option( 'b3_welcome_user_subject', $_POST[ 'b3_welcome_user_subject' ], true );
    
                    if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                        update_option( 'b3_request_access_message', stripslashes( $_POST[ 'b3_request_access_message' ] ), true );
                        update_option( 'b3_request_access_notification_addresses', $_POST[ 'b3_request_access_notification_addresses' ], true );
                        update_option( 'b3_request_access_subject', $_POST[ 'b3_request_access_subject' ], true );
                    }
    
                    $redirect_url = add_query_arg( 'success', 'emails_saved', $redirect_url );
    
                }
        
                wp_redirect( $redirect_url );
                exit;
    
            } elseif ( isset( $_POST[ 'b3_users_nonce' ] ) ) {
    
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=users' );
    
                if ( ! wp_verify_nonce( $_POST[ "b3_users_nonce" ], 'b3-users-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch' );
                } else {
                    
                    // Restrict admin
                    if ( isset( $_POST[ 'b3_restrict_admin' ] ) ) {
                        update_option( 'b3_restrict_admin', $_POST[ 'b3_restrict_admin' ], true );
                    } else {
                        delete_option( 'b3_restrict_admin' );
                    }
        
                    // Custom pages
                    if ( isset( $_POST[ 'b3_themed_profile' ] ) ) {
                        update_option( 'b3_themed_profile', $_POST[ 'b3_themed_profile' ], true );
                    } else {
                        delete_option( 'b3_themed_profile' );
                    }

                    // First/last name
                    if ( isset( $_POST[ 'b3_activate_first_last' ] ) ) {
                        update_option( 'b3_activate_first_last', $_POST[ 'b3_activate_first_last' ], true );
                    } else {
                        delete_option( 'b3_activate_first_last' );
                    }
        
                    // First/last name
                    if ( isset( $_POST[ 'b3_first_last_required' ] ) ) {
                        update_option( 'b3_first_last_required', $_POST[ 'b3_first_last_required' ], true );
                    } else {
                        delete_option( 'b3_first_last_required' );
                    }
        
                    $redirect_url = add_query_arg( 'success', 'settings_saved', $redirect_url );
        
                }
    
                wp_redirect( $redirect_url );
                exit;
    
            } elseif ( isset( $_POST[ 'b3_recaptcha_nonce' ] ) ) {
    
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=recaptcha' );
    
                if ( ! wp_verify_nonce( $_POST[ "b3_recaptcha_nonce" ], 'b3-recaptcha-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {
    
                    update_option( 'b3_recaptcha_public', $_POST[ 'b3_recaptcha_public' ], true );
                    update_option( 'b3_recaptcha_secret', $_POST[ 'b3_recaptcha_secret' ], true );
                    
                    $redirect_url = add_query_arg( 'success', 'recaptcha_saved', $redirect_url );
                
                }
    
                wp_redirect( $redirect_url );
                exit;

            }
        }
    }
    add_action( 'admin_init', 'b3_admin_form_handling' );
    
    
    // Admin settings
    function b3_approve_deny_users() {
    
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_manage_users_nonce' ] ) ) {
            
                if ( is_admin() ) {
                    $redirect_url = admin_url( 'admin.php?page=b3-user-approval' );
                } else {
                    $redirect_url = home_url( 'user-management' );
                }
            
                if ( ! wp_verify_nonce( $_POST[ "b3_manage_users_nonce" ], 'b3-manage-users-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {
                    
                    $approve   = ( isset( $_POST[ 'b3_approve_user' ] ) ) ? $_POST[ 'b3_approve_user' ] : false;
                    $reject    = ( isset( $_POST[ 'b3_reject_user' ] ) ) ? $_POST[ 'b3_reject_user' ] : false;
                    $user_id   = ( isset( $_POST[ 'b3_user_id' ] ) ) ? $_POST[ 'b3_user_id' ] : false;
                    $user_object = ( isset( $_POST[ 'b3_user_id' ] ) ) ? new WP_User( $user_id ) : false;
                    
                    if ( false != $approve && isset( $user_object->ID ) ) {
    
                        // activate user
                        $user_object = new WP_User( $user_id );
                        $user_object->set_role( get_option( 'default_role' ) );
    
                        // send mail
                        $blog_name  = get_option( 'blogname' ); // @TODO: add filter
                        $from_email = get_option( 'admin_email' ); // @TODO: add filter
                        $to         = $user_object->user_email;
                        $subject    = esc_html__( 'Account approved', 'b3-onboarding' );
                        $message    = sprintf( esc_html__( 'Welcome to %s. Your account has been approved and you can now login.', 'b3-onboarding' ), $blog_name );
                        $headers    = array(
                            'From: ' . $blog_name . ' <' . $from_email . '>',
                            'Content-Type: text/plain; charset=UTF-8',
                        );
                        
                        wp_mail( $to, $subject, $message, $headers );
    
                        do_action( 'b3_new_user_activated', $user_id );
                        do_action( 'b3_new_user_activated_by_admin', $user_id );
    
                        $redirect_url = add_query_arg( 'user', 'approved', $redirect_url );
    
                    } elseif ( false != $reject && isset( $user_object->ID ) ) {
    
                        require_once(ABSPATH.'wp-admin/includes/user.php' );
                        // do reject user
                        if ( true == wp_delete_user( $user_id ) ) {
                            // send mail
                            $blog_name  = get_option( 'blogname' ); // @TODO: add filter
                            $from_email = get_option( 'admin_email' ); // @TODO: add filter
                            $to         = $user_object->user_email;
                            $subject    = sprintf( esc_html__( 'Account rejected for %s', 'b3-onboarding' ), $blog_name );
                            $message    = sprintf( esc_html__( "We're sorry to have to inform you, but your request for access to %s was rejected.", "b3-onboarding" ), $blog_name );
                            $headers    = array(
                                'From: ' . $blog_name . ' <' . $from_email . '>',
                                'Content-Type: text/plain; charset=UTF-8',
                            );
                            wp_mail( $to, $subject, $message, $headers );
    
                            do_action( 'b3_new_user_rejected', $user_id );
    
                            $redirect_url = add_query_arg( 'user', 'rejected', $redirect_url );
                        } else {
                            // @TODO: add error
                        }
    
                    }
                }
                
                wp_redirect( $redirect_url );
                exit;
            }
        }
    }
    add_action( 'init', 'b3_approve_deny_users' );
    
    
    /**
     * Profile form handling
     */
    function b3_profile_form_handling() {
    
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] && ! empty( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'profile' ) {
            if ( isset( $_POST[ 'b3_profile_nonce' ] ) ) {
    
                $redirect_url = home_url( 'account' );
                if ( ! wp_verify_nonce( $_POST[ "b3_profile_nonce" ], 'b3-profile-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {
                    global $current_user, $wp_roles;
    
                    require_once( ABSPATH . 'wp-admin/includes/user.php' );
                    require_once( ABSPATH . 'wp-admin/includes/misc.php' );
    
                    define( 'IS_PROFILE_PAGE', true );
    
                    $errors = edit_user( $current_user->ID );
    
                    if ( ! is_wp_error( $errors ) ) {
                        $args     = array( 'updated' => 'true' );
                        $redirect = add_query_arg( $args );
                        wp_redirect( $redirect );
                        exit;
                    } else {
                        error_log('error in profile form handling');
                    }
    
                    if ( ! empty( $_POST[ 'first_name' ] ) ) {
                        wp_update_user( array( 'ID' => $current_user->ID, 'first_name' => esc_attr( $_POST[ 'first_name' ] ) ) );
                    }
                    if ( ! empty( $_POST[ 'last_name' ] ) ) {
                        wp_update_user( array( 'ID' => $current_user->ID, 'last_name' => esc_attr( $_POST[ 'last_name' ] ) ) );
                    }
                }
            }
        }
    }
    add_action( 'init', 'b3_profile_form_handling' );
    
