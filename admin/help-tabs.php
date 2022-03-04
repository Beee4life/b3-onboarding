<?php
    /**
     * Add help tabs
     *
     * @since 1.0.4
     *
     * @param $screen    object
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_help_tabs( $screen ) {

        $screen_array = array(
            'toplevel_page_b3-onboarding',
        );
        if ( ! in_array( $screen->id, $screen_array ) ) {
            return false;
        }

        if ( 'toplevel_page_b3-onboarding' == $screen->id ) {
            $screen->add_help_tab( array(
                'id'      => 'b3-settings',
                'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
                'content' => sprintf( '<h3>%s</h3>', esc_html__( 'Settings', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'You can disable the links below the form button on login/registration forms.', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'You can use a popup for the login form, when using the B3 sidebar widget.', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'You can use a popup for the login form, when using the B3 sidebar widget.', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'Activate the debug page.', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'If you select a logo, it will be loaded (but not shown) on full size ! So select a properly sized logo.', 'b3-onboarding' ) )
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => sprintf( '<h3>%s</h3>', esc_html__( 'Registration', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( "Your general setting for if 'users can register' can register is now disabled and is controlled by the setting on this page.", 'b3-onboarding' ) )
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-recaptcha',
                'title'   => esc_html__( 'reCaptcha', 'b3-onboarding' ),
                'content' => sprintf( '<h3>%s</h3>', esc_html__( 'reCaptcha', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( "The reCaptcha settings will show on a new tab after you the checked and saved the 'reCaptcha' setting on the registration tab.", 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', sprintf( esc_html__( 'Get your (free) reCaptcha keys %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( 'https://www.google.com/recaptcha/admin#list' ), esc_html__( 'here', 'b3-onboarding' ) ) ) )
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-emails',
                'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                'content' => sprintf( '<h3>%s</h3>', esc_html__( 'Emails', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'You can add any HTML you want in the email messages. Be sure to use the preview mode, before using it.', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'Save yourself a lot of work per email and use the template option. This is then wrapped around each message.', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'These are the available variables in emails.', 'b3-onboarding' ) ) . '
                    <ul>
                        <li>%activation_url% (' . esc_html__( 'only in user activation email', 'b3-onboarding' ) . ')</li>
                        <li>%blog_name% <sup>&sup1;</sup></li>
                        <li>%home_url% <sup>&sup1;</sup></li>
                        <li>%registration_date% <sup>&sup1;</sup></li>
                        <li>%reset_url% <sup>&sup3;</sup></li>
                        <li>%user_ip% <sup>&sup1;</sup></li>
                        <li>%user_login% <sup>&sup1;</sup></li>
                    </ul>
                    <sup>&sup1;</sup> ' . esc_html__( 'available in every email', 'b3-onboarding' ) . '
                    <br>
                    <sup>&sup2;</sup> ' . esc_html__( 'only available in admin notification', 'b3-onboarding' ) . '
                    <br>
                    <sup>&sup3;</sup> ' . esc_html__( 'only available in password reset email', 'b3-onboarding' ) . '
                    '
            ) );

            $shortcode_info = '<p>' . sprintf( esc_html__( 'More info about the use of shortcodes and their variables, please see %s.', 'b3-onboarding'  ), sprintf( '<a href="%s">%s</a>', esc_url( 'https://b3onboarding.berryplasman.com/faq/available-shortcodes/' ), esc_html__( 'here', 'b3-onboarding' ) ) ) . '</p>';
            $screen->add_help_tab( array(
                'id'      => 'b3-shortcodes',
                'title'   => esc_html__( 'Shortcodes', 'b3-onboarding' ),
                'content' => '<h3>' . esc_html__( 'Shortcodes', 'b3-onboarding' ) . '</h3>
                    <ul>
                    <li>
                        <b>[register-form]</b>
                        <br>
                        ' . esc_html__( 'This renders the registration page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[login-form]</b>
                        <br>
                        ' . esc_html__( 'This renders the login page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[lostpass-form]</b>
                        <br>
                        ' . esc_html__( 'This renders the lost password page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[resetpass-form]</b>
                        <br>
                        ' . esc_html__( 'This renders the reset password page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[account-page]</b>
                        <br>
                        ' . esc_html__( 'This renders the account page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[user-management]</b>
                        <br>
                        ' . esc_html__( 'This renders the user management page.', 'b3-onboarding' ) . '
                    </li>
                    </ul>
                    ' . $shortcode_info
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-developers',
                'title'   => esc_html__( 'Developers', 'b3-onboarding' ),
                // 'content' => '<h3>' . esc_html__( 'Developers', 'b3-onboarding' ) . '</h3>
                'content' => sprintf( '<h3>%s</h3>', esc_html__( 'Developers', 'b3-onboarding' ) ) .
                    sprintf( '<p>%s</p>', sprintf( esc_html__( "If you're a developer, you might want to check out %s (if you haven't already).", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( B3_PLUGIN_SITE . '/faq/localhost-development/' ), esc_html__( 'this FAQ topic', 'b3-onboarding' ) ) ) ) .
                    sprintf( '<p>%s</p>', esc_html__( 'It has some explanantion about how you can more easily test, when developing locally.', 'b3-onboarding' ) )
            ) );

            get_current_screen()->set_help_sidebar(
                sprintf( '<p><b>%s</b></p>', esc_html__( 'More info', 'b3-onboarding' ) ) .
                sprintf( '<p>%s</p>', sprintf( '<a href="%s">%s</a>', B3_PLUGIN_SITE . '?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=onboarding_admin&utm_campaign=free_promo', esc_html__( 'Official site', 'b3-onboarding' ) ) )
            );
        }
    }
    if ( is_main_site() ) {
        add_action( 'current_screen', 'b3_help_tabs', 5 );
    }
