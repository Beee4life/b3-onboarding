<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_handle_send_test_email() {
        check_ajax_referer( 'b3_send_test_email_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorized user.', 'b3-onboarding' ) ] );
        }

        $preview         = isset( $_POST[ 'preview' ] ) ? sanitize_text_field( $_POST[ 'preview' ] ) : '';
        $subject_message = b3_get_subject_message( $preview );

        if ( ! empty( $subject_message ) ) {
            $subject = $subject_message[ 'subject' ];
            $message = $subject_message[ 'message' ];
        }

        if ( ! empty( $subject ) && ! empty( $message ) ) {
            $subject = strtr( $subject, b3_get_replacement_vars( 'subject' ) );
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_get_replacement_vars() );
            $message = htmlspecialchars_decode( stripslashes( $message ) );
            $to      = get_option( 'admin_email' );
            $sent    = wp_mail( $to, $subject, $message );

            if ( $sent ) {
                wp_send_json_success( [ 'message' => __( 'Test email sent successfully!', 'b3-onboarding' ) ] );
            } else {
                wp_send_json_error( [ 'message' => __( 'Failed to send email.', 'b3-onboarding' ) ] );
            }
        } else {
            error_log( __( 'No subject and email.', 'b3-onboarding' ) );
        }
    }
    add_action( 'wp_ajax_b3_send_test_email', 'b3_handle_send_test_email' );
