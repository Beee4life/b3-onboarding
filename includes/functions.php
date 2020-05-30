<?php

    /**
     * Output the password fields
     * Not used
     *
     * Do I still need this ?
     */
    function b3_show_password_fields() {

        ob_start();
        ?>
        <p class="b3_message">
            <?php esc_html_e( "If you triggered this setting manually, be aware that it's not working yet.", "b3-onboarding" ); ?>

        </p>
        <div class="b3_form-element b3_form-element--register">
            <label class="b3_form-label" for="pass1"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
            <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3_form--input" />
        </div>

        <div class="b3_form-element b3_form-element--register">
            <label class="b3_form-label" for="pass2"><?php esc_html_e( 'Confirm Password', 'b3-onboarding' ); ?></label>
            <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3_form--input" />
        </div>
        <?php
        $results = ob_get_clean();

        return $results;
    }

    /**
     * Output any hidden fields
     */
    function b3_hidden_fields_registration_form() {

        $hidden_fields = '';
        $hidden_field_values = apply_filters( 'b3_do_filter_hidden_fields_values', [] );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            foreach( $hidden_field_values as $key => $value ) {
                $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
        }

        echo $hidden_fields;

    }


    /**
     * Output any extra request fields
     */
    function b3_extra_fields_registration() {

        $extra_fields       = '';
        $extra_field_values = apply_filters( 'b3_add_filter_extra_fields_values', [] );
        if ( is_array( $extra_field_values ) && ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $extra_field ) {
                echo b3_render_extra_field( $extra_field );
            }
        }

        echo $extra_fields;

    }


    /**
     * Add reCAPTCHA validation (not in use yet)
     *
     * @param $recaptcha_public
     * @param string $form_type
     */
    function b3_add_captcha_registration( $recaptcha_public, $form_type = 'register' ) {

        do_action( 'b3_before_recaptcha_' . $form_type );
        ?>
        <div class="recaptcha-container">
            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
        </div>
        <p></p>
        <?php
        do_action( 'b3_after_recaptcha_' . $form_type );
    }


    /**
     * Return all custom meta keys
     *
     * @return array
     */
    function b3_get_all_custom_meta_keys() {

        // @TODO: update this list
        $meta_keys = array(
            'b3_account_approved_message',
            'b3_account_approved_subject',
            'b3_account_page_id',
            'b3_action_links',
            'b3_activate_first_last',
            // 'b3_add_br_html_email', // not used yet
            'b3_approval_page_id',
            'b3_custom_emails',
            'b3_dashboard_widget',
            'b3_disable_action_links',
            'b3_email_styling',
            'b3_email_template',
            'b3_first_last_required',
            'b3_forgot_password_message',
            'b3_forgot_password_subject',
            'b3_forgotpass_page_id',
            'b3_front_end_approval',
            'b3_login_page_id',
            'b3_logout_page_id',
            // 'b3_mail_sending_method', // not used yet
            'b3_new_user_message',
            'b3_new_user_notification_addresses',
            'b3_new_user_subject',
            'b3_notification_content_type',
            'b3_notification_sender_email',
            'b3_notification_sender_name',
            'b3_register_page_id',
            'b3_registration_type',
            'b3_request_access_message',
            'b3_request_access_notification_addresses',
            'b3_request_access_subject',
            'b3_reset_page_id',
            'b3_restrict_admin',
            'b3_sidebar_widget',
            // 'b3_themed_profile', // @TODO: remove
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
        $registration_type    = get_option( 'b3_registration_type' );
        $welcome_user_boxes   = [];

        $settings_box = array(
            array(
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ),
        );
        $request_access_box = [];
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
                    'title' => esc_html__( 'Account approved email', 'b3-onboarding' ),
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
        $email_boxes = array_merge( $settings_box, $request_access_box, $activate_email_boxes, $welcome_user_boxes, $new_user_boxes, $default_boxes2, $styling_boxes );

        return $email_boxes;

    }


    /**
     * Return registration options
     *
     * @return array
     */
    function b3_registration_types() {
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
     * Add field for subdomain when WPMU is active
     */
    function b3_add_subdomain_field() {

        if ( 'all' == get_site_option( 'registration' ) && in_array( get_option( 'b3_registration_type') , [ 'request_access_subdomain', 'ms_register_site_user' ] ) ) {
            ob_start();
            ?>
            <?php // @TODO: add more fields for Multisite ?>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_subdomain"><?php esc_html_e( 'Desired (sub) domain', 'b3-onboarding' ); ?></label>
                <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-onboarding' ); ?>        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
            </div>
            <?php
            $output = ob_get_clean();

            echo $output;
        }

    }


    function b3_get_email_styling() {
        $custom_css = get_option( 'b3_email_styling', false );

        if ( false != $custom_css ) {
            $email_style = $custom_css;
        } else {
            $email_style = b3_default_email_styling();
        }

        return $email_style;
    }


    function b3_get_email_template() {
        $custom_template = get_option( 'b3_email_template', false );

        if ( false != $custom_template ) {
            $email_template = $custom_template;
        } else {
            $email_template = b3_default_email_template();
        }

        return $email_template;
    }


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
    function b3_default_email_template() {
        $default_template = file_get_contents( dirname(__FILE__) . '/default-email-template.html' );

        return $default_template;
    }


    function b3_default_email_content() {
        $default_content = file_get_contents( dirname(__FILE__) . '/default-email-content.html' );

        return $default_content;
    }


    /**
     * Return email activation subject (user)
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_email_activation_subject( $blogname ) {
        $b3_email_activation_subject = get_option( 'b3_email_activation_subject', false );
        if ( $b3_email_activation_subject ) {
            return $b3_email_activation_subject;
        } else {
            return esc_html__( 'Activate your account', 'b3-onboarding' );
        }
    }

    /**
     * Return email activation message (user)
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_email_activation_message( $blogname, $user ) {
        $b3_email_activation_message = get_option( 'b3_email_activation_message', false );
        if ( $b3_email_activation_message ) {
            return $b3_email_activation_message;
        } else {
            if ( false == $user ) {
                $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
                $message .= '<br /><br />' . "\n";
                $message .= sprintf( __( 'your registration to %s was successful.', 'b3-onboarding' ), $blogname ) . "\n";
                $message .= '<br /><br />' . "\n";
                $message .= sprintf( __( 'You only need to confirm your email address through <a href="%s">this link</a>.', 'b3-onboarding' ), '%activation_url%' ) . "\n";
                $message .= '<br /><br />' . "\n";
                $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
                $message .= '<br /><br />' . "\n";
                $message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
                $message .= '<br /><br />' . "\n";

                return $message;

            } else {
                return sprintf( esc_html__( 'Welcome %s, your registration to %s was successful. You only need to confirm your email address through this link %s.', 'b3-onboarding' ), $user->user_login, $blogname, b3_get_activation_url( $user ) );
            }
        }
    }

    /**
     * Return welcome user subject (user)
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_subject( $blogname ) {
        $b3_welcome_user_subject = get_option( 'b3_welcome_user_subject', false );
        if ( $b3_welcome_user_subject ) {
            return $b3_welcome_user_subject;
        } else {
            return sprintf( esc_html__( 'Welcome to %s', 'b3-onboarding' ), $blogname );
        }
    }

    /**
     * Return welcome user message (user)
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_welcome_user_message( $blogname, $user ) {
        $b3_welcome_user_message = get_option( 'b3_welcome_user_message', false );
        if ( $b3_welcome_user_message ) {
            return $b3_welcome_user_message;
        } else {

            $message = sprintf( esc_html__( 'Welcome %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= sprintf( __( 'your registration to %s was successful.', 'b3-onboarding' ), $blogname ) . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= sprintf( esc_html__( 'You can set your password here: %s.', 'b3-onboarding' ), b3_get_forgotpass_url() ) . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
            $message .= '<br /><br />' . "\n";

            return $message;
        }
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
            if ( false != get_option( 'b3_request_access_notification_addresses' ) ) {
                $email_addresses = get_option( 'b3_request_access_notification_addresses' );
            }
        } elseif ( 'open' == $registration_type ) {
            if ( false != get_option( 'b3_new_user_notification_addresses' ) ) {
                $email_addresses = get_option( 'b3_new_user_notification_addresses' );
            }
        }

        return $email_addresses;

    }


    /**
     * Get email subject for request access (admin)
     *
     * @return mixed|string
     */
    function b3_request_access_subject_admin() {
        $subject = get_option( 'b3_request_access_subject_admin', false );
        if ( ! $subject ) {
            $subject = esc_html__( 'A new user requests access', 'b3-onboarding' );
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
            $message = sprintf( __( 'A new user has requested access. You can approve/deny him/her on the "<a href="%s">User approval</a>" page.', 'b3-onboarding' ), esc_url( admin_url( 'admin.php?page=b3-user-approval' ) ) );
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
            $subject = sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), get_option( 'blogname' ) );
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
            $message = sprintf( __( "You have successfully requested access for %s. We'll inform you about the outcome.", "b3-onboarding" ), get_option( "blogname" ) );
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
            $subject = sprintf( __( 'Account approved', 'b3-onboarding' ) );
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
            $message = sprintf( esc_html__( 'Welcome to %s. Your account has been approved and you can now set your password on %s.', 'b3-onboarding' ), get_option( 'blogname' ), esc_url( b3_get_forgotpass_url() ) );
        }

        return $message;

    }


    /**
     * Get email subject for account approved
     *
     * @return mixed|string
     */
    function b3_get_account_activated_subject() {
        $subject = get_option( 'b3_account_activated_subject', false );
        if ( ! $subject ) {
            $subject = sprintf( __( 'Account activated', 'b3-onboarding' ) );
        }

        return $subject;

    }


    /**
     * Get email message for account approved
     *
     * @return mixed|string
     */
    function b3_get_account_activated_message() {
        $message = get_option( 'b3_account_activated_message', false );
        if ( ! $message ) {

            $message = sprintf( esc_html__( 'Hi %s', 'b3-onboarding' ), '%user_login%' ) . ',' . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= sprintf( __( 'you have confirmed your email address and can now set your password through <a href="%s">this link</a>.', 'b3-onboarding' ), '%forgotpass_url%' ) . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
            $message .= '<br /><br />' . "\n";
            $message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
            $message .= '<br /><br />' . "\n";

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
    function b3_get_new_user_subject( $blogname ) {
        $b3_new_user_subject = get_option( 'b3_new_user_subject', false );
        if ( $b3_new_user_subject ) {
            return $b3_new_user_subject;
        } else {
            return sprintf( esc_html__( 'New user at %s', 'b3-onboarding' ), $blogname );
        }
    }


    /**
     * Return new user message (admin)
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_new_user_message( $blogname, $user ) {

        $b3_new_user_message = get_option( 'b3_new_user_message', false );
        if ( false != $b3_new_user_message ) {
            $message = $b3_new_user_message;
        } else {
            $message = b3_default_new_user_admin_message();
        }

        $email_styling  = b3_get_email_styling();
        $email_template = b3_get_email_template();
        if ( false != $email_styling && false != $email_template ) {
            // replace email variables
            $vars = [];
            if ( strpos( $message, '%registration_date%' ) !== false ) {
                $date_time_format            = get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' );
                $vars[ 'registration_date' ] = date( $date_time_format, strtotime( $user->user_registered ) );
            }
            $message = strtr( $message, b3_replace_email_vars( $vars ) );
        }

        return $message;

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
        $admin_message .= '<br /><br />' . "\n";

        return $admin_message;
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
    function b3_default_forgot_password_message( $key, $user_login ) {

        // Create new message (text)
        $default_message = __( 'Hi', 'b3-onboarding' ) . ",\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( 'Someone requested a password reset for the account using this email address.', 'b3-onboarding' ) . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= sprintf( __( 'To (re)set your password, go to <a href="%s">this page</a>.', 'b3-onboarding' ), b3_get_password_reset_link( $key, $user_login ) ) . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= __( 'Greetings', 'b3-onboarding' ) . ',' . "\n";
        $default_message .= '<br /><br />' . "\n";
        $default_message .= sprintf( __( 'The %s crew', 'b3-onboarding' ), get_option( 'blogname' ) ) . "\n";
        // $default_message .= '<br /><br />';

        return $default_message;

    }


    /**
     * Replace vars in email
     *
     * @param $vars
     *
     * @return array
     */
    function b3_replace_email_vars( $vars, $activation = false ) {

        $user_data = false;
        if ( is_user_logged_in() ) {
            $user_data = get_userdata( get_current_user_id() );
        }
        $replacements = array(
            '%blog_name%'         => get_option( 'blogname' ),
            '%email_styling%'     => ( false != get_option( 'b3_email_styling' ) ) ? get_option( 'b3_email_styling' ) : b3_default_email_styling(),
            '%home_url%'          => get_home_url(),
            '%logo%'              => apply_filters( 'b3_email_logo', '' ),
            '%registration_date%' => ( isset( $vars[ 'registration_date' ] ) ) ? $vars[ 'registration_date' ] : false,
            '%reset_url%'         => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
            '%user_ip%'           => $_SERVER[ 'REMOTE_ADDR' ] ? : ( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ? : $_SERVER[ 'HTTP_CLIENT_IP' ] ),
            '%user_login%'        => ( false != $user_data ) ? $user_data->user_login : false,
        );
        if ( false != $activation ) {
            $replacements[ '%activation_url%' ] = b3_get_activation_url( $user_data );
        }

        return $replacements;
    }


    /**
     * Replace vars in email template
     *
     * @param bool $message
     *
     * @return bool|string
     */
    function b3_replace_template_styling( $message = false ) {

        if ( false != $message ) {
            // get $message from function
            $email_styling  = ( get_option( 'b3_email_styling', false ) ) ? : b3_default_email_styling();
            $email_template = ( get_option( 'b3_email_template', false ) ) ? : b3_default_email_template();

            if ( false != $email_styling && false != $email_template ) {
                // replace email_template + styling
                $replace_vars = [
                    '%email_styling%' => $email_styling,
                    '%email_message%' => $message,
                ];
                $message = strtr( $email_template, $replace_vars );
            }
        }

        return $message;
    }

    function b3_first_last_name_fields() {

        ob_start();
        ?>
            <?php $required = ( true == get_option( 'b3_first_last_required', false ) ) ? ' required="required"' : false; ?>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="first_name" id="b3_first_name" class="b3_form--input" value="<?php echo ( defined( 'WP_TESTING' ) ) ? 'First name' : false; ?>"<?php echo $required; ?>>
            </div>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="last_name" id="b3_last_name" class="b3_form--input" value="<?php echo ( defined( 'WP_TESTING' ) ) ? 'Last name' : false; ?>"<?php echo $required; ?>>
            </div>
        <?php
        $output = ob_get_clean();

        echo $output;
    }

    /**
     * Render login form
     */
    function b3_render_login_form( $attributes ) {

        ob_start();
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Return links below a (public) form
     */
    function b3_form_links( $current_form ) {

        $output = '';
        if ( true != get_option( 'b3_disable_action_links' ) ) {
            $page_types = [];

            switch( $current_form ) {

                case 'login':
                    $page_types[ 'forgotpassword' ] = [
                        'title' => esc_html__( 'Forgot password', 'b3-onboarding' ),
                        'link'  => b3_get_forgotpass_id( true )
                    ];
                    if ( 'closed' != get_option( 'registration_type' ) ) {
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
                    if ( 'closed' != get_option( 'registration_type' ) ) {
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
     * Return lost pass URL
     *
     * @return false|string
     */
    function b3_get_forgotpass_url() {

        $lost_password_id = get_option( 'b3_forgotpass_page_id' );
        if ( $lost_password_id ) {
            $url = get_permalink( $lost_password_id );
        } else {
            $url = wp_lostpassword_url();
        }

        return $url;
    }


    /**
     * Return reset pass URL
     *
     * @return false|string
     */
    function b3_get_resetpass_url() {

        $reset_password_id = get_option( 'b3_resetpass_page_id' );
        if ( false != $reset_password_id ) {
            $url = get_permalink( $reset_password_id );
        } else {
            $url = home_url( 'reset-password' );
        }

        return $url;
    }


    /**
     * Return unique password reset link
     *
     * @param $key
     * @param $user_login
     *
     * @return string
     */
    function b3_get_password_reset_link( $key, $user_login ) {
        // @TODO: make URL nicer
        $url = network_site_url( "wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";

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
        // error_log( $key );
        // error_log( $user_data->user_login );
        // die();

        $login_url      = wp_login_url();
        $activation_url = add_query_arg( array( 'action' => 'activate', 'key' => $key, 'user_login' => rawurlencode( $user_data->user_login ) ), $login_url );

        return $activation_url;
    }
