<?php
    /*
    Plugin Name:        B3 OnBoarding
    Plugin URI:         https://b3onboarding.berryplasman.com
    Description:        This plugin styles the default WordPress pages into your own design. It gives you full control over the registration/login process (aka onboarding).
    Version:            3.1.0
    Requires at least:  4.3
    Tested up to:       5.9.1
    Requires PHP:       5.6
    Author:             Beee
    Author URI:         https://berryplasman.com
    Tags:               user, management, registration, login, lost password, reset password, account, multisite, wpml, multilang, onboarding, onboard, user registration, user management, forms, email, override
    Text-domain:        b3-onboarding
    License:            GPL v2 (or later)
    License URI:        https://www.gnu.org/licenses/gpl-2.0.html
    Domain Path:        /languages
    Network:            true
       ___  ____ ____ ____
      / _ )/ __/  __/  __/
     / _  / _/   _/   _/
    /____/___/____/____/

    */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'B3Onboarding' ) ) {

        /**
         * Class B3Onboarding
         */
        class B3Onboarding {

            /**
             * Construct
             */
            function __construct() {
                if ( ! defined( 'B3_PLUGIN_URL' ) ) {
                    $plugin_url = plugins_url( '/', __FILE__ );
                    define( 'B3_PLUGIN_URL', $plugin_url );
                }

                if ( ! defined( 'B3_PLUGIN_PATH' ) ) {
                    $plugin_path = dirname( __FILE__ );
                    define( 'B3_PLUGIN_PATH', $plugin_path );
                }

                if ( ! defined( 'B3_PLUGIN_SETTINGS' ) ) {
                    $settings_url = admin_url( 'admin.php?page=b3-onboarding' );
                    define( 'B3_PLUGIN_SETTINGS', $settings_url );
                }

                if ( ! defined( 'B3_PLUGIN_SITE' ) ) {
                    $plugin_site = 'https://b3onboarding.berryplasman.com';
                    define( 'B3_PLUGIN_SITE', $plugin_site );
                }

                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '3.1.0',
                );
            }


            /**
             * This initializes the whole shabang
             */
            public function init() {
                // actions
                register_activation_hook( __FILE__,            array( $this, 'b3_plugin_activation' ) );
                register_deactivation_hook( __FILE__,          array( $this, 'b3_plugin_deactivation' ) );

                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_enqueue_scripts_frontend' ), 40 );
                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_add_recaptcha_js_to_footer' ) );
                add_action( 'login_enqueue_scripts',                array( $this, 'b3_add_recaptcha_js_to_footer' ) );
                add_action( 'wp_head',                              array( $this, 'b3_add_rc3' ) );
                add_action( 'admin_enqueue_scripts',                array( $this, 'b3_enqueue_scripts_backend' ) );
                add_action( 'admin_menu',                           array( $this, 'b3_add_admin_pages' ) );
                add_action( 'widgets_init',                         array( $this, 'b3_register_widgets' ) );
                add_action( 'wp_dashboard_setup',                   array( $this, 'b3_add_dashboard_widget' ) );
                add_action( 'init',                                 array( $this, 'b3_load_plugin_text_domain' ) );
                add_action( 'init',                                 array( $this, 'b3_registration_form_handling' ) );
                add_action( 'init',                                 array( $this, 'b3_reset_user_password' ) );
                add_action( 'admin_notices',                        array( $this, 'b3_admin_notices' ) );
                add_action( 'load-users.php',                       array( $this, 'b3_load_users_page' ) );

                // Multisite specific
                add_action( 'wp_initialize_site',                   array( $this, 'b3_after_create_site' ) );

                // Filters
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this, 'b3_settings_link' ) );

                include 'includes/true-false.php';
                include 'includes/actions-b3.php';
                include 'includes/actions-wp.php';
                include 'includes/class-b3-shortcodes.php';
                include 'includes/do-stuff.php';
                if ( is_localhost() ) {
                    include 'includes/examples.php';
                }
                include 'includes/filters-b3.php';
                include 'includes/filters-wp.php';
                include 'includes/functions.php';
                include 'includes/defaults.php';
                include 'includes/emails.php';
                include 'includes/redirects.php';
                include 'includes/form-handling.php';
                include 'includes/tabs/tabs.php';
                include 'admin/help-tabs.php';
                if ( get_option( 'b3_activate_filter_validation' ) ) {
                    include 'includes/verify-filters.php';
                }
            }


            /*
             * Do stuff upon plugin activation
             *
             * @since 2.0.0
             */
            public function b3_plugin_activation() {

                // create necessary pages
                b3_setup_initial_pages();
                // set default values
                b3_set_default_settings();

                // @TODO: check if this is needed on MS
                if ( ! is_multisite() ) {
                    $b3_activation = get_role( 'b3_activation' );
                    if ( ! $b3_activation ) {
                        add_role( 'b3_activation', esc_html__( 'Awaiting activation', 'b3-onboarding' ), array() );
                    }
                    $b3_approval = get_role( 'b3_approval' );
                    if ( ! $b3_approval ) {
                        add_role( 'b3_approval', esc_html__( 'Awaiting approval', 'b3-onboarding' ), array() );
                    }
                }
            }


            /**
             * Do stuff upon plugin deactivation
             */
            public function b3_plugin_deactivation() {
                // set registration option accordingly
                $registration_type = get_option( 'b3_registration_type' );
                if ( is_multisite() ) {
                    if ( is_main_site() ) {
                        if ( 'none' == $registration_type ) {
                            update_site_option( 'registration', 'none' );
                        } else {
                            update_site_option( 'registration', 'all' );
                        }
                    }
                } else {
                    if ( 'none' == $registration_type ) {
                        update_option( 'users_can_register', '0' );
                    } else {
                        update_option( 'users_can_register', '1' );
                    }
                }
            }


            /**
             * Load plugin text domain
             */
            public function b3_load_plugin_text_domain() {
                $plugin_folder = dirname( plugin_basename( __FILE__ ) );
                $locale        = apply_filters( 'plugin_locale', get_locale(), $plugin_folder );
                load_textdomain( $plugin_folder, trailingslashit( WP_LANG_DIR ) . $plugin_folder . '/' . $plugin_folder . '-' . $locale . '.mo' );
                load_plugin_textdomain( $plugin_folder, false, $plugin_folder . '/languages/' );
            }


            /*
             * Enqueue scripts front-end
             */
            public function b3_enqueue_scripts_frontend() {
                if ( ! is_admin() ) {
                    wp_deregister_script( 'jquery' ); // Deregister the included library
                    wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js', array(), '2.1.4', true );
                }

                if ( false != get_option( 'b3_use_popup', false ) ) {
                    wp_enqueue_script(
                        'modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js',
                        array( 'jquery' ), '0.9.1'
                    );
                    wp_enqueue_style(
                        'modal', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css',
                        false, '0.9.1'
                    );
                }

                wp_enqueue_style( 'b3ob-main', plugins_url( 'assets/css/style.css', __FILE__ ), array(), $this->settings[ 'version' ] );
                wp_enqueue_script( 'b3ob', plugins_url( 'assets/js/js.js', __FILE__ ), array( 'jquery' ), $this->settings[ 'version' ] );
            }


            /*
             * Enqueue scripts in backend
             */
            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3ob-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->settings[ 'version' ] );
                wp_enqueue_script( 'b3ob-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->settings[ 'version' ] );

                // Src: https://github.com/thomasgriffin/New-Media-Image-Uploader
                // Bail out early if we are not on a page add/edit screen.
                if ( ! ( 'toplevel_page_b3-onboarding' == get_current_screen()->base ) ) {
                    return;
                }

                // This function loads in the required media files for the media manager.
                wp_enqueue_media();

                // Register, localize and enqueue our custom JS.
                wp_register_script( 'b3-media', plugins_url( '/assets/js/media.js', __FILE__ ), array( 'jquery' ), $this->settings[ 'version' ], true );
                wp_localize_script( 'b3-media', 'b3_media',
                    array(
                        'title'     => esc_html__( 'Upload or choose your custom logo', 'b3-onboarding' ),
                        'button'    => esc_html__( 'Insert logo', 'b3-onboarding' ),
                    )
                );
                wp_enqueue_script( 'b3-media' );
            }


            /*
             * Adds a page to admin sidebar menu
             */
            public function b3_add_admin_pages() {
                include 'admin/admin-page.php';
                add_menu_page( 'B3 OnBoarding', 'B3 OnBoarding', apply_filters( 'b3_user_cap', 'manage_options' ), 'b3-onboarding', 'b3_user_register_settings', B3_PLUGIN_URL .  'assets/images/logo-b3onboarding-small.png', '83' );

                if ( in_array( get_option( 'b3_registration_type' ), [ 'request_access', 'request_access_subdomain' ] ) ) {
                    include 'admin/user-approval-page.php';
                    add_submenu_page( 'b3-onboarding', 'B3 OnBoarding ' . esc_html__( 'User Approval', 'b3-onboarding' ), esc_html__( 'User Approval', 'b3-onboarding' ), apply_filters( 'b3_user_cap', 'manage_options' ), 'b3-user-approval', 'b3_user_approval' );
                }

                if ( true == get_option( 'b3_debug_info' ) || is_localhost() ) {
                    include 'admin/debug-page.php';
                    add_submenu_page( 'b3-onboarding', 'B3 OnBoarding ' . esc_html__( 'Debug info', 'b3-onboarding' ), esc_html__( 'Debug info', 'b3-onboarding' ), apply_filters( 'b3_user_cap', 'manage_options' ), 'b3-debug', 'b3_debug_page' );
                }
            }


            /*
             * Register widgets (if activated)
             */
            public function b3_register_widgets() {
                if ( is_main_site() ) {
                    include 'includes/class-b3-sidebar-widget.php';
                }
            }


            /*
             * Add dashboard widget
             */
            public function b3_add_dashboard_widget() {
                /*
                 * Includes dashboard widget function + call
                 */
                if ( is_main_site() ) {
                    include 'admin/dashboard-widget.php';
                    if ( is_localhost() ) {
                        include 'admin/dashboard-widget-debug.php';
                    }
                }
            }


            /**
             * Add settings link to plugin page
             *
             * @param $links
             *
             * @return mixed
             */
            public function b3_settings_link( $links ) {
                $settings_link = sprintf( '<a href="admin.php?page=b3-onboarding">%s</a>', esc_html__( 'Settings', 'b3-onboarding' ) );
                array_unshift( $links, $settings_link );

                return $links;
            }


            /**
             * Check if user actions need to be taken
             */
            public function b3_load_users_page() {
                add_action( 'admin_notices', array( $this, 'b3_admin_notices' ) );

                if ( isset( $_GET[ 'action' ] ) && in_array( $_GET[ 'action' ], array( 'activate', 'resendactivation' ) ) ) {
                    $user_id = isset( $_GET[ 'user_id' ] ) ? $_GET[ 'user_id' ] : false;
                    if ( ! $user_id ) {
                        wp_die( esc_html__( "There's no user with that ID.", 'b3-onboarding' ) );
                    } elseif ( ! current_user_can( 'edit_user', $user_id ) ) {
                        wp_die( esc_html__( "You're not allowed to edit that user.", 'b3-onboarding' ) );
                    }

                    $user = new WP_User( $user_id );
                    $registration_type = false;
                    if ( in_array( 'b3_activation', $user->roles ) ) {
                        $registration_type = 'email_activation';
                    } elseif ( in_array( 'b3_approval', $user->roles ) ) {
                        $registration_type = 'request_access';
                    }

                    $redirect_to = isset( $_REQUEST[ 'wp_http_referer' ] ) ? remove_query_arg( array( 'wp_http_referer', 'updated' ), stripslashes( $_REQUEST[ 'wp_http_referer' ] ) ) : 'users.php';

                    switch( $_GET[ 'action' ] ) {
                        case 'activate' :
                            check_admin_referer( 'manual-activation' );
                            do_action( 'b3_manual_user_activate', $user_id );
                            $redirect_to = add_query_arg( 'update', 'activated', $redirect_to );
                            break;

                        case 'resendactivation' :
                            check_admin_referer( 'resend-activation' );
                            if ( 'email_activation' == $registration_type ) {
                                do_action( 'b3_resend_user_activation', $user_id );
                                $redirect_to = add_query_arg( 'update', 'sendactivation', $redirect_to );
                            }
                            break;
                    }

                    wp_safe_redirect( $redirect_to );
                    exit;
                }
            }

            /*
             * Error function
             *
             * @return WP_Error
             */
            public static function b3_errors() {
                static $wp_error; // Will hold global variable safely

                return isset( $wp_error ) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) );
            }

            /*
             * Displays error messages from form submissions
             */
            public static function b3_show_admin_notices() {
                if ( $codes = B3Onboarding::b3_errors()->get_error_codes() ) {
                    if ( is_wp_error( B3Onboarding::b3_errors() ) ) {

                        // Loop error codes and display errors
                        $notice_class = false;
                        $prefix       = false;
                        foreach ( $codes as $code ) {
                            if ( strpos( $code, 'success' ) !== false ) {
                                $notice_class = 'updated notice ';
                                $prefix     = false;
                            } elseif ( strpos( $code, 'error' ) !== false ) {
                                $notice_class = 'notice notice-error error ';
                                $prefix       = esc_html__( 'Error', 'b3-onboarding' );
                            } elseif ( strpos( $code, 'warning' ) !== false ) {
                                $notice_class = 'notice notice-warning ';
                                $prefix       = esc_html__( 'Warning', 'b3-onboarding' );
                            } elseif ( strpos( $code, 'info' ) !== false ) {
                                $notice_class = 'notice notice-info ';
                                $prefix       = false;
                            } else {
                                $notice_class = 'notice--error ';
                                $prefix       = esc_html__( 'Error', 'b3-onboarding' );
                            }
                        }
                        echo '<div class="' . $notice_class . 'is-dismissible">';
                        foreach ( $codes as $code ) {
                            $message = B3Onboarding::b3_errors()->get_error_message( $code );
                            $message = ( true == $prefix ) ? '<strong>' . $prefix . ':</strong> ' . $message : $message;
                            echo sprintf( '<p>%s</p>', $message );
                        }
                        echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_attr__( 'Dismiss this notice', 'b3-onboarding' ) . '</span></button>';
                        echo '</div>';
                    }
                }
            }


            /**
             * An action function used to include the reCAPTCHA JavaScript file
             * at the end of the page.
             */
            public function b3_add_recaptcha_js_to_footer() {
                if ( 1 == get_option( 'b3_activate_recaptcha' ) && is_page( b3_get_register_url( true ) ) ) {
                    wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array() );
                }
            }


            /*
             * Enqueue js for recaptcha
             */
            public function b3_add_rc3() {
                if ( 1 == get_option( 'b3_activate_recaptcha') && is_page( b3_get_register_url(true ) ) ) {
                    ?>
                    <script>
                        function onSubmit(token) {
                            document.getElementById('registerform').submit();
                        }
                    </script>
                    <?php
                }
            }


            /**
             * Handle registration form
             */
            public function b3_registration_form_handling() {
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( isset( $_POST[ 'b3_register_user' ] ) ) {
                        $redirect_url = b3_get_register_url();
                        if ( ! wp_verify_nonce( $_POST[ 'b3_register_user' ], 'b3-register-user' ) ) {
                            $redirect_url = add_query_arg( 'registration-error', 'unknown', $redirect_url );
                            wp_safe_redirect( $redirect_url );
                            exit;
                        } else {

                            $meta_data         = array();
                            $registration_type = get_option( 'b3_registration_type' );
                            $user_email        = ( isset( $_POST[ 'user_email' ] ) ) ? sanitize_email( $_POST[ 'user_email' ] ) : false;

                            if ( get_option( 'b3_honeypot' ) ) {
                                if ( isset( $_POST[ 'b3_pooh' ] ) ) {
                                    $errors = new WP_Error();
                                    $errors->add( 'honeypot', $this->b3_get_return_message( 'no_robots' ) );

                                    return $errors;
                                }
                            }

                            if ( 'blog' != $registration_type && ! is_email( $user_email ) ) {
                                $redirect_url = add_query_arg( 'registration-error', 'invalid_email', $redirect_url );
                                wp_safe_redirect( $redirect_url );
                                exit;
                            }

                            if ( isset( $_POST[ 'first_name' ] ) ) {
                                $meta_data[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
                            }
                            if ( isset( $_POST[ 'last_name' ] ) ) {
                                $meta_data[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
                            }

                            if ( ! is_multisite() ) {
                                $user_login = ( isset( $_POST[ 'user_login' ] ) ) ? sanitize_user( $_POST[ 'user_login' ] ) : false;
                                $register = true;
                                $role     = get_option( 'default_role', 'subscriber' );
                                if ( 'none' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                    $register     = false;

                                } elseif ( false != get_option( 'b3_activate_recaptcha' ) && ! b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );
                                    $register     = false;
                                }

                                if ( true == $register && 'none' != $registration_type ) {
                                    // Registration is not closed
                                    if ( 'request_access' == $registration_type ) {
                                        $role      = 'b3_approval';
                                        $query_arg = 'access_requested';
                                    } elseif ( 'email_activation' == $registration_type ) {
                                        $role      = 'b3_activation';
                                        $query_arg = 'confirm_email';
                                    } else {
                                        $query_arg      = 'success';
                                        $reset_password = ( true == get_option( 'b3_redirect_set_password' ) ) ? true : false;
                                    }

                                    $result = $this->b3_register_user( $user_email, $user_login, $registration_type, $role );

                                    if ( is_wp_error( $result ) ) {
                                        // Parse errors into a string and append as parameter to redirect
                                        $errors       = join( ',', $result->get_error_codes() );
                                        $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                    } else {
                                        // Success
                                        if ( isset( $reset_password ) && true == $reset_password ) {
                                            $reset_password_url = b3_get_lostpassword_url();
                                            if ( false != $reset_password_url ) {
                                                $redirect_url = $reset_password_url;
                                                $redirect_url = add_query_arg( 'registered', $query_arg, $redirect_url );
                                                $redirect_url = apply_filters( 'b3_redirect_after_register', $redirect_url );
                                                // @TODO: B4L: also add to MU register
                                            } else {
                                                $login_url    = b3_get_login_url();
                                                $redirect_url = $login_url;
                                            }
                                        } else {
                                            // redirect to login page
                                            $redirect_url = b3_get_login_url();
                                            $redirect_url = add_query_arg( 'registered', $query_arg, $redirect_url );
                                            $redirect_url = apply_filters( 'b3_redirect_after_register', $redirect_url );
                                        }
                                    }
                                }

                            } else {
                                // if is_multisite
                                $user_login = ( isset( $_POST[ 'user_name' ] ) ) ? sanitize_user( $_POST[ 'user_name' ] ) : false;
                                $register   = false;

                                if ( is_main_site() ) {
                                    if ( 'none' == $registration_type ) {
                                        // Registration closed, display error
                                        $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                    } elseif ( 'blog' == $registration_type ) {
                                        $user       = get_userdata( get_current_user_id() );
                                        $user_login = $user->user_login;
                                        $user_email = $user->user_email;
                                        $register   = true;
                                    } elseif ( 'request_access_subdomain' == $registration_type ) {
                                        $register   = true;
                                        $user_email = ( isset( $_POST[ 'user_email' ] ) ) ? $_POST[ 'user_email' ] : false;
                                        $user_login = ( isset( $_POST[ 'user_name' ] ) ) ? $_POST[ 'user_name' ] : false;
                                    } elseif ( false != get_option( 'b3_activate_recaptcha' ) && ! b3_verify_recaptcha() ) {
                                        // Recaptcha check failed, display error
                                        $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );
                                    } else {
                                        $register = true;
                                    }
                                }

                                if ( true == $register ) {
                                    $signup_for = ( isset( $_POST[ 'signup_for' ] ) ) ? $_POST[ 'signup_for' ] : false;
                                    $user_valid = wpmu_validate_user_signup( $user_login, $user_email );
                                    $errors     = $user_valid[ 'errors' ];

                                    if ( $errors->has_errors() ) {
                                        if ( 'blog' != $registration_type ) {
                                            $error_message_user_name  = $errors->get_error_message( 'user_name' );
                                            $error_message_user_email = $errors->get_error_message( 'user_email' );

                                            if ( ! empty( $error_message_user_name ) ) {
                                                if ( 'Sorry, that username already exists!' == $error_message_user_name ) {
                                                    $error_codes[] = 'username_exists';
                                                } elseif ( 'That username is currently reserved but may be available in a couple of days.' == $error_message_user_name ) {
                                                    $error_codes[] = 'wpmu_user_reserved';
                                                }
                                            } elseif ( ! empty( $error_message_user_email ) ) {
                                                if ( 'Sorry, that email address is already used!' == $error_message_user_email ) {
                                                    $error_codes[] = 'email_exists';
                                                } elseif ( 'That email address has already been used. Please check your inbox for an activation email. It will become available in a couple of days if you do nothing.' == $error_message_user_email ) {
                                                    $error_codes[] = 'wpmu_email_in_use';
                                                }
                                            } else {
                                                $redirect_url = add_query_arg( 'registration-error', 'unknown', $redirect_url );
                                                wp_safe_redirect( $redirect_url );
                                                exit;
                                            }
                                        }
                                    }

                                    if ( isset( $error_codes ) && ! empty( $error_codes ) ) {
                                        $errors       = join( ',', $error_codes );
                                        $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                        wp_safe_redirect( $redirect_url );
                                        exit;
                                    } else {

                                        if ( 'user' == $signup_for ) {
                                            $result = $this->b3_register_wpmu_user( $user_login, $user_email, false, false, false, $meta_data );
                                            if ( true == $result ) {
                                                // Success, redirect to login page.
                                                $redirect_url = b3_get_login_url();
                                                $redirect_url = add_query_arg( 'registered', 'confirm_email', $redirect_url );
                                            } elseif ( is_wp_error( $result ) ) {
                                                $redirect_url = b3_get_register_url();
                                                $errors       = join( ',', $result->get_error_codes() );
                                                $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                            }
                                        }
                                    }

                                    if ( ( 'blog' == $signup_for || 'request_access_subdomain' == $registration_type ) && empty( $error_codes ) ) {
                                        $meta_data[ 'lang_id' ] = ( isset( $_POST[ 'lang_id' ] ) ) ? $_POST[ 'lang_id' ] : 1;
                                        $meta_data[ 'public' ]  = ( isset( $_POST[ 'blog_public' ] ) ) ? $_POST[ 'blog_public' ] : 1;
                                        $user                   = '';

                                        if ( 'request_access_subdomain' == $registration_type ) {
                                            $meta_data[ 'deleted' ] = 1;
                                            $meta_data[ 'public' ]  = 0;
                                        }

                                        if ( is_user_logged_in() ) {
                                            $user = wp_get_current_user();
                                        } elseif ( isset( $user_login ) && ! empty( $user_login ) ) {
                                            $user = new WP_User();
                                            $user->user_login = $user_login;
                                        }


                                        $blog_info   = wpmu_validate_blog_signup( sanitize_text_field( $_POST[ 'blogname' ] ), sanitize_title( $_POST[ 'blog_title' ] ), $user );
                                        $domain      = $blog_info[ 'domain' ];
                                        $path        = $blog_info[ 'path' ];
                                        $blog_title  = $blog_info[ 'blog_title' ];
                                        $errors      = $blog_info[ 'errors' ];
                                        $error_codes = array();

                                        if ( $errors->has_errors() ) {
                                            $error_message_name  = $errors->get_error_message( 'blogname' );
                                            $error_message_title = $errors->get_error_message( 'blog_title' );

                                            if ( ! empty( $error_message_name ) ) {
                                                if ( 'Please enter a site name.' == $error_message_name ) {
                                                    $error_codes[] = 'no_address';
                                                } elseif ( 'Site name must be at least 4 characters.' == $error_message_name ) {
                                                    $error_codes[] = 'site_min4';
                                                } elseif ( 'Sorry, site names must have letters too!' == $error_message_name ) {
                                                    $error_codes[] = 'site_letters';
                                                } elseif ( 'Sorry, that site already exists!' == $error_message_name ) {
                                                    $error_codes[] = 'domain_exists';
                                                }
                                            } elseif ( ! empty( $error_message_title ) ) {
                                                if ( 'Please enter a site title.' == $error_message_title ) {
                                                    $error_codes[] = 'no_title';
                                                }
                                            }

                                            $errors       = join( ',', $error_codes );
                                            $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                        }

                                        if ( empty( $error_codes ) ) {
                                            // no errors
                                            $result = $this->b3_register_wpmu_user( $user_login, $user_email, $domain, $blog_title, $path, $meta_data );
                                            if ( is_wp_error( $result ) ) {
                                                $errors       = join( ',', $result->get_error_codes() );
                                                $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                            } else {
                                                if ( 'blog' == $registration_type ) {
                                                    // Success, redirect to message.
                                                    $redirect_url = add_query_arg( 'registered', 'new_blog', $redirect_url );
                                                    $redirect_url = add_query_arg( 'site_id', $result, $redirect_url );
                                                } elseif ( 'request_access_subdomain' == $registration_type ) {
                                                    $redirect_url = add_query_arg( 'registered', 'access_requested', $redirect_url );
                                                } elseif ( true == $result ) {
                                                    // Success, redirect to login page.
                                                    $redirect_url = b3_get_login_url();
                                                    $redirect_url = add_query_arg( 'registered', 'wpmu_confirm_email', $redirect_url );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            wp_safe_redirect( $redirect_url );
                            exit;
                        }
                    }
                }
            }


            /**
             * Resets the user's password if the password reset form was submitted (with custom passwords)
             *
             * @since 1.0.6
             */
            public function b3_reset_user_password() {
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    $rp_key   = ( isset( $_REQUEST[ 'rp_key' ] ) ) ? $_REQUEST[ 'rp_key' ] : false;
                    $rp_login = ( isset( $_REQUEST[ 'rp_login' ] ) ) ? $_REQUEST[ 'rp_login' ] : false;

                    if ( $rp_key && $rp_login ) {
                        $user = check_password_reset_key( $rp_key, $rp_login );

                        if ( ! $user || is_wp_error( $user ) ) {
                            $login_url = b3_get_login_url();
                            if ( $user && $user->get_error_code() === 'expired_key' ) {
                                $redirect_url = add_query_arg( 'login', 'expiredkey', $login_url );
                            } else {
                                $redirect_url = add_query_arg( 'login', 'invalidkey', $login_url );
                            }
                            wp_safe_redirect( $redirect_url );
                            exit;
                        }

                        if ( isset( $_POST[ 'pass1' ] ) ) {
                            if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] || empty( $_POST[ 'pass1' ] ) ) {
                                // Password is empty or don't match
                                $redirect_url = b3_get_reset_password_url();
                                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                                wp_safe_redirect( $redirect_url );
                                exit;
                            }

                            // Parameter checks OK, reset password
                            reset_password( $user, $_POST[ 'pass1' ] );
                            $redirect_url = b3_get_login_url();
                            $redirect_url = add_query_arg( 'password', 'changed', $redirect_url );

                            wp_safe_redirect( $redirect_url );
                            exit;

                        } else {
                            echo "Invalid request.";
                        }
                    }
                }
            }


            /**
             * Finds and returns a matching error message for the given error code.
             *
             * @since 1.0.6
             *
             * @param string $error_code The error code to look up.
             *
             * @return string               An error message.
             */
            public function b3_get_return_message( $error_code, $sprintf = false ) {

                switch( $error_code ) {
                    case 'empty_username':
                        return esc_html__( 'Please enter a user name', 'b3-onboarding' );

                    case 'empty_password':
                        return esc_html__( 'Please enter a password.', 'b3-onboarding' );

                    case 'incorrect_password':
                        $error_message = esc_html__( "The username or password you entered wasn't quite right.", 'b3-onboarding' );
                        $error_message .= '<br>';
                        $error_message .= sprintf( esc_attr__( 'Did you %s your password ?', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', wp_lostpassword_url(), esc_attr__( 'forget', 'b3-onboarding' ) ) );

                        return $error_message;

                    case 'logged_out':
                        return esc_html__( "You are logged out.", 'b3-onboarding' );

                    // Registration errors
                    case 'username_exists':
                        return esc_html__( 'This username is already in use.', 'b3-onboarding' );

                    case 'disallowed_username':
                        return esc_html__( 'That user name is not allowed, please choose another.', 'b3-onboarding' );

                    case 'invalid_email':
                        return esc_html__( 'The email address you entered is not valid.', 'b3-onboarding' );

                    case 'invalid_username':
                        if ( 1 == get_option( 'b3_register_email_only' ) ) {
                            return esc_html__( 'The email address you entered is not valid.', 'b3-onboarding' );
                        } else {
                            return esc_html__( 'The user login you entered is not valid.', 'b3-onboarding' );
                        }

                    case 'email_exists':
                        return esc_html__( 'An account already exists with this email address.', 'b3-onboarding' );

                    case 'closed':
                        return esc_html__( 'Registering new users is currently not allowed.', 'b3-onboarding' );

                    case 'recaptcha_failed':
                        return esc_html__( 'The Google reCAPTCHA check failed. Are you a robot?', 'b3-onboarding' );

                    case 'no_privacy':
                        return esc_html__( 'You have to accept the privacy statement.', 'b3-onboarding' );

                    case 'empty_field':
                        if ( false != $sprintf ) {
                            return sprintf( esc_html__( "You didn't select an option for '%s'.", 'b3-onboarding' ), $sprintf );
                        } else {
                            return esc_html__( "You didn't select an option.", 'b3-onboarding' );
                        }

                    case 'access_requested':
                        $access_requested_string = esc_html__( 'You have sucessfully requested access. Someone will check your request.', 'b3-onboarding' );
                        return apply_filters( 'b3_registration_access_requested_message', $access_requested_string );

                    case 'confirm_email':
                        $confirm_email_string = esc_html__( 'You have sucessfully registered but need to confirm your email address first. Please check your email for an activation link.', 'b3-onboarding' );
                        return apply_filters( 'b3_registration_confirm_email_message', $confirm_email_string );

                    case 'honeypot':
                        return esc_html__( 'No robo signups.', 'b3-onboarding' );

                    // Lost password
                    case 'invalidcombo':
                        return esc_html__( 'There are no users registered with this email address.', 'b3-onboarding' );

                    case 'wait_approval':
                        return esc_html__( 'You have to get approved first.', 'b3-onboarding' );

                    case 'wait_confirmation':
                        return esc_html__( 'You have to confirm your email address first. Please check your inbox.', 'b3-onboarding' );

                    case 'password_updated':
                        return esc_html__( 'Your password has been changed. You can login now.', 'b3-onboarding' );

                    case 'lost_password_sent':
                        return esc_html__( 'Check your email for a link to reset your password.', 'b3-onboarding' );

                    // Registration
                    case 'pw_too_easy':
                        return esc_html__( 'That password is too easy, please use a better one.', 'b3-onboarding' );

                    case 'registration_success':
                        if ( false == get_option( 'b3_activate_custom_passwords' ) ) {
                            return esc_html__( 'You have successfully registered. Please check your email for a link to set your password.', 'b3-onboarding' );
                        } else {
                            return esc_html__( 'You have successfully registered. You can now login.', 'b3-onboarding' );
                        }

                    case 'registration_success_enter_password':
                        return sprintf(
                            esc_html__( 'You have successfully registered to %s. Enter your email address to set your password.', 'b3-onboarding' ),
                            get_bloginfo( 'name' )
                        );

                    // Activation
                    case 'activate_success':
                        if ( false == get_option( 'b3_activate_custom_passwords' ) ) {
                            return esc_html__( 'You have successfully activated your account. You can initiate a password (re)set below.', 'b3-onboarding' );
                        } else {
                            return esc_html__( 'You have successfully activated your account. You can now login.', 'b3-onboarding' );
                        }

                    case 'mu_activate_success':
                        return esc_html__( 'You have successfully activated your account. Your password has been emailed to you.', 'b3-onboarding' );

                    case 'invalid_key':
                        return esc_html__( 'The activation link you used is not valid.', 'b3-onboarding' );

                    case 'invalid_user':
                        return esc_html__( 'There appears to be no user account associated with this link.', 'b3-onboarding' );

                    // Reset password
                    case 'expiredkey':  // same error as next
                    case 'invalidkey':
                        return esc_html__( 'The password reset link you used is not valid anymore.', 'b3-onboarding' );

                    case 'password_mismatch':
                    case 'password_reset_mismatch':
                        return esc_html__( "The two passwords you entered don't match.", 'b3-onboarding' );

                    case 'password_reset_empty':
                        return esc_html__( "Sorry, we don't accept empty passwords.", 'b3-onboarding' );

                    case 'password_too_easy':
                        return esc_html__( 'Sorry, that password is too easy.', 'b3-onbaording' );

                    // Multisite
                    case 'domain_exists':
                        return esc_html__( 'Sorry, this domain has already been taken.', 'b3-onboarding' );

                    case 'no_address':
                        return esc_html__( 'Please enter a site address.', 'b3-onboarding' );

                    case 'site_min4':
                        return esc_html__( 'Site name must be at least 4 characters.', 'b3-onboarding' );

                    case 'site_letters':
                        return esc_html__( 'Sorry, site names must have letters too.', 'b3-onboarding' );

                    case 'no_title':
                        return esc_html__( 'Please enter a title.', 'b3-onboarding' );

                    case 'user_registered':
                        return esc_html__( 'You have successfully registered. Please check your email for an activation link.', 'b3-onboarding' );

                    case 'wpmu_user_reserved':
                        return esc_html__( 'That username is currently reserved but may be available in a couple of days.', 'b3-onboarding' );

                    case 'wpmu_email_in_use':
                        return esc_html__( 'That email address has already been used. Please check your inbox for an activation email. It will become available in a couple of days if you do nothing.', 'b3-onboarding' );

                    // Account remove
                    case 'account_remove':
                        return esc_html__( 'Your account has been deleted.', 'b3-onboarding' );

                    // Admin
                    case 'settings_saved': // same message
                    case 'pages_saved': // same message
                    case 'emails_saved':
                        return esc_html__( 'Settings saved', 'b3-onboarding' );

                    // Validation
                    case 'no_space':
                        return esc_html__( "You can't use a space there.", 'b3-onboarding' );

                    // Used on website for showcase
                    case 'dummy':
                        return esc_html__( 'You have just registered an account successfully but since this is a demonstration setup, your user account has been deleted immediately again.', 'b3-onboarding' );

                    default:
                        break;
                }

                return esc_html__( 'An unknown error occurred. Please try again later.', 'b3-onboarding' );
            }


            /**
             * Validates and then completes the (normal) user signup process if all went well.
             *
             * @since 1.0.6
             *
             * @param $user_email
             * @param $user_login
             * @param $registration_type
             * @param $role
             *
             * @return int|void|WP_Error
             */
            private function b3_register_user( $user_email, $user_login, $registration_type, $role = 'subscriber' ) {
                $errors                       = new WP_Error();
                $registration_with_email_only = get_option( 'b3_register_email_only' );
                $user_data                    = array(
                    'user_login' => $user_login,
                    'user_email' => $user_email,
                    'user_pass'  => '', // for possible/future custom passwords
                    'role'       => $role,
                );
                $use_custom_passwords = true;

                if ( false == $registration_with_email_only ) {
                    if ( username_exists( $user_login ) ) {
                        $errors->add( 'username_exists', $this->b3_get_return_message( 'username_exists' ) );

                        return $errors;
                    }

                    if ( in_array( $user_login, b3_get_disallowed_usernames() ) ) {
                        $errors->add( 'disallowed_username', $this->b3_get_return_message( 'disallowed_username' ) );

                        return $errors;
                    }
                }

                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'invalid_email', $this->b3_get_return_message( 'invalid_email' ) );

                    return $errors;
                }

                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->b3_get_return_message( 'email_exists' ) );

                    return $errors;
                }

                if ( true == $use_custom_passwords ) {
                    if ( isset( $_POST[ 'pass1' ] ) && isset( $_POST[ 'pass2' ] ) ) {
                        $easy_passwords = array(
                            '1234',
                            '000000',
                            '111111',
                            '123456',
                            '12345678',
                            'abcdef',
                            'password',
                            'wachtwoord',
                        );
                        if ( in_array( $_POST[ 'pass1' ], $easy_passwords ) ) {
                            $errors->add( 'pw_too_easy', $this->b3_get_return_message( 'password_too_easy' ) );

                            return $errors;
                        }

                        if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] || empty( $_POST[ 'pass1' ] ) ) {
                            // Password is empty or don't match
                            $errors->add( 'password_mismatch', $this->b3_get_return_message( 'password_mismatch' ) );

                            return $errors;
                        } elseif ( $_POST[ 'pass1' ] == $_POST[ 'pass2' ] ) {
                            $hashed_password          = wp_hash_password( $_POST[ 'pass1' ] );
                            $user_data[ 'user_pass' ] = $hashed_password;
                        }
                    }
                }

                if ( ! b3_verify_privacy() ) {
                    $errors->add( 'no_privacy', $this->b3_get_return_message( 'no_privacy' ) );

                    return $errors;
                }

                $extra_field_errors = apply_filters( 'b3_extra_fields_validation', [] );
                if ( ! empty( $extra_field_errors ) ) {
                    foreach( $extra_field_errors as $extra_field_error ) {
                        $errors->add( $extra_field_error[ 'error_code' ], $extra_field_error[ 'error_message' ] );
                        $errors->add( 'field_' . $extra_field_error[ 'id' ], '' );
                    }

                    return $errors;
                }

                $user_id = wp_insert_user( $user_data );
                if ( ! is_wp_error( $user_id ) ) {
                    if ( true == $use_custom_passwords && isset( $_POST[ 'pass1' ] ) ) {
                        wp_set_password( $_POST[ 'pass1' ], $user_id );
                    }

                    $inform = 'both';
                    if ( 'email_activation' == $registration_type ) {
                        // never notify an admin if a user hasn't confirmed email yet
                        $inform = 'user';
                    }
                    $inform = apply_filters( 'b3_custom_register_inform', $inform );
                    wp_new_user_notification( $user_id, null, $inform );
                    do_action( 'b3_after_email_sent', $user_id, true );
                }

                return $user_id;
            }


            /**
             * Validates and then completes WPMU signup process if all went well.
             *
             * @since 1.0.6
             *
             * @param       $user_name
             * @param       $user_email
             * @param       $domain
             * @param array $meta
             *
             * @return bool|WP_Error
             */
            private function b3_register_wpmu_user( $user_name, $user_email, $domain, $blog_title, $path, $meta = array() ) {
                $b3_register_type = get_option( 'b3_registration_type' );

                if ( is_main_site() ) {
                    if ( in_array( $b3_register_type, [ 'request_access', 'request_access_subdomain', 'user', 'all', 'site' ] )) {
                        if ( false == $domain ) {
                            wpmu_signup_user( $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );

                            return true;
                        } else {
                            wpmu_signup_blog( $domain, $path, $blog_title, $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );

                            return true;
                        }
                    } elseif ( 'blog' == $b3_register_type ) {
                        if ( false != $user_name ) {
                            $user = get_user_by( 'login', $user_name );
                        } else {
                            $user = get_userdata( get_current_user_id() );
                        }
                        $blog_id = wpmu_create_blog( $domain, $path, $blog_title, $user->ID, apply_filters( 'add_signup_meta', $meta ), get_current_network_id() );

                        return $blog_id;
                    } else {
                        $errors = new WP_Error( 'unknown', $this->b3_get_return_message( 'unknown' ) );
                    }
                }

                if ( isset( $errors ) && false != $errors ) {
                    return $errors;
                }

                return false;
            }


            /**
             * Renders the contents of the given template to a string and returns it.
             *
             * @since 1.0.6
             *
             * @param string $template_name The name of the template to render (without .php)
             * @param array $attributes The PHP variables for the template
             *
             * @return string               The contents of the template.
             */
            public function b3_get_template_html( $template_name, $attributes = null ) {
                if ( ! $attributes ) {
                    $attributes = array();
                }

                if ( 'user-management' == $template_name ) {
                    $template_paths = array(
                        B3_PLUGIN_PATH . '/templates/',
                    );
                } else {
                    $template_paths = array(
                        get_stylesheet_directory() . '/b3-onboarding/',
                        get_stylesheet_directory() . '/plugins/b3-onboarding/',
                        get_template_directory() . '/b3-onboarding/',
                        get_template_directory() . '/plugins/b3-onboarding/',
                        B3_PLUGIN_PATH . '/templates/',
                    );
                }
                foreach( $template_paths as $possible_location ) {
                    if ( file_exists( $possible_location . $template_name . '.php' )) {
                        $location = $possible_location;
                        break;
                    }
                }

                ob_start();
                do_action( 'b3_do_before_' . $template_name );
                include $location . $template_name . '.php';
                do_action( 'b3_do_after_' . $template_name );
                $html = ob_get_contents();
                ob_end_clean();

                return $html;
            }


            /**
             * Add admin notices
             *
             * @since 1.0.6
             */
            public function b3_admin_notices() {
                $screen_ids = [
                    'toplevel_page_b3-onboarding',
                    'b3-onboarding_page_b3-debug',
                    'b3-onboarding_page_b3-user-approval',
                ];

                if ( in_array( get_current_screen()->id, $screen_ids ) ) {
                    if ( strpos( $this->settings[ 'version' ], 'beta' ) !== false ) {
                        $message = sprintf( esc_html__( "You're using a beta version of %s, which is not released yet and can give some unexpected results.", 'b3-onboarding' ), 'B3 OnbOarding' );
                        if ( is_localhost() ) {
                            echo sprintf( '<div class="notice notice-warning"><p>%s</p></div>', $message );
                        } else {
                            echo sprintf( '<div class="error"><p>%s</p></div>', $message );
                        }
                    } else {
                        if ( 'none' != get_option( 'b3_registration_type' ) ) {
                            if ( false == get_option( 'b3_register_page_id' ) ) {
                                $message = sprintf( esc_html__( "You haven't set a page yet for registration. Set it %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-onboarding&tab=pages' ), esc_html__( 'here', 'b3-onboarding' ) ) );
                                echo sprintf( '<div class="error"><p>%s</p></div>', $message );
                            }
                        }
                    }
                }

                // no page for front-end approval
                if ( false == get_option( 'b3_approval_page_id' ) && true == get_option( 'b3_front_end_approval' ) ) {
                    echo sprintf( '<div class="error"><p>%s</p></div>', sprintf( esc_html__( 'You have not set a page for front-end user approval. Set it %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=pages' ) ), esc_html__( 'here', 'b3-onboarding' ) ) ) );
                }

                // manual actions
                // @TODO: B4L: look into this, when is it used
                if ( isset( $_GET[ 'update' ] ) ) {
                    if ( in_array( $_GET[ 'update' ], array( 'activated', 'sendactivation' ) ) ) {
                        echo '<div id="message" class="updated"><p>';
                        if ( 'activated' == $_GET[ 'update' ] ) {
                            _e( 'User activated.', 'b3-onboarding' );
                        } elseif ( 'sendactivation' == $_GET[ 'update' ] ) {
                            _e( 'Activation mail resent.', 'b3-onboarding' );
                        }
                        echo '</p></div>';
                    }
                }

                global $pagenow;
                if ( is_blog_admin() && $pagenow === 'options-general.php' && ! isset ( $_GET[ 'page' ] ) && ! is_multisite() ) {
                    echo sprintf( '<div class="notice notice-info"><p>'. esc_html__( "%s takes control over the 'Membership' option. You can change this %s.", 'b3-onboarding' ) . '</p></div>',
                        'B3 OnBoarding',
                        sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=registration' ) ), esc_html__( 'here', 'b3-onboarding' ) )
                    );
                }

                if ( get_option( 'b3_activate_filter_validation' ) ) {
                    do_action( 'b3_verify_filter_input' );
                }
            }


            /**
             * Do stuff after create site
             *
             * @param $site
             */
            public function b3_after_create_site( $site ) {
                // create necessary pages
                b3_setup_initial_pages( $site->blog_id );
                // set default values
                b3_set_default_settings( $site->blog_id );
            }
        }


        /**
         * The main function responsible for returning the one true B3Onboarding instance to functions everywhere.
         *
         * @return \B3Onboarding
         */
        function init_b3_onboarding() {
            global $b3_onboarding;

            if ( ! isset( $b3_onboarding ) ) {
                $b3_onboarding = new B3Onboarding();
                $b3_onboarding->init();
            }

            return $b3_onboarding;
        }

        init_b3_onboarding();

    }
