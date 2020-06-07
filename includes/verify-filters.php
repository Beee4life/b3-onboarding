<?php
    /**
     * Get all filtered output and verify it
     *
     * @TODO: create array with filter names (maybe type) - loop through it and verify
     *
     * @since 2.0.0
     */
    function b3_verify_filter_input() {

        $custom_filters = array(
            'b3_account_activated_message_user',
            'b3_account_activated_subject_user',
            'b3_account_approved_message',
            'b3_account_approved_subject',
            'b3_account_rejected_message',
            'b3_account_rejected_subject',
            'b3_add_filter_extra_fields_values',
            'b3_custom_register_inform',
            'b3_email_activation_message_user',
            'b3_email_activation_subject_user',
            'b3_email_footer_text',
            'b3_email_styling',
            'b3_email_template',
            'b3_filter_closed_message',
            'b3_filter_hidden_fields_values',
            'b3_link_color',
            'b3_main_logo',
            'b3_new_user_mesage',
            'b3_new_user_notification_addresses',
            'b3_new_user_subject', //
            'b3_notification_sender_email',
            'b3_request_access_message_admin',
            'b3_request_access_subject_admin',
            'b3_request_access_message_user',
            'b3_request_access_subject_user',
            'b3_welcome_user_message',
            'b3_welcome_user_subject',
        );

        $account_activated_message_user = apply_filters( 'b3_account_activated_message_user', 'no_filter_defined' );
        $account_activated_subject_user = apply_filters( 'b3_account_activated_subject_user', 'no_filter_defined' );
        $account_approved_message       = apply_filters( 'b3_account_approved_message', 'no_filter_defined' );
        $account_approved_subject       = apply_filters( 'b3_account_approved_subject', 'no_filter_defined' );
        $account_rejected_message       = apply_filters( 'b3_account_rejected_message', 'no_filter_defined' );
        $account_rejected_subject       = apply_filters( 'b3_account_rejected_subject', 'no_filter_defined' );
        $email_activation_message_user  = apply_filters( 'b3_email_activation_message_user', 'no_filter_defined' );
        $email_activation_subject_user  = apply_filters( 'b3_email_activation_subject_user', 'no_filter_defined' );
        $email_styling                  = apply_filters( 'b3_email_styling', 'no_filter_defined' );
        $email_template                 = apply_filters( 'b3_email_template', 'no_filter_defined' );
        $error_messages                 = array();
        $extra_fields                   = apply_filters( 'b3_add_filter_extra_fields_values', 'no_filter_defined' );
        $from_email                     = apply_filters( 'b3_notification_sender_email', 'no_filter_defined' );
        $footer_text                    = apply_filters( 'b3_email_footer_text', 'no_filter_defined' );
        $link_color                     = apply_filters( 'b3_link_color', 'no_filter_defined' );
        $logo                           = apply_filters( 'b3_main_logo', 'no_filter_defined' );
        $new_user_message                 = apply_filters( 'b3_new_user_mesage', 'no_filter_defined' );
        $new_user_subject                 = apply_filters( 'b3_new_user_subject', 'no_filter_defined' );
        $register_inform                = apply_filters( 'b3_custom_register_inform', 'no_filter_defined' );
        $request_access_message_admin   = apply_filters( 'b3_request_access_message_admin', 'no_filter_defined' );
        $request_access_subject_admin   = apply_filters( 'b3_request_access_subject_admin', 'no_filter_defined' );
        $request_access_message_user    = apply_filters( 'b3_request_access_message_user', 'no_filter_defined' );
        $request_access_subject_user    = apply_filters( 'b3_request_access_subject_user', 'no_filter_defined' );
        $welcome_user_message           = apply_filters( 'b3_welcome_user_message', 'no_filter_defined' );
        $welcome_user_subject           = apply_filters( 'b3_welcome_user_subject', 'no_filter_defined' );

        // @TODO: add verification for these
        $closed_message                   = apply_filters( 'b3_filter_closed_message', 'no_filter_defined' );
        $filter_hidden_fields             = apply_filters( 'b3_filter_hidden_fields_values', 'no_filter_defined' );
        $new_user_notifications_addresses = apply_filters( 'b3_new_user_notification_addresses', 'no_filter_defined' );


        // SETTINGS
        if ( 'no_filter_defined' != $logo ) {
            if ( false == is_string( $logo ) ) {
                $error_messages[] = __( 'The logo url you set in the filter "b3_main_logo" is not a string.', 'b3-onboarding' );
            } elseif ( false === wp_http_validate_url( $logo ) ) {
                $error_messages[] = __( 'The logo url you set in the filter "b3_main_logo" is not a valid url.', 'b3-onboarding' );
            } elseif ( ! file_exists( $logo ) ) {
                $error_messages[] = __( 'The logo url you set in the filter "b3_main_logo" does not exist or is unreachable.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $link_color ) {
            if ( false == is_string( $link_color ) ) {
                $error_messages[] = __( 'The link color you set in the filter "b3_link_color" is not a string.', 'b3-onboarding' );
            } elseif ( false == sanitize_hex_color( $link_color ) ) {
                $error_messages[] = __( 'The link color you set in the filter "b3_link_color" is not valid.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $register_inform ) {
            if ( false == is_string( $register_inform ) ) {
                $error_messages[] = __( 'The \'who to inform\' you set in the filter "b3_custom_register_inform" is not a string.', 'b3-onboarding' );
            }
        }


        // EMAIL
        if ( 'no_filter_defined' != $from_email ) {
            if ( false == is_email( $from_email ) ) {
                $error_messages[] = __( 'The email address you set in the filter "b3_notification_sender_email" is invalid.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_styling ) {
            if ( false == is_string( $email_styling ) ) {
                $error_messages[] = __( 'The css you set in the filter "b3_email_styling" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_template ) {
            if ( false == is_string( $email_template ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_email_template" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_activated_message_user ) {
            if ( false == is_string( $account_activated_message_user ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_account_activated_message_user" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_activated_subject_user ) {
            if ( false == is_string( $account_activated_subject_user ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_account_activated_subject_user" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_approved_message ) {
            if ( false == is_string( $account_approved_message ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_account_approved_message" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_approved_subject ) {
            if ( false == is_string( $account_approved_subject ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_account_approved_subject" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_rejected_message ) {
            if ( false == is_string( $account_rejected_message ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_account_rejected_message" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $account_rejected_subject ) {
            if ( false == is_string( $account_rejected_subject ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_account_rejected_subject" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_activation_message_user ) {
            if ( false == is_string( $email_activation_message_user ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_email_activation_message_user" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $email_activation_subject_user ) {
            if ( false == is_string( $email_activation_subject_user ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_email_activation_subject_user" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $new_user_message ) {
            if ( false == is_string( $new_user_message ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_new_user_mesage" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $new_user_subject ) {
            if ( false == is_string( $new_user_subject ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_new_user_subject" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_message_admin ) {
            if ( false == is_string( $request_access_message_admin ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_request_access_message_admin" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_subject_admin ) {
            if ( false == is_string( $request_access_subject_admin ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_request_access_subject_admin" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_message_user ) {
            if ( false == is_string( $request_access_message_user ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_request_access_message_user" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $request_access_subject_user ) {
            if ( false == is_string( $request_access_subject_user ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_request_access_subject_user" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $welcome_user_message ) {
            if ( false == is_string( $welcome_user_message ) ) {
                $error_messages[] = __( 'The message you set in the filter "b3_welcome_user_message" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $welcome_user_subject ) {
            if ( false == is_string( $welcome_user_subject ) ) {
                $error_messages[] = __( 'The subject you set in the filter "b3_welcome_user_subject" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $footer_text ) {
            if ( false == is_string( $footer_text ) ) {
                $error_messages[] = __( 'The footer text you set in the filter "b3_email_footer_text" is not a string.', 'b3-onboarding' );
            }
        }

        if ( 'no_filter_defined' != $extra_fields ) {
            // @TODO: add in-depth field validation
            if ( false == is_array( $extra_fields ) ) {
                $error_messages[] = __( 'The extra field values you set in the filter "b3_add_filter_extra_fields_values" is not an array.', 'b3-onboarding' );
            } elseif ( empty( $extra_fields ) ) {
                $error_messages[] = __( 'The extra field values you set in the filter "b3_add_filter_extra_fields_values" is an empty array.', 'b3-onboarding' );
            }
        }

        if ( ! empty( $error_messages ) ) {
            foreach( $error_messages as $message ) {
                echo '<div class="error"><p>' . $message . '</p></div>';
            }
        }

    }
    add_action( 'b3_verify_filter_input', 'b3_verify_filter_input' );
