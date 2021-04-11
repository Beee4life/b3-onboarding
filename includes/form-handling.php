<?php

    /**
     * Function which handles admin page settings
     *
     * @since 1.0.0
     */
    function b3_admin_form_handling() {

        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3ob_settings_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ 'b3ob_settings_nonce' ], 'b3ob-settings-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    if ( isset( $_POST[ 'b3_disable_wordpress_forms' ] ) && 1 == $_POST[ 'b3_disable_wordpress_forms' ] ) {
                        update_site_option( 'b3_disable_wordpress_forms', 1 );
                    } else {
                        delete_site_option( 'b3_disable_wordpress_forms' );
                    }

                    if ( isset( $_POST[ 'b3_style_wordpress_forms' ] ) && 1 == $_POST[ 'b3_style_wordpress_forms' ] ) {
                        update_site_option( 'b3_style_wordpress_forms', 1 );
                    } else {
                        delete_site_option( 'b3_style_wordpress_forms' );
                    }

                    if ( ( isset( $_POST[ 'b3_style_wordpress_forms' ] ) && 1 == $_POST[ 'b3_style_wordpress_forms' ] ) && ( isset( $_POST[ 'b3_disable_wordpress_forms' ] ) && 1 == $_POST[ 'b3_disable_wordpress_forms' ] ) ) {
                        // can't be at same time
                        update_site_option( 'b3_disable_wordpress_forms', 1 );
                        delete_site_option( 'b3_style_wordpress_forms' );
                    }

                    if ( isset( $_POST[ 'b3_disable_action_links' ] ) && 1 == $_POST[ 'b3_disable_action_links' ] ) {
                        update_site_option( 'b3_disable_action_links', 1 );
                    } else {
                        delete_site_option( 'b3_disable_action_links' );
                    }

                    if ( isset( $_POST[ 'b3_activate_recaptcha' ] ) && 1 == $_POST[ 'b3_activate_recaptcha' ] ) {
                        update_site_option( 'b3_activate_recaptcha', 1 );
                    } else {
                        delete_site_option( 'b3_activate_recaptcha' );
                        delete_site_option( 'b3_recaptcha_on' );
                    }

                    if ( isset( $_POST[ 'b3_debug_info' ] ) && 1 == $_POST[ 'b3_debug_info' ] ) {
                        update_site_option( 'b3_debug_info', 1 );
                    } else {
                        delete_site_option( 'b3_debug_info' );
                    }

                    if ( isset( $_POST[ 'b3_activate_filter_validation' ] ) && 1 == $_POST[ 'b3_activate_filter_validation' ] ) {
                        update_site_option( 'b3_activate_filter_validation', 1 );
                    } else {
                        delete_site_option( 'b3_activate_filter_validation' );
                    }

                    if ( isset( $_POST[ 'b3_main_logo' ] ) ) {
                        update_site_option( 'b3_main_logo', sanitize_file_name( $_POST[ 'b3_main_logo' ] ) );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'General settings saved', 'b3-onboarding' ) );

                    return;

                }

            } elseif ( isset( $_POST[ 'b3_pages_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ 'b3_pages_nonce' ], 'b3-pages-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    $page_ids = array(
                        'b3_account_page_id',
                        'b3_lost_password_page_id',
                        'b3_login_page_id',
                        'b3_logout_page_id',
                        'b3_register_page_id',
                        'b3_reset_password_page_id',
                    );
                    if ( isset( $_POST[ 'b3_approval_page_id' ] ) ) {
                        $page_ids[] = 'b3_approval_page_id';
                    }
                    foreach( $page_ids as $option_name ) {
                        $current_id = get_site_option( $option_name );
                        if ( $current_id != $_POST[ $option_name ] ) {
                            update_site_option( $option_name, $_POST[ $option_name ] );
                            delete_post_meta( $current_id, '_b3_page' );
                            update_post_meta( $_POST[ $option_name ], '_b3_page', true );
                        }
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Pages settings saved', 'b3-onboarding' ) );

                    return;
                }

            } elseif ( isset( $_POST[ 'b3_registration_nonce' ] ) ) {
                if ( ! wp_verify_nonce( $_POST[ 'b3_registration_nonce' ], 'b3-registration-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    if ( isset( $_POST[ 'b3_registration_type' ] ) ) {
                        if ( is_multisite() ) {
                            $ms_registration_type = sanitize_text_field( $_POST[ 'b3_registration_type' ] );
                            if ( 'closed' == $ms_registration_type ) {
                                update_site_option( 'b3_registration_type', $ms_registration_type );
                            } elseif ( 'request_access_subdomain' == $ms_registration_type ) {
                                update_site_option( 'b3_registration_type', $ms_registration_type );
                            } elseif ( 'blog' == $ms_registration_type ) {
                                update_site_option( 'b3_registration_type', $ms_registration_type );
                            } elseif ( 'user' == $ms_registration_type ) {
                                update_site_option( 'b3_registration_type', $ms_registration_type );
                            } elseif ( 'all' == $ms_registration_type ) {
                                update_site_option( 'b3_registration_type', $ms_registration_type );
                            }
                        } else {
                            if ( 'closed' == $_POST[ 'b3_registration_type' ] ) {
                                update_option( 'users_can_register', 0 );
                            } else {
                                update_option( 'users_can_register', 1 );
                            }
                            update_option( 'b3_registration_type', $_POST[ 'b3_registration_type' ] );
                        }
                    }

                    if ( 'closed' == get_site_option( 'b3_registration_type' ) ) {
                        if ( isset( $_POST[ 'b3_registration_closed_message' ] ) ) {
                            update_site_option( 'b3_registration_closed_message', htmlspecialchars( $_POST[ 'b3_registration_closed_message' ] ) );
                        } else {
                            delete_site_option( 'b3_registration_closed_message' );
                        }
                    }

                    if ( isset( $_POST[ 'b3_register_email_only' ] ) && 1 == $_POST[ 'b3_register_email_only' ] ) {
                        update_site_option( 'b3_register_email_only', 1 );
                    } else {
                        delete_site_option( 'b3_register_email_only' );
                    }

                    if ( isset( $_POST[ 'b3_activate_custom_passwords' ] ) && 1 == $_POST[ 'b3_activate_custom_passwords' ] ) {
                        update_site_option( 'b3_activate_custom_passwords', 1 );
                    } else {
                        delete_site_option( 'b3_activate_custom_passwords' );
                    }

                    if ( isset( $_POST[ 'b3_first_last_required' ] ) && 1 == $_POST[ 'b3_first_last_required' ] ) {
                        update_site_option( 'b3_first_last_required', 1 );
                    } else {
                        delete_site_option( 'b3_first_last_required' );
                    }

                    if ( isset( $_POST[ 'b3_activate_first_last' ] ) && 1 == $_POST[ 'b3_activate_first_last' ] ) {
                        update_site_option( 'b3_activate_first_last', 1 );
                    } else {
                        delete_site_option( 'b3_activate_first_last' );
                        delete_site_option( 'b3_first_last_required' );
                    }

                    if ( isset( $_POST[ 'b3_redirect_set_password' ] ) && 1 == $_POST[ 'b3_redirect_set_password' ] ) {
                        update_site_option( 'b3_redirect_set_password', 1 );
                    } else {
                        delete_site_option( 'b3_redirect_set_password' );
                    }

                    if ( isset( $_POST[ 'b3_privacy' ] ) && 1 == $_POST[ 'b3_privacy' ] ) {
                        update_site_option( 'b3_privacy', 1 );
                    } else {
                        delete_site_option( 'b3_privacy' );
                    }

                    if ( isset( $_POST[ 'b3_privacy_page' ] ) ) {
                        update_site_option( 'b3_privacy_page', $_POST[ 'b3_privacy_page' ] );
                    } else {
                        delete_site_option( 'b3_privacy_page' );
                    }

                    if ( isset( $_POST[ 'b3_privacy_text' ] ) ) {
                        update_site_option( 'b3_privacy_text', htmlspecialchars( $_POST[ 'b3_privacy_text' ] ) );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Registration settings saved', 'b3-onboarding' ) );

                    return;

                }

            } elseif ( isset( $_POST[ 'b3_loginpage_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ 'b3_loginpage_nonce' ], 'b3-loginpage-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    if ( ! empty( $_POST[ 'b3_loginpage_bg_color' ] ) ) {
                        $color = $_POST[ 'b3_loginpage_bg_color' ];
                        if ( '#' == substr( $_POST[ 'b3_loginpage_bg_color' ], 0, 1 ) ) {
                            $color = substr( $_POST[ 'b3_loginpage_bg_color' ], 1 );
                        }
                        if ( empty( sanitize_hex_color_no_hash( $color ) ) ) {
                            B3Onboarding::b3_errors()->add( 'error_wrong_hexlength', esc_html__( 'Your hex code is incorrect.', 'b3-onboarding' ) );

                            return;
                        }
                        update_site_option( 'b3_loginpage_bg_color', $color );
                    } else {
                        delete_site_option( 'b3_loginpage_bg_color' );
                    }

                    update_site_option( 'b3_loginpage_font_family', $_POST[ 'b3_loginpage_font_family' ] );
                    update_site_option( 'b3_loginpage_font_size', $_POST[ 'b3_loginpage_font_size' ] );

                    $max_width  = 320;
                    $max_height = 150;
                    if ( $_POST[ 'b3_loginpage_logo_width' ] >= $max_width ) {
                        update_site_option( 'b3_loginpage_logo_width', $max_width );
                    } else {
                        update_site_option( 'b3_loginpage_logo_width', $_POST[ 'b3_loginpage_logo_width' ] );
                    }
                    if ( $_POST[ 'b3_loginpage_logo_height' ] >= $max_height ) {
                        update_site_option( 'b3_loginpage_logo_height', $max_height );
                    } else {
                        update_site_option( 'b3_loginpage_logo_height', $_POST[ 'b3_loginpage_logo_height' ] );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Login page settings saved', 'b3-onboarding' ) );

                    return;

                }

            } elseif ( isset( $_POST[ 'b3_emails_nonce' ] ) ) {
                if ( ! wp_verify_nonce( $_POST[ 'b3_emails_nonce' ], 'b3-emails-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    if ( ! empty( $_POST[ 'b3_notification_sender_email' ] ) ) {
                        if ( ! is_email( $_POST[ 'b3_notification_sender_email' ] ) ) {
                            B3Onboarding::b3_errors()->add( 'error_invalid_email', esc_html__( 'That is not a valid email address.', 'b3-onboarding' ) );

                            return;
                        } else {
                            $sender_email = $_POST[ 'b3_notification_sender_email' ];
                        }
                    }

                    /* general boxes */
                    if ( isset( $sender_email ) ) {
                        update_site_option( 'b3_notification_sender_email', $sender_email );
                    } else {
                        delete_site_option( 'b3_notification_sender_email' );
                    }
                    if ( isset( $_POST[ 'b3_notification_sender_name' ] ) && ! empty( $_POST[ 'b3_notification_sender_name' ] ) ) {
                        update_site_option( 'b3_notification_sender_name', $_POST[ 'b3_notification_sender_name' ] );
                    } else {
                        delete_site_option( 'b3_notification_sender_email' );
                    }

                    if ( ! empty( $_POST[ 'b3_link_color' ] ) ) {
                        $color = $_POST[ 'b3_link_color' ];
                        if ( '#' == substr( $_POST[ 'b3_link_color' ], 0, 1 ) ) {
                            if ( empty( sanitize_hex_color( $color ) ) ) {
                                $error_message = esc_html__( 'The length of your hex color is incorrect.', 'b3-onboarding' );
                                $return_error  = true;
                            }
                        } else {
                            // @TODO: add verification for rgba, hsl, rgb
                            $error_message = esc_html__( "Your hex color is incorrect, it's missing a hashtag (#).", 'b3-onboarding' );
                            if ( empty( sanitize_hex_color_no_hash( $color ) ) ) {
                                $error_message = esc_html__( "Your hex color is incorrect, it's missing a hashtag (#) and the color value is incorrect.", 'b3-onboarding' );
                            }
                            $return_error  = true;
                        }
                        if ( isset( $return_error ) && true === $return_error ) {
                            B3Onboarding::b3_errors()->add( 'error_wrong_hex_color', $error_message );

                            return;
                        }
                        update_site_option( 'b3_link_color', $color );
                    } else {
                        delete_site_option( 'b3_link_color' );
                    }

                    if ( isset( $_POST[ 'b3_activate_custom_emails' ] ) && 1 == $_POST[ 'b3_activate_custom_emails' ] ) {
                        update_site_option( 'b3_custom_emails', 1 );
                    } else {
                        delete_site_option( 'b3_custom_emails' );
                    }

                    if ( isset( $_POST[ 'b3_logo_in_email' ] ) && 1 == $_POST[ 'b3_logo_in_email' ] ) {
                        update_site_option( 'b3_logo_in_email', 1 );
                    } else {
                        delete_site_option( 'b3_logo_in_email' );
                    }

                    if ( isset( $_POST[ 'b3_email_styling' ] ) && ! empty( $_POST[ 'b3_email_styling' ] ) ) {
                        update_site_option( 'b3_email_styling', stripslashes( $_POST[ 'b3_email_styling' ] ) );
                    } else {
                        delete_site_option( 'b3_email_styling' );
                    }
                    if ( isset( $_POST[ 'b3_email_template' ] ) && ! empty( $_POST[ 'b3_email_template' ] ) ) {
                        update_site_option( 'b3_email_template', stripslashes( $_POST[ 'b3_email_template' ] ) );
                    } else {
                        delete_site_option( 'b3_email_template' );
                    }

                    if ( isset( $_POST[ 'b3_lost_password_subject' ] ) && ! empty( $_POST[ 'b3_lost_password_subject' ] ) ) {
                        update_site_option( 'b3_lost_password_subject', $_POST[ 'b3_lost_password_subject' ] );
                    } else {
                        delete_site_option( 'b3_lost_password_subject' );
                    }
                    if ( isset( $_POST[ 'b3_lost_password_message' ] ) && ! empty( $_POST[ 'b3_lost_password_message' ] ) ) {
                        update_site_option( 'b3_lost_password_message', htmlspecialchars( $_POST[ 'b3_lost_password_message' ] ) );
                    } else {
                        delete_site_option( 'b3_lost_password_message' );
                    }
                    if ( isset( $_POST[ 'b3_disable_admin_notification_password_change' ] ) && 1 == $_POST[ 'b3_disable_admin_notification_password_change' ] ) {
                        update_site_option( 'b3_disable_admin_notification_password_change', 1 );
                    } else {
                        delete_site_option( 'b3_disable_admin_notification_password_change' );
                    }

                    if ( isset( $_POST[ 'b3_disable_user_notification_password_change' ] ) && 1 == $_POST[ 'b3_disable_user_notification_password_change' ] ) {
                        update_site_option( 'b3_disable_user_notification_password_change', 1 );
                    } else {
                        delete_site_option( 'b3_disable_user_notification_password_change' );
                    }

                    if ( isset( $_POST[ 'b3_disable_admin_notification_new_user' ] ) && 1 == $_POST[ 'b3_disable_admin_notification_new_user' ] ) {
                        update_site_option( 'b3_disable_admin_notification_new_user', 1 );
                        if ( is_multisite() ) {
                            update_site_option( 'registrationnotification', 'no' );
                        }
                    } else {
                        delete_site_option( 'b3_disable_admin_notification_new_user' );
                        if ( is_multisite() ) {
                            update_site_option( 'registrationnotification', 'yes' );
                        }
                    }

                    /* specific boxes */
                    if ( in_array( get_site_option( 'b3_registration_type' ), array( 'open', 'email_activation' ) ) ) {
                        if ( isset( $_POST[ 'b3_account_activated_subject' ] ) ) {
                            update_site_option( 'b3_account_activated_subject', $_POST[ 'b3_account_activated_subject' ] );
                        }
                        if ( isset( $_POST[ 'b3_account_activated_message' ] ) ) {
                            update_site_option( 'b3_account_activated_message', htmlspecialchars( $_POST[ 'b3_account_activated_message' ] ) );
                        }

                        update_site_option( 'b3_new_user_message', htmlspecialchars( $_POST[ 'b3_new_user_message' ] ) );

                        if ( isset( $_POST[ 'b3_new_user_notification_addresses' ] ) && ! empty( $_POST[ 'b3_new_user_notification_addresses' ] ) ) {
                            $email_array = explode( ',', $_POST[ 'b3_new_user_notification_addresses' ] );
                            if ( ! empty( $email_array ) ) {
                                foreach( $email_array as $email ) {
                                    $email = trim( $email );
                                    if ( ! is_email( $email ) ) {
                                        B3Onboarding::b3_errors()->add( 'error_invalid_email', sprintf( __( '"%s" is not a valid email address.', 'b3-onboarding' ), $email ) );

                                        return;
                                    } else {
                                        $emails[] = sanitize_email( $email );
                                    }
                                }
                            }
                            if ( isset( $emails ) ) {
                                $email_string = implode( ',', $emails );
                                update_site_option( 'b3_new_user_notification_addresses', $email_string );
                            }
                        } else {
                            delete_site_option( 'b3_new_user_notification_addresses' );
                        }

                        update_site_option( 'b3_new_user_subject', $_POST[ 'b3_new_user_subject' ] );

                        if ( isset( $_POST[ 'b3_welcome_user_message' ] ) ) {
                            update_site_option( 'b3_welcome_user_message', htmlspecialchars( $_POST[ 'b3_welcome_user_message' ] ) );
                        }
                        if ( isset( $_POST[ 'b3_welcome_user_subject' ] ) ) {
                            update_site_option( 'b3_welcome_user_subject', stripslashes( $_POST[ 'b3_welcome_user_subject' ] ) );
                        }

                        if ( in_array( get_site_option( 'b3_registration_type' ), array( 'email_activation' ) ) ) {
                            update_site_option( 'b3_email_activation_subject', stripslashes( $_POST[ 'b3_email_activation_subject' ] ) );
                            update_site_option( 'b3_email_activation_message', htmlspecialchars( $_POST[ 'b3_email_activation_message' ] ) );
                        }
                    }

                    if ( 'request_access' == get_site_option( 'b3_registration_type' ) ) {
                        update_site_option( 'b3_account_approved_message', htmlspecialchars( $_POST[ 'b3_account_approved_message' ], ENT_QUOTES ) );
                        update_site_option( 'b3_account_approved_subject', $_POST[ 'b3_account_approved_subject' ] );
                        update_site_option( 'b3_request_access_message_admin', htmlspecialchars( $_POST[ 'b3_request_access_message_admin' ] ) );
                        update_site_option( 'b3_request_access_message_user', htmlspecialchars( $_POST[ 'b3_request_access_message_user' ] ) );
                        update_site_option( 'b3_request_access_notification_addresses', $_POST[ 'b3_request_access_notification_addresses' ] );
                        update_site_option( 'b3_request_access_subject_admin', $_POST[ 'b3_request_access_subject_admin' ] );
                        update_site_option( 'b3_request_access_subject_user', $_POST[ 'b3_request_access_subject_user' ] );
                        update_site_option( 'b3_account_rejected_message', htmlspecialchars( $_POST[ 'b3_account_rejected_message' ], ENT_QUOTES ) );
                        update_site_option( 'b3_account_rejected_subject', $_POST[ 'b3_account_rejected_subject' ] );

                        if ( isset( $_POST[ 'b3_disable_delete_user_email' ] ) && 1 == $_POST[ 'b3_disable_delete_user_email' ] ) {
                            update_site_option( 'b3_disable_delete_user_email', 1 );
                        } else {
                            delete_site_option( 'b3_disable_delete_user_email' );
                        }
                    }

                    if ( is_multisite() ) {
                        if ( isset( $_POST[ 'b3_confirm_wpmu_user_subject' ] ) && ! empty( $_POST[ 'b3_confirm_wpmu_user_subject' ] ) ) {
                            update_site_option( 'b3_confirm_wpmu_user_subject', stripslashes( $_POST[ 'b3_confirm_wpmu_user_subject' ] ) );
                        } else {
                            delete_site_option( 'b3_confirm_wpmu_user_subject' );
                        }
                        if ( isset( $_POST[ 'b3_confirm_wpmu_user_message' ] ) && ! empty( $_POST[ 'b3_confirm_wpmu_user_message' ] ) ) {
                            update_site_option( 'b3_confirm_wpmu_user_message', htmlspecialchars( $_POST[ 'b3_confirm_wpmu_user_message' ] ) );
                        } else {
                            delete_site_option( 'b3_confirm_wpmu_user_message' );
                        }
                        if ( isset( $_POST[ 'b3_activated_wpmu_user_subject' ] ) && ! empty( $_POST[ 'b3_activated_wpmu_user_subject' ] ) ) {
                            update_site_option( 'b3_activated_wpmu_user_subject', stripslashes( $_POST[ 'b3_activated_wpmu_user_subject' ] ) );
                        } else {
                            delete_site_option( 'b3_activated_wpmu_user_subject' );
                        }
                        if ( isset( $_POST[ 'b3_activated_wpmu_user_message' ] ) && ! empty( $_POST[ 'b3_activated_wpmu_user_message' ] ) ) {
                            update_site_option( 'b3_activated_wpmu_user_message', htmlspecialchars( $_POST[ 'b3_activated_wpmu_user_message' ] ) );
                        } else {
                            delete_site_option( 'b3_activated_wpmu_user_message' );
                        }

                        if ( isset( $_POST[ 'b3_activate_wpmu_user_site_subject' ] ) && ! empty( $_POST[ 'b3_activate_wpmu_user_site_subject' ] ) ) {
                            update_site_option( 'b3_activate_wpmu_user_site_subject', stripslashes( $_POST[ 'b3_activate_wpmu_user_site_subject' ] ) );
                        } else {
                            delete_site_option( 'b3_activate_wpmu_user_site_subject' );
                        }
                        if ( isset( $_POST[ 'b3_activate_wpmu_user_site_message' ] ) && ! empty( $_POST[ 'b3_activate_wpmu_user_site_message' ] ) ) {
                            update_site_option( 'b3_activate_wpmu_user_site_message', htmlspecialchars( $_POST[ 'b3_activate_wpmu_user_site_message' ] ) );
                        } else {
                            delete_site_option( 'b3_activate_wpmu_user_site_message' );
                        }
                        if ( isset( $_POST[ 'b3_activated_wpmu_user_site_subject' ] ) && ! empty( $_POST[ 'b3_activated_wpmu_user_site_subject' ] ) ) {
                            update_site_option( 'b3_activated_wpmu_user_site_subject', stripslashes( $_POST[ 'b3_activated_wpmu_user_site_subject' ] ) );
                        } else {
                            delete_site_option( 'b3_activated_wpmu_user_site_subject' );
                        }
                        if ( isset( $_POST[ 'b3_activated_wpmu_user_site_message' ] ) && ! empty( $_POST[ 'b3_activated_wpmu_user_site_message' ] ) ) {
                            update_site_option( 'b3_activated_wpmu_user_site_message', htmlspecialchars( $_POST[ 'b3_activated_wpmu_user_site_message' ] ) );
                        } else {
                            delete_site_option( 'b3_activated_wpmu_user_site_message' );
                        }

                        if ( isset( $_POST[ 'b3_new_wpmu_user_admin_subject' ] ) && ! empty( $_POST[ 'b3_new_wpmu_user_admin_subject' ] ) ) {
                            update_site_option( 'b3_new_wpmu_user_admin_subject', stripslashes( $_POST[ 'b3_new_wpmu_user_admin_subject' ] ) );
                        } else {
                            delete_site_option( 'b3_new_wpmu_user_admin_subject' );
                        }
                        if ( isset( $_POST[ 'b3_new_wpmu_user_admin_message' ] ) && ! empty( $_POST[ 'b3_new_wpmu_user_admin_message' ] ) ) {
                            update_site_option( 'b3_new_wpmu_user_admin_message', htmlspecialchars( $_POST[ 'b3_new_wpmu_user_admin_message' ] ) );
                        } else {
                            delete_site_option( 'b3_new_wpmu_user_admin_message' );
                        }
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'Email settings saved', 'b3-onboarding' ) );

                    return;
                }

            } elseif ( isset( $_POST[ 'b3_users_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ 'b3_users_nonce' ], 'b3-users-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    if ( isset( $_POST[ 'b3_activate_frontend_approval' ] ) && 1 == $_POST[ 'b3_activate_frontend_approval' ] ) {
                        update_site_option( 'b3_front_end_approval', 1 );
                    } else {
                        delete_site_option( 'b3_front_end_approval' );
                        delete_site_option( 'b3_approval_page_id' );
                    }

                    if ( isset( $_POST[ 'b3_user_may_delete' ] ) && 1 == $_POST[ 'b3_user_may_delete' ] ) {
                        update_site_option( 'b3_user_may_delete', 1 );
                    } else {
                        delete_site_option( 'b3_user_may_delete' );
                    }

                    if ( isset( $_POST[ 'b3_restrict_admin' ] ) ) {
                        update_site_option( 'b3_restrict_admin', $_POST[ 'b3_restrict_admin' ] );
                    } else {
                        delete_site_option( 'b3_restrict_admin' );
                    }

                    if ( isset( $_POST[ 'b3_hide_admin_bar' ] ) ) {
                        update_site_option( 'b3_hide_admin_bar', 1 );
                    } else {
                        delete_site_option( 'b3_hide_admin_bar' );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'User settings saved', 'b3-onboarding' ) );

                    return;
                }

            } elseif ( isset( $_POST[ 'b3_recaptcha_nonce' ] ) ) {

                if ( ! wp_verify_nonce( $_POST[ 'b3_recaptcha_nonce' ], 'b3-recaptcha-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    update_site_option( 'b3_recaptcha_public', sanitize_key( $_POST[ 'b3_recaptcha_public' ] ) );
                    update_site_option( 'b3_recaptcha_secret', sanitize_key( $_POST[ 'b3_recaptcha_secret' ] ) );
                    update_site_option( 'b3_recaptcha_version', sanitize_text_field( $_POST[ 'b3_recaptcha_version' ] ) );

                    if ( isset( $_POST[ 'b3_recaptcha_on' ] ) ) {
                        update_site_option( 'b3_recaptcha_on', $_POST[ 'b3_recaptcha_on' ] );
                    } else {
                        delete_site_option( 'b3_recaptcha_on' );
                    }

                    B3Onboarding::b3_errors()->add( 'success_settings_saved', esc_html__( 'reCaptcha settings saved', 'b3-onboarding' ) );

                    return;
                }
            }
        }
    }
    add_action( 'wp_loaded', 'b3_admin_form_handling', 1 );


    /**
     * Function which handles approve/deny user form
     *
     * @since 1.0.4
     */
    function b3_approve_deny_users() {

        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_manage_users_nonce' ] ) ) {

                $redirect_url = admin_url( 'admin.php?page=b3-user-approval' );
                if ( ! is_admin() ) {
                    $approval_link = b3_get_user_approval_link();
                    if ( false != $approval_link ) {
                        $redirect_url = $approval_link;
                    }
                }

                if ( ! wp_verify_nonce( $_POST[ 'b3_manage_users_nonce' ], 'b3-manage-users-nonce' ) ) {
                    B3Onboarding::b3_errors()->add( 'error_nonce_mismatch', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                    return;
                } else {

                    $approve     = ( isset( $_POST[ 'b3_approve_user' ] ) ) ? true : false;
                    $reject      = ( isset( $_POST[ 'b3_reject_user' ] ) ) ? true : false;
                    $signup_id   = ( isset( $_POST[ 'b3_signup_id' ] ) ) ? (int) $_POST[ 'b3_signup_id' ] : false;
                    $user_id     = ( isset( $_POST[ 'b3_user_id' ] ) ) ? (int) $_POST[ 'b3_user_id' ] : false;
                    $user_object = ( isset( $_POST[ 'b3_user_id' ] ) ) ? new WP_User( $user_id ) : false;

                    if ( false != $signup_id ) {
                        // multisite signup
                        global $wpdb;
                        $signup_info = $wpdb->get_row( "SELECT * FROM $wpdb->signups WHERE signup_id = $signup_id" );

                        if ( false != $approve ) {
                            do_action( 'b3_approve_wpmu_signup', $signup_info );
                            $redirect_url = add_query_arg( 'user', 'approved', $redirect_url );
                        } elseif ( false != $reject ) {
                            // @TODO: create new action
                            $redirect_url = add_query_arg( 'user', 'rejected', $redirect_url );
                        }

                    } elseif ( isset( $user_object->ID ) ) {
                        if ( false != $approve ) {
                            do_action( 'b3_approve_user', [ 'user_id' => $user_id ] );
                            $redirect_url = add_query_arg( 'user', 'approved', $redirect_url );
                        } elseif ( false != $reject ) {
                            do_action( 'b3_before_reject_user', $user_id );
                            require_once( ABSPATH . 'wp-admin/includes/user.php' );
                            if ( true == wp_delete_user( $user_id ) ) {
                                $redirect_url = add_query_arg( 'user', 'rejected', $redirect_url );
                            } else {
                                $redirect_url = add_query_arg( 'user', 'not-deleted', $redirect_url );
                            }
                        }
                    }

                    wp_safe_redirect( $redirect_url );
                    exit;
                }
            }
        }
    }
    add_action( 'wp_loaded', 'b3_approve_deny_users' );


    /**
     * Function to handle (front-end) profile form editing
     *
     * @since 1.0.4
     */
    function b3_profile_form_handling() {
        $account_page_id = b3_get_account_url( true );
        if ( false != $account_page_id && is_page( $account_page_id ) && is_user_logged_in() ) {
            require_once( ABSPATH . 'wp-admin/includes/user.php' );
            require_once( ABSPATH . 'wp-admin/includes/misc.php' );
            define( 'IS_PROFILE_PAGE', true );
        }

        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] && ! empty( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'profile' ) {
            $current_user = wp_get_current_user();
            check_admin_referer( 'update-user_' . $current_user->ID );
            wp_enqueue_script( 'user-profile' );

            if ( ! current_user_can( 'edit_user', $current_user->ID ) ) {
                wp_die( __( 'You do not have permission to edit this user.', 'b3-onboarding' ) );
            }
            if ( isset( $_POST[ 'b3_delete_account' ] ) ) {
                $redirect_url = b3_get_login_url();
                if ( true == wp_delete_user( $current_user->ID ) ) {
                    $redirect_url = add_query_arg( 'account', 'removed', $redirect_url );
                }
                wp_safe_redirect( $redirect_url );
                exit;

            } else {

                $errors = edit_user( $current_user->ID );
                do_action( 'b3_after_save_profile', $current_user->ID );

                if ( ! is_wp_error( $errors ) ) {
                    wp_safe_redirect( add_query_arg( 'updated', 'true' ) );
                    exit;
                }
            }
        }
    }
    add_action( 'template_redirect', 'b3_profile_form_handling' );
