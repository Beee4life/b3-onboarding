<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $user_approved    = esc_html__( 'User is successfully approved.', 'b3-onboarding' );
    $user_not_deleted = esc_html__( 'User is successfully rejected but there was an error deleting the account.', 'b3-onboarding' );
    $user_rejected    = esc_html__( 'User is successfully rejected and the account is deleted.', 'b3-onboarding' );

    if ( ! empty( $_GET[ 'user' ] ) ) {
        if ( is_admin() ) {
            if ( 'approved' == $_GET[ 'user' ] ) {
                B3Onboarding::b3_errors()->add( 'success_user_approved', $user_approved );
            } elseif ( 'rejected' == $_GET[ 'user' ] ) {
                B3Onboarding::b3_errors()->add( 'success_user_rejected', $user_rejected );
            } elseif ( 'not-deleted' == $_GET[ 'user' ] ) {
                B3Onboarding::b3_errors()->add( 'error_user_delete', $user_not_deleted );
            }
            B3Onboarding::b3_show_admin_notices();
        } else {
            $message = false;
            if ( 'approved' == $_GET[ 'user' ] ) {
                $message = $user_approved;
            } elseif ( 'rejected' == $_GET[ 'user' ] ) {
                $message = $user_rejected;
            } elseif ( 'not-deleted' == $_GET[ 'user' ] ) {
                $message = $user_not_deleted;
            }
            if ( $message ) {
                echo sprintf( '<p class="b3_message">%s</p>', $message );
            }
        }
    }
