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
        $screen_array = [
            'toplevel_page_b3-onboarding',
        ];
        if ( ! in_array( $screen->id, $screen_array ) ) {
            return false;
        }

        if ( 'toplevel_page_b3-onboarding' === $screen->id ) {
            $tabs = [];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Registration', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( "The general setting for if 'users can register' is now disabled and is controlled by the setting on this page.", 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'The "Registration" tab has several options to change registration settings, such as:', 'b3-onboarding' ) );
            echo '<ul>';
            echo sprintf( '<li>%s</li>', esc_html__( 'activate admin approval', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'registering with email address only', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'use first/last name', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'make first/last name required', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate magic link', 'b3-onboarding' ) );
            /* translators: brand name (no translation) */
            echo sprintf( '<li>%s</li>', sprintf( esc_html__( 'activate %s', 'b3-onboarding' ), esc_html( 'reCaptcha' ) ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate honeypot', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate terms checkbox', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate privacy checkbox', 'b3-onboarding' ) );
            echo '</ul>';
            $registration_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => $registration_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Emails', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'You can set the link color for the emails here, the logo and select whether to use our template or use your own.', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'You can add any HTML you want in the email messages. Be sure to use the preview mode, before using it.', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'Save yourself a lot of work per email and use the template option. This is then wrapped around each message.', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', sprintf( 'You can find all email variables %s.', sprintf( '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_SITE . '/faq/email-variables/' ), esc_html__( 'on our website', 'b3-onboarding' ) ) ) );
            $emails_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-emails',
                'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                'content' => $emails_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Users', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'The "Users" tab shows various user settings.', 'b3-onboarding' ) );
            echo '<ul>';
            echo sprintf( '<li>%s</li>', esc_html__( 'restrict admin access', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate a welcome page', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'hide admin bar for users', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'disallow domains', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'disallow usernames', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'if a user can delete his account', 'b3-onboarding' ) );
            echo '</ul>';
            /* translators: link to filter on website */
            echo sprintf( '<p>%s</p>', sprintf( esc_html__( 'If you select a logo, it will be loaded (but not shown) on full size ! So select a properly sized logo. Or you can use the filter %s to use a perfectly cropped image.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', 'https://b3onboarding.berryplasman.com/filter/b3_main_logo/', 'b3_main_logo' ) ) );
            $users_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-users',
                'title'   => esc_html__( 'Users', 'b3-onboarding' ),
                'content' => $users_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html( 'reCaptcha' ) );
            echo sprintf( '<p>%s</p>', esc_html__( sprintf( 'On the "%s" tab you can select which version to use and which theme.', 'reCatpcha' ), 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'And of course you can set your public and private key here.', 'b3-onboarding' ) );
            $recaptcha_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-recaptcha',
                'title'   => esc_html( 'reCaptcha' ),
                'content' => $recaptcha_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Pages', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'The "Pages" tab shows which pages are used for which action. The plugin relies on this, so make sure every action has a page set for it.', 'b3-onboarding' ) );
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
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Settings', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'The "Settings" tab holds has various global settings, such as:', 'b3-onboarding' ) );
            echo '<ul>';
            echo sprintf( '<li>%s</li>', esc_html__( 'disable the links below the form button on login/registration forms', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'use a popup for the login form, when using the B3 sidebar widget', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate filter validation', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'activate the debug page', 'b3-onboarding' ) );
            echo sprintf( '<li>%s</li>', esc_html__( 'set various messages above forms', 'b3-onboarding' ) );
            echo '</ul>';
            $settings_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-settings',
                'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
                'content' => $settings_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Shortcodes', 'b3-onboarding' ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'These are the available shortcodes.', 'b3-onboarding' ) );
            echo '<ul>';
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[register-form]', esc_html__( 'This renders the registration page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[login-form]', esc_html__( 'This renders the login page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[lostpass-form]', esc_html__( 'This renders the lost password page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[resetpass-form]', esc_html__( 'This renders the reset password page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[account-page]', esc_html__( 'This renders the account page.', 'b3-onboarding' ) );
            echo sprintf( '<li><b>%s</b><br>%s</li>', '[user-management]', esc_html__( 'This renders the user management page.', 'b3-onboarding' ) );
            echo '</ul>';
            /* translators: here */
            echo '<p>' . sprintf( esc_html__( 'More info about the use of shortcodes and their variables, please see %s.', 'b3-onboarding'  ), sprintf( '<a href="%s">%s</a>', esc_url( 'https://b3onboarding.berryplasman.com/faq/available-shortcodes/' ), esc_html__( 'here', 'b3-onboarding' ) ) ) . '</p>';
            $shortcodes_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-shortcodes',
                'title'   => esc_html__( 'Shortcodes', 'b3-onboarding' ),
                'content' => $shortcodes_message,
            ];

            ob_start();
            echo sprintf( '<h3>%s</h3>', esc_html__( 'Developers', 'b3-onboarding' ) );
            /* translators: link to faq item */
            echo sprintf( '<p>%s</p>', sprintf( esc_html__( "If you're a developer, you might want to check out %s (if you haven't already).", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_SITE . '/faq/localhost-development/' ), esc_html__( 'this FAQ topic', 'b3-onboarding' ) ) ) );
            echo sprintf( '<p>%s</p>', esc_html__( 'It has some explanantion about how you can more easily test, when developing locally.', 'b3-onboarding' ) );
            $developers_message = ob_get_clean();

            $tabs[] = [
                'id'      => 'b3-developers',
                'title'   => esc_html__( 'Developers', 'b3-onboarding' ),
                'content' => $developers_message,
            ];

            foreach( $tabs as $tab ) {
                $screen->add_help_tab( [
                    'id'      => $tab[ 'id' ],
                    'title'   => $tab[ 'title' ],
                    'content' => $tab[ 'content' ],
                ] );
            }

            ob_start();
            echo sprintf( '<p><b>%s</b></p>', esc_html__( 'More info', 'b3-onboarding' ) );
            if ( isset( $_SERVER[ 'SERVER_NAME' ] ) ) {
                $server_name = sanitize_text_field( wp_unslash( $_SERVER[ 'SERVER_NAME' ] ) );
                echo sprintf( '<p>%s</p>', sprintf( '<a href="%1$s">%2$s</a>', esc_url( B3OB_PLUGIN_SITE ) . '?utm_source=' . esc_attr( $server_name ) . '&utm_medium=onboarding_admin&utm_campaign=free_promo', esc_html__( 'Official site', 'b3-onboarding' ) ) );
            } else {
                echo sprintf( '<p>%s</p>', sprintf( '<a href="%1$s">%2$s</a>', esc_url( B3OB_PLUGIN_SITE ), esc_html__( 'Official site', 'b3-onboarding' ) ) );
            }
            echo sprintf( '<p>%s</p>', sprintf( '<a href="%s">%s</a>', 'https://github.com/Beee4life/b3-onboarding/', 'Github' ) );
            $sidebar_content = ob_get_clean();
            get_current_screen()->set_help_sidebar( $sidebar_content );
        }
    }

    if ( is_main_site() ) {
        add_action( 'current_screen', 'b3_help_tabs', 5 );
    }
