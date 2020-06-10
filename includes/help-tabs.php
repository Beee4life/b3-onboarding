<?php

    /**
     * Add help tabs
     *
     * @since 1.0.4
     *
     * @param $screen    object
     */
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
                'content' => '<h3>' . esc_html__( 'Settings', 'b3-onboarding' ) . '</h3>
                    <p>' . esc_html__( 'If you select a logo, it will be loaded (but not shown) on full size ! So select a properly sized logo.', 'b3-onboarding' ) . '</p>
                    '
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => '<h3>' . esc_html__( 'Registration', 'b3-onboarding' ) . '</h3>
                    <p>' . esc_html__( "Your general setting for if 'users can register' can register is now disabled and is controlled by the setting on this page.", 'b3-onboarding' ) . '</p>
                    <p>' . esc_html__( 'If you want to add a reCaptcha verification, make sure you get the v2 keys. V3 is not supported yet.', 'b3-onboarding' ) . '</p>
                    <p>' . sprintf( __( 'Get your (free) reCaptcha keys <a href="%s" target="_blank" rel="noopener">here</a>.', 'b3-onboarding' ), esc_url( 'https://www.google.com/recaptcha/admin#list' ) ) . '</p>
                    '
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-emails',
                'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                'content' => '<h3>' . esc_html__( 'Emails', 'b3-onboarding' ) . '</h3>
                    <p>' . esc_html__( 'You can add any HTML you want in the email messages. Be sure to use the preview mode, before using it.', 'b3-onboarding' ) . '</p>
                    <p>' . esc_html__( 'Save yourself a lot of work per email and use the template option. This is then wrapped around each message.', 'b3-onboarding' ) . '</p>
                    <p>' . esc_html__( 'These are the available variables in emails.', 'b3-onboarding' ) . '</p>
                    <ul>
                        <li>%activation_url% (' . __( 'only in user activation email', 'b3-onboarding' ) . ')</li>
                        <li>%blog_name% <sup>&sup1;</sup></li>
                        <li>%email_footer% <sup>&sup1;</sup></li>
                        <li>%email_styling% <sup>&sup1;</sup></li>
                        <li>%home_url% <sup>&sup1;</sup></li>
                        <li>%registration_date% <sup>&sup1;</sup></li>
                        <li>%reset_url% <sup>&sup3;</sup></li>
                        <li>%user_ip% <sup>&sup1;</sup></li>
                        <li>%user_login% <sup>&sup1;</sup></li>
                    </ul>
                    <sup>&sup1;</sup> ' . __( 'available in every email', 'b3-onboarding' ) . '
                    <br />
                    <sup>&sup2;</sup> ' . __( 'only available in admin notification', 'b3-onboarding' ) . '
                    <br />
                    <sup>&sup3;</sup> ' . __( 'only available in password reset email', 'b3-onboarding' ) . '
                    '
            ) );

            if ( defined( 'LOCALHOST' ) && 1 == LOCALHOST ) {
                $shortcode_info = '<p>' . sprintf( __( 'More info about the use of shortcodes and their variables, please see <a href="%s">here</a>.', 'b3-onboarding'  ), B3_PLUGIN_SITE . '/documentation/shortcodes/' ) . '</p>';
            } else {
                $shortcode_info = false;
            }
            $screen->add_help_tab( array(
                'id'      => 'b3-shortcodes',
                'title'   => esc_html__( 'Shortcodes', 'b3-onboarding' ),
                'content' => '<h3>' . esc_html__( 'Shortcodes', 'b3-onboarding' ) . '</h3>
                    <ul>
                    <li>
                        <b>[register-form]</b>
                        <br />
                        ' . esc_html__( 'This renders the registration page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[login-form]</b>
                        <br />
                        ' . esc_html__( 'This renders the login page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[lostpass-form]</b>
                        <br />
                        ' . esc_html__( 'This renders the lost password page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[resetpass-form]</b>
                        <br />
                        ' . esc_html__( 'This renders the reset password page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[account-page]</b>
                        <br />
                        ' . esc_html__( 'This renders the account page.', 'b3-onboarding' ) . '
                    </li>
                    <li>
                        <b>[user-management]</b>
                        <br />
                        ' . esc_html__( 'This renders the user management page.', 'b3-onboarding' ) . '
                    </li>
                    </ul>
                    ' . $shortcode_info
            ) );
        }

        if ( defined( 'LOCALHOST' ) && 1 == LOCALHOST ) {
            $site = '<p><strong>' . esc_html__( 'More info', 'b3-onboarding' ) . '</strong></p>
            <p><a href="https://b3onboarding.berryplasman.com?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=onboarding_admin&utm_campaign=free_promo">' . __( 'Official site', 'b3-onboarding' ) . '</a></p>';
        } else {
            $site = false;
        }
        get_current_screen()->set_help_sidebar(
            '<p><strong>' . esc_html__( 'Author', 'b3-onboarding' ) . '</strong></p>
            <p><a href="https://berryplasman.com?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=onboarding_admin&utm_campaign=free_promo">Berry Plasman</a></p>
            ' . $site
        );
    }
    add_action( 'current_screen', 'b3_help_tabs', 5 );
