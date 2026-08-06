<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Return email styling and default styling if false
    function b3_get_email_styling( $link_color = false ) {
        $email_styling = get_option( 'b3_email_styling' );

        if ( ! $email_styling ) {
            $email_styling = b3_default_email_styling( $link_color );
        }

        return apply_filters( 'b3_email_styling', $email_styling );
    }

    // Return link color for emails
    function b3_get_link_color() {
        $link_color = get_option( 'b3_link_color' );

        if ( ! $link_color ) {
            $link_color = b3_default_link_color();
        }

        return apply_filters( 'b3_link_color', $link_color );
    }

    // Return user email template and default template if false
    function b3_get_email_template( $hide_logo = false ) {
        $email_template = get_option( 'b3_email_template' );

        if ( ! $email_template ) {
            $email_template = b3_default_email_template( $hide_logo );
        }

        return apply_filters( 'b3_email_template', $email_template );
    }

    // Return default email footer
    // @TODO: maybe add user input option
    function b3_get_email_footer() {
        return apply_filters( 'b3_email_footer_text', b3_default_email_footer() );
    }

    // Get notification addresses
    function b3_get_notification_addresses( $registration_type ) {
        $email_addresses = get_site_option( 'admin_email' );
        $admin_approval  = get_option( 'b3_needs_admin_approval' );

        if ( $admin_approval ) {
            if ( false != get_option( 'b3_request_access_notification_addresses' ) ) {
                $email_addresses = get_option( 'b3_request_access_notification_addresses' );
            }
        } elseif ( 'open' === $registration_type ) {
            if ( false != get_option( 'b3_new_user_notification_addresses' ) ) {
                $email_addresses = get_option( 'b3_new_user_notification_addresses' );
            }
        }

        return apply_filters( 'b3_new_user_notification_addresses', $email_addresses );
    }

    // Return email activation subject (user)
    function b3_get_email_activation_subject_user() {
        $subject = get_option( 'b3_email_activation_subject' );

        if ( ! $subject ) {
            $subject = b3_default_email_activation_subject();
        }

        return apply_filters( 'b3_email_activation_subject_user', $subject );
    }

    // Return email activation message (user)
    function b3_get_email_activation_message_user() {
        $message = get_option( 'b3_email_activation_message' );

        if ( ! $message ) {
            $message = b3_default_email_activation_message();
        }

        return apply_filters( 'b3_email_activation_message_user', $message );
    }

    // Return welcome user subject (user)
    function b3_get_welcome_user_subject() {
        $subject = get_option( 'b3_welcome_user_subject' );

        if ( ! $subject ) {
            $subject = b3_default_welcome_user_subject();
        }

        return apply_filters( 'b3_welcome_user_subject', $subject );
    }

    // Return welcome user message (user)
    function b3_get_welcome_user_message( $user_email = '' ) {
        $message = get_option( 'b3_welcome_user_message' );

        if ( ! $message ) {
            $message = b3_default_welcome_user_message( $user_email );
        }

        return apply_filters( 'b3_welcome_user_message', $message, $email );
    }

    // New site created message
    function b3_get_new_site_created_message() {
        // @TODO: maybe add user input option
        $message = b3_default_message_new_site_created();

        return apply_filters( 'b3_new_site_created_message', $message );
    }

    // Get email subject for request access (admin)
    function b3_get_request_access_subject_admin() {
        $subject = get_option( 'b3_request_access_subject_admin' );

        if ( ! $subject ) {
            $subject = b3_default_request_access_subject_admin();
        }

        return apply_filters( 'b3_request_access_subject_admin', $subject );
    }

    // Get email message for request access (admin)
    function b3_get_request_access_message_admin() {
        $message = get_option( 'b3_request_access_message_admin' );

        if ( ! $message ) {
            $message = b3_default_request_access_message_admin();
        }

        return apply_filters( 'b3_request_access_message_admin', $message );
    }

    // Get email subject for request access (user)
    function b3_get_request_access_subject_user() {
        $subject = get_option( 'b3_request_access_subject_user' );

        if ( ! $subject ) {
            $subject = b3_default_request_access_subject_user();
        }

        return apply_filters( 'b3_request_access_subject_user', $subject );
    }

    // Get email message for request access (user)
    function b3_get_request_access_message_user() {
        $message = get_option( 'b3_request_access_message_user' );

        if ( ! $message ) {
            $message = b3_default_request_access_message_user();
        }

        return apply_filters( 'b3_request_access_message_user', $message );
    }

    // Get email subject for account approved
    function b3_get_account_approved_subject() {
        $subject = get_option( 'b3_account_approved_subject' );

        if ( ! $subject ) {
            $subject = b3_default_account_approved_subject();
        }

        return apply_filters( 'b3_account_approved_subject', $subject );
    }

    // Get email message for account approved
    function b3_get_account_approved_message() {
        $message = get_option( 'b3_account_approved_message' );

        if ( ! $message ) {
            $message = b3_default_account_approved_message();
        }

        return apply_filters( 'b3_account_approved_message', $message );
    }

    // Get email subject for account activated (user)
    function b3_get_account_activated_subject_user() {
        $subject = get_option( 'b3_account_activated_subject' );

        if ( ! $subject ) {
            $subject = b3_default_account_activated_subject();
        }

        return apply_filters( 'b3_account_activated_subject_user', $subject );
    }

    // Get email message for account activated (user)
    // @TODO: maybe merge with welcome
    function b3_get_account_activated_message_user( $email = '' ) {
        $message = get_option( 'b3_account_activated_message' );

        if ( ! $message ) {
            $message = b3_default_account_activated_message( $email );
        }

        return apply_filters( 'b3_account_activated_message_user', $message );
    }

    // Get account rejected subject (user)
    function b3_get_account_rejected_subject() {
        $subject = get_option( 'b3_account_rejected_subject' );

        if ( ! $subject ) {
            $subject = b3_default_account_rejected_subject() . "\n";
        }

        return apply_filters( 'b3_account_rejected_subject', $subject );
    }

    // Get account rejected message (user)
    function b3_get_account_rejected_message() {
        $message = get_option( 'b3_account_rejected_message' );

        if ( ! $message ) {
            $message = b3_default_account_rejected_message() . "\n";
        }

        return apply_filters( 'b3_account_rejected_message', $message );
    }

    // Get lost password message (user)
    function b3_get_lost_password_message() {
        $message = get_option( 'b3_lost_password_message' );

        if ( ! $message ) {
            $message = b3_default_lost_password_message() . "\n";
        }

        return apply_filters( 'b3_lost_password_message', $message );
    }

    // Return new user subject (admin)
    function b3_get_new_user_subject() {
        $subject = get_option( 'b3_new_user_subject' );

        if ( ! $subject ) {
            $subject = b3_default_new_user_admin_subject() . "\n";
        }

        return apply_filters( 'b3_new_user_subject', $subject );
    }

    // Return new user message (admin)
    function b3_get_new_user_message() {
        $message = get_option( 'b3_new_user_message' );

        if ( ! $message ) {
            $message = b3_default_new_user_admin_message();
        }

        return apply_filters( 'b3_new_user_message', $message );
    }

    // Get password subject (user)
    function b3_get_lost_password_subject() {
        $subject = get_option( 'b3_lost_password_subject' );

        if ( ! $subject ) {
            $subject = b3_default_lost_password_subject();
        }

        return apply_filters( 'b3_lost_password_subject', $subject );
    }

    // Get sender email
    function b3_get_notification_sender_email() {
        $notification_sender_email = get_option( 'b3_notification_sender_email' );

        if ( ! $notification_sender_email ) {
            $notification_sender_email = get_site_option( 'admin_email' );
        }

        return apply_filters( 'b3_notification_sender_email', $notification_sender_email );
    }

    // Get sender name
    function b3_get_notification_sender_name() {
        $notification_sender_name = get_option( 'b3_notification_sender_name' );

        if ( ! $notification_sender_name ) {
            $notification_sender_name = get_option( 'blogname' );
        }

        return apply_filters( 'b3_notification_sender_name', $notification_sender_name );
    }

    // Get manually added welcome message user
    function b3_get_manual_welcome_user_message() {
        $manual_welcome_message = get_option( 'b3_welcome_user_message_manual' );

        if ( ! $manual_welcome_message ) {
            $manual_welcome_message = b3_default_manual_welcome_user_message();
        }

        return apply_filters( 'b3_welcome_user_message_manual', $manual_welcome_message );
    }

    // Get email intro
    function b3_get_email_intro( $welcome = false ) {
        $welcome = ( false === $welcome ) ? esc_html__( 'Welcome', 'b3-onboarding' ) : $welcome;

        if ( true == get_option( 'b3_register_email_only' ) || true == get_option( 'b3_use_magic_link' ) ) {
            $message = esc_html( $welcome ) . ',' . "\n";
        } else {
            $message = esc_html( $welcome ) . ' %user_login%' . ',' . "\n";
        }

        return apply_filters( 'b3_email_intro', $message );
    }

    // Get magic link email
    function b3_get_magic_link_subject() {
        $subject = get_option( 'b3_magic_link_subject' );

        if ( ! $subject ) {
            $subject = b3_default_magic_link_subject();
        }

        return apply_filters( 'b3_magic_link_subject', $subject );
    }

    // Get magic link email
    function b3_get_magic_link_message( $magic_link = false ) {
        $message = '';

        if ( $magic_link ) {
            // maybe add user input for this email
            $message = b3_default_magic_link_message( $magic_link );
        }

        return apply_filters( 'b3_magic_link_message', $message );
    }

    // Get otp password
    function b3_get_otp_password() {
        $pw_special_chars       = apply_filters( 'b3_password_special_chars', true );
        $pw_extra_special_chars = apply_filters( 'b3_password_extra_special_chars', false );
        $otp_password           = wp_generate_password( 8, $pw_special_chars, $pw_extra_special_chars );

        return $otp_password;
    }

    // Get hashed slug
    function b3_get_hashed_slug( $user_email = '', $otp_password = '' ) {
        if ( $user_email && $otp_password ) {
            $hashed_password = password_hash( $otp_password, PASSWORD_BCRYPT );

            if ( $hashed_password ) {
                $email_hash     = md5( strtolower( trim( $user_email ) ) );
                $transient_key  = sprintf( 'otp_%s', $email_hash );
                $amount_minutes = apply_filters( 'b3_magic_link_time_out', 5 );
                $slug           = sprintf( '%s:%s', $user_email, $hashed_password );
                $hashed_slug    = standard_to_base64url( $slug );

                if ( is_multisite() && ( doing_action( 'wpmu_activate_blog' ) || isset( $_GET[ 'activate' ] ) ) ) {
                    global $wpdb;
                    $meta_key    = '_site_transient_' . $transient_key;
                    $timeout_key = '_site_transient_timeout_' . $transient_key;
                    $expiration  = time() + ( $amount_minutes * MINUTE_IN_SECONDS );

                    $wpdb->query( $wpdb->prepare(
                        "INSERT INTO {$wpdb->sitemeta} (site_id, meta_key, meta_value)
                     VALUES (1, %s, %s)
                     ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)",
                        $meta_key,
                        $hashed_password
                    ) );

                    $wpdb->query( $wpdb->prepare(
                        "INSERT INTO {$wpdb->sitemeta} (site_id, meta_key, meta_value)
                     VALUES (1, %s, %s)
                     ON DUPLICATE KEY UPDATE meta_value = VALUES(meta_value)",
                        $timeout_key,
                        $expiration
                    ) );

                    return $hashed_slug;
                }

                $transient_set = set_site_transient( $transient_key, $hashed_password, $amount_minutes * MINUTE_IN_SECONDS );

                if ( $transient_set ) {
                    return $hashed_slug;
                }
            }
        }

        return false;
    }

    function b3_get_magic_link_url( $user_email ) {
        if ( $user_email ) {
            $otp_password = b3_get_otp_password();
            $hashed_slug  = b3_get_hashed_slug( $user_email, $otp_password );

            if ( $hashed_slug ) {
                $login_link = b3_get_login_url();
                $login_link = add_query_arg( 'login', 'enter_code', $login_link );
                $login_link = add_query_arg( 'otpcode', $hashed_slug, $login_link );

                return $login_link;
            }
        }

        return false;
    }
