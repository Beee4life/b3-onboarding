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
        $custom_css = get_option( 'b3_email_styling' );

        if ( false != $custom_css ) {
            $email_style = $custom_css;
        } else {
            $email_style = b3_default_email_styling( $link_color );
        }

        return $email_style;
    }


    /**
     * Return link color for emails
     *
     * @since 2.0.0
     *
     * @return bool|mixed|string|void
     */
    function b3_get_link_color() {
        $color = get_option( 'b3_link_color' );

        if ( false != $color ) {
            $email_style = $color;
        } else {
            $email_style = b3_default_link_color();
        }

        return $email_style;
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
        $custom_template = get_option( 'b3_email_template' );

        if ( false != $custom_template ) {
            $email_template = $custom_template;
        } else {
            $email_template = b3_default_email_template( $hide_logo );
        }

        return $email_template;
    }


    /**
     * Return default email footer
     *
     * @since 2.0.0
     *
     * @TODO: add user input option
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_footer() {
        $email_footer = b3_default_email_footer();

        return $email_footer;
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
        if ( 'request_access' == $registration_type ) {
            if ( false != get_option( 'b3_request_access_notification_addresses' ) ) {
                $email_addresses = get_option( 'b3_request_access_notification_addresses' );
            }
        } elseif ( 'open' == $registration_type ) {
            if ( false != get_option( 'b3_new_user_notification_addresses' ) ) {
                $email_addresses = get_option( 'b3_new_user_notification_addresses' );
            }
        }

        return $email_addresses;
    }


    /**
     * Return email activation subject (user)
     *
     * @since 1.0.0
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_email_activation_subject_user() {
        $b3_email_activation_subject = get_option( 'b3_email_activation_subject' );
        if ( $b3_email_activation_subject ) {
            $subject = $b3_email_activation_subject;
        } else {
            $subject = b3_default_email_activation_subject();
        }

        return $subject;
    }


    /**
     * Return email activation message (user)
     *
     * @since 1.0.0
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_email_activation_message_user() {
        $b3_email_activation_message = get_option( 'b3_email_activation_message' );
        if ( $b3_email_activation_message ) {
            $message = $b3_email_activation_message;
        } else {
            $message = b3_default_email_activation_message();
        }

        return $message;
    }


    /**
     * Return welcome user subject (user)
     *
     * @since 1.0.0
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_subject() {
        $b3_welcome_user_subject = get_option( 'b3_welcome_user_subject' );
        if ( $b3_welcome_user_subject ) {
            $message = $b3_welcome_user_subject;
        } else {
            $message = b3_default_welcome_user_subject();
        }

        return $message;
    }


    /**
     * Return welcome user message (user)
     *
     * @since 1.0.0
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_message() {
        $b3_welcome_user_message = get_option( 'b3_welcome_user_message' );
        if ( $b3_welcome_user_message ) {
            $message = $b3_welcome_user_message;
        } else {
            $message = b3_default_welcome_user_message();
        }

        return $message;
    }


    /**
     * New site created message
     *
     * @return string
     */
    function b3_get_new_site_created_message() {
        // @TODO: add user input option
        $message = b3_default_message_new_site_created();

        return $message;
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

        return $subject;
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

        return $message;
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

        return $subject;
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

        return $message;
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

        return $subject;
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

        return $message;
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

        return $subject;
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

        return $message;
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

        return $subject;
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

        return $message;
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

        return $message;
    }


    /**
     * Return new user subject (admin)
     *
     * @since 1.0.0
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_new_user_subject() {
        $b3_new_user_subject = get_option( 'b3_new_user_subject' );
        if ( $b3_new_user_subject ) {
            $message = $b3_new_user_subject;
        } else {
            $message = b3_default_new_user_admin_subject() . "\n";
        }

        return $message;
    }


    /**
     * Return new user message (admin)
     *
     * @since 1.0.0
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_new_user_message() {
        $new_user_message = get_option( 'b3_new_user_message' );
        if ( false != $new_user_message ) {
            $message = $new_user_message;
        } else {
            $message = b3_default_new_user_admin_message();
        }

        return $message;
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

        return $subject;
    }


    /**
     * Get sender email
     *
     * @since 1.0.0
     *
     * @return bool|mixed|void
     */
    function b3_get_notification_sender_email() {
        $sender_email = get_option( 'b3_notification_sender_email' );
        if ( false == $sender_email ) {
            $admin_email = get_site_option( 'admin_email' );
            if ( false != $admin_email ) {
                $sender_email = $admin_email;
            }
        }

        return $sender_email;
    }

    /**
     * Get sender name
     *
     * @since 1.0.0
     *
     * @return bool|mixed|void
     */
    function b3_get_notification_sender_name() {
        $sender_name = get_option( 'b3_notification_sender_name' );
        if ( false == $sender_name ) {
            $blog_name = get_option( 'blogname' );
            if ( false != $blog_name ) {
                $sender_name = $blog_name;
            }
        }

        return $sender_name;
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
        if ( false != $manual_welcome_message ) {
            $message = $manual_welcome_message;
        } else {
            $message = b3_default_manual_welcome_user_message();
        }

        return $message;
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
        $welcome = ( false == $welcome ) ? esc_html__( 'Welcome', 'b3-onboarding' ) : $welcome;
        if ( true == get_option( 'b3_register_email_only' ) ) {
            $message = esc_html__( $welcome, 'b3-onboarding' ) . ',' . "\n";
        } else {
            $message = $welcome . ' %user_login%' . ',' . "\n";
        }

        return $message;
    }
