<?php
    /**
     * Get all filtered output and verify it
     *
     * @since 2.0.0
     */
    function b3_verify_filter_input() {
        
        $error_messages = [];
        $custom_filters = [
            'b3_account_activated_message_user'        => [ 'string' ],
            'b3_account_activated_subject_user'        => [ 'string' ],
            'b3_account_approved_message'              => [ 'string' ],
            'b3_account_approved_subject'              => [ 'string' ],
            'b3_account_rejected_message'              => [ 'string' ],
            'b3_account_rejected_subject'              => [ 'string' ],
            'b3_attributes'                            => [ 'array' ],
            'b3_custom_register_inform'                => [ 'string' ],
            'b3_dashboard_url'                         => [ 'string' ],
            'b3_default_greetings'                     => [ 'string' ],
            'b3_disable_action_links'                  => [ 'bool', 'string' ],
            'b3_disallowed_domains'                    => [ 'array' ],
            'b3_disallowed_usernames'                  => [ 'array' ],
            'b3_easy_passwords'                        => [ 'array' ],
            'b3_email_activation_message_user'         => [ 'string' ],
            'b3_email_activation_subject_user'         => [ 'string' ],
            'b3_email_intro'                           => [ 'string' ],
            'b3_email_footer_text'                     => [ 'string' ],
            'b3_email_styling'                         => [ 'string' ],
            'b3_email_template'                        => [ 'string' ],
            'b3_extra_fields'                          => [ 'array', 'extra' ],
            'b3_extra_fields_validation'               => [ 'array' ],
            'b3_hidden_fields'                         => [ 'array', 'hidden' ],
            'b3_hide_development_notice'               => [ 'bool' ],
            'b3_link_color'                            => [ 'hex_color' ],
            'b3_localhost'                             => [ 'bool' ],
            'b3_localhost_blogname'                    => [ 'string' ],
            'b3_localhost_blogtitle'                   => [ 'string' ],
            'b3_localhost_email'                       => [ 'email' ],
            'b3_localhost_username'                    => [ 'string' ],
            'b3_logged_in_registration_only_message'   => [ 'string' ],
            'b3_lost_password_message'                 => [ 'string' ],
            'b3_lost_password_subject'                 => [ 'string' ],
            'b3_main_logo'                             => [ 'url', 'file' ],
            'b3_message_above_login'                   => [ 'string' ],
            'b3_message_above_lost_password'           => [ 'string' ],
            'b3_message_above_magiclink'               => [ 'string' ],
            'b3_message_above_new_blog'                => [ 'string' ],
            'b3_message_above_registration'            => [ 'string' ],
            'b3_message_above_request_access'          => [ 'string' ],
            'b3_message_above_request_site'            => [ 'string' ],
            'b3_new_site_created_message'              => [ 'string' ],
            'b3_new_user_message'                      => [ 'string' ],
            'b3_new_user_notification_addresses'       => [ 'email' ],
            'b3_new_user_subject'                      => [ 'string' ],
            'b3_new_wpmu_user_message_admin'           => [ 'string' ],
            'b3_new_wpmu_user_subject_admin'           => [ 'string' ],
            'b3_notification_sender_email'             => [ 'email' ],
            'b3_notification_sender_name'              => [ 'string' ],
            'b3_otp_email'                             => [ 'string' ],
            'b3_otp_time_out'                          => [ 'int' ],
            'b3_password_special_chars'                => [ 'bool' ],
            'b3_password_extra_special_chars'          => [ 'bool' ],
            'b3_privacy_text'                          => [ 'string' ],
            'b3_recaptcha_public'                      => [ 'string' ],
            'b3_recaptcha_secret'                      => [ 'string' ],
            'b3_redirect_after_register'               => [ 'url' ],
            'b3_register_for'                          => [ 'string' ],
            'b3_registration_access_requested_message' => [ 'string' ],
            'b3_registration_closed_message'           => [ 'string' ],
            'b3_registration_confirm_email_message'    => [ 'string' ],
            'b3_request_access_message_admin'          => [ 'string' ],
            'b3_request_access_message_user'           => [ 'string' ],
            'b3_request_access_subject_admin'          => [ 'string' ],
            'b3_request_access_subject_user'           => [ 'string' ],
            'b3_show_email_widget'                     => [ 'bool' ],
            'b3_signup_for_site'                       => [ 'string' ],
            'b3_signup_for_user'                       => [ 'string' ],
            'b3_password_special_chars'                => [ 'bool' ],
            'b3_password_extra_special_chars'          => [ 'bool' ],
            'b3_user_cap'                              => [ 'string' ],
            'b3_welcome_page'                          => [ 'string' ],
            'b3_welcome_user_message'                  => [ 'string' ],
            'b3_welcome_user_message_manual'           => [ 'string' ],
            'b3_welcome_user_subject'                  => [ 'string' ],
            'b3_widget_links'                          => [ 'array' ],
            'b3_wpmu_activate_user_blog_message'       => [ 'string' ],
            'b3_wpmu_activate_user_blog_subject'       => [ 'string' ],
            'b3_wpmu_activate_user_message'            => [ 'string' ],
            'b3_wpmu_activate_user_subject'            => [ 'string' ],
            'b3_wpmu_user_activated_message'           => [ 'string' ],
            'b3_wpmu_user_activated_subject'           => [ 'string' ],
        ];
        
        foreach( $custom_filters as $filter => $validation ) {
            $default       = ( in_array( $validation, [ 'array' ] ) ) ? [] : 'no_filter_defined';
            $filter_output = apply_filters( $filter, $default );
            if ( 'no_filter_defined' != $filter_output || is_array( $filter_output ) && empty( $filter_output ) ) {
                if ( in_array( 'email', $validation ) ) {
                    if ( is_string( $filter_output ) ) {
                        if ( ! is_email( trim( $filter_output ) ) ) {
                            $error_messages[] = sprintf( esc_html__( 'The email address "%s", which you set in the filter "%s", is invalid.', 'b3-onboarding' ), $filter_output, $filter );
                        }
                    } elseif ( is_array( $filter_output ) ) {
                        foreach( $filter_output as $email ) {
                            if ( ! is_email( trim( $email ) ) ) {
                                $error_messages[] = sprintf( esc_html__( 'The email address "%s", which you set in the filter "%s", is invalid.', 'b3-onboarding' ), $email, $filter );
                            }
                        }
                    } else {
                        $error_messages[] = sprintf( esc_html__( 'The value, which you set in the filter "%s", is not a string or an array.', 'b3-onboarding' ), $filter );
                    }
                    
                } elseif ( in_array( 'int', $validation ) ) {
                    if ( ! is_int( $filter_output ) ) {
                        $error_messages[] = sprintf( esc_html__( 'The value, which you set in the filter "%s", is not an integer.', 'b3-onboarding' ), $filter );
                    }
                } elseif ( in_array( 'url', $validation ) ) {
                    if ( filter_var( $filter_output, FILTER_VALIDATE_URL ) === false ) {
                        $error_messages[] = sprintf( esc_html__( 'The url, which you set in the filter "%s", is not a valid url.', 'b3-onboarding' ), $filter );
                    } else {
                        if ( in_array( 'file', $validation ) ) {
                            if ( false === b3_check_remote_file( $filter_output ) ) {
                                $error_messages[] = sprintf( esc_html__( 'The url, which you set in the filter "%s", does not exist or is unreachable.', 'b3-onboarding' ), $filter );
                            }
                        }
                    }
                    
                } elseif ( in_array( 'hex_color', $validation ) ) {
                    if ( false == $filter_output ) {
                        $error_messages[] = sprintf( esc_html__( 'The color, which you set in the filter "%s", is not invalid.', 'b3-onboarding' ), $filter );
                    } elseif ( false == is_string( $filter_output ) ) {
                        $error_messages[] = sprintf( esc_html__( 'The color (%s), which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $filter_output, $filter );
                    } elseif ( false == sanitize_hex_color( $filter_output ) ) {
                        $error_messages[] = sprintf( esc_html__( 'The color (%s), which you set in the filter "%s", is invalid.', 'b3-onboarding' ), $filter_output, $filter );
                    }
                    
                } elseif ( in_array( 'array', $validation ) ) {
                    if ( 'b3_extra_fields_validation' == $filter ) {
                        $extra_fields = apply_filters( 'b3_extra_fields', [] );
                        if ( ! empty( $extra_fields ) && ! empty( $filter_output ) ) {
                            if ( ! is_array( $filter_output ) ) {
                                $error_messages[] = sprintf( esc_html__( 'The value, which you set in the filter "%s", is not an array.', 'b3-onboarding' ), $filter );
                            } else {
                                $error_messages[] = sprintf( esc_html__( 'There are 1 or more errors in the filter "%s".', 'b3-onboarding' ), $filter );
                            }
                        }
                    } else {
                        if ( ! is_array( $filter_output ) ) {
                            $error_messages[] = sprintf( esc_html__( 'The value, which you set in the filter "%s", is not an array.', 'b3-onboarding' ), $filter );
                        } elseif ( empty( $filter_output ) ) {
                            $error_messages[] = sprintf( esc_html__( 'The value, which you set in the filter "%s", is an empty array.', 'b3-onboarding' ), $filter );
                        } else {
                            if ( in_array( 'hidden', $validation ) ) {
                                foreach( $filter_output as $key => $value ) {
                                    if ( ! is_string( $key ) ) {
                                        $error_messages[] = sprintf( esc_html__( 'The field ID "%s", which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $key, $filter );
                                    }
                                    if ( ! is_string( $value ) ) {
                                        $error_messages[] = sprintf( esc_html__( 'The field value "%s", which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $key, $filter );
                                    }
                                }
                            } elseif ( in_array( 'extra', $validation ) ) {
                                foreach( $filter_output as $field ) {
                                    if ( ! isset( $field[ 'type' ] ) ) {
                                        if ( isset( $field[ 'label' ] ) || isset( $field[ 'id' ] ) ) {
                                            if ( isset( $field[ 'label' ] ) ) {
                                                $replace = $field[ 'label' ];
                                            } elseif ( isset( $field[ 'id' ] ) ) {
                                                $replace = $field[ 'id' ];
                                            }
                                            $error_messages[] = sprintf( esc_html__( 'You didn\'t set a field type for "%s".', 'b3-onboarding' ), $replace );
                                        } else {
                                            $error_messages[] = esc_html__( 'You didn\'t set a field type for one of your fields.', 'b3-onboarding' );
                                        }
                                    } else {
                                        if ( ! isset( $field[ 'label' ] ) && isset( $field[ 'id' ] ) ) {
                                            $error_messages[] = sprintf( esc_html__( 'A label for the field "%s" in the filter "%s" is required.', 'b3-onboarding' ), $field[ 'id' ], $filter );
                                        }
                                        if ( ! isset( $field[ 'id' ] ) && isset( $field[ 'label' ] ) ) {
                                            $error_messages[] = sprintf( esc_html__( 'An ID for the field "%s" in the filter "%s" is required.', 'b3-onboarding' ), $field[ 'label' ], $filter );
                                        }
                                        if ( ! isset( $field[ 'id' ] ) && ! isset( $field[ 'label' ] ) ) {
                                            $error_messages[] = sprintf( esc_html__( 'An ID and label for your option in the filter "%s" is required.', 'b3-onboarding' ), $filter );
                                        }
                                        if ( in_array( $field[ 'type' ], [ 'checkbox', 'radio', 'select' ] ) ) {
                                            if ( ! isset( $field[ 'options' ] ) ) {
                                                if ( isset( $field[ 'label' ] ) || isset( $field[ 'id' ] ) ) {
                                                    if ( isset( $field[ 'label' ] ) ) {
                                                        $replace = $field[ 'label' ];
                                                    } elseif ( isset( $field[ 'id' ] ) ) {
                                                        $replace = $field[ 'id' ];
                                                    }
                                                    $error_messages[] = sprintf( esc_html__( 'You didn\'t set any options for the field "%s".', 'b3-onboarding' ), $replace );
                                                } else {
                                                    $error_messages[] = esc_html__( "You didn't set a field type for one of your fields.", 'b3-onboarding' );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } elseif ( in_array( 'string', $validation ) ) {
                    if ( ! is_string( $filter_output ) ) {
                        $error_messages[] = sprintf( esc_html__( 'The value, which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $filter );
                    }
                }
            }
        }
        
        if ( ! empty( $error_messages ) ) {
            foreach( $error_messages as $message ) {
                echo sprintf( '<div class="error"><p>%s</p></div>', $message );
            }
        }
    }
    add_action( 'b3_verify_filter_input', 'b3_verify_filter_input' );
