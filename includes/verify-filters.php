<?php
    /**
     * Get all filtered output and verify it
     *
     * @since 2.0.0
     */
    function b3_verify_filter_input() {

        $error_messages = array();
        $custom_filters = array(
            'b3_account_activated_message_user'        => array( 'string' ),
            'b3_account_activated_subject_user'        => array( 'string' ),
            'b3_account_approved_message'              => array( 'string' ),
            'b3_account_approved_subject'              => array( 'string' ),
            'b3_account_rejected_message'              => array( 'string' ),
            'b3_account_rejected_subject'              => array( 'string' ),
            'b3_custom_register_inform'                => array( 'string' ),
            'b3_dashboard_url'                         => array( 'string' ), // @TODO: check
            'b3_disable_action_links'                  => array( 'bool', 'string' ),
            'b3_disallowed_usernames'                  => array( 'array' ),
            'b3_email_activation_message_user'         => array( 'string' ),
            'b3_email_activation_subject_user'         => array( 'string' ),
            'b3_email_footer_text'                     => array( 'string' ),
            'b3_email_styling'                         => array( 'string' ),
            'b3_email_template'                        => array( 'string' ),
            'b3_extra_fields'                          => array( 'array', 'extra' ),
            'b3_extra_fields_validation'               => array( 'array' ), // @TODO: check
            'b3_hidden_fields'                         => array( 'array', 'hidden' ),
            'b3_link_color'                            => array( 'hex_color' ),
            'b3_localhost'                             => array( 'bool' ),
            'b3_localhost_email'                       => array( 'email' ),
            'b3_localhost_username'                    => array( 'string' ),
            'b3_logged_in_registration_only_message'   => array( 'string' ),
            'b3_lost_password_message'                 => array( 'string' ),
            'b3_lost_password_subject'                 => array( 'string' ),
            'b3_main_logo'                             => array( 'url', 'file' ),
            'b3_message_above_login'                   => array( 'string' ),
            'b3_message_above_lost_password'           => array( 'string' ),
            'b3_message_above_new_blog'                => array( 'string' ),
            'b3_message_above_registration'            => array( 'string' ),
            'b3_message_above_request_access'          => array( 'string' ),
            'b3_new_user_message'                      => array( 'string' ),
            'b3_new_user_notification_addresses'       => array( 'email' ),
            'b3_new_user_subject'                      => array( 'string' ),
            'b3_notification_sender_email'             => array( 'email' ),
            'b3_notification_sender_name'              => array( 'string' ), // @TODO: check
            'b3_privacy_text'                          => array( 'string' ),
            'b3_redirect_after_register'               => array( 'url' ),
            'b3_register_for'                          => array( 'string' ),
            'b3_registration_access_requested_message' => array( 'string' ),
            'b3_registration_closed_message'           => array( 'string' ),
            'b3_registration_confirm_email_message'    => array( 'string' ),
            'b3_request_access_message_admin'          => array( 'string' ),
            'b3_request_access_message_user'           => array( 'string' ),
            'b3_request_access_subject_admin'          => array( 'string' ),
            'b3_request_access_subject_user'           => array( 'string' ),
            'b3_reserved_usernames'                    => array( 'array' ), // @TODO: check
            'b3_signup_for_site'                       => array( 'string' ),
            'b3_signup_for_user'                       => array( 'string' ),
            'b3_user_cap'                              => array( 'string' ),
            'b3_welcome_user_message'                  => array( 'string' ),
            'b3_welcome_user_message_manual'           => array( 'string' ),
            'b3_welcome_user_subject'                  => array( 'string' ),
            'b3_widget_links'                          => array( 'array' ),
            'b3_wpmu_activate_user_message'            => array( 'string' ),
            'b3_wpmu_activate_user_subject'            => array( 'string' ),
            'b3_wpmu_user_activated_message'           => array( 'string' ),
            'b3_wpmu_user_activated_subject'           => array( 'string' ),
        );

        foreach( $custom_filters as $filter => $validation ) {
            $default       = ( in_array( $validation, [ 'array' ] ) ) ? [] : 'no_filter_defined';
            $filter_output = apply_filters( $filter, $default );
            if ( 'no_filter_defined' != $filter_output || is_array( $filter_output ) && empty( $filter_output ) ) {
                if ( in_array( 'email', $validation ) ) {
                    if ( is_string( $filter_output ) ) {
                        if ( ! is_email( trim( $filter_output ) ) ) {
                            $error_messages[] = sprintf( __( 'The email address "%s", which you set in the filter "%s", is invalid.', 'b3-onboarding' ), $filter_output, $filter );
                        }
                    } elseif ( is_array( $filter_output ) ) {
                        foreach( $filter_output as $email ) {
                            if ( ! is_email( trim( $email ) ) ) {
                                $error_messages[] = sprintf( __( 'The email address "%s", which you set in the filter "%s", is invalid.', 'b3-onboarding' ), $email, $filter );
                            }
                        }
                    } else {
                        $error_messages[] = sprintf( __( 'The value, which you set in the filter "%s", is not a string or an array.', 'b3-onboarding' ), $filter );
                    }

                } elseif ( in_array( 'url', $validation ) ) {
                    if ( filter_var( $filter_output, FILTER_VALIDATE_URL ) === false ) {
                        $error_messages[] = sprintf( __( 'The url, which you set in the filter "%s", is not a valid url.', 'b3-onboarding' ), $filter );
                    } else {
                        if ( in_array( 'file', $validation ) ) {
                            if ( false === b3_check_remote_file( $filter_output ) ) {
                                $error_messages[] = sprintf( __( 'The url, which you set in the filter "%s", does not exist or is unreachable.', 'b3-onboarding' ), $filter );
                            }
                        }
                    }

                } elseif ( in_array( 'hex_color', $validation ) ) {
                    if ( false == $filter_output ) {
                        $error_messages[] = sprintf( __( 'The color, which you set in the filter "%s", is not invalid.', 'b3-onboarding' ), $filter );
                    } elseif ( false == is_string( $filter_output ) ) {
                        $error_messages[] = sprintf( __( 'The color (%s), which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $filter_output, $filter );
                    } elseif ( false == sanitize_hex_color( $filter_output ) ) {
                        $error_messages[] = sprintf( __( 'The color (%s), which you set in the filter "%s", is invalid.', 'b3-onboarding' ), $filter_output, $filter );
                    }

                } elseif ( in_array( 'array', $validation ) ) {
                    if ( ! is_array( $filter_output ) ) {
                        $error_messages[] = sprintf( __( 'The value, which you set in the filter "%s", is not an array.', 'b3-onboarding' ), $filter );
                    } elseif ( empty( $filter_output ) ) {
                        $error_messages[] = sprintf( __( 'The value, which you set in the filter "%s", is an empty array.', 'b3-onboarding' ), $filter );
                    } else {
                        if ( in_array( 'hidden', $validation ) ) {
                            foreach( $filter_output as $key => $value ) {
                                if ( ! is_string( $key ) ) {
                                    $error_messages[] = sprintf( __( 'The field ID "%s", which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $key, $filter );
                                }
                                if ( ! is_string( $value ) ) {
                                    $error_messages[] = sprintf( __( 'The field value "%s", which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $key, $filter );
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
                                        $error_messages[] = sprintf( __( 'You didn\'t set a field type for "%s".', 'b3-onboarding' ), $replace );
                                    } else {
                                        $error_messages[] = __( 'You didn\'t set a field type for one of your fields.', 'b3-onboarding' );
                                    }
                                } else {
                                    if ( ! isset( $field[ 'label' ] ) && isset( $field[ 'id' ] ) ) {
                                        $error_messages[] = sprintf( __( 'A label for the field "%s" in the filter "%s" is required.', 'b3-onboarding' ), $field[ 'id' ], $filter );
                                    }
                                    if ( ! isset( $field[ 'id' ] ) && isset( $field[ 'label' ] ) ) {
                                        $error_messages[] = sprintf( __( 'An ID for the field "%s" in the filter "%s" is required.', 'b3-onboarding' ), $field[ 'label' ], $filter );
                                    }
                                    if ( ! isset( $field[ 'id' ] ) && ! isset( $field[ 'label' ] ) ) {
                                        $error_messages[] = sprintf( __( 'An ID and label for your option in the filter "%s" is required.', 'b3-onboarding' ), $filter );
                                    }
                                    if ( in_array( $field[ 'type' ], [ 'checkbox', 'radio', 'select' ] ) ) {
                                        if ( ! isset( $field[ 'options' ] ) ) {
                                            if ( isset( $field[ 'label' ] ) || isset( $field[ 'id' ] ) ) {
                                                if ( isset( $field[ 'label' ] ) ) {
                                                    $replace = $field[ 'label' ];
                                                } elseif ( isset( $field[ 'id' ] ) ) {
                                                    $replace = $field[ 'id' ];
                                                }
                                                $error_messages[] = sprintf( __( 'You didn\'t set any options for the field "%s".', 'b3-onboarding' ), $replace );
                                            } else {
                                                $error_messages[] = __( 'You didn\'t set a field type for one of your fields.', 'b3-onboarding' );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } elseif ( in_array( 'string', $validation ) ) {
                    if ( ! is_string( $filter_output ) ) {
                        $error_messages[] = sprintf( __( 'The value, which you set in the filter "%s", is not a string.', 'b3-onboarding' ), $filter );
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
