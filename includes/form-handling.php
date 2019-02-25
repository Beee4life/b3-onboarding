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
                    
                    // Dashboard widget
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
                
                    // reCAPTCHA
                    if ( isset( $_POST[ 'b3_activate_recaptcha' ] ) ) {
                        update_option( 'b3_recaptcha', '1', true );
                    } else {
                        delete_option( 'b3_recaptcha' );
                    }
                
                    // Privacy
                    if ( isset( $_POST[ 'b3_activate_privacy' ] ) ) {
                        update_option( 'b3_privacy', '1', true );
                    } else {
                        delete_option( 'b3_privacy' );
                    }
                
                    // reCAPTCHA
                    if ( isset( $_POST[ 'b3_activate_custom_emails' ] ) ) {
                        update_option( 'b3_custom_emails', '1', true );
                    } else {
                        delete_option( 'b3_custom_emails' );
                        // @TODO: delete all custom emails
                    }
    
                    $redirect_url = add_query_arg( 'success', 'settings_saved', $redirect_url );
    
                }
    
                wp_redirect( $redirect_url );
                exit;
    
            } elseif ( isset( $_POST[ 'b3_pages_nonce' ] ) ) {
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=pages' );
                if ( ! wp_verify_nonce( $_POST[ "b3_pages_nonce" ], 'b3-pages-nonce' ) ) {
                    // @TODO: add error ?
                } else {
        
                    $loopable_ids = [
                        'b3_forgotpass_page_id',
                        'b3_login_page_id',
                        'b3_register_page_id',
                        'b3_resetpass_page_id',
                    ];
                    foreach( $loopable_ids as $page ) {
                        update_option( $page, $_POST[ $page ], true );
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
    
                    update_option( 'b3_notification_sender_name', $_POST[ 'b3_notification_sender_name' ], true );
                    update_option( 'b3_notification_sender_email', $_POST[ 'b3_notification_sender_email' ], true );
                    // update_option( 'b3_mail_sending_method', $_POST[ 'b3_mail_sending_method' ], true );
                    update_option( 'b3_welcome_user_subject', $_POST[ 'b3_welcome_user_subject' ], true );
                    update_option( 'b3_welcome_user_message', $_POST[ 'b3_welcome_user_message' ], true );
                    update_option( 'b3_new_user_subject', $_POST[ 'b3_new_user_subject' ], true );
                    update_option( 'b3_new_user_message', $_POST[ 'b3_new_user_message' ], true );
                    update_option( 'b3_request_access_subject', $_POST[ 'b3_request_access_subject' ], true );
                    update_option( 'b3_request_access_message', $_POST[ 'b3_request_access_message' ], true );
                    update_option( 'b3_email_styling', $_POST[ 'b3_email_styling' ], true );
                    update_option( 'b3_email_template', $_POST[ 'b3_email_template' ], true );

                    // if ( "0" == $_POST[ 'b3_add_br_html_email' ] ) {
                    //     update_option( "b3_add_br_html_email", "0", true );
                    // } else {
                    //     update_option( "b3_add_br_html_email", "1", true );
                    // }
    
                    $redirect_url = add_query_arg( 'success', 'emails_saved', $redirect_url );
    
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
            if ( isset( $_POST[ 'b3_users_nonce' ] ) ) {
            
                if ( is_admin() ) {
                    $redirect_url = admin_url( 'admin.php?page=b3-user-approval' );
                } else {
                    $redirect_url = home_url( 'user-management' );
                }
            
                if ( ! wp_verify_nonce( $_POST[ "b3_users_nonce" ], 'b3-users-nonce' ) ) {
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
                        $site_name  = get_option( 'blogname' );
                        $from_email = get_option( 'admin_email' );
                        $to         = $user_object->user_email;
                        $subject    = esc_html__( 'Account approved', 'b3-onboarding' );
                        $message    = sprintf( esc_html__( 'Welcome to %s. Your account has been approved and you can now login.', 'b3-onboarding' ), $site_name );
                        $headers    = array(
                            'From: ' . $site_name . ' <' . $from_email . '>',
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
                            $site_name  = get_option( 'blogname' );
                            $from_email = get_option( 'admin_email' );
                            $to         = $user_object->user_email;
                            $subject    = sprintf( esc_html__( 'Account rejected for %s', 'b3-onboarding' ), $site_name );
                            $message    = sprintf( esc_html__( "We're sorry to have to inform you, but your request for access to %s was rejected.", "b3-onboarding" ), $site_name );
                            $headers    = array(
                                'From: ' . $site_name . ' <' . $from_email . '>',
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
