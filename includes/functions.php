<?php
    
    /**
     * Output the password fields
     */
    function b3_show_password_fields() {
        
        ob_start();
        ?>
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass1"><?php _e( 'Password', 'b3-onboarding' ); ?></label>
            <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3__form--input" />
        </div>
        
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass2"><?php _e( 'Confirm Password', 'b3-onboarding' ); ?></label>
            <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3__form--input" />
        </div>
        <?php
        $results = ob_get_clean();
        
        echo $results;
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
    
        $meta_keys = array(
            'b3_account_page_id',
            'b3_approval_page_id',
            'b3_add_br_html_email', // not used yet
            'b3_dashboard_widget', // not used yet
            'b3_email_styling',
            'b3_email_template',
            'b3_first_last_name',
            'b3_first_last_required',
            'b3_forgot_password_message',
            'b3_forgot_password_subject',
            'b3_forgotpass_page_id',
            'b3_login_page_id',
            'b3_logout_page_id',
            'b3_mail_sending_method', // not used yet
            'b3_new_user_message',
            'b3_new_user_notification_addresses',
            'b3_new_user_subject',
            'b3_notification_sender_email',
            'b3_notification_sender_name',
            'b3_request_access_message',
            'b3_request_access_notification_addresses',
            'b3_request_access_subject',
            'b3_register_page_id',
            'b3_registration_type',
            'b3_restrict_admin',
            'b3_resetpass_page_id',
            'b3_themed_profile',
            'b3_sidebar_widget',
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
        
        $settings_box = array(
            array(
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ),
        );
        $request_access_box = [];
        if ( in_array( get_option( 'b3_registration_type' ), [ 'request_access', 'request_access_subdomain' ] ) ) {
            $request_access_box = array(
                array(
                    'id'    => 'request_access',
                    'title' => esc_html__( 'Request access email', 'b3-onboarding' ),
                ),
            );
        }
        $default_boxes1 = array(
            array(
                'id'    => 'welcome_email_user',
                'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            ),
            array(
                'id'    => 'new_user_admin',
                'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
            ),
        );
        $default_boxes2 = array(
            array(
                'id'    => 'forgot_password',
                'title' => esc_html__( 'Forgot password email', 'b3-onboarding' ),
            ),
            // array(
            //     'id'    => 'password_changed',
            //     'title' => esc_html__( 'Reset password email', 'b3-onboarding' ),
            // ),
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
        $email_boxes = array_merge( $settings_box, $request_access_box, $default_boxes1, $default_boxes2, $styling_boxes );
    
        if ( is_multisite() ) {
            // $email_boxes = array_merge( $email_boxes, $default_boxes2 );
        }
        
        return $email_boxes;
    
    }
    
    
    /**
     * Return registration options
     *
     * @return array
     */
    function b3_registration_types() {
        $registration_options = array();

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

        $multisite_options1 = array(
            array(
                'value' => 'request_access',
                'label' => esc_html__( 'Request access (admin approval)', 'b3-onboarding' ),
            ),
        );
        $multisite_options2 = array(
            array(
                'value' => 'request_access_subdomain',
                'label' => esc_html__( 'Request access (admin approval + user domain request)', 'b3-onboarding' ),
            ),
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
            $registration_options = array_merge( $closed_option, $registration_options, $normal_options );
        } else {
            $mu_registration = get_site_option( 'registration' );
            if ( ! is_main_site() ) {
                if ( 'none' != $mu_registration ) {
                    $registration_options = array_merge( $closed_option, $normal_options );
                }
            } else {
                if ( 'none' != $mu_registration ) {
                    $registration_options = array_merge( $closed_option, $multisite_options2 );
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
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_subdomain"><?php esc_html_e( 'Desired (sub) domain', 'b3-user-register' ); ?></label>
                <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3__form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-user-register' ); ?>        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
            </div>
            <?php
            $output = ob_get_clean();
    
            echo $output;
        }
        
    }


    /**
     * Return default email styling
     *
     * @return false|string
     */
    function b3_default_email_styling() {
        $default_css = file_get_contents( dirname(__FILE__) . '/default-styling.css' );
        
        return $default_css;
    }
    
    
    /**
     * Return default email template
     *
     * @return false|string
     */
    function b3_default_email_template() {
        $default_template = file_get_contents( dirname(__FILE__) . '/default-template.html' );
        
        return $default_template;
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
            return sprintf( esc_html__( 'Welcome %s, your registration to %s was successful. You can set your password here: %s.', 'b3-onboarding' ), $user->user_login, $blogname, get_permalink( b3_get_forgotpass_id() ) );
        }
    }
    
    
    /**
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
    
        $email_styling  = get_option( 'b3_email_styling', false );
        $email_template = get_option( 'b3_email_template', false );
        if ( false != $email_styling && false != $email_template ) {
            // replace email variables
            $vars = [];
            if ( strpos( $message, '%registration_date%' ) !== false ) {
                $vars[ 'registration_date' ] = $user->user_registered;
        
            }
            $message = strtr( $message, b3_replace_email_vars( $vars ) );
        }
        
        return $message;
    
    }
    
    
    /**
     * Return request access email subject (user)
     *
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_get_request_access_subject( $blogname ) {
        $b3_request_access_subject = get_option( 'b3_request_access_subject', false );
        if ( $b3_request_access_subject ) {
            return $b3_request_access_subject;
        } else {
            // default
            return sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), $blogname );
        }
    }
    
    
    /**
     * Request access email message (user)
     *
     * @param $blogname
     * @param $user
     *
     * @return mixed|string
     */
    function b3_get_request_access_message( $blogname, $user ) {
        $b3_request_access_message = get_option( 'b3_request_access_message', false );
        if ( $b3_request_access_message ) {
            return $b3_request_access_message;
        } else {
            // default
            return sprintf( esc_html__( "You have successfully requested access to %s. We'll inform you by email.", "b3-onboarding" ), $blogname );

        }
    }
    
    
    /**
     * Default new user message (admin)
     *
     * @return string
     */
    function b3_default_new_user_admin_message() {
    
        $admin_message = sprintf( __( 'A new user registered at %s on %s', 'b3-onboarding' ), get_option( 'blogname' ), '%registration_date%' ) . "\r\n\r\n";
        $admin_message .= sprintf( __( 'Login: %s', 'b3-onboarding' ), '%user_login%' ) . "\r\n\r\n";
        $admin_message .= sprintf( __( 'IP: %s', 'b3-onboarding' ), '%user_ip%' ) . "\r\n\r\n";
    
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
    function b3_default_forgot_password_message() {
        
        // Create new message (text)
        $default_message = __( 'Hello!', 'b3-onboarding' ) . "\r\n\r\n";
        $default_message .= __( 'Someone requested a password reset for the account using this email address.', 'b3-onboarding' ) . "\r\n\r\n";
        $default_message .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\r\n\r\n";
        $default_message .= __( "To reset your password to something you'd like, visit the following address:", "b3-onboarding" ) . "\r\n\r\n";
        $default_message .= "%reset_url%\r\n\r\n";
        $default_message .= __( 'Thanks!', 'b3-onboarding' ) . "\r\n";
        
        return $default_message;
    
    }
    
    
    /**
     * Replace vars in email
     *
     * @param $vars
     *
     * @return array
     */
    function b3_replace_email_vars( $vars ) {
    
        $replacements = array(
            '%blog_name%'         => get_option( 'blogname' ),
            '%email_styling%'     => ( false != get_option( 'b3_email_styling' ) ) ? get_option( 'b3_email_styling' ) : b3_default_email_styling(),
            '%home_url%'          => get_home_url(),
            '%registration_date%' => ( isset( $vars[ 'registration_date' ] ) ) ? $vars[ 'registration_date' ] : false,
            '%reset_url%'         => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
            '%user_ip%'           => $_SERVER[ 'REMOTE_ADDR' ] ? : ( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ? : $_SERVER[ 'HTTP_CLIENT_IP' ] ),
        );
        
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
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="b3_first_name" id="b3_first_name" class="b3__form--input"<?php echo $required; ?>>
            </div>
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
                <input type="text" name="b3_last_name" id="b3_last_name" class="b3__form--input"<?php echo $required; ?>>
            </div>
        <?php
        $output = ob_get_clean();
        
        echo $output;
    }
    
    /**
     * Render login form
     */
    function b3_render_login_form() {
        
        ob_start();
        ?>
        <form name="loginform" id="loginform" action="http://bedrock.beee/wp/wp-login.php" method="post">
            <table class="b3__table b3__table--login" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <label for="user_login">Email address</label>
                    </td>
                    <td>
                        <input type="text" name="log" id="user_login" class="input" value="" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="user_pass">Password</label>
                    </td>
                    <td>
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember Me</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In">
                        <input type="hidden" name="redirect_to" value="">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php echo b3_form_links( 'login' ); ?>
                    </td>
                </tr>
            </table>
        </form>
        <?php
        $output = ob_get_clean();
        
        return $output;
    }
    
    /**
     * Return links below a (public) form
     */
    function b3_form_links( $current_form ) {
    
        $output = '';
        $page_types = [];
        switch ( $current_form ) {
    
            case 'login':
                $page_types[ 'lostpassword' ] = [
                    'title' => 'Lost password',
                    'link'  => b3_get_forgotpass_id( true )
                ];
                if ( 'closed' != get_option( 'registration_type' ) ) {
                    $page_types[ 'register' ] = [
                        'title' => __( 'Register', 'b3-onboarding' ),
                        'link'  => b3_get_register_id( true )
                    ];
                }
                break;
                
            case 'register':
                $page_types[ 'login' ] = [
                    'title' => __( 'Log In', 'b3-onboarding' ),
                    'link'  => b3_get_login_id( true )
                ];
                $page_types[ 'forgotpass' ] = [
                    'title' => __( 'Lost password', 'b3-onboarding' ),
                    'link'  => b3_get_forgotpass_id( true )
                ];
                break;
                
            default:
                break;
        }
        
        if ( count( $page_types ) > 0 ) {
            ob_start();
            echo '<ul class="b3__form-links"><!--';
            foreach( $page_types as $key => $values ) {
                echo '--><li><a href="' . $values[ 'link' ] . '" rel="nofollow">' . $values[ 'title' ] . '</a></li><!--';
            }
            echo '--></ul>';
            $output = ob_get_clean();
        }
        
        return $output;
    }
