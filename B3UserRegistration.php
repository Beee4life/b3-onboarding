<?php
    /*
    Plugin Name: B3 - Custom user registration/login
    Version: 0.0.1
    Tags: user, management, registration, login, forgot password, reset password, account
    Plugin URI: http://www.berrplasman.com
    Description: This plugin handles the registration/login process
    Author: Beee
    Author URI: http://berryplasman.com
    Text-domain: b3-user-register

    Source: https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-1-replace-the-login-page--cms-23627

    http://www.berryplasman.com
       ___  ____ ____ ____
      / _ )/ __/  __/  __/
     / _  / _/   _/   _/
    /____/___/____/____/

    
    @TODO:
    - processing through AJAX
    
    */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    if ( ! class_exists( 'B3UserRegistration' ) ) :

        class B3UserRegistration {

            /**
             * Initializes the plugin.
             *
             * To keep the initialization fast, only add filter and action
             * hooks in the constructor.
             */
            public function __construct() {

                // vars
                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '0.0.1',
                );

                // actions
                register_activation_hook( __FILE__,     array( $this, 'plugin_activation' ) );
                // register_deactivation_hook( __FILE__,   array( $this, 'plugin_deactivation' ) );

                add_action( 'wp_enqueue_scripts',                    array( $this, 'b3_enqueue_scripts_frontend' ) );
                add_action( 'admin_enqueue_scripts',                 array( $this, 'b3_enqueue_scripts_backend' ) );
                add_action( 'admin_menu',                            array( $this, 'b3_add_admin_pages' ) );
                add_action( 'admin_init',                            array( $this, 'b3_register_settings_fields' ) );
                add_action( 'init',                                  array( $this, 'b3_registration_form_handling' ) );
                add_action( 'init',                                  array( $this, 'b3_forgot_pass_form_handling' ) );
                add_action( 'init',                                  array( $this, 'b3_reset_pass_handling' ) );
                add_action( 'init',                                  array( $this, 'b3_activation_handling' ) );
                add_action( 'admin_init',                            array( $this, 'b3_add_post_notice' ) );
                // add_action( 'wp_print_footer_scripts',               array( $this, 'add_captcha_js_to_footer' ) );

                add_filter( 'display_post_states',                   array( $this, 'b3_add_post_state' ), 10, 2 );

                add_shortcode( 'register-form',                 array( $this, 'b3_render_register_form' ) );
                add_shortcode( 'login-form',                    array( $this, 'b3_render_login_form' ) );
                add_shortcode( 'forgotpass-form',               array( $this, 'b3_render_forgot_password_form' ) );
                add_shortcode( 'resetpass-form',                array( $this, 'b3_render_reset_password_form' ) );
                // add_shortcode( 'account-page',                  array( $this, 'b3_render_account_page' ) );
                
                include( 'includes/do-stuff.php' );
                include( 'includes/examples.php' );
                include( 'includes/functions.php' );
                include( 'includes/tabs.php' );
            }

            /*
             * Do stuff upon plugin activation
             */
            public function plugin_activation() {
                
                // @TODO: add if for if user wants to add pages
                b3_create_initial_pages();
            }


            /*
             * Do stuff upon plugin activation
             */
            public function plugin_deactivation() {
                // @TODO: remove settings
            }
    
    
            /**
             * Enqueue scripts front-end
             */
            public function b3_enqueue_scripts_frontend() {
                wp_enqueue_style( 'b3-main', plugins_url( 'assets/css/style.css', __FILE__ ) );
                // wp_enqueue_script( 'b3-user-register-js', plugins_url( 'assets/js/js.js', __FILE__ ), array( 'jquery' ) );
            }
    
    
            /**
             * Enqueue scripts in backend
             */
            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3-admin', plugins_url( 'assets/css/admin.css', __FILE__ ) );
                wp_enqueue_script( 'b3-tabs', plugins_url( 'assets/js/tabs.js', __FILE__ ), array( 'jquery' ) );
            }
    
    
            /**
             * Adds a page to admin sidebar menu
             */
            public function b3_add_admin_pages() {
                add_menu_page( 'B3 User Registration', 'User Registration Settings', 'manage_options', 'b3-user-register-settings', 'b3_user_register_settings' );
                include( 'includes/admin-page.php' ); // content for the settings page
            }
    
    
            /**
             * Handle registration form
             */
            public function b3_registration_form_handling() {
    
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( isset( $_POST[ 'b3_register_user' ] ) ) {
                        $redirect_url = home_url( 'register' );
                        if ( ! wp_verify_nonce( $_POST[ "b3_register_user" ], 'b3-register-user' ) ) {
                        } else {
    
                            $user_login = ( isset( $_POST[ 'b3_user_login' ] ) ) ? $_POST[ 'b3_user_login' ] : false;
                            $user_email = ( isset( $_POST[ 'b3_user_email' ] ) ) ? $_POST[ 'b3_user_email' ] : false;
                            $first_name = ( isset( $_POST[ 'b3_first_name' ] ) ) ? sanitize_text_field( $_POST[ 'b3_first_name' ] ) : false;
                            $last_name  = ( isset( $_POST[ 'b3_last_name' ] ) ) ? sanitize_text_field( $_POST[ 'b3_last_name' ] ) : false;
                            $meta_data  = [];
            
                            if ( ! is_multisite() && ! get_option( 'users_can_register' ) ) {
                                // Registration closed, display error
                                $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                // @TODO: add option if users should still be allowed to register
                            }

                            if ( is_multisite() ) {
                                $meta_data = array(
                                    'blog_public' => '1',
                                    'lang_id'     => '0',
                                );
                                $meta       = apply_filters( 'add_user_meta', $meta_data );
                                $sub_domain = ( isset( $_POST[ 'b3_subdomain' ] ) ) ? $_POST[ 'b3_subdomain' ] : false;
                                if ( false != $sub_domain ) {
                                    // @TODO: check this for options
                                    if ( true == domain_exists( $sub_domain, '/' ) ) {
                                        $redirect_url = add_query_arg( 'registration-error', 'domain_exists', $redirect_url );
                                    }
                                    $result = $this->b3_register_wpmu_user( $user_login, $user_email, $sub_domain, $meta );
                                }
                                
                            } else {
                                
                                if ( false != $first_name ) {
                                    $meta_data[ 'first_name' ] = $first_name;
                                }
                                if ( false != $last_name ) {
                                    $meta_data[ 'last_name' ] = $last_name;
                                }
                                
                                // register new user
                                $result = $this->b3_register_user( $user_login, $user_email, $meta_data );
    
                                if ( is_wp_error( $result ) ) {
                                    // Parse errors into a string and append as parameter to redirect
                                    $errors       = join( ',', $result->get_error_codes() );
                                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                                } else {
                                    // Success, redirect to login page.
                                    $redirect_url = home_url( 'login' ); // @TODO: make dynamic
                                    $redirect_url = add_query_arg( 'registered', 'success', $redirect_url );
                                }
                            }
                        }
    
                        wp_redirect( $redirect_url );
                        exit;

                    }
                }
            }
    
    
            /**
             *
             */
            public function b3_forgot_pass_form_handling() {
                $show_custom_passwords = get_option( 'b3_custom_passwords' );

                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( isset( $_POST[ 'b3_forgot_pass' ] ) ) {
                        $redirect_url = home_url( 'forgot-password' );
                        if ( ! wp_verify_nonce( $_POST[ 'b3_forgot_pass' ], 'b3-forgot-pass' ) ) {
                            // @TODO: add error
                        } else {
    
                            $user_email = ( isset( $_POST[ 'b3_user_email' ] ) ) ? $_POST[ 'b3_user_email' ] : false;
                            if ( true == $show_custom_passwords ) {
                                $pass1 = ( isset( $_POST[ 'pass1' ] ) ) ? $_POST[ 'pass1' ] : false;
                                $pass2 = ( isset( $_POST[ 'pass2' ] ) ) ? $_POST[ 'pass2' ] : false;
                                if ( $pass1 != $pass2 ) {
                                    // @TODO: add error
                                    $redirect_url = add_query_arg( 'errors', 'password_reset_mismatch', $redirect_url );
                                }
                            } else {
    
                                $errors = retrieve_password();
                                if ( is_wp_error( $errors ) ) {
                                    // Errors found
                                    $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
                                } else {
                                    // Email sent
                                    $redirect_url = home_url( 'login' ); // @TODO: make dynamic/filterable
                                    $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                                }
                                
                            }
    
                            wp_redirect( $redirect_url );
                            exit;
    
                        }
                    }
                }
            }
    
    
            public function b3_reset_pass_handling() {
    
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    $rp_key   = ( isset( $_REQUEST[ 'rp_key' ] ) ) ? $_REQUEST[ 'rp_key' ] : false;
                    $rp_login = ( isset( $_REQUEST[ 'rp_login' ] ) ) ? $_REQUEST[ 'rp_login' ] : false;
        
                    $user = check_password_reset_key( $rp_key, $rp_login );
        
                    if ( ! $user || is_wp_error( $user ) ) {
                        if ( $user && $user->get_error_code() === 'expired_key' ) {
                            wp_redirect( home_url( 'login/?login=expiredkey' ) );
                        } else {
                            wp_redirect( home_url( 'login/?login=invalidkey' ) );
                        }
                        exit;
                    }
        
                    if ( isset( $_POST[ 'pass1' ] ) ) {
                        if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] ) {
                            // Passwords don't match
                            $redirect_url = home_url( 'reset-password' ); // @TODO: make dynamic/filterable
                            $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                            $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                            $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
                
                            wp_redirect( $redirect_url );
                            exit;
                        }
            
                        if ( empty( $_POST[ 'pass1' ] ) ) {
                            // Password is empty
                            $redirect_url = home_url( 'reset-password' ); // @TODO: make dynamic/filterable
                            $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                            $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                            $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
                
                            wp_redirect( $redirect_url );
                            exit;
                        }
            
                        // Parameter checks OK, reset password
                        reset_password( $user, $_POST[ 'pass1' ] );
                        wp_redirect( home_url( 'login/?password=changed' ) ); // @TODO: make dynamic/filterable
                    } else {
                        echo "Invalid request.";
                    }
        
                    exit;
                }
            }
    
    
            public function b3_activation_handling() {
            }
    
    
    
            /**
             * Add post states for plugin pages
             * @param $post_states
             * @param $post
             *
             * @return array
             */
            public function b3_add_post_state( $post_states, $post ) {
                if ( $post->post_name == 'account' ) {
                    $post_states[] = 'B3: Account';
                }
        
                return $post_states;
            }
    
    
            /**
             * Redirects the user to the correct page depending on whether he / she
             * is an admin or not.
             *
             * @param string $redirect_to   An optional redirect_to URL for admin users
             */
            private function redirect_logged_in_user( $redirect_to = null ) {
                $user = wp_get_current_user();
                if ( user_can( $user, 'manage_options' ) ) {
                    if ( $redirect_to ) {
                        wp_safe_redirect( $redirect_to );
                    } else {
                        wp_redirect( admin_url() );
                    }
                } else {
                    wp_redirect( home_url( 'member-account' ) );
                }
            }
    
    
            /**
             *
             */
            public function b3_add_post_notice() {
                if ( isset( $_GET[ 'post' ] ) && ! empty( $_GET[ 'post' ] ) ) {
                    $post = get_post( $_GET[ 'post' ] );
                    if ( 'page' == $post->post_type && 'account' == $post->post_name ) {
                        /* Add a notice to the edit page */
                        add_action( 'edit_form_after_title', array( $this, 'b3_add_page_notice' ), 1 );
                    }
                }
            }


            /**
             *
             */
            public function b3_add_page_notice() {
                echo '<div class="notice notice-warning inline"><p>' . __( 'You are currently editing the profile edit page. Do not edit the title or slug of this page!', 'b3-user-register' ) . '</p></div>';
            }

            
            /**
             * Finds and returns a matching error message for the given error code.
             *
             * @param string $error_code    The error code to look up.
             *
             * @return string               An error message.
             */
            private function get_error_message( $error_code ) {
                switch ( $error_code ) {

                    // Login errors
                    case 'empty_username':
                        return __( 'Enter a user name', 'b3-user-register' );

                    case 'empty_password':
                        return __( 'You need to enter a password to login.', 'b3-user-register' );

                    case 'invalid_username':
                        return __(
                            "We don't have any users with that email address. Maybe you used a different one when signing up?",
                            'b3-user-register'
                        );

                    case 'incorrect_password':
                        $err = __(
                            "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                            'b3-user-register'
                        );
                        return sprintf( $err, wp_lostpassword_url() );

                    // Registration errors
                    case 'username_exists':
                        return __( 'This username address is already in use.', 'b3-user-register' );

                    case 'email':
                        return __( 'The email address you entered is not valid.', 'b3-user-register' );

                    case 'email_exists':
                        return __( 'An account exists with this email address.', 'b3-user-register' );

                    case 'closed':
                        return __( 'Registering new users is currently not allowed.', 'b3-user-register' );

                    case 'captcha':
                        return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'b3-user-register' );

                    // Lost password
                    case 'invalid_email':
                    case 'invalidcombo':
                        return __( 'There are no users registered with this email address.', 'b3-user-register' );

                    // Reset password
                    case 'expiredkey':
                    case 'invalidkey':
                        return __( 'The password reset link you used is not valid anymore.', 'b3-user-register' );

                    case 'password_reset_mismatch':
                        return __( "The two passwords you entered don't match.", 'b3-user-register' );

                    case 'password_reset_empty':
                        return __( "Sorry, we don't accept empty passwords.", 'b3-user-register' );

                    // Multisite
                    case 'domain_exists':
                        return __( "Sorry, this subdomain has already been taken.", 'b3-user-register' );

                    case 'user_registered':
                        return __( "You have successfully registered. Please check your email for an activation link.", 'b3-user-register' );

                    default:
                        break;
                }

                return __( 'An unknown error occurred. Please try again later.', 'b3-user-register' );
            }


            /**
             * Validates and then completes the new user signup process if all went well.
             *
             * @param string $user_login
             * @param string $user_email
             * @param string array $meta
             *
             * @return int|WP_Error
             */
            private function b3_register_user( $user_login, $user_email, $meta = array() ) {
                $errors = new WP_Error();
    
                if ( username_exists( $user_login ) ) {
                    $errors->add( 'username_exists', $this->get_error_message( 'username_exists' ) );
                    return $errors;
                }
    
                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'email', $this->get_error_message( 'email' ) );
                    return $errors;
                }
    
                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->get_error_message( 'email_exists' ) );
                    return $errors;
                }
    
                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'email', $this->get_error_message( 'email' ) );
                    return $errors;
                }

                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
                    return $errors;
                }

                $password   = wp_generate_password( 12, false );
                $first_name = ( isset( $meta[ 'first_name' ] ) && ! empty( $meta[ 'first_name' ] ) ) ? $meta[ 'first_name' ] : false;
                $last_name  = ( isset( $meta[ 'last_name' ] ) && ! empty( $meta[ 'last_name' ] ) ) ? $meta[ 'last_name' ] : false;
    
                $user_data = array(
                    'user_login' => $user_login,
                    'user_email' => $user_email,
                    'user_pass'  => $password,
                );
                if ( false != $first_name ) {
                    $user_data[ 'first_name' ] = $first_name;
                }
                if ( false != $first_name ) {
                    $user_data[ 'last_name' ] = $last_name;
                }
    
                return 160878;
    
                $user_id = wp_insert_user( $user_data );
                if ( ! is_wp_error( $user_id ) ) {
                    wp_new_user_notification( $user_id, null, $password );
                }
                // @TODO: add if for if user needs to activate
                // @TODO: add if for if admin needs to activate

                return $user_id;
            }
    
            private function b3_register_wpmu_user( $user_name, $user_email, $sub_domain, $meta = array() ) {
                $errors = new WP_Error();
    
                $register_type = get_site_option( 'registration' );
                if ( 'user' == $register_type ) {
                    wpmu_signup_user( $user_name, $user_email, $meta );
                } elseif ( 'all' == $register_type ) {
                    wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                } elseif ( 'none' == $register_type ) {
                    wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                    // @TODO: add if for user or user + site
                    // @TODO: add if for if user needs to activate
                    // @TODO: add if for if admin needs to activate
                }
    
                $errors->add( 'user_registered', $this->get_error_message( 'user_registered' ) );
                return $errors;
            }


            /**
             * An action function used to include the reCAPTCHA JavaScript file
             * at the end of the page.
             */
            public function add_captcha_js_to_footer() {
                echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
            }


            /**
             * Registers the settings fields needed by the plugin (echoed on user page)
             */
            public function b3_register_settings_fields() {
                // Create settings fields for the two keys used by reCAPTCHA
                register_setting( 'general', 'b3-user-register-recaptcha-site-key' );
                register_setting( 'general', 'b3-user-register-recaptcha-secret-key' );
        
                ## add to site settings (general)
                // add_settings_field(
                //     'b3-user-register-recaptcha-site-key',
                //     '<label for="b3-user-register-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'b3-user-register' ) . '</label>',
                //     array( $this, 'render_recaptcha_site_key_field' ),
                //     'general'
                // );
        
                // add_settings_field(
                //     'b3-user-register-recaptcha-secret-key',
                //     '<label for="b3-user-register-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'b3-user-register' ) . '</label>',
                //     array( $this, 'render_recaptcha_secret_key_field' ),
                //     'general'
                // );
            }
    
            public function render_recaptcha_site_key_field() {
                $value = get_option( 'b3-user-register-recaptcha-site-key', '' );
                echo '<input type="text" id="b3-user-register-recaptcha-site-key" name="b3-user-register-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';
            }
    
            public function render_recaptcha_secret_key_field() {
                $value = get_option( 'b3-user-register-recaptcha-secret-key', '' );
                echo '<input type="text" id="b3-user-register-recaptcha-secret-key" name="b3-user-register-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';
            }
    
            
            ## FORM RENDERS
            
            /**
             * A shortcode for rendering the new user registration form.
             *
             * @param  array   $attributes  Shortcode attributes.
             * @param  string  $content     The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_register_form( $user_variables, $content = null ) {
        
                // Parse shortcode attributes
                $default_attributes = array(
                    'show_title' => false,
                    'template'   => 'register-form',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );
        
                // @TODO: IF
                // Retrieve recaptcha key
                $attributes[ 'recaptcha_site_key' ] = get_option( 'b3-user-register-recaptcha-site-key', null );
        
                if ( is_user_logged_in() ) {
                    return __( 'You are already signed in.', 'b3-user-register' );
                    // } elseif ( ! get_option( 'users_can_register' ) ) {
                    //     return __( 'Registering new users is currently not allowed.', 'b3-user-register' );
                } else {
            
                    // Retrieve possible errors from request parameters
                    $attributes[ 'errors' ] = array();
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );
                
                        foreach ( $error_codes as $error_code ) {
                            $attributes[ 'errors' ][] = $this->get_error_message( $error_code );
                        }
                    }
    
                    return $this->get_template_html( $attributes[ 'template' ], $attributes );
                }
            }
    
    
            /**
             * A shortcode for rendering the login form.
             *
             * @param  array   $attributes  Shortcode attributes.
             * @param  string  $content     The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_login_form( $user_variables, $content = null ) {
        
                // Parse shortcode attributes
                $default_attributes = array(
                    'show_title' => false,
                    'template'   => 'login-form',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );
        
                $show_title = $attributes['show_title'];
        
                if ( is_user_logged_in() ) {
                    return __( 'You are already signed in.', 'b3-user-register' );
                }
        
                // Pass the redirect parameter to the WordPress login functionality: by default,
                // don't specify a redirect, but if a valid redirect URL has been passed as
                // request parameter, use it.
                $attributes[ 'redirect' ] = '';
                if ( isset( $_REQUEST[ 'redirect_to' ] ) ) {
                    $attributes[ 'redirect' ] = wp_validate_redirect( $_REQUEST[ 'redirect_to' ], $attributes[ 'redirect' ] );
                }
        
                // Error messages
                $errors = array();
                if ( isset( $_REQUEST[ 'login' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'login' ] );
            
                    foreach ( $error_codes as $code ) {
                        $errors [] = $this->get_error_message( $code );
                    }
                }
                $attributes[ 'errors' ] = $errors;
        
                // Check if user just updated password
                $attributes[ 'password_updated' ] = isset( $_REQUEST[ 'password' ] ) && $_REQUEST[ 'password' ] == 'changed';
                // Check if the user just requested a new password
                $attributes[ 'lost_password_sent' ] = isset( $_REQUEST[ 'checkemail' ] ) && $_REQUEST[ 'checkemail' ] == 'confirm';
                // Check if user just logged out
                $attributes[ 'logged_out' ] = isset( $_REQUEST[ 'logged_out' ] ) && $_REQUEST[ 'logged_out' ] == true;
                // Check if the user just registered
                $attributes[ 'registered' ] = isset( $_REQUEST[ 'registered' ] );
        
                // Render the login form using an external template
                // return $this->get_template_html( 'login-form', $attributes );
                return $this->get_template_html( $attributes[ 'template' ], $attributes );
            }
    
    
            /**
             * A shortcode for rendering the form used to initiate the password reset.
             *
             * @param  array   $attributes  Shortcode attributes.
             * @param  string  $content     The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_forgot_password_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array(
                    'show_title' => false,
                    'template'   => 'forgotpass-form',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );
    
                // Retrieve possible errors from request parameters
                $attributes[ 'errors' ] = array();
                if ( isset( $_REQUEST[ 'errors' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'errors' ] );
        
                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ] [] = $this->get_error_message( $error_code );
                    }
                }
    
                if ( is_user_logged_in() ) {
                    return __( 'You are already logged in.', 'b3-user-register' );
                } else {
                    return $this->get_template_html( $attributes[ 'template' ], $attributes );
                }
            }
    
    
            /**
             * A shortcode for rendering the form used to reset a user's password.
             *
             * @param  array   $attributes  Shortcode attributes.
             * @param  string  $content     The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_reset_password_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array(
                    'show_title' => false,
                    'template'   => 'resetpass-form',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return __( 'You are already signed in.', 'b3-user-register' );
                } else {
                    if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
                        $attributes['login'] = $_REQUEST['login'];
                        $attributes['key'] = $_REQUEST['key'];

                        // Error messages
                        $errors = array();
                        if ( isset( $_REQUEST['error'] ) ) {
                            $error_codes = explode( ',', $_REQUEST['error'] );

                            foreach ( $error_codes as $code ) {
                                $errors []= $this->get_error_message( $code );
                            }
                        }
                        $attributes['errors'] = $errors;
    
                        return $this->get_template_html( $attributes[ 'template' ], $attributes );
                    } else {
                        return __( 'Invalid password reset link.', 'b3-user-register' );
                    }
                }
            }
    
    
            /**
             * A shortcode to render the account page
             *
             * @param      $attributes
             * @param null $content
             *
             * @return string
             */
            public function b3_render_account_page( $user_variables, $content = null ) {
        
                // Parse shortcode attributes
                $default_attributes = array(
                    'show_title' => false,
                    'template'   => 'account-page',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );
    
                return $this->get_template_html( $attributes[ 'template' ], $attributes );
            }
    
    
            /**
             * Renders the contents of the given template to a string and returns it.
             *
             * @param string $template_name The name of the template to render (without .php)
             * @param array  $attributes    The PHP variables for the template
             *
             * @return string               The contents of the template.
             */
            private function get_template_html( $template_name, $attributes = null ) {
                if ( ! $attributes ) {
                    $attributes = array();
                }
        
                ob_start();
        
                do_action( 'b3_before_' . $template_name );
        
                require( 'templates/' . $template_name . '.php');
        
                do_action( 'b3_after_' . $template_name );
        
                $html = ob_get_contents();
                ob_end_clean();
        
                return $html;
            }
    
    
        }

        // Initialize the plugin
        $b3_registration_plugin = new B3UserRegistration();

    endif; // class_exists check
