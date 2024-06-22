<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Copied from wp-login.php since we bypass it and can't hook in/piggyback on the function in this file.
     *
     * @return bool|int|string|WP_Error
     */
    function b3_retrieve_password() {
        $errors    = new WP_Error();
        $user_data = false;

        if ( empty( $_POST[ 'user_login' ] ) || ! is_string( $_POST[ 'user_login' ] ) ) {
            $errors->add( 'empty_username', sprintf( '<strong>%s</strong>: %s.', esc_html__( 'Error', 'b3-onboarding' ), esc_html__( 'Enter a username or email address', 'b3-onboarding' ) ) );
        } elseif ( strpos( $_POST['user_login'], '@' ) ) {
            $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
            if ( empty( $user_data ) ) {
                $errors->add( 'invalid_email', sprintf( '<strong>%s</strong>: %s.', esc_html__( 'Error', 'b3-onboarding' ), esc_html__( 'There is no account with that username or email address', 'b3-onboarding' ) ) );
            }
        } else {
            $login     = trim( wp_unslash( $_POST['user_login'] ) );
            $user_data = get_user_by( 'login', $login );
        }

        /**
         * Fires before errors are returned from a password reset request.
         *
         * @since 2.1.0
         * @since 4.4.0 Added the `$errors` parameter.
         * @since 5.4.0 Added the `$user_data` parameter.
         *
         * @param WP_Error $errors A WP_Error object containing any errors generated
         *                         by using invalid credentials.
         * @param WP_User|false    WP_User object if found, false if the user does not exist.
         */
        do_action( 'lostpassword_post', $errors, $user_data );

        if ( $errors->has_errors() ) {
            return $errors;
        }

        if ( ! $user_data ) {
            $errors->add( 'invalidcombo', sprintf( '<strong>%s</strong>: %s.', esc_html__( 'Error', 'b3-onboarding' ), esc_html__( 'There is no account with that username or email address', 'b3-onboarding' ) ) );
            return $errors;
        }

        // Redefining user_login ensures we return the right case in the email.
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key        = get_password_reset_key( $user_data );

        if ( is_wp_error( $key ) ) {
            return $key;
        }

        if ( is_multisite() ) {
            $site_name = get_network()->site_name;
        } else {
            /*
             * The blogname option is escaped with esc_html on the way into the database
             * in sanitize_option we want to reverse this for the plain text arena of emails.
             */
            $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
        }

        $message = esc_html__( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
        /* translators: %s: Site name. */
        $message .= sprintf( esc_html__( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
        /* translators: %s: User login. */
        $message .= sprintf( esc_html__( 'Username: %s' ), $user_login ) . "\r\n\r\n";
        $message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
        $message .= esc_html__( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
        $message .= network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n";

        /* translators: Password reset notification email subject. %s: Site title. */
        $title = sprintf( __( '[%s] Password Reset' ), $site_name );


        /**
         * Filters the subject of the password reset email.
         *
         * @since 2.8.0
         * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
         *
         * @param string  $title      Default email title.
         * @param string  $user_login The username for the user.
         * @param WP_User $user_data  WP_User object.
         */
        $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

        /**
         * Filters the message body of the password reset mail.
         *
         * If the filtered message is empty, the password reset email will not be sent.
         *
         * @since 2.8.0
         * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
         *
         * @param string  $message    Default mail message.
         * @param string  $key        The activation key.
         * @param string  $user_login The username for the user.
         * @param WP_User $user_data  WP_User object.
         */
        $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

        if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
            $errors->add(
                'retrieve_password_email_failure',
                sprintf( '<strong>%s</strong>: %s',
                    esc_html__( 'Error', 'b3-onboarding' ),
                    sprintf( __( 'The email could not be sent. Your site may not be correctly configured to send emails. %s.' ),
                        sprintf( '<a href="%s">%s</a>',
                            esc_url( 'https://wordpress.org/support/article/resetting-your-password/' ),
                            esc_html__( 'Get support for resetting your password', 'b3-onboarding' ) ) )
                )
            );
            
            return $errors;
        }

        return true;
    }


    /**
     * For email override in new user + blog
     *
     * @param $domain
     *
     * @return false|int
     */
    function b3_get_signup_id( $domain ) {
        if ( $domain ) {
            $blog_id = get_blog_id_from_url( $domain );
            if ( false != $blog_id ) {
                return $blog_id;
            }
        }

        return false;
    }


    /**
     * Get admin tabs
     *
     * @return array[]
     */
    function b3_get_admin_tabs() {
        $tabs = [
            [
                'id'      => 'registration',
                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                'content' => b3_render_tab_content( 'registration' ),
                'icon'    => 'shield',
            ],
        ];
        
        $tabs[] = [
            'id'      => 'emails',
            'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'emails' ),
            'icon'    => 'email',
        ];
        if ( get_option( 'b3_activate_custom_emails' ) ) {
            $tabs[] = [
                'id'      => 'template',
                'title'   => esc_html__( 'Template', 'b3-onboarding' ),
                'content' => b3_render_tab_content( 'template' ),
                'icon'    => 'admin-customizer',
            ];
        }
        
        if ( is_main_site() ) {
            if ( ! is_multisite() ) {
                $tabs[] = [
                    'id'      => 'users',
                    'title'   => esc_html__( 'Users', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'users' ),
                    'icon'    => 'admin-users',
                ];
            }
            
            if ( true == get_option( 'b3_activate_recaptcha' ) ) {
                $tabs[] = [
                    'id'      => 'recaptcha',
                    'title'   => esc_html__( 'reCaptcha', 'b3-onboarding' ),
                    'content' => b3_render_tab_content( 'recaptcha' ),
                    'icon'    => 'plus-alt',
                ];
            }
        }
        
        $tabs[] = [
            'id'      => 'pages',
            'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'pages' ),
            'icon'    => 'admin-page',
        ];
        
        $tabs[] = [
            'id'      => 'settings',
            'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
            'content' => b3_render_tab_content( 'settings' ),
            'icon'    => 'admin-generic',
        ];
        
        return $tabs;
    }
    
    
    /**
     * Checks that the reCAPTCHA parameter (both versions) sent with the registration
     * request is valid.
     *
     * @return bool True if the CAPTCHA is OK, otherwise false.
     */
    function b3_verify_recaptcha() {
        if ( isset ( $_POST[ 'g-recaptcha-response' ] ) ) {
            $recaptcha_response = $_POST[ 'g-recaptcha-response' ];
        } else {
            return false;
        }

        $recaptcha_secret = apply_filters( 'b3_recaptcha_secret', get_option( 'b3_recaptcha_secret' ) );
        $success          = false;
        if ( false != $recaptcha_secret ) {
            $response = wp_remote_post(
                'https://www.google.com/recaptcha/api/siteverify', [
                    'body' => [
                        'secret'   => $recaptcha_secret,
                        'response' => $recaptcha_response,
                    ],
                ] );

            $response_body = wp_remote_retrieve_body( $response );
            $response_code = wp_remote_retrieve_response_code( $response );

            if ( 200 == $response_code && $response && is_array( $response ) ) {
                $decoded_response = json_decode( $response_body );
                $success          = $decoded_response->success;
            }
        }

        return $success;
    }


    /**
     * Get email preview link
     *
     * @param $id
     *
     * @return false|string
     */
    function b3_get_preview_link( $id ) {
        if ( $id ) {
            return sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', esc_url( B3OB_PLUGIN_SETTINGS . '&preview=' . $id ), esc_html__( 'Preview', 'b3-onboarding' ) );
        }

        return false;
    }


    /**
     * Get plugin file (from name)
     *
     * @param $plugin_name
     *
     * @return int|string|null
     */
    function b3_get_plugin_file( $plugin_name ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        foreach( get_plugins() as $plugin_file => $plugin_info ) {
            if ( $plugin_info[ 'Name' ] === $plugin_name ) {
                return $plugin_file;
            }
        }

        return null;
    }


    /**
     * Set default settings
     *
     * @since 2.0.0
     */
    function b3_set_default_settings( $blog_id = false ) {
        if ( false != $blog_id ) {
            switch_to_blog( $blog_id );
        }
        $plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . b3_get_plugin_file( 'B3 OnBoarding' ) );
        update_option( 'b3ob_version', $plugin_data[ 'Version' ], false );
        update_option( 'b3_disable_admin_notification_password_change', 1, false );
        update_option( 'b3_logo_in_email', 1, false );

        if ( ! is_multisite() ) {
            update_option( 'b3_dashboard_widget', 1, false );
            update_option( 'b3_hide_admin_bar', 1, false );
            update_option( 'users_can_register', 0 );

            $restrict_admin = get_option( 'b3_restrict_admin' );
            if ( false == $restrict_admin || is_array( $restrict_admin ) && empty( $restrict_admin ) ) {
                update_option( 'b3_restrict_admin', [ 'subscriber', 'b3_activation', 'b3_approval' ], false );
            }

        } elseif ( is_main_site() && false == $blog_id ) {
            update_option( 'b3_dashboard_widget', 1, false );
            update_site_option( 'registrationnotification', 'no' );
        }
        
        if ( false == get_option( 'b3_registration_type' ) ) {
            if ( ! is_multisite() ) {
                update_option( 'b3_registration_type', 'none', false );
            } else {
                if ( is_main_site() && false == $blog_id ) {
                    update_option( 'b3_registration_type', get_site_option( 'registration' ), false );
                }
            }
        }
        
        if ( false != get_option( 'wp_page_for_privacy_policy' ) ) {
            update_option( 'b3_privacy_page_id', get_option( 'wp_page_for_privacy_policy' ), false );
        }

        if ( false != $blog_id ) {
            restore_current_blog();
        }
    }


    /**
     * Get all possible template locations
     *
     * @since 3.2.0
     *
     * @return string[]
     */
    function b3_get_template_paths() {
        $stylesheet_directory = trailingslashit( get_stylesheet_directory() );
        $template_directory   = trailingslashit( get_template_directory() );
        
        $template_paths = [
            $stylesheet_directory . 'b3-onboarding/',
            $stylesheet_directory . 'plugins/b3-onboarding/',
            $template_directory . 'b3-onboarding/',
            $template_directory . 'plugins/b3-onboarding/',
            trailingslashit( B3OB_PLUGIN_PATH ) . 'templates/',
        ];
        
        return $template_paths;
    }


    /**
     * Locate file in possible template locations
     *
     * @since 3.2.0
     *
     * @param $template_name
     *
     * @return false|string
     */
    function b3_locate_template( $template_name ) {
        foreach( b3_get_template_paths() as $location ) {
            if ( file_exists( $location . $template_name . '.php' )) {
                return $location . $template_name . '.php';
            }
        }

        return false;
    }


    /**
     * Render template
     *
     * @since 3.2.0
     *
     * @param $template_name
     * @param array $attributes
     * @param false $current_user
     */
    function b3_get_template( $template_name, $attributes = [], $current_user = false ) {
        if ( $template_name ) {
            $template = b3_locate_template( $template_name );

            do_action( 'b3_do_before_template', $template_name );
            if ( file_exists( $template ) ) {
                include $template;
            }
            do_action( 'b3_do_after_template', $template_name );
        }
    }


    /**
     * New function to do all replacements in 1 function
     *
     * @since 3.8.0
     *
     * @param $type
     * @param $vars
     * @param $activation
     *
     * @return array
     * @throws Exception
     */
    function b3_get_replacement_vars( $type = 'message', $vars = [], $activation = false ) {
        $replacements = [];
        $user_data    = false;

        if ( isset( $vars[ 'user_data' ] ) ) {
            $user_data = $vars[ 'user_data' ];
        } elseif ( is_user_logged_in() ) {
            $user_data = get_userdata( get_current_user_id() );
            if ( false != $user_data ) {
                $vars[ 'user_data' ] = $user_data;
            }
        }
        
        $blog_id    = ( isset( $vars[ 'site' ]->blog_id ) ) ? $vars[ 'site' ]->blog_id : get_current_blog_id();
        $user_login = false;

        if ( '1' == get_option( 'b3_register_email_only' ) && isset( $user_data->user_login ) ) {
            $user_login = $user_data->user_login;
        } elseif ( isset( $user_data->data->user_login ) ) {
            $user_login = $user_data->data->user_login;
        }

        if ( isset( $vars[ 'registration_date' ] ) ) {
            $registration_date_gmt = $vars[ 'registration_date' ];
        } elseif ( isset( $vars[ 'user_data' ]->user_registered ) ) {
            $registration_date_gmt = $vars[ 'user_data' ]->user_registered;
        } else {
            $registration_date_gmt = false;
        }
        if ( $registration_date_gmt ) {
            $local_registration_date = b3_get_local_date_time( $registration_date_gmt );
        }

        if ( isset( $vars[ 'blog_id' ] ) ) {
            switch_to_blog( $vars[ 'blog_id' ] );
            $replacements[ '%site_name%' ] = get_option( 'blogname' );
            restore_current_blog();
        }
        
        // @TODO: merge $replacements
        switch( $type ) {
            case 'message':
                $replacements = [
                    '%account_page%' => esc_url( b3_get_account_url() ),
                    '%blog_name%'    => ( is_multisite() ) ? get_blog_option( $blog_id, 'blogname' ) : get_option( 'blogname' ),
                    '%home_url%'     => get_home_url( $blog_id, '/' ),
                    '%login_url%'    => esc_url( b3_get_login_url() ),
                    '%logo%'         => esc_url( b3_get_main_logo() ),
                    '%lostpass_url%' => b3_get_lostpassword_url(),
                    '%reset_url%'    => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
                    '%user_ip%'      => b3_get_user_ip(),
                    '%user_login%'   => $user_login,
                ];
                if ( isset( $local_registration_date ) ) {
                    $replacements[ '%registration_date%' ] = $local_registration_date;
                }
                break;
            case 'subject':
                $replacements = [
                    '%blog_name%'    => ( is_multisite() ) ? get_blog_option( $blog_id, 'blogname' ) : get_option( 'blogname' ),
                    '%network_name%' => get_site_option( 'site_name' ),
                    '%user_login%'   => $user_login,
                    '%first_name%'   => ( false != $user_data ) ? $user_data->first_name : false,
                ];
                break;
            default:
                $replacements = [];
        }

        if ( is_multisite() ) {
            $options_site_url                 = esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) );
            $replacements[ '%network_name%' ] = get_site_option( 'site_name' );
            $replacements[ '%settings_url%' ] = $options_site_url;

            if ( isset( $vars[ 'blog_id' ] )  ) {
                $replacements[ '%home_url%' ]  = get_home_url( $vars[ 'blog_id' ] );
            }
            if ( isset( $vars[ 'domain' ] ) && isset( $vars[ 'path' ] )  ) {
                $replacements[ '%home_url%' ] = b3_get_protocol() . '://' . $vars[ 'domain' ] . $vars[ 'path' ];
            }
            if ( isset( $vars[ 'user_password' ] ) ) {
                $replacements[ '%user_password%' ] = $vars[ 'user_password' ];
            }
            $replacements[ 'network_name' ] = get_option( 'name' );
        }

        if ( false != $activation ) {
            if ( is_multisite() ) {
                if ( isset( $vars[ 'key' ] ) ) {
                    $activate_url                       = b3_get_login_url() . "?activate=user&key={$vars[ 'key' ]}";
                    $replacements[ '%activation_url%' ] = esc_url( $activate_url );
                }
            } else {
                $replacements[ '%activation_url%' ] = b3_get_activation_url( $user_data );
            }
        }

        return $replacements;
    }
    
    
    /**
     * Get approvement table headers
     *
     * @param $attributes
     *
     * @return array
     */
    function b3_get_approvement_table_headers( $attributes ) {
        $headers[] = ( is_multisite() ) ? esc_html__( 'Signup ID', 'b3-onboarding' ) : esc_html__( 'User ID', 'b3-onboarding' );
        
        if ( $attributes ) {
            if ( false == $attributes[ 'register_email_only' ] ) {
                $headers[] = esc_html__( 'User name', 'b3-onboarding' );
            }
            if ( false != $attributes[ 'show_first_last_name' ] ) {
                $headers[] = esc_html__( 'First name', 'b3-onboarding' );
                $headers[] = esc_html__( 'Last name', 'b3-onboarding' );
            }
        }
        $headers[] = esc_html__( 'Email', 'b3-onboarding' );
        
        if ( is_multisite() ) {
            $headers[] = esc_html__( 'Domain', 'b3-onboarding' );
            $headers[] = esc_html__( 'Site name', 'b3-onboarding' );
        }
        $headers[] = esc_html__( 'Actions', 'b3-onboarding' );
        
        return $headers;
    }
    
    
    /**
     * Render approvement table row
     *
     * @param $user
     * @param $attributes
     *
     * @return false|string
     */
    function b3_render_approvement_table_row( $user, $attributes ) {
        ob_start();
        echo '<tr>';
        echo sprintf( '<td>%s</td>', ( is_multisite() ) ? $user->signup_id : $user->ID );
        
        if ( false == $attributes[ 'register_email_only' ] ) {
            echo sprintf( '<td>%s</td>', $user->user_login );
        }
        
        if ( false != $attributes[ 'show_first_last_name' ] ) {
            if ( is_multisite() ) {
                $meta       = unserialize( $user->meta );
                $first_name = ( isset( $meta[ 'first_name' ] ) ) ? $meta[ 'first_name' ] : '';
                $last_name  = ( isset( $meta[ 'last_name' ] ) ) ? $meta[ 'last_name' ] : '';
                echo sprintf( '<td>%s</td>', $first_name );
                echo sprintf( '<td>%s</td>', $last_name );
            } else {
                echo sprintf( '<td>%s</td>', $user->first_name );
                echo sprintf( '<td>%s</td>', $user->last_name );
            }
        }
        echo sprintf( '<td>%s</td>', $user->user_email );
        if ( is_multisite() ) {
            echo sprintf( '<td>%s</td>', $user->domain );
            echo sprintf( '<td>%s</td>', $user->title );
        }
        echo '<td>';
        ?>
        <form name="b3_user_management" method="post">
            <input name="b3_manage_users_nonce" type="hidden"
                   value="<?php echo wp_create_nonce( 'b3-manage-users-nonce' ); ?>"/>
            <input name="b3_approve_user" class="button" type="submit"
                   value="<?php echo esc_attr__( 'Approve', 'b3-onboarding' ); ?>"/>
            <input name="b3_reject_user" class="button" type="submit"
                   value="<?php echo esc_attr__( 'Reject', 'b3-onboarding' ); ?>"/>
            <?php if ( is_multisite() ) { ?>
                <input name="b3_signup_id" type="hidden" value="<?php echo esc_attr( $user->signup_id ); ?>"/>
            <?php } else { ?>
                <input name="b3_user_id" type="hidden" value="<?php echo esc_attr( $user->ID ); ?>"/>
            <?php } ?>
        </form>
        <?php
        echo '</td>';
        echo '</tr>';
        $output = ob_get_clean();
        
        return $output;
    }
    
    
    /**
     * Get user IP
     *
     * @return mixed
     * @since 3.9.0
     *
     */
    function b3_get_user_ip() {
        // More info: http://itman.in/en/how-to-get-client-ip-address-in-php/
        if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
            // check ip from share internet
            $user_ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
        } elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
            // to check ip is pass from proxy
            $user_ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        } else {
            $user_ip = $_SERVER[ 'REMOTE_ADDR' ];
        }
        
        return $user_ip;
    }
