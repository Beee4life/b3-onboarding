<?php

    /**
     * Return default email styling
     *
     * @return false|string
     */
    function b3_default_email_styling() {
        $default_css = file_get_contents( dirname(__FILE__) . '/default-email-styling.css' );

        return $default_css;
    }

    /**
     * Return default email template
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
     * Default email content
     *
     * @return false|string
     */
    function b3_default_email_content( $hide_logo = false ) {
        $default_content = file_get_contents( dirname(__FILE__) . '/default-email-content.html' );

        if ( true == $hide_logo ) {
            $default_content = file_get_contents( dirname(__FILE__) . '/default-email-content-no-logo.html' );
        }

        return $default_content;
    }

    /**
     * Default email footer
     *
     * @return false|string
     */
    function b3_default_email_footer() {
        $default_content = __( 'This is an automated email from the website <a href="%home_url%">%blog_name%</a>.', 'b3-onboarding' );

        return $default_content;
    }

    /**
     * Default email logo
     *
     * @return false|string
     */
    function b3_default_email_logo() {
        return B3_PLUGIN_URL . '/assets/images/logo-b3onboarding.png';
    }

    /**
     * Default login logo
     *
     * @return false|string
     */
    function b3_default_login_logo() {
        return B3_PLUGIN_URL . '/assets/images/logo-b3onboarding.png';
    }

    /**
     * Default new user subject (admin)
     *
     * @return string
     */
    function b3_default_new_user_admin_subject() {
        return sprintf( esc_html__( 'New user at %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }

    /**
     * Default new user message (admin)
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
     * Default account rejected subject (user)
     *
     * @return string
     */
    function b3_default_account_activated_subject() {
        return esc_html__( 'Account activated', 'b3-onboarding' );
    }

    /**
     * Default account activated message (user)
     *
     * @return string
     */
    function b3_default_account_activated_message() {
        $message = sprintf( esc_html__( 'Hi %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'you have confirmed your email address and can now set your password through <a href="%s">this link</a>.', 'b3-onboarding' ), '%forgotpass_url%' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $message;
    }

    /**
     * Default account approved subject (admin)
     *
     * @return string
     */
    function b3_default_request_access_subject_admin() {
        return esc_html__( 'A new user requests access', 'b3-onboarding' );
    }

    /**
     * Default account approved message (admin)
     *
     * @return string
     */
    function b3_default_request_access_message_admin() {
        $approval_link                = b3_get_user_approval_id( true );
        $user_approval_page           = ( false != $approval_link ) ? $approval_link : esc_url( admin_url( 'admin.php?page=b3-user-approval' ) );
        $request_access_message_admin = sprintf( __( 'A new user has requested access. You can approve/deny him/her on the "<a href="%s">User approval</a>" page.', 'b3-onboarding' ), $user_approval_page );

        return $request_access_message_admin;
    }

    /**
     * Default account approved subject (user)
     *
     * @return string
     */
    function b3_default_request_access_subject_user() {
        return sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }

    /**
     * Default account approved message (user)
     *
     * @return string
     */
    function b3_default_request_access_message_user() {
        return sprintf( __( "You have successfully requested access for %s. We'll inform you about the outcome.", 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Default account approved subject (user)
     *
     * @return string
     */
    function b3_default_account_approved_subject() {
        return esc_html__( 'Account approved', 'b3-onboarding' );
    }

    /**
     * Default account approved message (user)
     *
     * @return string
     */
    function b3_default_account_approved_message() {
        return sprintf( __( 'Welcome to %s. Your account has been approved and you can now set your password <a href="%s">here</a>.', 'b3-onboarding' ), get_option( 'blogname' ), esc_url( b3_get_forgotpass_id( true ) ) );
    }


    /**
     * Default account rejected subject (user)
     *
     * @return string
     */
    function b3_default_account_rejected_subject() {
        return esc_html__( 'Account rejected', 'b3-onboarding' );
    }

    /**
     * Default account rejected message (user)
     *
     * @return string
     */
    function b3_default_account_rejected_message() {
        return esc_html__( 'Your account request has been rejected.', 'b3-onboarding' );
    }

    /**
     * Default forgot password subject (user)
     *
     * @return string
     */
    function b3_default_forgot_password_subject() {
        return sprintf( esc_html__( 'Password reset for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Default forgot password message (user)
     *
     * @return string
     */
    function b3_default_forgot_password_message() {
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
     * Default welcome user subject (user)
     *
     * @return string
     */
    function b3_default_welcome_user_subject() {
        return sprintf( esc_html__( 'Welcome to %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }

    /**
     * Default welcome user message (user)
     *
     * @return string
     */
    function b3_default_welcome_user_message() {
        $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'your registration to %s was successful.', 'b3-onboarding' ), '%blog_name%' ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( __( 'You can set your password <a href="%s">here</a>.', 'b3-onboarding' ), b3_get_forgotpass_id( true ) ) . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $message .= '<br /><br />' . "\n";
        $message .= sprintf( esc_html__( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return $message;

    }

    /**
     * Default welcome user subject (user)
     *
     * @return string
     */
    function b3_default_email_activation_subject() {
        return esc_html__( 'Confirm your email address', 'b3-onboarding' );
    }

    /**
     * Default welcome user message (user)
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
