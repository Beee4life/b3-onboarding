<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

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
            if ( current_user_can( 'manage_options' ) ) {
                $default_css .= "\n";
                $default_css .= '/*';
                $default_css .= "\n";
                $default_css .= 'This is the color definition which can be filtered.';
                $default_css .= "\n";
                $default_css .= '#b3_email_main is added so it overrides the previous definition.';
                $default_css .= "\n";
                $default_css .= '*/';
            }
            $default_css .= "\n";
            $default_css .= '.big-link-container {' . "\n";
            $default_css .= '    background-color: ' . $link_color . ";\n";
            $default_css .= '}' . "\n";
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

        if ( true === $hide_logo ) {
            $default_template = file_get_contents( dirname(__FILE__) . '/default-email-template-no-logo.html' );
        }

        return $default_template;
    }


    /**
     * Return default email footer text
     *
     * @since 2.0.0
     *
     * @return false|string
     */
    function b3_default_email_footer() {
        $anchor = ( is_multisite() ) ? '%network_name%' : '%blog_name%';

        return sprintf( esc_html__( 'This is an automated email from the website %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '%home_url%', $anchor ) );
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
        return B3OB_PLUGIN_URL . 'assets/images/logo-b3onboarding.png';
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
        $admin_message = sprintf( esc_html__( 'A new user registered at %s on %s.', 'b3-onboarding' ), get_option( 'blogname' ), '%registration_date%' ) . "\n";
        $admin_message .= '<br><br>' . "\n";
        if ( true == get_option( 'b3_activate_custom_passwords' ) ) {
            $admin_message .= sprintf( esc_html__( 'User ID: %s', 'b3-onboarding' ), '%user_login%' ) . "\n";
        } else {
            $admin_message .= sprintf( esc_html__( 'User name: %s', 'b3-onboarding' ), '%user_login%' ) . "\n";
        }
        $admin_message .= '<br><br>' . "\n";
        $admin_message .= sprintf( esc_html__( 'IP: %s', 'b3-onboarding' ), '%user_ip%' ) . "\n";

        return $admin_message;
    }


    /**
     * Return default account activated subject (user)
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
        $message = b3_get_email_intro( esc_html__( 'Hi', 'b3-onboarding' ) );
        $message .= '<br><br>' . "\n";
        if ( ! get_option( 'b3_activate_custom_passwords' ) && ! get_option( 'b3_use_magic_link' ) ) {
            $lost_pass_link = '%lostpass_url%';
            $lost_pass_link = sprintf( '<a href="%s">%s</a>', esc_url( $lost_pass_link ), strtoupper( __( 'Set password', 'b3-onboarding' ) ) );
            $button         = sprintf( '<div class="big-link">%s</div>', $lost_pass_link ) . "\n";
            $message        .= esc_html__( 'you have confirmed your email address and can now set a password through the link below.', 'b3-onboarding' );

        } else {
            $login_link = b3_get_login_url();
            $login_link = sprintf( '<a href="%s">%s</a>', esc_url( $login_link ), strtoupper( __( 'Login', 'b3-onboarding' ) ) );
            $button     = sprintf( '<div class="big-link">%s</div>', $login_link ) . "\n";
            $message    .= esc_html__( 'you have confirmed your email address and can now login through the link below.', 'b3-onboarding' );
        }
        $message .= '<br><br>' . "\n";
        $message .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

        return $message;
    }


    /**
     * Return default request access subject (admin)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_subject_admin() {
        return esc_html__( 'A new user requests access', 'b3-onboarding' );
    }


    /**
     * Return default request access message (admin)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_message_admin() {
        $approval_link                = b3_get_user_approval_link();
        $user_approval_page           = ( false != $approval_link ) ? $approval_link : esc_url( admin_url( 'admin.php?page=b3-user-approval' ) );
        $request_access_message_admin = sprintf( esc_html__( 'A new user has requested access. You can approve/deny him/her on the "%s" page.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', $user_approval_page, esc_html__( 'User approval', 'b3-onboarding' ) ) );

        return $request_access_message_admin;
    }


    /**
     * Return default request access subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_subject_user() {
        return sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
    }


    /**
     * Return default request access message (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_request_access_message_user() {
        ob_start();
        echo sprintf( esc_html__( "You have successfully requested access for %s. We'll inform you about the outcome.", 'b3-onboarding' ), get_option( 'blogname' ) );
        echo '<br>';
        echo b3_default_greetings();

        return ob_get_clean();
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
        if ( get_option( 'b3_activate_custom_passwords' ) || get_option( 'b3_use_magic_link' ) ) {
            $link = b3_get_login_url();
            $label = esc_html__( 'Login', 'b3-onboarding' );
        } else {
            $link = '%reset_url%';
            $label = esc_html__( 'Set password', 'b3-onboarding' );
        }
        
        $link_element = sprintf( '<a href="%s">%s</a>', $link, strtoupper( $label ) );
        $button       = sprintf( '<div class="big-link">%s</div>', $link_element ) . "\n";

        if ( true == get_option( 'b3_activate_custom_passwords' ) ) {
            $message = sprintf( esc_html__( 'Welcome to %s. Your account has been approved and you can now login by clicking the button below.', 'b3-onboarding' ), get_option( 'blogname' ) );
        } else {
            $message = sprintf( esc_html__( 'Welcome to %s. Your account has been approved and you can now set your password by clicking the button below.', 'b3-onboarding' ), get_option( 'blogname' ) );
        }
        $message .= '<br><br>' . "\n";
        $message .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        $message .= b3_default_greetings();

        return $message;
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
        $message = sprintf( esc_html__( "We're sorry to tell you, your request for access to %s has been rejected.", 'b3-onboarding' ), get_option( 'blogname' ) );
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

        return $message;
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
        $lost_pass_url = sprintf( '<a href="%s">%s</a>',  '%reset_url%', strtoupper( __( 'Reset password', 'b3-onboarding' ) ) );
        $button        = sprintf( '<div class="big-link">%s</div>', $lost_pass_url ) . "\n";
        $message       = b3_get_email_intro( esc_html__( 'Hi', 'b3-onboarding' ) );
        $message       .= '<br><br>' . "\n";
        $message       .= esc_html__( 'Someone requested a password reset for the account using this email address.', 'b3-onboarding' ) . "\n";
        $message       .= '<br><br>' . "\n";
        $message       .= esc_html__( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\n";
        $message       .= '<br><br>' . "\n";
        $message       .= esc_html__( 'To (re)set your password, click the button below.', 'b3-onboarding' );
        $message       .= '<br><br>' . "\n";
        $message       .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        $message       .= b3_default_greetings();

        return $message;

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
        $activation_link = sprintf( '<a href="%s">%s</a>', b3_get_lostpassword_url(), strtoupper( esc_html__( 'Set password', 'b3-onboarding' ) ) );
        $button          = sprintf( '<div class="big-link">%s</div>', $activation_link ) . "\n";
        $message = b3_get_email_intro();
        $message .= '<br><br>' . "\n";
        $message .= sprintf( esc_html__( 'your registration to %s was successful.', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        if ( true != get_option( 'b3_activate_custom_passwords' ) ) {
            $message .= '<br><br>' . "\n";
            $message .= __( 'You can set your password by clicking the button below.', 'b3-onboarding' ) . "\n";
            $message .= '<br><br>' . "\n";
            $message .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        }
        $message .= b3_default_greetings();

        return $message;
    }


    /**
     * Override MANUAL welcome user email
     *
     * @return string
     */
    function b3_default_manual_welcome_user_message() {
        $message = b3_get_email_intro();
        $message .= '<br><br>' . "\n";
        $message .= sprintf( esc_html__( 'your account on %s has been created.', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= sprintf( esc_html__( 'You can (re)set your password %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', b3_get_lostpassword_url(), esc_html__( 'here', 'b3-onboarding' ) ) );
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

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
        $activation_link = sprintf( '<a href="%s">%s</a>', '%activation_url%', strtoupper( __( 'Confirm email', 'b3-onboarding' ) ) );
        $button          = sprintf( '<div class="big-link">%s</div>', $activation_link ) . "\n";
        $message         = b3_get_email_intro();
        $message         .= '<br><br>' . "\n";
        $message         .= sprintf( esc_html__( 'your registration to %s was successful.', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        $message         .= '<br><br>' . "\n";
        $message         .= esc_html__( 'You only need to confirm your email address through the link below.', 'b3-onboarding' );
        $message         .= '<br><br>' . "\n";
        $message         .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        $message         .= b3_default_greetings();

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
        return esc_html__( '%s: Confirm your registration', 'b3-onboarding' );
    }


    /**
     * Default activate user email message (WPMU)
     *
     * @return string|void
     */
    function b3_default_wpmu_activate_user_message() {
        $link_element = sprintf( '<a href="%s">%s</a>', '%2$s', strtoupper( __( 'Activate account', 'b3-onboarding' ) ) );
        $button       = sprintf( '<div class="big-link">%s</div>', $link_element ) . "\n";
        $message      = esc_html__( 'Dear %1$s,', 'b3-onboarding' ) . "\n";
        $message      .= '<br><br>' . "\n";
        $message      .= esc_html__( 'To activate your account, please click the link below.', 'b3-onboarding' ) . "\n";
        $message      .= '<br><br>' . "\n";
        $message      .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        $message      .= '<br>' . "\n";
        $message      .= esc_html__( 'After you activate, you will receive *another email* with your password.', 'b3-onboarding' );
        $message      .= '<br>' . "\n";
        $message      .= b3_default_greetings();

        return $message;
    }


    /**
     * Default user activated email message (WPMU)
     *
     * @return string|void
     */
    function b3_default_wpmu_user_activated_subject() {
        return esc_html__( 'Welcome to %1$s', 'b3-onboarding' );
    }


    /**
     * Default user activated email message (WPMU)
     *
     * @return string|void
     */
    function b3_default_wpmu_user_activated_message() {
        $link_element = sprintf( '<a href="%s">%s</a>', '%4$s', strtoupper( __( 'Login', 'b3-onboarding' ) ) );
        $button       = sprintf( '<div class="big-link">%s</div>', $link_element ) . "\n";
        $message      = esc_html__( 'Howdy %1$s,', 'b3-onboarding' ) . "\n";
        $message      .= '<br><br>' . "\n";
        $message      .= esc_html__( 'Your new account is set up.', 'b3-onboarding' ) . "\n";
        $message      .= '<br><br>' . "\n";
        $message      .= esc_html__( 'You can log in with the following information:', 'b3-onboarding' ) . "\n";
        $message      .= '<br>' . "\n";
        $message      .= esc_html__( 'Username: %2$s', 'b3-onboarding' ) . "\n";
        $message      .= '<br>' . "\n";
        $message      .= esc_html__( 'Password: %3$s', 'b3-onboarding' ) . "\n";
        $message      .= '<br>' . "\n";
        $message      .= esc_html__( 'You can login through the link below.', 'b3-onboarding' );
        $message      .= '<br><br>' . "\n";
        $message      .= sprintf( '<div class="big-link-container">%s</div>', $button ) . "\n";
        $message      .= b3_default_greetings();

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
        return esc_html__( 'New User Registration: %s', 'b3-onboarding' );
    }


    /**
     * Default admin message for new wpmu user (no site)
     *
     * @param false $user
     *
     * @return string
     */
    function b3_default_message_new_wpmu_user_admin() {
        $message = esc_html__( 'New user: %user_login%', 'b3-onboarding' ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'Remote IP address: %user_ip%.', 'b3-onboarding' ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= sprintf( esc_html__( 'Disable these notifications %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '%settings_url%', esc_html__( 'here', 'b3-onboarding' ) ) );
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

        return $message;
    }


    /**
     * Default "New Site Created" email message
     *
     * @return string
     */
    function b3_default_message_new_site_created() {
        // @TODO: add if for when added by admin
        $message = esc_html__( 'New site created by: %user_login%', 'b3-onboarding' ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'Site address: %home_url%', 'b3-onboarding' ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'Site name: %blog_name%', 'b3-onboarding' );
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

        return $message;
    }


    /**
     * Default subject new wpmu user (blog)
     *
     * @param false $user
     *
     * @return string|void
     */
    function b3_default_subject_new_wpmu_user_blog( $user = false ) {
        $subject = _x( '[%network_name%] Activate your account', 'New site notification email subject' );

        return $subject;

    }


    /**
     * Default message new wpmu user (blog)
     *
     * @param false $user
     *
     * @return string
     */
    function b3_default_message_new_wpmu_user_blog( $user = false ) {

        $message = '';
        if ( false != $user ) {
            $message .= 'Hi %user_login%' . ",\n";
            $message .= '<br><br>' . "\n";
        }
        $message .= sprintf( esc_html__( 'To activate your registration, please click %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '%activation_url%', esc_html__( 'here', 'b3-onboarding' ) ) ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'After you activate, you will receive *another email* with your login.', 'b3-onboarding' ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'After you activate, you can visit your site here:', 'b3-onboarding' ) . "\n";
        $message .= '<br>' . "\n";
        $message .= '<a href="%home_url%">%home_url%</a>';
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

        return $message;
    }


    /**
     * Default subject welcome new wpmu user (blog)
     *
     * @return string
     */
    function b3_default_subject_welcome_wpmu_user_blog() {
        return 'New %network_name% Site: %site_name%';
    }


    /**
     * Default message welcome new wpmu user (blog)
     *
     * @param false $user_login
     *
     * @return string
     */
    function b3_default_message_welcome_wpmu_user_blog( $user_login = false ) {
        $message = '';
        if ( false != $user_login ) {
            $message .= 'Hi %user_login%' . ",\n";
            $message .= '<br><br>' . "\n";
        }
        // @TODO: add optional magic link
        $message .= sprintf( esc_html__( 'Your new site has been successfully set up at %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '%home_url%', '%home_url%' ) ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'You can log in to the administrator account with the following information', 'b3-onboarding' ) . ":\n";
        $message .= '<br>' . "\n";
        $message .= esc_html__( 'Username', 'b3-onboarding' ) . ': ' . '%user_login%' . "\n";
        $message .= '<br>' . "\n";
        $message .= esc_html__( 'Password', 'b3-onboarding' ) . ': ' . '%user_password%' . "\n";
        $message .= '<br><br>' . "\n";
        $message .= sprintf( esc_html__( 'Login here: %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '%login_url%', '%login_url%' ) ) . "\n";
        $message .= '<br><br>' . "\n";
        $message .= esc_html__( 'Enjoy your new site.', 'b3-onboarding' );
        $message .= '<br>' . "\n";
        $message .= b3_default_greetings();

        return $message;
    }


    /**
     * Return default registration message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_message_above_registration() {
        return esc_html__( 'Register For This Site' );
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
     * Return default registration register blog message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_logged_in_registration_only_message() {
        return sprintf( esc_html__( 'You must first %s, and then you can create a new site.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', b3_get_login_url(), esc_html__( 'log in', 'b3-onboarding' ) ) ) . "\n";
    }


    /**
     * Return default lost password message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_message_above_lost_password() {
        if ( 1 == get_option( 'b3_register_email_only' ) ) {
            return esc_html__( 'Please enter your email address. You will receive an email with a link to (re)set your password.', 'b3-onboarding' );
        } else {
            return esc_html__( 'Please enter your username or email address. You will receive an email with a link to (re)set your password.', 'b3-onboarding' );
        }
    }


    /**
     * Return default request access message
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_message_above_request_access() {
        return esc_html__( 'You have to request access for this website.', 'b3-onboarding' );
    }


    /**
     * Return default privacy text
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_default_privacy_text() {
        $message      = esc_html__( 'Accept privacy settings', 'b3-onboarding' );
        $privacy_page = get_option( 'b3_privacy_page_id' );

        if ( false != $privacy_page ) {
            if ( class_exists( 'SitePress' ) ) {
                $privacy_page = apply_filters( 'wpml_object_id', $privacy_page, 'page', true );
            }
            $privacy_page_object = get_post( $privacy_page );
            if ( is_object( $privacy_page_object ) ) {
                $link    = get_the_permalink( $privacy_page_object );
                $message = sprintf( esc_html__( 'Accept %s', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( $link ), esc_html__( 'privacy settings', 'b3-onboarding' ) ) ) . "\n";
            }
        }

        return $message;
    }


    /**
     * Return default greetings under each mail
     *
     * @return string
     */
    function b3_default_greetings() {
        $greetings = "\n" . '<br>' . "\n";
        $greetings .= esc_html__( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $greetings .= '<br><br>' . "\n";
        $greetings .= sprintf( esc_html__( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";

        return apply_filters( 'b3_default_greetings', $greetings );
    }


    /**
     * Get default reserved names
     *
     * @since 3.5.0
     */
    function b3_get_default_reserved_user_names() {
        $default_reserved_names = [
            'admin',
            'administrator',
            'www',
            'web',
            'root',
            'main',
            'invite',
            'files',
        ];
        
        return $default_reserved_names;
    }


    /**
     * Get 'easy' passwords
     *
     * @since 3.5.0
     *
     * @return mixed|void
     */
    function b3_get_default_easy_passwords() {
        $default_passwords = [
            '1234',
            '000000',
            '111111',
            '123456',
            '12345678',
            'abcdef',
            'god',
            'love',
            '1password',
            'password',
            '1wachtwoord',
            'wachtwoord',
        ];
        
        return $default_passwords;
    }
    
    
    /**
     * Get default magic link email
     *
     * @since 3.11.0
     *
     * @param $password
     * @param $slug
     *
     * @return string
     */
    function b3_get_default_magiclink_email( $password, $slug ) {
        if ( $password && $slug ) {
            $login_link = b3_get_login_url();
            $login_link = add_query_arg( 'login', 'enter_code', $login_link );
            $login_link = add_query_arg( 'otpcode', $slug, $login_link );
            $enter_url  = sprintf( '<a href="%s">%s</a>', esc_url( $login_link ), strtoupper( __( 'Login', 'b3-onboarding' ) ) );
            $your_code  = sprintf( '<div class="big-link">%s</div>', $enter_url ) . "\n";
            $message    = b3_get_email_intro( esc_html__( 'Hi', 'b3-onboarding' ) );
            $message    .= '<br><br>' . "\n";
            $message    .= esc_html__( 'Someone requested a "magic login link" for the account using this email address.', 'b3-onboarding' ) . "\n";
            $message    .= '<br><br>' . "\n";
            $message    .= esc_html__( 'If this request was made by you, you can click the following link to login.', 'b3-onboarding' ) . "\n";
            $message    .= '<br><br>' . "\n";
            $message    .= sprintf( '<div class="big-link-container">%s</div>', $your_code ) . "\n";
            $message    .= '<br>' . "\n";
            $message    .= esc_html__( "If this was a mistake, or you didn't ask for a 'magic link', just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\n";
            $message    .= '<br>' . "\n";
            $message    .= b3_default_greetings();
            
            return $message;
        }
        
        return '';
    }

    
    function b3_default_admin_pages() {
        $b3_pages = [
            [
                'id'      => 'register_page',
                'label'   => esc_html__( 'Register', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_register_page_id' ),
            ],
            [
                'id'      => 'login_page',
                'label'   => esc_html__( 'Log In', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_login_page_id' ),
            ],
            [
                'id'      => 'logout_page',
                'label'   => esc_html__( 'Log Out', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_logout_page_id' ),
            ],
            [
                'id'      => 'lost_password_page',
                'label'   => esc_html__( 'Lost Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_lost_password_page_id' ),
            ],
            [
                'id'      => 'reset_password_page',
                'label'   => esc_html__( 'Reset Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_reset_password_page_id' ),
            ],
            [
                'id'      => 'account_page',
                'label'   => esc_html__( 'Account', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_account_page_id' ),
            ],
        ];
        
        if ( true == get_option( 'b3_front_end_approval' ) ) {
            $front_end_approval = [
                'id'      => 'approval_page',
                'label'   => esc_html__( 'Approval page', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_approval_page_id' ),
            ];
            
            $b3_pages[] = $front_end_approval;
        }
        
        return $b3_pages;
    }
