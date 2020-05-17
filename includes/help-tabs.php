<?php

    /**
     * Add help tabs
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
                'id'      => 'b3-email-vars',
                'title'   => esc_html__( 'Email variables', 'b3-onboarding' ),
                'content' => '<h3>' . esc_html__( 'Email variables', 'b3-onboarding' ) . '</h3>
                    <p>' . esc_html__( 'These are the available variables in emails.', 'b3-onboarding' ) . '</p>
                    <ul>
                        <li>%blog_name% = ' . get_option( 'blogname' ) . '</li>
                        <li>%email_styling%</li>
                        <li>%home_url% = ' . get_home_url() . '</li>
                        <li>%registration_date% (only available in admin notification)</li>
                        <li>%reset_url% (only available in reset password email)</li>
                        <li>%user_ip% (only available in admin notification)</li>
                        <li>%user_login%</li>
                    </ul>
                    '
            ) );

            $screen->add_help_tab( array(
                'id'      => 'b3-loginpage',
                'title'   => esc_html__( 'Login page design', 'b3-onboarding' ),
                'content' => '<h3>' . esc_html__( 'Login page design', 'b3-onboarding' ) . '</h3>
                    <p>' . esc_html__( 'Here you can style the default Wordpress register/login page.', 'b3-onboarding' ) . '</p>
                    <ul>
                        <li>%blog_name% = ' . get_option( 'blogname' ) . '</li>
                        <li>%home_url% = ' . get_home_url() . '</li>
                    </ul>
                    '
            ) );

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
                        <b>[forgotpass-form]</b>
                        <br />
                        ' . esc_html__( 'This renders the forgot password page.', 'b3-onboarding' ) . '
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
                    <li class="hidden">
                        <b>[delete-account]</b>
                        <br />
                        ' . esc_html__( 'This renders the delete account page.', 'b3-onboarding' ) . '
                    </li>
                    </ul>
                    '
            ) );
        }

        get_current_screen()->set_help_sidebar(
            '<p><strong>' . esc_html__( 'Author', 'b3-onboarding' ) . '</strong></p>
            <p><a href="http://www.berryplasman.com?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=onboarding_admin&utm_campaign=free_promo">berryplasman.com</a></p>'
        );

        return false;
    }
    add_filter( 'current_screen', 'b3_help_tabs', 5, 3 );
