<?php
    // default/fallback values
    include('defaults.php');

    /**
     * Return all custom meta keys
     *
     * @return array
     */
    function b3_get_all_custom_meta_keys() {

        // @TODO: keep this list updated
        $meta_keys = array(
            'b3_account_activated_message',
            'b3_account_activated_subject',
            'b3_account_approved_message',
            'b3_account_approved_subject',
            'b3_account_page_id', // set on activate
            'b3_account_rejected_message',
            'b3_account_rejected_subject',
            'b3_action_links',
            'b3_activate_first_last',
            'b3_approval_page_id',
            'b3_disable_admin_notification_password_change',
            'b3_disable_admin_notification_new_user',
            'b3_disable_delete_user_email',
            'b3_custom_emails', // @TODO: look into this one
            'b3_custom_passwords', // not in use yet
            'b3_dashboard_widget', // set on activate
            'b3_debug_info',
            'b3_disable_action_links',
            'b3_email_activation_message',
            'b3_email_activation_subject',
            'b3_email_styling', // set on activate
            'b3_email_template', // set on activate
            'b3_first_last_required',
            'b3_force_custom_login_page',
            'b3_forgot_password_message',
            'b3_forgot_password_subject',
            'b3_forgotpass_page_id', // set on activate
            'b3_front_end_approval',
            'b3_login_logo',
            'b3_login_page_id', // set on activate
            'b3_loginpage_bg_color',
            'b3_loginpage_font_family',
            'b3_loginpage_font_size',
            'b3_loginpage_logo_width',
            'b3_loginpage_logo_height',
            'b3_logo_in_email', // set on activate
            'b3_logout_page_id', // set on activate
            'b3_new_user_message',
            'b3_new_user_notification_addresses',
            'b3_new_user_subject',
            'b3_notification_sender_email', // set on activate
            'b3_notification_sender_name', // set on activate
            'b3_privacy_page',
            'b3_recaptcha',
            'b3_recaptcha_public',
            'b3_recaptcha_secret',
            'b3_recaptcha_version',
            'b3_register_page_id', // set on activate
            'b3_registration_type', // set on activate
            'b3_request_access_message_admin',
            'b3_request_access_message_user',
            'b3_request_access_notification_addresses',
            'b3_request_access_subject_admin',
            'b3_request_access_subject_user',
            'b3_reset_page_id', // set on activate
            'b3_restrict_admin', // set on activate
            'b3_sidebar_widget', // set on activate
            'b3_style_default_pages',
            'b3_users_may_delete',
            'b3_welcome_user_message',
            'b3_welcome_user_subject',
        );

        return $meta_keys;
    }

    /**
     * Create an array of available email 'boxes'
     *
     * @return array
     */
    function b3_get_email_boxes() {

        $activate_email_boxes = [];
        $new_user_boxes       = [];
        $registration_type    = get_option( 'b3_registration_type', false );
        $request_access_box   = [];
        $welcome_user_boxes   = [];

        $settings_box = array(
            array(
                'id'    => 'email_settings',
                'title' => esc_html__( 'Global email settings', 'b3-onboarding' ),
            ),
        );
        if ( in_array( $registration_type, [ 'request_access', 'request_access_subdomain' ] ) ) {
            $request_access_box = array(
                array(
                    'id'    => 'request_access_user',
                    'title' => esc_html__( 'Request access email (user)', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'request_access_admin',
                    'title' => esc_html__( 'Request access email (admin)', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'account_approved',
                    'title' => esc_html__( 'Account approved email (user)', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'account_rejected',
                    'title' => esc_html__( 'Account rejected email (user)', 'b3-onboarding' ),
                ),
            );
        }
        if ( in_array( $registration_type, [ 'email_activation' ] ) ) {
            $activate_email_boxes = array(
                array(
                    'id'    => 'email_activation',
                    'title' => esc_html__( 'Email activation (user)', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'account_activated',
                    'title' => esc_html__( 'Account activated (user)', 'b3-onboarding' ),
                ),
            );
        }
        if ( in_array( $registration_type, [ 'open' ] ) ) {
            $welcome_user_boxes = array(
                array(
                    'id'    => 'welcome_email_user',
                    'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
                ),
            );
        }
        if ( in_array( $registration_type, [ 'open', 'email_activation' ] ) ) {
            $new_user_boxes = array(
                array(
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                ),
            );
        }
        $default_boxes2 = array(
            array(
                'id'    => 'forgot_password',
                'title' => esc_html__( 'Forgot password email', 'b3-onboarding' ),
            ),
        );
        $styling_boxes = array();
        if ( false != get_option( 'b3_custom_emails', false ) && ( defined( 'WP_ENV' ) && 'development' == WP_ENV ) ) {
            $styling_boxes = array(
                array(
                    'id'    => 'email_styling',
                    'title' => esc_html__( 'Email styling', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'email_template',
                    'title' => esc_html__( 'Email template', 'b3-onboarding' ),
                ),
            );
        }
        $email_boxes = array_merge( $settings_box, $new_user_boxes, $request_access_box, $activate_email_boxes, $welcome_user_boxes, $default_boxes2, $styling_boxes );

        return $email_boxes;

    }


    /**
     * Return registration options
     *
     * @return array
     */
    function b3_get_registration_types() {
        $registration_options = [];
        $closed_option = array(
            array(
                'value' => 'closed',
                'label' => esc_html__( 'Closed (for everyone)', 'b3-onboarding' ),
            ),
        );

        $normal_options = array(
            array(
                'value' => 'request_access',
                'label' => esc_html__( 'Request access (admin approval)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'email_activation',
                'label' => esc_html__( 'Open (user needs to confirm email)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'open',
                'label' => esc_html__( 'Open (user is instantly active)', 'b3-onboarding' ),
            ),
        );

        $multisite_options = array(
            // array(
            //     'value' => 'request_access_subdomain',
            //     'label' => esc_html__( 'Request access (admin approval + user domain request)', 'b3-onboarding' ),
            // ),
            array(
                'value' => 'ms_loggedin_register',
                'label' => esc_html__( 'Logged in user may register a site', 'b3-onboarding' ),
            ),
            array(
                'value' => 'ms_register_user',
                'label' => esc_html__( 'Visitor may register user', 'b3-onboarding' ),
            ),
            array(
                'value' => 'ms_register_site_user',
                'label' => esc_html__( 'Visitor may register user + site', 'b3-onboarding' ),
            ),
        );

        if ( ! is_multisite() ) {
            // @TODO: get registration type
            $registration_options = array_merge( $closed_option, $registration_options, $normal_options );
        } else {
            $mu_registration = get_site_option( 'registration' );
            if ( ! is_main_site() ) {
                if ( 'none' != $mu_registration ) {
                    $registration_options = $normal_options;
                }
            } else {
                $registration_options = array_merge( $closed_option, $multisite_options );
                if ( 'none' != $mu_registration ) {
                }
            }

        }

        return $registration_options;
    }


    /**
     * Return email styling and default styling if false
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_styling( $link_color = false ) {
        $custom_css = get_option( 'b3_email_styling', false );

        if ( false != $custom_css ) {
            $email_style = $custom_css;
        } else {
            $email_style = b3_default_email_styling( $link_color );
        }

        return $email_style;
    }


    /**
     * Return user email template and default template if false
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_template( $hide_logo = false ) {
        $custom_template = get_option( 'b3_email_template', false );

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
     * @TODO: add user input option
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_email_footer() {
        $email_logo = b3_default_email_footer();

        return $email_logo;
    }


    /**
     * Get notification addresses
     *
     * @param $registration_type
     *
     * @return mixed
     */
    function b3_get_notification_addresses( $registration_type ) {
        $email_addresses = get_option( 'admin_email' );
        if ( 'request_access' == $registration_type ) {
            if ( false != get_option( 'b3_request_access_notification_addresses', false ) ) {
                $email_addresses = get_option( 'b3_request_access_notification_addresses' );
            }
        } elseif ( 'open' == $registration_type ) {
            if ( false != get_option( 'b3_new_user_notification_addresses', false ) ) {
                $email_addresses = get_option( 'b3_new_user_notification_addresses' );
            }
        }

        return $email_addresses;

    }


    /**
     * Return email activation subject (user)
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_email_activation_subject_user() {
        $b3_email_activation_subject = get_option( 'b3_email_activation_subject', false );
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
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_email_activation_message_user() {
        $b3_email_activation_message = get_option( 'b3_email_activation_message', false );
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
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_subject() {
        $b3_welcome_user_subject = get_option( 'b3_welcome_user_subject', false );
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
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_message() {
        $b3_welcome_user_message = get_option( 'b3_welcome_user_message', false );
        if ( $b3_welcome_user_message ) {
            $message = $b3_welcome_user_message;
        } else {
            $message = b3_default_welcome_user_message();
        }

        return $message;
    }


    /**
     * Get email subject for request access (admin)
     *
     * @return mixed|string
     */
    function b3_request_access_subject_admin() {
        $subject = get_option( 'b3_request_access_subject_admin', false );
        if ( ! $subject ) {
            $subject = b3_default_request_access_subject_admin();
        }

        return $subject;

    }


    /**
     * Get email message for request access (admin)
     *
     * @return mixed|string
     */
    function b3_request_access_message_admin() {
        $message = get_option( 'b3_request_access_message_admin', false );
        if ( ! $message ) {
            $message = b3_default_request_access_message_admin();
        }

        return $message;

    }


    /**
     * Get email subject for request access (user)
     *
     * @return mixed|string
     */
    function b3_request_access_subject_user() {
        $subject = get_option( 'b3_request_access_subject_user', false );
        if ( ! $subject ) {
            $subject = b3_default_request_access_subject_user();
        }

        return $subject;

    }


    /**
     * Get email message for request access (user)
     *
     * @return mixed|string
     */
    function b3_request_access_message_user() {
        $message = get_option( 'b3_request_access_message_user', false );
        if ( ! $message ) {
            $message = b3_default_request_access_message_user();
        }

        return $message;

    }


    /**
     * Get email subject for account approved
     *
     * @return mixed|string
     */
    function b3_get_account_approved_subject() {
        $subject = get_option( 'b3_account_approved_subject', false );
        if ( ! $subject ) {
            $subject = b3_default_account_approved_subject();
        }

        return $subject;

    }


    /**
     * Get email message for account approved
     *
     * @return mixed|string
     */
    function b3_get_account_approved_message() {
        $message = get_option( 'b3_account_approved_message', false );
        if ( ! $message ) {
            $message = b3_default_account_approved_message();
        }

        return $message;

    }


    /**
     * Get email subject for account approved (user)
     *
     * @return mixed|string
     */
    function b3_get_account_activated_subject_user() {
        $subject = get_option( 'b3_account_activated_subject', false );
        if ( ! $subject ) {
            $subject = b3_default_account_activated_subject();
        }

        return $subject;

    }


    /**
     * Get email message for account approved (user)
     *
     * @TODO: maybe merge with welcome
     *
     * @return mixed|string
     */
    function b3_get_account_activated_message_user() {
        $message = get_option( 'b3_account_activated_message', false );
        if ( ! $message ) {
            $message = b3_default_account_activated_message();
        }

        return $message;

    }


    /**
     * Get account rejected subject (user)
     *
     * @return bool|mixed|string|void
     */
    function b3_get_account_rejected_subject() {
        $subject = get_option( 'b3_account_rejected_subject', false );
        if ( ! $subject ) {
            $subject = b3_default_account_rejected_subject() . "\n";
        }

        return $subject;

    }


    /**
     * Get account rejected message (user)
     *
     * @return bool|mixed|string|void
     */
    function b3_get_account_rejected_message() {
        $message = get_option( 'b3_account_rejected_message', false );
        if ( ! $message ) {
            $message = b3_default_account_rejected_message() . "\n";
        }

        return $message;

    }


    /**
     * Get password subject (user)
     *
     * @return bool|mixed|string|void
     */
    function b3_get_password_reset_subject() {
        $subject = get_option( 'b3_forgot_password_subject', false );
        if ( ! $subject ) {
            $subject = b3_default_forgot_password_subject();
        }

        return $subject;

    }


    /**
     * Get account rejected message (user)
     *
     * @return bool|mixed|string|void
     */
    function b3_get_password_reset_message() {
        $message = get_option( 'b3_forgot_password_message', false );
        if ( ! $message ) {
            $message = b3_default_forgot_password_message() . "\n";
        }

        return $message;

    }


    /**
     * Return new user subject (admin)
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_new_user_subject() {
        $b3_new_user_subject = get_option( 'b3_new_user_subject', false );
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
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_new_user_message() {

        $new_user_message = get_option( 'b3_new_user_message', false );
        if ( false != $new_user_message ) {
            $message = $new_user_message;
        } else {
            $message = b3_default_new_user_admin_message();
        }

        return $message;

    }


    /**
     * Return user email logo and default logo if false
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_login_logo() {
        $custom_logo = get_option( 'b3_login_logo', false );

        if ( false != $custom_logo ) {
            $login_logo = $custom_logo;
        } else {
            $login_logo = b3_default_login_logo();
        }

        return $login_logo;
    }


    /**
     * Return user email logo and default logo if false
     *
     * @return bool|false|mixed|string|void
     */
    function b3_get_main_logo() {
        $custom_logo = get_option( 'b3_main_logo', false );

        if ( false != $custom_logo ) {
            $main_logo = $custom_logo;
        } else {
            $main_logo = b3_default_main_logo();
        }

        return $main_logo;
    }


    /**
     * @return bool|mixed|string|void
     */
    function b3_get_privacy_text() {

        $privacy_text = get_option( 'b3_privacy_text', false );
        if ( false != $privacy_text ) {
            $message = stripslashes( $privacy_text );
        } else {
            $message = b3_default_privacy_text();
        }

        return $message;

    }


    /**
     * Return links below a (public) form
     *
     * @TODO: show with use of filter (next to setting)
     */
    function b3_get_form_links( $current_form ) {

        $output = '';
        if ( true != get_option( 'b3_disable_action_links', false ) ) {
            $page_types = [];

            switch( $current_form ) {

                case 'login':
                    $page_types[ 'forgotpassword' ] = [
                        'title' => esc_html__( 'Forgot password', 'b3-onboarding' ),
                        'link'  => b3_get_forgotpass_id( true )
                    ];
                    if ( 'closed' != get_option( 'b3_registration_type', false ) ) {
                        $page_types[ 'register' ] = [
                            'title' => esc_html__( 'Register', 'b3-onboarding' ),
                            'link'  => b3_get_register_id( true )
                        ];
                    }
                    break;

                case 'register':
                    $page_types[ 'login' ] = [
                        'title' => esc_html__( 'Log In', 'b3-onboarding' ),
                        'link'  => b3_get_login_id( true )
                    ];
                    $page_types[ 'fogotpassword' ] = [
                        'title' => esc_html__( 'Forgot password', 'b3-onboarding' ),
                        'link'  => b3_get_forgotpass_id( true )
                    ];
                    break;

                case 'forgotpassword':
                    $page_types[ 'login' ] = [
                        'title' => esc_html__( 'Log In', 'b3-onboarding' ),
                        'link'  => b3_get_login_id( true )
                    ];
                    if ( 'closed' != get_option( 'b3_registration_type', false ) ) {
                        $page_types[ 'register' ] = [
                            'title' => esc_html__( 'Register', 'b3-onboarding' ),
                            'link'  => b3_get_register_id( true )
                        ];
                    }
                    break;

                default:
                    break;
            }

            if ( count( $page_types ) > 0 ) {
                ob_start();
                echo '<ul class="b3_form-links"><!--';
                foreach( $page_types as $key => $values ) {
                    echo '--><li><a href="' . $values[ 'link' ] . '" rel="nofollow">' . $values[ 'title' ] . '</a></li><!--';
                }
                echo '--></ul>';
                $output = ob_get_clean();
            }

        }

        return $output;
    }


    /**
     * Return unique password reset link
     *
     * @TODO: check where this was intended to use
     *
     * @param $key
     * @param $user_login
     *
     * @return string
     */
    function b3_get_password_reset_link( $key, $user_login ) {
        // @TODO: make URL nicer
        $url = network_site_url( "wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode( $user_login ), 'login' );

        return $url;
    }


    /**
     * Get a unique activation url for a user
     *
     * @param $user
     *
     * @return string
     */
    function b3_get_activation_url( $user_data ) {

        // Generate an activation key
        $key = wp_generate_password( 20, false );

        // Set the activation key for the user
        global $wpdb;
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_data->user_login ) );

        $login_url      = wp_login_url();
        $activation_url = add_query_arg( array( 'action' => 'activate', 'key' => $key, 'user_login' => rawurlencode( $user_data->user_login ) ), $login_url );

        return $activation_url;
    }

    /**
     * Get sender email
     *
     * @return bool|mixed|void
     */
    function b3_get_notification_sender_email() {

        $sender_email = get_option( 'b3_notification_sender_email', false );
        if ( false == $sender_email ) {
            $admin_email = get_option( 'admin_email' );
            if ( false != $admin_email ) {
                $sender_email = $admin_email;
            }
        }

        return $sender_email;
    }

    /**
     * Get sender name
     *
     * @return bool|mixed|void
     */
    function b3_get_notification_sender_name() {

        $sender_name = get_option( 'b3_notification_sender_name', false );
        if ( false == $sender_name ) {
            $blog_name = get_option( 'blogname' );
            if ( false != $blog_name ) {
                $sender_name = $blog_name;
            }
        }

        return $sender_name;
    }

    /**
     * General opening of settings field
     */
    function b3_get_settings_field_open( $hide = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        echo '<div class="b3_settings-field' . $hide_class . '">';
    }

    /**
     * General opening of settings label
     */
    function b3_get_label_field_open( $hide = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        echo '<div class="b3_settings-label' . $hide_class . '">';
    }

    /**
     * Close a div.
     * This function is not really needed, but it prevents PhpStorm from throwing a ton of errors
     */
    function b3_get_close() {
        echo '</div>';
    }

    /**
     * Return submit button
     *
     * @param bool $submit_value
     */
    function b3_submit_button( $submit_value = false ) {
        if ( false == $submit_value ) {
            $submit_value = esc_attr__( 'Save settings', 'b3-onboarding' );
        }
        echo '<input class="button button-primary button--submit" type="submit" value="' . $submit_value . '" />';
    }
