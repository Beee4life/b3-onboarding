<?php
    /**
     * Get all filtered output and verify it
     *
     * @TODO: create array with filter names (maybe type) - loop through it and verify
     *
     * @since 2.0.0
     */
    function b3_verify_filter_input() {

        $error_messages = array();
        $custom_filters = array(
            'b3_account_activated_message_user'  => array( 'string' ),
            'b3_account_activated_subject_user'  => array( 'string' ),
            'b3_account_approved_message'        => array( 'string' ),
            'b3_account_approved_subject'        => array( 'string' ),
            'b3_account_rejected_message'        => array( 'string' ),
            'b3_account_rejected_subject'        => array( 'string' ),
            'b3_before_password_reset'           => array( 'string' ),
            'b3_before_request_access'           => array( 'string' ),
            'b3_custom_register_inform'          => array( 'string' ),
            'b3_email_activation_message_user'   => array( 'string' ),
            'b3_email_activation_subject_user'   => array( 'string' ),
            'b3_email_footer_text'               => array( 'string' ),
            'b3_email_styling'                   => array( 'string' ),
            'b3_email_template'                  => array( 'string' ),
            'b3_extra_fields'                    => array( 'array', 'extra' ),
            'b3_hidden_fields'                   => array( 'array', 'hidden' ),
            'b3_link_color'                      => array( 'hex_color' ),
            'b3_main_logo'                       => array( 'url', 'file' ),
            'b3_new_user_message'                => array( 'string' ),
            'b3_new_user_notification_addresses' => array( 'email' ),
            'b3_new_user_subject'                => array( 'string' ),
            'b3_notification_sender_email'       => array( 'email' ),
            'b3_password_reset_message'          => array( 'string' ), // @TODO: only used in preview
            'b3_password_reset_subject'          => array( 'string' ), // @TODO: only used in preview
            'b3_privacy_text'                    => array( 'string' ),
            'b3_registration_closed_message'     => array( 'string' ),
            'b3_request_access_message_admin'    => array( 'string' ),
            'b3_request_access_subject_admin'    => array( 'string' ),
            'b3_request_access_message_user'     => array( 'string' ),
            'b3_request_access_subject_user'     => array( 'string' ),
            'b3_welcome_user_message'            => array( 'string' ),
            'b3_welcome_user_subject'            => array( 'string' ),
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
                                // @TODO: verify extra fields
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
                echo '<div class="error"><p>' . $message . '</p></div>';
            }
        }

    }
    add_action( 'b3_verify_filter_input', 'b3_verify_filter_input' );
