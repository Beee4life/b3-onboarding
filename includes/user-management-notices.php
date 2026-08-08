<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $user_approved          = esc_html__( 'User is successfully approved.', 'b3-onboarding' );
    $user_site_approved     = esc_html__( 'User and site are successfully approved.', 'b3-onboarding' );
    $user_not_deleted       = esc_html__( 'User is successfully rejected but there was an error deleting the account.', 'b3-onboarding' );
    $user_rejected          = esc_html__( 'User is successfully rejected and the account is deleted.', 'b3-onboarding' );
    $user_rejected_not_site = esc_html__( 'User is successfully rejected, the account is deleted but not the site.', 'b3-onboarding' );
    $user_site_rejected     = esc_html__( 'User is successfully rejected and the user account and site are deleted.', 'b3-onboarding' );

    if ( ! empty( $_GET[ 'user' ] ) ) {
        $user_var = sanitize_text_field( wp_unslash( $_GET[ 'user' ] ) );

        if ( is_admin() ) {
            if ( 'user_approved' === $user_var ) {
                B3Onboarding::b3_errors()->add( 'success_user_approved', $user_approved );
            } elseif ( 'user_site_approved' === $user_var ) {
                B3Onboarding::b3_errors()->add( 'success_user_site_approved', $user_site_approved );
            } elseif ( 'rejected' === $user_var ) {
                B3Onboarding::b3_errors()->add( 'success_user_rejected', $user_rejected );
            } elseif ( 'user_rejected_not_site' === $user_var ) {
                B3Onboarding::b3_errors()->add( 'success_user_rejected_not_site', $user_rejected_not_site );
            } elseif ( 'user_site_rejected' === $user_var ) {
                B3Onboarding::b3_errors()->add( 'success_user_site_rejected', $user_site_rejected );
            } elseif ( 'not-deleted' === $user_var ) {
                B3Onboarding::b3_errors()->add( 'error_user_delete', $user_not_deleted );
            }
            B3Onboarding::b3_show_admin_notices();

        } else {
            $message = false;
            if ( 'approved' === $user_var ) {
                $message = $user_approved;
            } elseif ( 'rejected' === $user_var ) {
                $message = $user_rejected;
            } elseif ( 'user_rejected_not_site' === $user_var ) {
                $message = $user_rejected_not_site;
            } elseif ( 'user_site_rejected' === $user_var ) {
                $message = $user_site_rejected;
            } elseif ( 'not-deleted' === $user_var ) {
                $message = $user_not_deleted;
            }
            if ( $message ) {
                echo sprintf( '<p class="b3_message">%s</p>', esc_html( $message ) );
            }
        }
    }
