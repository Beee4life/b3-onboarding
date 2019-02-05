<?php
    
    /**
     * Redirect the user to the custom login page instead of wp-login.php.
     */
    function redirect_to_custom_login() {
        if ( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' ) {
            $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : null;
            
            if ( is_user_logged_in() ) {
                // $this->redirect_logged_in_user( $redirect_to );
                exit;
            }
            
            // The rest are redirected to the login page
            $login_url = home_url( 'login' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }
            
            wp_redirect( $login_url );
            exit;
        }
    }
    
    
    /**
     * Returns the URL to which the user should be redirected after the (successful) login.
     *
     * @param string           $redirect_to           The redirect destination URL.
     * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
     * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
     *
     * @return string Redirect URL
     */
    function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
        $redirect_url = home_url();
        
        if ( ! isset( $user->ID ) ) {
            return $redirect_url;
        }
        
        if ( user_can( $user, 'manage_options' ) ) {
            // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
            if ( $requested_redirect_to == '' ) {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $requested_redirect_to;
            }
        } else {
            // Non-admin users always go to their account page after login
            $redirect_url = home_url( 'member-account' );
        }
        
        return wp_validate_redirect( $redirect_url, home_url() );
    }
    
    
    /**
     * Redirect the user after authentication if there were any errors.
     *
     * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
     * @param string            $username   The user name used to log in.
     * @param string            $password   The password used to log in.
     *
     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
     */
    function maybe_redirect_at_authenticate( $user, $username, $password ) {
        // Check if the earlier authenticate filter (most likely,
        // the default WordPress authentication) functions have found errors
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );
                
                $login_url = home_url( 'login' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );
                
                wp_redirect( $login_url );
                exit;
            }
        }
        
        return $user;
    }
    
    
    /**
     * Returns the message body for the password reset mail.
     * Called through the retrieve_password_message filter.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        // Create new message
        $msg  = esc_html__( 'Hello!', 'b3-onboarding' ) . "\r\n\r\n";
        $msg .= sprintf( esc_html__( 'You asked us to reset your password for your account using the email address %s.', 'b3-onboarding' ), $user_login ) . "\r\n\r\n";
        $msg .= esc_html__( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\r\n\r\n";
        $msg .= esc_html__( 'To reset your password, visit the following address:', 'b3-onboarding' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
        $msg .= esc_html__( 'Thanks!', 'b3-onboarding' ) . "\r\n";
        
        return $msg;
    }
    
    
    /**
     * Redirect to custom login page after the user has been logged out.
     */
    function redirect_after_logout() {
        $redirect_url = home_url( 'login?logged_out=true' );
        wp_safe_redirect( $redirect_url );
        exit;
    }
    
    
    /**
     * Redirects the user to the custom "Forgot your password?" page instead of
     * wp-login.php?action=lostpassword.
     */
    function redirect_to_custom_lostpassword() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                // $this->redirect_logged_in_user();
                exit;
            }
            
            wp_redirect( home_url( 'lost-password' ) );
            exit;
        }
    }
    
    
    /**
     * Redirects to the custom password reset page, or the login page
     * if there are errors.
     */
    function redirect_to_custom_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            // Verify key / login combo
            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'login?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'login?login=invalidkey' ) );
                }
                exit;
            }
            
            $redirect_url = home_url( 'member-password-reset' );
            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
            
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    
    /**
     * Redirects the user to the custom registration page instead
     * of wp-login.php?action=register.
     */
    function redirect_to_custom_register() {
        if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( is_user_logged_in() ) {
                // $this->redirect_logged_in_user();
            } else {
                wp_redirect( home_url( 'register-test' ) );
            }
            exit;
        }
    }
    
    
    /**
     * Handles the registration of a new user.
     *
     * Used through the action hook "login_form_register" activated on wp-login.php
     * when accessed through the registration action.
     */
    function do_register_user() {
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            $redirect_url = home_url( 'register-test' );
            
            if ( ! get_option( 'users_can_register' ) ) {
                // Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            // } elseif ( ! $this->verify_recaptcha() ) {
                // Recaptcha check failed, display error
                // $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
            } else {
                $email      = $_POST[ 'email' ];
                $first_name = sanitize_text_field( $_POST[ 'first_name' ] );
                $last_name  = sanitize_text_field( $_POST[ 'last_name' ] );
    
                $result = '';
                // $result = $this->xregister_user( $user_login, $user_email, $misc = array() );
                
                if ( is_wp_error( $result ) ) {
                    // Parse errors into a string and append as parameter to redirect
                    $errors       = join( ',', $result->get_error_codes() );
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                } else {
                    // Success, redirect to login page.
                    $redirect_url = home_url( 'login' );
                    $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
                }
            }
            
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    
    /**
     * Initiates password reset.
     */
    function do_password_lost() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $errors = retrieve_password();
            if ( is_wp_error( $errors ) ) {
                // Errors found
                $redirect_url = home_url( 'member-password-lost' );
                $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
            } else {
                // Email sent
                $redirect_url = home_url( 'login' );
                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
            }
            
            wp_redirect( $redirect_url );
            exit;
        }
    }
    
    
    /**
     * Resets the user's password if the password reset form was submitted.
     */
    function do_password_reset() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $rp_key = $_REQUEST['rp_key'];
            $rp_login = $_REQUEST['rp_login'];
            
            $user = check_password_reset_key( $rp_key, $rp_login );
            
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'login?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'login?login=invalidkey' ) );
                }
                exit;
            }
            
            if ( isset( $_POST['pass1'] ) ) {
                if ( $_POST['pass1'] != $_POST['pass2'] ) {
                    // Passwords don't match
                    $redirect_url = home_url( 'reset-password' );
                    
                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
                    
                    wp_redirect( $redirect_url );
                    exit;
                }
                
                if ( empty( $_POST['pass1'] ) ) {
                    // Password is empty
                    $redirect_url = home_url( 'reset-password' );
                    
                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
                    
                    wp_redirect( $redirect_url );
                    exit;
                }
                
                // Parameter checks OK, reset password
                reset_password( $user, $_POST['pass1'] );
                wp_redirect( home_url( 'login?password=changed' ) );
            } else {
                echo "Invalid request.";
            }
            
            exit;
        }
    }
    
    /**
     * Checks that the reCAPTCHA parameter sent with the registration
     * request is valid.
     *
     * @return bool True if the CAPTCHA is OK, otherwise false.
     * is private function
     */
    function verify_recaptcha() {
        // This field is set by the recaptcha widget if check is successful
        if ( isset ( $_POST['g-recaptcha-response'] ) ) {
            $captcha_response = $_POST['g-recaptcha-response'];
        } else {
            return false;
        }
        
        // Verify the captcha response from Google
        $response = wp_remote_post(
            'https://www.google.com/recaptcha/api/siteverify',
            array(
                'body' => array(
                    'secret' => get_option( 'b3-onboarding-recaptcha-secret-key' ),
                    'response' => $captcha_response
                )
            )
        );
        
        $success = false;
        if ( $response && is_array( $response ) ) {
            $decoded_response = json_decode( $response[ 'body' ] );
            $success          = $decoded_response->success;
        }
        
        return $success;
    }
