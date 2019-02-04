<?php
    /*
    Plugin Name: B3 - Custom user registration/login
    Version: 1.0
    Tags: log
    Plugin URI:
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
                    'version' => '1.0',
                );

                // actions
                // register_activation_hook( __FILE__,     array( $this, 'plugin_activation' ) );
                // register_deactivation_hook( __FILE__,   array( $this, 'plugin_deactivation' ) );

                // add_action( 'wp_print_footer_scripts',               array( $this, 'add_captcha_js_to_footer' ) );
                add_action( 'admin_menu',                            array( $this, 'b3_add_admin_pages' ) );
                add_action( 'admin_init',                            array( $this, 'b3_register_settings_fields' ) );

                add_filter( 'display_post_states',                   array( $this, 'b3_add_post_state' ), 10, 2 );

                add_shortcode( 'register-form',                 array( $this, 'b3_render_register_form' ) );
                add_shortcode( 'login-form',                    array( $this, 'b3_render_login_form' ) );
                // add_shortcode( 'account-page',                  array( $this, 'b3_render_account_page' ) );
                // add_shortcode( 'reset-password-form',           array( $this, 'b3_render_password_lost_form' ) );
                // add_shortcode( 'custom-password-reset-form',    array( $this, 'b3_render_password_reset_form' ) );
                
                // include( 'includes/shortcodes.php' );
            }

            /*
             * Do stuff upon plugin activation
             */
            public function plugin_activation() {
                // Information needed for creating the plugin's pages
                $page_definitions = array(
                    'member-login' => array(
                        'title' => __( 'Log In', 'sd-login' ),
                        'content' => '[custom-login-form]'
                    ),
                    'member-account' => array(
                        'title' => __( 'Your Account', 'sd-login' ),
                        'content' => '[account-info]'
                    ),
                );

                foreach ( $page_definitions as $slug => $page ) {
                    // Check that the page doesn't exist already
                    $query = new WP_Query( 'pagename=' . $slug );
                    if ( ! $query->have_posts() ) {
                        // Add the page using the data from the array above
                        wp_insert_post(
                            array(
                                'post_content'   => $page['content'],
                                'post_name'      => $slug,
                                'post_title'     => $page['title'],
                                'post_status'    => 'publish',
                                'post_type'      => 'page',
                                'ping_status'    => 'closed',
                                'comment_status' => 'closed',
                            )
                        );
                    }
                }            }


            /*
             * Do stuff upon plugin activation
             */
            public function plugin_deactivation() {
            }


            /**
             * Redirect the user to the custom login page instead of wp-login.php.
             */
            public function redirect_to_custom_login() {
                if ( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' ) {
                    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : null;

                    if ( is_user_logged_in() ) {
                        $this->redirect_logged_in_user( $redirect_to );
                        exit;
                    }

                    // The rest are redirected to the login page
                    $login_url = home_url( 'login-test' );
                    if ( ! empty( $redirect_to ) ) {
                        $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
                    }

                    wp_redirect( $login_url );
                    exit;
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
                error_log( 'HIT' );
                
                // Parse shortcode attributes
                $default_attributes = array(
                    'template'   => 'login-form-custom',
                    'show_title' => false
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );
                
                $show_title = $attributes['show_title'];

                if ( is_user_logged_in() ) {
                    return __( 'You are already signed in.', 'sd-login' );
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
                return $this->get_template_html( 'login-form-custom', $attributes );
            }


            /**
             * A shortcode to render the account page
             *
             * @param      $attributes
             * @param null $content
             *
             * @return string
             */
            public function render_account_page( $user_variables, $content = null ) {

                // Parse shortcode attributes
                $default_attributes = array( 'show_title' => false );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                return $this->get_template_html( 'account-page', $attributes );
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

                do_action( 'personalize_login_before_' . $template_name );

                require( 'templates/' . $template_name . '.php');

                do_action( 'personalize_login_after_' . $template_name );

                $html = ob_get_contents();
                ob_end_clean();

                return $html;
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

                        $login_url = home_url( 'login-test' );
                        $login_url = add_query_arg( 'login', $error_codes, $login_url );

                        wp_redirect( $login_url );
                        exit;
                    }
                }

                return $user;
            }


            /**
             * Enqueue scripts
             *
             * @since 6.4
             * @access public
             */
            public function wp_enqueue_scripts() {
                wp_enqueue_script( 'sd-login-js', plugins_url( 'assets/js/js.js', __FILE__ ), array( 'jquery' ) );
                wp_enqueue_style( 'password-strength', plugins_url( 'assets/css/css.css', __FILE__ ) );
            }


            /**
             * @param $post_states
             * @param $post
             *
             * @return array
             */
            public function b3_add_post_state( $post_states, $post ) {
                if ( $post->post_name == 'member-account' ) {
                    $post_states[] = 'Account page';
                }

                return $post_states;
            }


            /**
             *
             */
            public function ecs_add_post_notice() {
                global $post;
                if( isset( $post->post_name ) && ( $post->post_name == 'member-account' ) ) {
                    /* Add a notice to the edit page */
                    add_action( 'edit_form_after_title', array( $this, 'ecs_add_page_notice' ), 1 );
                    /* Remove the WYSIWYG editor */
                    // remove_post_type_support( 'page', 'editor' );
                }
            }


            /**
             *
             */
            function ecs_add_page_notice() {
                echo '<div class="notice notice-warning inline"><p>' . __( 'You are currently editing the profile edit page. Do not edit the title or slug of this page!', 'sd-login' ) . '</p></div>';
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
                        return __( 'Enter a user name', 'sd-login' );

                    case 'empty_password':
                        return __( 'You need to enter a password to login.', 'sd-login' );

                    case 'invalid_username':
                        return __(
                            "We don't have any users with that email address. Maybe you used a different one when signing up?",
                            'sd-login'
                        );

                    case 'incorrect_password':
                        $err = __(
                            "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                            'sd-login'
                        );
                        return sprintf( $err, wp_lostpassword_url() );

                    // Registration errors
                    case 'email':
                        return __( 'The email address you entered is not valid.', 'sd-login' );

                    case 'email_exists':
                        return __( 'An account exists with this email address.', 'sd-login' );

                    case 'closed':
                        return __( 'Registering new users is currently not allowed.', 'sd-login' );

                    case 'captcha':
                        return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'sd-login' );

                    // Lost password
                    case 'invalid_email':
                    case 'invalidcombo':
                        return __( 'There are no users registered with this email address.', 'sd-login' );

                    // Reset password

                    case 'expiredkey':
                    case 'invalidkey':
                        return __( 'The password reset link you used is not valid anymore.', 'sd-login' );

                    case 'password_reset_mismatch':
                        return __( "The two passwords you entered don't match.", 'sd-login' );

                    case 'password_reset_empty':
                        return __( "Sorry, we don't accept empty passwords.", 'sd-login' );

                    default:
                        break;
                }

                return __( 'An unknown error occurred. Please try again later.', 'sd-login' );
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
            public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
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
            public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
                // Create new message
                $msg  = __( 'Hello!', 'sd-login' ) . "\r\n\r\n";
                $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'sd-login' ), $user_login ) . "\r\n\r\n";
                $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'sd-login' ) . "\r\n\r\n";
                $msg .= __( 'To reset your password, visit the following address:', 'sd-login' ) . "\r\n\r\n";
                $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
                $msg .= __( 'Thanks!', 'sd-login' ) . "\r\n";

                return $msg;
            }


            /**
             * Redirect to custom login page after the user has been logged out.
             */
            public function redirect_after_logout() {
                $redirect_url = home_url( 'login-test?logged_out=true' );
                wp_safe_redirect( $redirect_url );
                exit;
            }


            /**
             * Redirects the user to the custom "Forgot your password?" page instead of
             * wp-login.php?action=lostpassword.
             */
            public function redirect_to_custom_lostpassword() {
                if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
                    if ( is_user_logged_in() ) {
                        $this->redirect_logged_in_user();
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
            public function redirect_to_custom_password_reset() {
                if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
                    // Verify key / login combo
                    $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
                    if ( ! $user || is_wp_error( $user ) ) {
                        if ( $user && $user->get_error_code() === 'expired_key' ) {
                            wp_redirect( home_url( 'login-test?login=expiredkey' ) );
                        } else {
                            wp_redirect( home_url( 'login-test?login=invalidkey' ) );
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
                    'template' => 'register-form'
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                // @TODO: IF
                // Retrieve recaptcha key
                $attributes['recaptcha_site_key'] = get_option( 'sd-login-recaptcha-site-key', null );

                if ( is_user_logged_in() ) {
                    return __( 'You are already signed in.', 'sd-login' );
                // } elseif ( ! get_option( 'users_can_register' ) ) {
                //     return __( 'Registering new users is currently not allowed.', 'sd-login' );
                } else {

                    // Retrieve possible errors from request parameters
                    $attributes[ 'errors' ] = array();
                    if ( isset( $_REQUEST[ 'register-errors' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'register-errors' ] );

                        foreach ( $error_codes as $error_code ) {
                            $attributes[ 'errors' ][] = $this->get_error_message( $error_code );
                        }
                    }

                    return $this->get_template_html( $attributes['template'], $attributes );
                }
            }


            /**
             * Redirects the user to the custom registration page instead
             * of wp-login.php?action=register.
             */
            public function redirect_to_custom_register() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( is_user_logged_in() ) {
                        $this->redirect_logged_in_user();
                    } else {
                        wp_redirect( home_url( 'register-test' ) );
                    }
                    exit;
                }
            }


            /**
             * Validates and then completes the new user signup process if all went well.
             *
             * @param string $email         The new user's email address
             * @param string $first_name    The new user's first name
             * @param string $last_name     The new user's last name
             *
             * @return int|WP_Error         The id of the user that was created, or error if failed.
             */
            private function register_user( $email, $first_name, $last_name ) {
                $errors = new WP_Error();

                // Email address is used as both username and email. It is also the only
                // parameter we need to validate
                if ( ! is_email( $email ) ) {
                    $errors->add( 'email', $this->get_error_message( 'email' ) );
                    return $errors;
                }

                if ( username_exists( $email ) || email_exists( $email ) ) {
                    $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
                    return $errors;
                }

                // Generate the password so that the subscriber will have to check email...
                $password = wp_generate_password( 12, false );

                $user_data = array(
                    'user_login'    => $email,
                    'user_email'    => $email,
                    'user_pass'     => $password,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'nickname'      => $first_name,
                );

                $user_id = wp_insert_user( $user_data );
                wp_new_user_notification( $user_id, null, $password );

                return $user_id;
            }


            /**
             * Handles the registration of a new user.
             *
             * Used through the action hook "login_form_register" activated on wp-login.php
             * when accessed through the registration action.
             */
            public function do_register_user() {
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    $redirect_url = home_url( 'register-test' );

                    if ( ! get_option( 'users_can_register' ) ) {
                        // Registration closed, display error
                        $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
                    } elseif ( ! $this->verify_recaptcha() ) {
                        // Recaptcha check failed, display error
                        $redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
                    } else {
                        $email      = $_POST[ 'email' ];
                        $first_name = sanitize_text_field( $_POST[ 'first_name' ] );
                        $last_name  = sanitize_text_field( $_POST[ 'last_name' ] );

                        $result = $this->register_user( $email, $first_name, $last_name );

                        if ( is_wp_error( $result ) ) {
                            // Parse errors into a string and append as parameter to redirect
                            $errors       = join( ',', $result->get_error_codes() );
                            $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                        } else {
                            // Success, redirect to login page.
                            $redirect_url = home_url( 'login-test' );
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
            public function do_password_lost() {
                if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
                    $errors = retrieve_password();
                    if ( is_wp_error( $errors ) ) {
                        // Errors found
                        $redirect_url = home_url( 'member-password-lost' );
                        $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
                    } else {
                        // Email sent
                        $redirect_url = home_url( 'login-test' );
                        $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                    }

                    wp_redirect( $redirect_url );
                    exit;
                }
            }


            /**
             * Resets the user's password if the password reset form was submitted.
             */
            public function do_password_reset() {
                if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
                    $rp_key = $_REQUEST['rp_key'];
                    $rp_login = $_REQUEST['rp_login'];

                    $user = check_password_reset_key( $rp_key, $rp_login );

                    if ( ! $user || is_wp_error( $user ) ) {
                        if ( $user && $user->get_error_code() === 'expired_key' ) {
                            wp_redirect( home_url( 'login-test?login=expiredkey' ) );
                        } else {
                            wp_redirect( home_url( 'login-test?login=invalidkey' ) );
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
                        wp_redirect( home_url( 'login-test?password=changed' ) );
                    } else {
                        echo "Invalid request.";
                    }

                    exit;
                }
            }


            /**
             * An action function used to include the reCAPTCHA JavaScript file
             * at the end of the page.
             */
            public function add_captcha_js_to_footer() {
                echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
            }


            /**
             * Checks that the reCAPTCHA parameter sent with the registration
             * request is valid.
             *
             * @return bool True if the CAPTCHA is OK, otherwise false.
             */
            private function verify_recaptcha() {
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
                            'secret' => get_option( 'sd-login-recaptcha-secret-key' ),
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


            /**
             * Adds a page to admin sidebar menu
             */
            public function b3_add_admin_pages() {
                add_menu_page( 'SD Login', 'Login settings', 'manage_options', 'sd-login-settings', 'b3_login_settings' );
                include( 'includes/login-settings.php' ); // content for the settings page
            }


            /**
             * A shortcode for rendering the form used to reset a user's password.
             *
             * @param  array   $attributes  Shortcode attributes.
             * @param  string  $content     The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function render_password_reset_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array( 'show_title' => false );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return __( 'You are already signed in.', 'sd-login' );
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

                        return $this->get_template_html( 'password_reset_form', $attributes );
                    } else {
                        return __( 'Invalid password reset link.', 'sd-login' );
                    }
                }
            }


            /**
             * A shortcode for rendering the form used to initiate the password reset.
             *
             * @param  array   $attributes  Shortcode attributes.
             * @param  string  $content     The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function render_password_lost_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array( 'show_title' => false );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                // Retrieve possible errors from request parameters
                $attributes['errors'] = array();
                if ( isset( $_REQUEST['errors'] ) ) {
                    $error_codes = explode( ',', $_REQUEST['errors'] );

                    foreach ( $error_codes as $error_code ) {
                        $attributes['errors'] []= $this->get_error_message( $error_code );
                    }
                }

                if ( is_user_logged_in() ) {
                    return __( 'You are already logged in.', 'sd-login' );
                } else {
                    return $this->get_template_html( 'lost-password', $attributes );
                }
            }


            /**
             * Registers the settings fields needed by the plugin.
             */
            public function b3_register_settings_fields() {
                // Create settings fields for the two keys used by reCAPTCHA
                register_setting( 'general', 'b3-login-recaptcha-site-key' );
                register_setting( 'general', 'b3-login-recaptcha-secret-key' );

                add_settings_field(
                    'b3-login-recaptcha-site-key',
                    '<label for="b3-login-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'b3-login' ) . '</label>',
                    array( $this, 'render_recaptcha_site_key_field' ),
                    'general'
                );

                add_settings_field(
                    'b3-login-recaptcha-secret-key',
                    '<label for="b3-login-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'b3-login' ) . '</label>',
                    array( $this, 'render_recaptcha_secret_key_field' ),
                    'general'
                );
            }

            public function render_recaptcha_site_key_field() {
                $value = get_option( 'b3-login-recaptcha-site-key', '' );
                echo '<input type="text" id="b3-login-recaptcha-site-key" name="b3-login-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';
            }

            public function render_recaptcha_secret_key_field() {
                $value = get_option( 'b3-login-recaptcha-secret-key', '' );
                echo '<input type="text" id="b3-login-recaptcha-secret-key" name="b3-login-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';
            }

        }

        // Initialize the plugin
        $b3_registration_plugin = new B3UserRegistration();

    endif; // class_exists check
