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
            'b3_custom_register_inform'          => array( 'string' ),
            'b3_email_activation_message_user'   => array( 'string' ),
            'b3_email_activation_subject_user'   => array( 'string' ),
            'b3_email_footer_text'               => array( 'string' ),
            'b3_email_styling'                   => array( 'string' ),
            'b3_email_template'                  => array( 'string' ),
            // 'b3_extra_fields'                    => array( 'array' ), // @TODO: verify
            'b3_hidden_fields'                   => array( 'array', 'hidden' ), // @TODO: verify
            'b3_link_color'                      => array( 'hex_color' ),
            'b3_main_logo'                       => array( 'url', 'file' ),
            'b3_new_user_message'                => array( 'string' ),
            'b3_new_user_notification_addresses' => array( 'email' ),
            'b3_new_user_subject'                => array( 'string' ),
            'b3_notification_sender_email'       => array( 'email' ),
            'b3_password_reset_message'          => array( 'string' ), // @TODO: check if used
            'b3_password_reset_subject'          => array( 'string' ), // @TODO: check if used
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
            if ( 'b3_hidden_fields' == $filter ) {
                // echo '<pre>'; var_dump($filter_output); echo '</pre>'; exit;
                // echo '<pre>'; var_dump($validation); echo '</pre>'; exit;
            }

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
                        $error_messages[] = sprintf( __( 'The color (%s), which you set in the filter "%s", is not valid.', 'b3-onboarding' ), $filter_output, $filter );
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
                                // @TODO: verify fields
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
        // echo '<pre>'; var_dump($error_messages); echo '</pre>'; exit;

        // $account_activated_message_user   = apply_filters( 'b3_account_activated_message_user', 'no_filter_defined' );
        // $account_activated_subject_user   = apply_filters( 'b3_account_activated_subject_user', 'no_filter_defined' );
        // $account_approved_message         = apply_filters( 'b3_account_approved_message', 'no_filter_defined' );
        // $account_approved_subject         = apply_filters( 'b3_account_approved_subject', 'no_filter_defined' );
        // $account_rejected_message         = apply_filters( 'b3_account_rejected_message', 'no_filter_defined' );
        // $account_rejected_subject         = apply_filters( 'b3_account_rejected_subject', 'no_filter_defined' );
        // $registration_closed_message      = apply_filters( 'b3_registration_closed_message', 'no_filter_defined' );
        // $email_activation_message_user    = apply_filters( 'b3_email_activation_message_user', 'no_filter_defined' );
        // $email_activation_subject_user    = apply_filters( 'b3_email_activation_subject_user', 'no_filter_defined' );
        // $email_styling                    = apply_filters( 'b3_email_styling', 'no_filter_defined' );
        // $email_template                   = apply_filters( 'b3_email_template', 'no_filter_defined' );
        // $extra_fields                     = apply_filters( 'b3_extra_fields', 'no_filter_defined' );
        // $from_email                       = apply_filters( 'b3_notification_sender_email', 'no_filter_defined' );
        // $footer_text                      = apply_filters( 'b3_email_footer_text', 'no_filter_defined' );
        // $link_color                       = apply_filters( 'b3_link_color', 'no_filter_defined' );
        // $logo                             = apply_filters( 'b3_main_logo', 'no_filter_defined' );
        // $new_user_message                 = apply_filters( 'b3_new_user_mesage', 'no_filter_defined' );
        // $new_user_notifications_addresses = apply_filters( 'b3_new_user_notification_addresses', 'no_filter_defined' );
        // $new_user_subject                 = apply_filters( 'b3_new_user_subject', 'no_filter_defined' );
        // $register_inform                  = apply_filters( 'b3_custom_register_inform', 'no_filter_defined' );
        // $request_access_message_admin     = apply_filters( 'b3_request_access_message_admin', 'no_filter_defined' );
        // $request_access_subject_admin     = apply_filters( 'b3_request_access_subject_admin', 'no_filter_defined' );
        // $request_access_message_user      = apply_filters( 'b3_request_access_message_user', 'no_filter_defined' );
        // $request_access_subject_user      = apply_filters( 'b3_request_access_subject_user', 'no_filter_defined' );
        // $welcome_user_message             = apply_filters( 'b3_welcome_user_message', 'no_filter_defined' );
        // $welcome_user_subject             = apply_filters( 'b3_welcome_user_subject', 'no_filter_defined' );

        // @TODO: add verification for these
        // $hidden_fields                    = apply_filters( 'b3_hidden_fields', 'no_filter_defined' );


        // SETTINGS
        /*
        if ( 'no_filter_defined' != $logo ) {
            if ( false == is_string( $logo ) ) {
                $error_messages[] = __( 'The logo url, which you set in the filter "b3_main_logo", is not a string.', 'b3-onboarding' );
            } elseif ( false === wp_http_validate_url( $logo ) ) {
                $error_messages[] = __( 'The logo url, which you set in the filter "b3_main_logo", is not a valid url.', 'b3-onboarding' );
            } elseif ( ! file_exists( $logo ) ) {
                $error_messages[] = __( 'The logo url, which you set in the filter "b3_main_logo", does not exist or is unreachable.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $link_color ) {
            if ( false == is_string( $link_color ) ) {
                $error_messages[] = __( 'The link color, which you set in the filter "b3_link_color", is not a string.', 'b3-onboarding' );
            } elseif ( false == sanitize_hex_color( $link_color ) ) {
                $error_messages[] = __( 'The link color, which you set in the filter "b3_link_color", is not valid.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $register_inform ) {
            if ( false == is_string( $register_inform ) ) {
                $error_messages[] = __( 'The \'who to inform\', which you set in the filter "b3_custom_register_inform", is not a string.', 'b3-onboarding' );
            }
        }


        // FORM
        if ( 'no_filter_defined' != $registration_closed_message ) {
            if ( ! is_string( $registration_closed_message ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_registration_closed_message", is not a string.', 'b3-onboarding' );
            }
        }


        // EMAIL
        if ( 'no_filter_defined' != $new_user_notifications_addresses ) {
            if ( is_string( $new_user_notifications_addresses ) ) {
                $email_array = explode( ',', $new_user_notifications_addresses );
            } elseif ( is_array( $new_user_notifications_addresses ) ) {
                $email_array = $new_user_notifications_addresses;
            } else {
                $error_messages[] = __( 'The email address(es), which you set in the filter "b3_new_user_notification_addresses", is not an array or a string.', 'b3-onboarding' );
            }
            if ( isset( $email_array ) && ! empty( $email_array ) ) {
                foreach( $email_array as $email ) {
                    $email = trim( $email );
                    if ( ! is_email( $email ) ) {
                        $error_messages[] = sprintf( __( 'The email address "%s", which you set in the filter "b3_new_user_notification_addresses", is invalid.', 'b3-onboarding' ), $email );
                    }
                }
            }
        }

        if ( 'no_filter_defined' != $from_email ) {
            if ( false == is_email( $from_email ) ) {
                $error_messages[] = __( 'The email address, which you set in the filter "b3_notification_sender_email", is invalid.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_styling ) {
            if ( false == is_string( $email_styling ) ) {
                $error_messages[] = __( 'The css, which you set in the filter "b3_email_styling", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_template ) {
            if ( false == is_string( $email_template ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_email_template", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_activated_message_user ) {
            if ( false == is_string( $account_activated_message_user ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_account_activated_message_user", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_activated_subject_user ) {
            if ( false == is_string( $account_activated_subject_user ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_account_activated_subject_user", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_approved_message ) {
            if ( false == is_string( $account_approved_message ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_account_approved_message", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_approved_subject ) {
            if ( false == is_string( $account_approved_subject ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_account_approved_subject", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_rejected_message ) {
            if ( false == is_string( $account_rejected_message ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_account_rejected_message", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_rejected_subject ) {
            if ( false == is_string( $account_rejected_subject ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_account_rejected_subject", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_activation_message_user ) {
            if ( false == is_string( $email_activation_message_user ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_email_activation_message_user", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_activation_subject_user ) {
            if ( false == is_string( $email_activation_subject_user ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_email_activation_subject_user", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $new_user_message ) {
            if ( false == is_string( $new_user_message ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_new_user_mesage", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $new_user_subject ) {
            if ( false == is_string( $new_user_subject ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_new_user_subject", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_message_admin ) {
            if ( false == is_string( $request_access_message_admin ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_request_access_message_admin", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_subject_admin ) {
            if ( false == is_string( $request_access_subject_admin ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_request_access_subject_admin", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_message_user ) {
            if ( false == is_string( $request_access_message_user ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_request_access_message_user", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_subject_user ) {
            if ( false == is_string( $request_access_subject_user ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_request_access_subject_user", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $welcome_user_message ) {
            if ( false == is_string( $welcome_user_message ) ) {
                $error_messages[] = __( 'The message, which you set in the filter "b3_welcome_user_message", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $welcome_user_subject ) {
            if ( false == is_string( $welcome_user_subject ) ) {
                $error_messages[] = __( 'The subject, which you set in the filter "b3_welcome_user_subject", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $footer_text ) {
            if ( false == is_string( $footer_text ) ) {
                $error_messages[] = __( 'The footer text, which you set in the filter "b3_email_footer_text", is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $extra_fields ) {
            // @TODO: add in-depth field validation
            if ( false == is_array( $extra_fields ) ) {
                $error_messages[] = __( 'The extra field values you set in the filter "b3_extra_fields", is not an array.', 'b3-onboarding' );
            } elseif ( empty( $extra_fields ) ) {
                $error_messages[] = __( 'The extra field values you set in the filter "b3_extra_fields", is an empty array.', 'b3-onboarding' );
            }
        }
        */

        if ( ! empty( $error_messages ) ) {
            foreach( $error_messages as $message ) {
                echo '<div class="error"><p>' . $message . '</p></div>';
            }
        }

    }
    add_action( 'b3_verify_filter_input', 'b3_verify_filter_input' );
