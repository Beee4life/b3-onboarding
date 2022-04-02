<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Add help tabs
     *
     * @since 1.0.4
     *
     * @param $screen    object
     *
     * @return false|void
     */
    function b3_help_tabs( $screen ) {
        $screen_array = array(
            'toplevel_page_b3-onboarding',
        );
        if ( ! in_array( $screen->id, $screen_array ) ) {
            return false;
        }

        if ( 'toplevel_page_b3-onboarding' == $screen->id ) {
            $tabs = [];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Registration', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( "Your general setting for if 'users can register' is now disabled and is controlled by the setting on this page.", 'b3-onboarding' ) );
            echo '<p>';
            echo esc_html__( 'This page has several options to change registration settings, such as:', 'b3-onboarding' );
            echo '<br>- ' . esc_html__( 'registering with email address only', 'b3-onboarding' );
            echo '<br>- ' . esc_html__( 'use first/last name', 'b3-onboarding' );
            echo '<br>- ' . esc_html__( 'make first/last name required', 'b3-onboarding' );
            echo '<br>- ' . esc_html__( 'activate recaptcha', 'b3-onboarding' );
            echo '<br>- ' . esc_html__( 'activate honeypot', 'b3-onboarding' );
            echo '<br>- ' . esc_html__( 'activate privacy checkbox', 'b3-onboarding' );
            echo '</p>';
            $registration_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => $registration_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Pages', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'This page shows which pages are used for which action. The plugin relies on this, so make sure every action has a page set for it.', 'b3-onboarding' ) );
            if ( class_exists( 'Sitepress' ) ) {
                echo sprintf( '<p>%s</p>', esc_html__( 'Choose a page in your default language.', 'b3-onboarding' ) );
            }
            $pages_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-pages',
                'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
                'content' => $pages_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Emails', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'You can add any HTML you want in the email messages. Be sure to use the preview mode, before using it.', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'Save yourself a lot of work per email and use the template option. This is then wrapped around each message.', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'These are the available variables in emails.', 'b3-onboarding' ) . '
                    <ul>
                        <li>%activation_url% (' . esc_html__( 'only in user activation email', 'b3-onboarding' ) . ')</li>
                        <li>%account_page% <sup>&sup1;</sup></li>
                        <li>%blog_name% <sup>&sup1;</sup></li>
                        <li>%email_footer% <sup>&sup1;</sup></li>
                        <li>%home_url% <sup>&sup1;</sup></li>
                        <li>%login_url% <sup>&sup1;</sup></li>
                        <li>%logo% <sup>&sup1;</sup></li>
                        <li>%lostpass_url% <sup>&sup1;</sup></li>
                        <li>%network_name% <sup>&sup1;</sup></li>
                        <li>%registration_date% <sup>&sup1;</sup></li>
                        <li>%reset_url% <sup>&sup2;</sup></li>
                        <li>%user_ip% <sup>&sup1;</sup></li>
                        <li>%user_login% <sup>&sup1;</sup></li>
                    </ul>
                    <sup>&sup1;</sup> ' . esc_html__( 'available in every email', 'b3-onboarding' ) . '
                    <br>
                    <sup>&sup2;</sup> ' . esc_html__( 'only available in password reset email', 'b3-onboarding' ) .
                    sprintf( '<p>%s</p>', sprintf( 'You can also find all variables %s.', sprintf( '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_SITE . '/faq/email-variables/' ), esc_html__( 'on our website' ) ) ) )

            );
            $emails_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-emails',
                'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                'content' => $emails_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Shortcodes', 'b3-onboarding' ) );
            echo '<ul>';
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[register-form]', esc_html__( 'This renders the registration page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[login-form]', esc_html__( 'This renders the login page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[lostpass-form]', esc_html__( 'This renders the lost password page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[resetpass-form]', esc_html__( 'This renders the reset password page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[account-page]', esc_html__( 'This renders the account page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[user-management]', esc_html__( 'This renders the user management page.', 'b3-onboarding' ) );
            echo '</ul>';
            echo '<p>' . sprintf( esc_html__( 'More info about the use of shortcodes and their variables, please see %s.', 'b3-onboarding'  ), sprintf( '<a href="%s">%s</a>', esc_url( 'https://b3onboarding.berryplasman.com/faq/available-shortcodes/' ), esc_html__( 'here', 'b3-onboarding' ) ) ) . '</p>';
            $shortcodes_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-shortcodes',
                'title'   => esc_html__( 'Shortcodes', 'b3-onboarding' ),
                'content' => $shortcodes_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Settings', 'b3-onboarding' ) );
            echo '<p>';
            echo esc_html__( 'This page has various global settings, such as:', 'b3-onboarding' );
            echo '</p>';
            echo '<ul>';
            echo sprintf( '<li>%s</li>', esc_html__( 'disable the links below the form button on login/registration forms', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'use a popup for the login form, when using the B3 sidebar widget', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate the debug page', 'b3-onboarding' ) );
            echo '</ul>';
            echo sprintf( '<p>%s</p>', esc_html__( 'If you select a logo, it will be loaded (but not shown) on full size ! So select a properly sized logo.', 'b3-onboarding' ) );
            $settings_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-settings',
                'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
                'content' => $settings_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Developers', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', sprintf( esc_html__( "If you're a developer, you might want to check out %s (if you haven't already).", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_SITE . '/faq/localhost-development/' ), esc_html__( 'this FAQ topic', 'b3-onboarding' ) ) ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'It has some explanantion about how you can more easily test, when developing locally.', 'b3-onboarding' ) );
            $developers_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-developers',
                'title'   => esc_html__( 'Developers', 'b3-onboarding' ),
                'content' => $developers_message,
            ];

            foreach( $tabs as $tab ) {
                $screen->add_help_tab( array(
                    'id'      => $tab[ 'id' ],
                    'title'   => $tab[ 'title' ],
                    'content' => $tab[ 'content' ],
                ) );
            }

            ob_start();
            echo sprintf( '<p><b>%s</b></p>', esc_html__( 'More info', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', sprintf( '<a href="%s">%s</a>', B3OB_PLUGIN_SITE . '?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=onboarding_admin&utm_campaign=free_promo', esc_html__( 'Official site', 'b3-onboarding' ) ) );
            $sidebar_content = ob_get_clean();
            get_current_screen()->set_help_sidebar( $sidebar_content );
        }
    }

    if ( is_main_site() ) {
        add_action( 'current_screen', 'b3_help_tabs', 5 );
    }
