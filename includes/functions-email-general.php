<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Return email styling and default styling if false
     *
     * @since 1.0.0
     *
     * @param bool $link_color
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_styling( $link_color = false ) {
        $email_styling = get_option( 'b3_email_styling' );

        if ( ! $email_styling ) {
            $email_styling = b3_default_email_styling( $link_color );
        }

        return apply_filters( 'b3_email_styling', $email_styling );
    }


    /**
     * Return link color for emails
     *
     * @since 2.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_link_color() {
        $link_color = get_option( 'b3_link_color' );

        if ( ! $link_color ) {
            $link_color = b3_default_link_color();
        }

        return apply_filters( 'b3_link_color', $link_color );
    }


    /**
     * Return user email template and default template if false
     *
     * @since 1.0.0
     *
     * @param bool $hide_logo
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_template( $hide_logo = false ) {
        $email_template = get_option( 'b3_email_template' );

        if ( ! $email_template ) {
            $email_template = b3_default_email_template( $hide_logo );
        }

        return apply_filters( 'b3_email_template', $email_template );
    }


    /**
     * Return default email footer
     *
     * @since 2.0.0
     *
     * @TODO: maybe add user input option
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_footer() {
        return apply_filters( 'b3_email_footer_text', b3_default_email_footer() );
    }


    /**
     * Get notification addresses
     *
     * @since 1.0.0
     *
     * @param $registration_type
     *
     * @return mixed
     */
    function b3_get_notification_addresses( $registration_type ) {
        $email_addresses = get_site_option( 'admin_email' );
        
        if ( 'request_access' === $registration_type ) {
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


    /**
     * Return email activation subject (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_email_activation_subject_user() {
        $subject = get_option( 'b3_email_activation_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_email_activation_subject();
        }

        return apply_filters( 'b3_email_activation_subject_user', $subject );
    }


    /**
     * Return email activation message (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_email_activation_message_user() {
        $message = get_option( 'b3_email_activation_message' );
        
        if ( ! $message ) {
            $message = b3_default_email_activation_message();
        }

        return apply_filters( 'b3_email_activation_message_user', $message );
    }


    /**
     * Return welcome user subject (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_subject() {
        $subject = get_option( 'b3_welcome_user_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_welcome_user_subject();
        }

        return apply_filters( 'b3_welcome_user_subject', $subject );
    }


    /**
     * Return welcome user message (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_message() {
        $message = get_option( 'b3_welcome_user_message' );
        
        if ( ! $message ) {
            $message = b3_default_welcome_user_message();
        }

        return apply_filters( 'b3_welcome_user_message', $message );
    }


    /**
     * New site created message
     *
     * @return string
     */
    function b3_get_new_site_created_message() {
        // @TODO: maybe add user input option
        $message = b3_default_message_new_site_created();

        return apply_filters( 'b3_new_site_created_message', $message );
    }


    /**
     * Get email subject for request access (admin)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_request_access_subject_admin() {
        $subject = get_option( 'b3_request_access_subject_admin' );
        
        if ( ! $subject ) {
            $subject = b3_default_request_access_subject_admin();
        }

        return apply_filters( 'b3_request_access_subject_admin', $subject );
    }


    /**
     * Get email message for request access (admin)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_request_access_message_admin() {
        $message = get_option( 'b3_request_access_message_admin' );
        
        if ( ! $message ) {
            $message = b3_default_request_access_message_admin();
        }

        return apply_filters( 'b3_request_access_message_admin', $message );
    }


    /**
     * Get email subject for request access (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_request_access_subject_user() {
        $subject = get_option( 'b3_request_access_subject_user' );
        
        if ( ! $subject ) {
            $subject = b3_default_request_access_subject_user();
        }

        return apply_filters( 'b3_request_access_subject_user', $subject );
    }


    /**
     * Get email message for request access (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_request_access_message_user() {
        $message = get_option( 'b3_request_access_message_user' );
        
        if ( ! $message ) {
            $message = b3_default_request_access_message_user();
        }

        return apply_filters( 'b3_request_access_message_user', $message );
    }


    /**
     * Get email subject for account approved
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_account_approved_subject() {
        $subject = get_option( 'b3_account_approved_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_account_approved_subject();
        }

        return apply_filters( 'b3_account_approved_subject', $subject );
    }


    /**
     * Get email message for account approved
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_account_approved_message() {
        $message = get_option( 'b3_account_approved_message' );
        
        if ( ! $message ) {
            $message = b3_default_account_approved_message();
        }

        return apply_filters( 'b3_account_approved_message', $message );
    }


    /**
     * Get email subject for account activated (user)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_account_activated_subject_user() {
        $subject = get_option( 'b3_account_activated_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_account_activated_subject();
        }

        return apply_filters( 'b3_account_activated_subject_user', $subject );
    }


    /**
     * Get email message for account activated (user)
     *
     * @since 1.0.0
     *
     * @TODO: maybe merge with welcome
     *
     * @return mixed|string
     */
    function b3_get_account_activated_message_user() {
        $message = get_option( 'b3_account_activated_message' );
        
        if ( ! $message ) {
            $message = b3_default_account_activated_message();
        }

        return apply_filters( 'b3_account_activated_message_user', $message );
    }


    /**
     * Get account rejected subject (user)
     *
     * @since 1.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_account_rejected_subject() {
        $subject = get_option( 'b3_account_rejected_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_account_rejected_subject() . "\n";
        }

        return apply_filters( 'b3_account_rejected_subject', $subject );
    }


    /**
     * Get account rejected message (user)
     *
     * @since 1.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_account_rejected_message() {
        $message = get_option( 'b3_account_rejected_message' );
        
        if ( ! $message ) {
            $message = b3_default_account_rejected_message() . "\n";
        }

        return apply_filters( 'b3_account_rejected_message', $message );
    }


    /**
     * Get lost password message (user)
     *
     * @since 1.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_lost_password_message() {
        $message = get_option( 'b3_lost_password_message' );
        
        if ( ! $message ) {
            $message = b3_default_lost_password_message() . "\n";
        }

        return apply_filters( 'b3_lost_password_message', $message );
    }


    /**
     * Return new user subject (admin)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_new_user_subject() {
        $subject = get_option( 'b3_new_user_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_new_user_admin_subject() . "\n";
        }

        return apply_filters( 'b3_new_user_subject', $subject );
    }


    /**
     * Return new user message (admin)
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    function b3_get_new_user_message() {
        $message = get_option( 'b3_new_user_message' );
        
        if ( ! $message ) {
            $message = b3_default_new_user_admin_message();
        }

        return apply_filters( 'b3_new_user_message', $message );
    }


    /**
     * Get password subject (user)
     *
     * @since 2.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_lost_password_subject() {
        $subject = get_option( 'b3_lost_password_subject' );
        
        if ( ! $subject ) {
            $subject = b3_default_lost_password_subject();
        }

        return apply_filters( 'b3_lost_password_subject', $subject );
    }


    /**
     * Get sender email
     *
     * @since 1.0.0
     *
     * @return bool|mixed|void
     */
    function b3_get_notification_sender_email() {
        $notification_sender_email = get_option( 'b3_notification_sender_email' );
        
        if ( ! $notification_sender_email ) {
            $notification_sender_email = get_site_option( 'admin_email' );
        }

        return apply_filters( 'b3_notification_sender_email', $notification_sender_email );
    }

    /**
     * Get sender name
     *
     * @since 1.0.0
     *
     * @return bool|mixed|void
     */
    function b3_get_notification_sender_name() {
        $notification_sender_name = get_option( 'b3_notification_sender_name' );
        
        if ( ! $notification_sender_name ) {
            $notification_sender_name = get_option( 'blogname' );
        }

        return apply_filters( 'b3_notification_sender_name', $notification_sender_name );
    }


    /**
     * Get manually added welcome message user
     *
     * @since 2.3.0
     *
     * @return string
     */
    function b3_get_manual_welcome_user_message() {
        $manual_welcome_message = get_option( 'b3_welcome_user_message_manual' );
        
        if ( ! $manual_welcome_message ) {
            $manual_welcome_message = b3_default_manual_welcome_user_message();
        }

        return apply_filters( 'b3_welcome_user_message_manual', $manual_welcome_message );
    }

    /**
     * Get email intro
     *
     * @since 3.1.0
     *
     * @param false $welcome
     *
     * @return string
     */
    function b3_get_email_intro( $welcome = false ) {
        $welcome = ( false === $welcome ) ? esc_html__( 'Welcome', 'b3-onboarding' ) : $welcome;
        
        if ( true == get_option( 'b3_register_email_only' ) || true == get_option( 'b3_use_magic_link' ) ) {
            $message = esc_html__( $welcome, 'b3-onboarding' ) . ',' . "\n";
        } else {
            $message = $welcome . ' %user_login%' . ',' . "\n";
        }
        
        return apply_filters( 'b3_email_intro', $message );
    }

    /**
     * Get magic link email
     *
     * @since 3.11.0
     *
     * @param false $password
     * @param false $slug
     *
     * @return string
     */
    function b3_get_magic_link_email( $password = false, $slug = false ) {
        $message = '';
        
        if ( $password && $slug ) {
            // maybe add user input for this email
            $message = b3_get_default_magiclink_email( $password, $slug );
            
        }
        
        return apply_filters( 'b3_magic_link_email', $message );
    }
