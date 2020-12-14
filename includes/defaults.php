<?php
    /**
     * Return default email styling
     *
     * @since 1.0.6
     *
     * @return false|string
     */
    function b3_default_email_styling( $link_color = false ) {
        $default_css = file_get_contents( dirname(__FILE__) . '/default-email-styling.css' );

        if ( false != $link_color ) {
            $default_css .= "\n";
            $default_css .= '/*';
            $default_css .= "\n";
            $default_css .= 'This is the color definition which can be filtered.';
            $default_css .= "\n";
            $default_css .= '#b3_email_main is added so it overrides the previous definition.';
            $default_css .= "\n";
            $default_css .= '*/';
            $default_css .= "\n";
            $default_css .= '#b3_email_main a:link,' . "\n";
            $default_css .= '#b3_email_main a:visited,' . "\n";
            $default_css .= '#b3_email_main a:active {' . "\n";
            $default_css .= '    color: ' . $link_color . ";\n";
            $default_css .= '}' . "\n";
        }

        return $default_css;
    }


    /**
     * Return default email template
     *
     * @since 1.0.6
     *
     * @return false|string
     */
    function b3_default_email_template( $hide_logo = false ) {
        $default_template = file_get_contents( dirname(__FILE__) . '/default-email-template.html' );

        if ( true == $hide_logo ) {
            $default_template = file_get_contents( dirname(__FILE__) . '/default-email-template-no-logo.html' );
        }

        return $default_template;
    }


    /**
     * Return default email footer
     *
     * @since 2.0.0
     *
     * @return false|string
     */
    function b3_default_email_footer() {
        $anchor = ( is_multisite() ) ? '%network_name%' : '%blog_name%';

        return __( 'This is an automated email from the website <a href="%home_url%">' . $anchor . '</a>.', 'b3-onboarding' );
    }


    /**
     * Return default link color
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_link_color() {
        return '#e0144b';
    }


    /**
     * Return default main logo
     *
     * @since 2.0.0
     *
     * @return false|string
     */
    function b3_default_main_logo() {
        return B3_PLUGIN_URL . 'assets/images/logo-b3onboarding.png';
    }


    /**
     * Return default new user subject (admin)
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_default_new_user_admin_subject() {
        return sprintf( esc_html__( 'New user at %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Return default new user message (admin)
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_default_new_user_admin_message() {
        $admin_message = sprintf( __( 'A new user registered at %s on %s', 'b3-onboarding' ), get_option( 'blogname' ), '%registration_date%' ) . ".\n";
        $admin_message .= '<br /><br />' . "\n";
        if ( true == get_site_option( 'b3_activate_custom_passwords' ) ) {
            $admin_message .= sprintf( __( 'User ID: %s', 'b3-onboarding' ), '%user_login%' ) . "\n";
        } else {
            $admin_message .= sprintf( __( 'User name: %s', 'b3-onboarding' ), '%user_login%' ) . "\n";
        }
        $admin_message .= '<br /><br />' . "\n";
        $admin_message .= sprintf( __( 'IP: %s', 'b3-onboarding' ), '%user_ip%' ) . "\n";

        return $admin_message;
    }


    /**
     * Return default account rejected subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_account_activated_subject() {
        return esc_html__( 'Account activated', 'b3-onboarding' );
    }


    /**
     * Return default account activated message (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_account_activated_message() {

        if ( true == get_site_option( 'b3_register_email_only' ) ) {
            $message = esc_html__( 'Hi', 'b3-onboarding' ) . ',' . "\n";
        } else {
            $message = sprintf( esc_html__( 'Hi %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        }
        $message .= '<br /><br />' . "\n";
        if ( true != get_site_option( 'b3_activate_custom_passwords' ) ) {
            $message .= sprintf( __( 'you have confirmed your email address and can now set your password through <a href="%s">this link</a>.', 'b3-onboarding' ), '%lostpass_url%' ) . "\n";
        } else {
            $message .= sprintf( __( 'you have confirmed your email address and can now login <a href="%s">here</a>.', 'b3-onboarding' ), b3_get_login_url() ) . "\n";
        }
        $message .= '<br /><br />' . "\n";
        $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $message;
    }


    /**
     * Return default account approved subject (admin)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_subject_admin() {
        return esc_html__( 'A new user requests access', 'b3-onboarding' );
    }


    /**
     * Return default account approved message (admin)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_message_admin() {
        $approval_link                = b3_get_user_approval_link();
        $user_approval_page           = ( false != $approval_link ) ? $approval_link : esc_url( admin_url( 'admin.php?page=b3-user-approval' ) );
        $request_access_message_admin = sprintf( __( 'A new user has requested access. You can approve/deny him/her on the "<a href="%s">User approval</a>" page.', 'b3-onboarding' ), $user_approval_page );

        return $request_access_message_admin;
    }


    /**
     * Return default account approved subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_subject_user() {
        return sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Return default account approved message (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_message_user() {
        return sprintf( __( "You have successfully requested access for %s. We'll inform you about the outcome.", 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Return default account approved subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_account_approved_subject() {
        return esc_html__( 'Account approved', 'b3-onboarding' );
    }


    /**
     * Return default account approved message (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_account_approved_message() {
        if ( true == get_site_option( 'b3_activate_custom_passwords', false ) ) {
            return sprintf( __( 'Welcome to %s. Your account has been approved and you can now login <a href="%s">here</a>.', 'b3-onboarding' ), get_option( 'blogname' ), esc_url( b3_get_login_url() ) );
        } else {
            return sprintf( __( 'Welcome to %s. Your account has been approved and you can now set your password <a href="%s">here</a>.', 'b3-onboarding' ), get_option( 'blogname' ), esc_url( b3_get_lostpassword_url() ) );
        }
    }


    /**
     * Return default account rejected subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_account_rejected_subject() {
        return esc_html__( 'Account rejected', 'b3-onboarding' );
    }


    /**
     * Return default account rejected message (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_account_rejected_message() {
        return esc_html__( 'Your account request has been rejected.', 'b3-onboarding' );
    }


    /**
     * Return default lost password subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_lost_password_subject() {
        return sprintf( esc_html__( 'Password reset for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Return default lost password message (user)
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_default_lost_password_message() {
        $default_message = __( 'Hi', 'b3-onboarding' ) . ",\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( 'Someone requested a password reset for the account using this email address.', 'b3-onboarding' ) . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( 'To (re)set your password, go to <a href="%reset_url%">this page</a>.', 'b3-onboarding' ) . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $default_message;

    }


    /**
     * Return default welcome user subject (user)
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_default_welcome_user_subject() {
        return sprintf( esc_html__( 'Welcome to %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Return default welcome user message (user)
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_default_welcome_user_message() {
        if ( true == get_site_option( 'b3_register_email_only' ) ) {
            $message = esc_html__( 'Welcome', 'b3-onboarding' ) . ',' . "\n";
        } else {
            $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        }
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'your registration to %s was successful.', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        $message .= '<br /><br />' . "\n";
        if ( true != get_site_option( 'b3_activate_custom_passwords' ) ) {
            $message .= sprintf( __( 'You can set your password <a href="%s">here</a>.', 'b3-onboarding' ), b3_get_lostpassword_url() ) . "\n";
            $message .= '<br /><br />' . "\n";
        }
        $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $message;
    }


    function b3_default_manual_welcome_user_message() {
        if ( true == get_site_option( 'b3_register_email_only' ) ) {
            $message = esc_html__( 'Welcome', 'b3-onboarding' ) . ',' . "\n";
        } else {
            $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        }
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'your account on %s has been created.', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'You can now set your password <a href="%s">here</a>.', 'b3-onboarding' ), b3_get_lostpassword_url() ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $message;
    }


    /**
     * Return default welcome user subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_email_activation_subject() {
        return esc_html__( 'Confirm your email address', 'b3-onboarding' );
    }


    /**
     * Return default welcome user message (user)
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_default_email_activation_message() {

        if ( 1 == get_site_option( 'b3_register_email_only', false ) ) {
            $message = esc_html__( 'Welcome', 'b3-onboarding' ) . ',' . "\n";
        } else {
            $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        }
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'your registration to %s was successful.', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'You only need to confirm your email address through <a href="%s">this link</a>.', 'b3-onboarding' ), '%activation_url%' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $message;
    }


    /**
     * Default activate user email subject (WPMU)
     *
     * %s = Site name, translated/replaced by Wordpress
     *
     * @return string|void
     */
    function b3_default_wpmu_activate_user_subject() {
        return __( '%s: Confirm your registration', 'b3-onboarding' );
    }


    /**
     * Default activate user email message (WPMU)
     *
     * @return string|void
     */
    function b3_default_wpmu_activate_user_message() {
        $message = __( 'Dear %1$s,', 'b3-onboarding' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'To activate your account, please click <a href="%2$s">this link</a>.', 'b3-onboarding' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'After you activate, you will receive *another email* with your password.', 'b3-onboarding' );

        return $message;
    }


    /**
     * Default user activated email message (WPMU)
     *
     * @return string|void
     */
    function b3_default_wpmu_user_activated_subject() {
        return __( 'Welcome to %1$s', 'b3-onboarding' );
    }


    /**
     * Default user activated email message (WPMU)
     *
     * @return string|void
     */
    function b3_default_wpmu_user_activated_message() {
        $message = __( 'Howdy %1$s,', 'b3-onboarding' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'Your new account is set up.', 'b3-onboarding' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'You can log in with the following information:', 'b3-onboarding' ) . "\n";
        $message .= '<br />' . "\n";
        $message .= __( 'Username: %2$s', 'b3-onboarding' ) . "\n";
        $message .= '<br />' . "\n";
        $message .= __( 'Password: %3$s', 'b3-onboarding' ) . "\n";
        $message .= '<br />' . "\n";
        $message .= __( 'You can login <a href="%4$s">here</a>.', 'b3-onboarding' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'The Team @ %5$s', 'b3-onboarding' );

        return $message;
    }


    /**
     * Default admin subject for new wpmu user (no site)
     *
     * @param false $user
     *
     * @return string
     */
    function b3_default_subject_new_wpmu_user_admin() {
        return __( 'New User Registration: %s', 'b3-onboarding' );
    }


    /**
     * Default admin message for new wpmu user (no site)
     *
     * @param false $user
     *
     * @return string
     */
    function b3_default_message_new_wpmu_user_admin( $user = false ) {

        $options_site_url = esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) );

        if ( false != $user ) {
            /* translators: New user notification email. 1: User login, 2: User IP address, 3: URL to Network Settings screen. */
            $split_message = __( 'New user: %1$s', 'b3-onboarding' ) . "\n";
            $split_message .= '<br /><br />' . "\n";
            $split_message .= __( 'Remote IP address: %2$s.', 'b3-onboarding' ) . "\n";
            $split_message .= '<br /><br />' . "\n";
            $split_message .= __( 'Disable these notifications <a href="%3$s">here</a>.', 'b3-onboarding' );

            $message = sprintf(
                $split_message,
                $user->user_login,
                wp_unslash( $_SERVER[ 'REMOTE_ADDR' ] ),
                $options_site_url
            );
        } else {
            // for placeholder only
            /* translators: New user notification email. 1: User IP address, 2: URL to Network Settings screen. */
            $split_message = __( 'New user: dummy', 'b3-onboarding' ) . "\n";
            $split_message .= '<br /><br />' . "\n";
            $split_message .= __( 'Remote IP address: %1$s.', 'b3-onboarding' ) . "\n";
            $split_message .= '<br /><br />' . "\n";
            $split_message .= __( 'Disable these notifications <a href="%2$s">here</a>.', 'b3-onboarding' );

            $message = sprintf(
                $split_message,
                wp_unslash( $_SERVER[ 'REMOTE_ADDR' ] ),
                $options_site_url
            );
        }

        return $message;
    }


    function b3_default_subject_new_wpmu_user_blog( $user = false ) {

        /* translators: New site notification email subject. 1: Network title, 2: New site URL. */
        $subject = _x( '[%network_name%] Activate your account', 'New site notification email subject' );

        return $subject;

    }


    function b3_default_message_new_wpmu_user_blog( $user = false ) {

        $split_message = '';
        if ( false != $user ) {
            $split_message .= 'Hi %user_login%' . ",\n";
            $split_message .= '<br /><br />' . "\n";
        }
        $split_message .= __( 'To activate your registration, please click <a href="%activation_url%">here</a>.', 'b3-onboarding') . "\n";
        $split_message .= '<br /><br />' . "\n";
        $split_message .= __( 'After you activate, you will receive *another email* with your login.', 'b3-onboarding' ) . "\n";
        $split_message .= '<br /><br />' . "\n";
        $split_message .= __( 'After you activate, you can visit your site here:', 'b3-onboarding' ) . "\n";
        $split_message .= '<br />' . "\n";
        $split_message .= '<a href="%home_url%">%home_url%</a>' . "\n";

        return $split_message;
    }


    function b3_default_subject_welcome_wpmu_user_blog() {
        return 'New %network_name% Site: %site_name%';
    }


    function b3_default_message_welcome_wpmu_user_blog( $user_login = false ) {

        $split_message = '';
        if ( false != $user_login ) {
            $split_message .= 'Hi %user_login%' . ",\n";
            $split_message .= '<br /><br />' . "\n";
        }
        $split_message .= __( 'Your new site has been successfully set up at <a href="%home_url%">%home_url%</a>.', 'b3-onboarding') . "\n";
        $split_message .= '<br /><br />' . "\n";
        $split_message .= __( 'You can log in to the administrator account with the following information', 'b3-onboarding' ) . ":\n";
        $split_message .= '<br />' . "\n";
        $split_message .= __( 'Username', 'b3-onboarding' ) . ': ' . '%user_login%' . "\n";
        $split_message .= '<br />' . "\n";
        $split_message .= __( 'Password', 'b3-onboarding' ) . ': ' . '%user_password%' . "\n";
        $split_message .= '<br /><br />' . "\n";
        $split_message .= __( 'Login here: <a href="%login_url%">%login_url%</a>', 'b3-onboarding' ) . "\n";
        $split_message .= '<br /><br />' . "\n";
        $split_message .= __( 'Enjoy your new site.', 'b3-onboarding' ) . "\n";
        $split_message .= '<br /><br />' . "\n";
        $split_message .= __( 'Afzender', 'b3-onboarding' ) . "\n";

        return $split_message;
    }


    /**
     * Return default registration message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_get_default_message_above_registration() {
        return __( 'Register For This Site' );
    }


    /**
     * Return default registration closed message text
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_registration_closed_message() {
        return esc_html__( 'Registering new users is currently not allowed.', 'b3-onboarding' );
    }


    function b3_default_logged_in_registration_only_message() {
        return sprintf( __( 'You must first <a href="%s">log in</a>, and then you can create a new site.' ), wp_login_url() );
    }


    /**
     * Return default lost password message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_get_default_message_above_lost_password() {
        if ( 1 == get_site_option( 'b3_register_email_only' ) ) {
            return __( 'Please enter your email address. You will receive an email with a link to (re)set your password.', 'b3-onboarding' );
        } else {
            return __( 'Please enter your username or email address. You will receive an email with a link to (re)set your password.', 'b3-onboarding' );
        }
    }


    /**
     * Return default request access message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_get_default_message_above_request_access() {
        return __( 'You have to request access for this website.', 'b3-onboarding' );
    }


    /**
     * Return default privacy text
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_privacy_text() {
        $message      = __( 'Accept privacy settings', 'b3-onboarding' );
        $privacy_page = get_site_option( 'b3_privacy_page' );

        if ( false != $privacy_page ) {
            $privacy_page_object = get_post( $privacy_page );
            if ( is_object( $privacy_page_object ) ) {
                $link    = get_the_permalink( $privacy_page_object );
                $message = sprintf( __( 'Accept <a href="%s" target="_blank" rel="noopener">privacy settings</a>', 'b3-onboarding' ), esc_url( $link ) );
            }
        }

        return $message;
    }
