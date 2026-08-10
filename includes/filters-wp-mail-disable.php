<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Disable/filter password change notification mail (admin)
    function b3_password_changed_email_admin( $wp_mail, $user, $blogname ) {
        /* translators: username */
        $message = sprintf( esc_html__( 'Password changed for user: %s', 'b3-onboarding' ), $user->user_login );
        $message = b3_replace_template_styling( $message );
        $message = strtr( $message, b3_get_replacement_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );
        $subject = __( 'User changed password', 'b3-onboarding' ); // default: [blog name] Password changed

        $wp_mail[ 'subject' ] = $subject;
        $wp_mail[ 'message' ] = $message;

        if ( get_option( 'b3_disable_admin_notification_password_change' ) ) {
            $wp_mail = [
                'to'      => false,
                'subject' => false,
                'message' => false,
                'headers' => false,
            ];
        }

        return $wp_mail;
    }
    add_filter( 'wp_password_change_notification_email', 'b3_password_changed_email_admin', 10, 3 );

    // Disable admin email when registration is closed
    function b3_disable_admin_email( $status, $site, $user ) {
        if ( 'none' === get_option( 'b3_registration_type' ) ) {
            return false;
        }

        return $status;
    }
    add_filter( 'send_new_site_email', 'b3_disable_admin_email', 10, 3 );

    // Disable WPMU user signup email to take it over
    function b3_disable_wpmu_user_signup_notification( $user_login, $user_email, $key, $meta = [] ) {
        if ( is_admin() && isset( $_POST[ 'action' ] ) && 'createuser' === $_POST[ 'action' ] ) {
            return true;
        }
        return false;
    }
    add_filter( 'wpmu_signup_user_notification', 'b3_disable_wpmu_user_signup_notification', 10, 4 );

    // Disable WPMU user welcome email to take it over
    function b3_disable_welcome_mu_user_email( $user_id, $password, $meta ) {
        return false;
    }
    add_filter( 'wpmu_welcome_user_notification', 'b3_disable_welcome_mu_user_email', 10, 3 );

    // Disable email for register site + user (WPMU)
    function b3_disable_signup_mu_user_blog_email() {
        return false;
    }
    add_filter( 'wpmu_signup_blog_notification', 'b3_disable_signup_mu_user_blog_email' );

    // Disable new user mail with login credentials
    function b3_disable_welcome_mu_user_blog_email( $blog_id, $user_id, $password, $title, $meta ) {
        return false;
    }
    add_filter( 'wpmu_welcome_notification', 'b3_disable_welcome_mu_user_blog_email', 10, 5 );

    function b3_disable_admin_notification_manually_added( $value, $site, $user ) {
        return false;
    }
    add_filter( 'send_new_site_email', 'b3_disable_admin_notification_manually_added', 15, 3 );
