<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Register AJAX action for logged-in users
    function b3_handle_send_test_email() {
        // Check nonce security
        check_ajax_referer( 'b3_send_test_email_nonce', 'nonce' );

        // Check user permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Unauthorized user.', 'b3-onboarding' ) ) );
        }

        $lorem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non purus magna. Nam quam est, rutrum non consequat sed, finibus quis mi. Vestibulum eget felis risus. Phasellus nibh ligula, tristique non lorem in, blandit <a href="">iaculis</a> enim. In eleifend fermentum scelerisque. Mauris ultrices tortor non massa lobortis, eget molestie nunc fringilla. Integer fermentum ultrices quam vel scelerisque. Nullam non augue laoreet, sagittis orci ac, eleifend massa.
            <br><br>
            Quisque <a href="">quis nibh</a> gravida, condimentum nibh sed, facilisis ligula. Phasellus placerat, metus a ultricies vulputate, arcu massa ullamcorper enim, id iaculis nisl augue eu dolor. Aliquam vel nisi at lacus ultrices fringilla. In cursus mattis lectus, non ultricies orci vulputate nec. Fusce non vestibulum nulla. Cras libero metus, fermentum sit amet venenatis sit amet, vestibulum vitae lectus. Donec interdum volutpat blandit.
            <br><br>
            <div class="big-link-container"><div class="big-link"><a href="">BUTTON LABEL</a></div></div>
            <br>
            Morbi vehicula metus vestibulum, eleifend arcu quis, rutrum massa. Sed porttitor pellentesque convallis. Suspendisse potenti. Nam dapibus vitae tortor a egestas. Ut at lobortis tortor. Sed tellus sem, pulvinar sit amet posuere non, vulputate vitae mi. Vestibulum ac massa suscipit, placerat risus ut, rutrum turpis. Integer in risus ac turpis dapibus viverra. Nulla facilisi. Nam ut cursus felis. Pellentesque <a href="">congue scelerisque</a> nisl, nec ultricies ex. Vivamus id ex ac dolor porttitor tempus. Maecenas pulvinar porta nunc, in mollis erat egestas et.';
        $preview     = isset( $_POST[ 'preview' ] ) ? sanitize_text_field( $_POST[ 'preview' ] ) : '';

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
