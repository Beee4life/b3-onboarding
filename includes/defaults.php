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
            $default_css .= '#b3_email_main a:link,' . "\n";
            $default_css .= '#b3_email_main a:visited,' . "\n";
            $default_css .= '#b3_email_main a:active {' . "\n";
            $default_css .= '    color: #' . $link_color . ";\n";
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
        return __( 'This is an automated email from the website <a href="%home_url%">%blog_name%</a>.', 'b3-onboarding' );
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
        $admin_message .= sprintf( __( 'User name: %s', 'b3-onboarding' ), '%user_login%' ) . "\n";
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
        $message = sprintf( esc_html__( 'Hi %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'you have confirmed your email address and can now set your password through <a href="%s">this link</a>.', 'b3-onboarding' ), '%lostpass_url%' ) . "\n";
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
        return sprintf( __( 'Welcome to %s. Your account has been approved and you can now set your password <a href="%s">here</a>.', 'b3-onboarding' ), get_option( 'blogname' ), esc_url( b3_get_lostpassword_url() ) );
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
        $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'your registration to %s was successful.', 'b3-onboarding' ), '%blog_name%' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'You can set your password <a href="%s">here</a>.', 'b3-onboarding' ), b3_get_lostpassword_url() ) . "\n";
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
        $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
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


    /**
     * Return default lost password message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_get_default_message_above_lost_password() {
        return __( "Please enter your username or email address. You will receive an email message with instructions on how to reset your password.", 'b3-onboarding' );
        // return __( "Enter your email address and we'll send you a link to reset your password.", 'b3-onboarding' );
    }


    /**
     * Return default request access message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_get_default_message_above_request_access() {
        return __( "You have to request access for this website.", 'b3-onboarding' );
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
        $privacy_page = get_option( 'b3_privacy_page' );

        if ( false != $privacy_page ) {
            $privacy_page_object = get_post( $privacy_page );
            if ( is_object( $privacy_page_object ) ) {
                $link    = get_the_permalink( $privacy_page_object );
                $message = sprintf( __( 'Accept <a href="%s" target="_blank" rel="noopener">privacy settings</a>', 'b3-onboarding' ), esc_url( $link ) );
            }
        }

        return $message;
    }
