<?php
    /*
    Plugin Name: B3 - Onboarding
    Version: 1.0.4
    Tags: user, management, registration, login, forgot password, reset password, account
    Description: This plugin handles the registration/login process
    Author: Beee
    Author URI: http://www.berryplasman.com
    Text-domain: b3-onboarding
    License: GNU v3

    http://www.berryplasman.com
       ___  ____ ____ ____
      / _ )/ __/  __/  __/
     / _  / _/   _/   _/
    /____/___/____/____/

    */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
    
    if ( false == get_option( 'b3_approval_page_id' ) && true == get_option( 'b3_front_end_approval' ) ) {
        add_action( 'admin_notices', function () {
            echo sprintf( '<div class="error"><p>'. __( 'You have not set a page for front-end user approval. Set it <a href="%s">%s</a>', 'b3-onboarding' ) . '.</p></div>',
                esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=pages' ) ),
                esc_html__( 'here', 'b3-onboarding' )
            );
        } );
    }

    if ( ! class_exists( 'B3Onboarding' ) ) {
    
        class B3Onboarding {
        
            /**
             * Initializes the plugin.
             *
             * To keep the initialization fast, only add filter and action
             * hooks in the constructor.
             */
            /**
             *  A dummy constructor to ensure plugin is only initialized once
             */
            function __construct() {
                if ( ! defined( 'B3_PLUGIN_URL' ) ) {
                    $plugin_url = plugins_url( '', __FILE__ );
                    define( 'B3_PLUGIN_URL', $plugin_url );
                }
    
                if ( ! defined( 'B3_PLUGIN_PATH' ) ) {
                    $plugin_path = trailingslashit( dirname( __FILE__ ) );
                    define( 'B3_PLUGIN_PATH', $plugin_path );
                }
    
                if ( ! defined( 'B3_PLUGIN_SETTINGS' ) ) {
                    $plugin_url = admin_url( 'admin.php?page=b3-onboarding' );
                    define( 'B3_PLUGIN_SETTINGS', $plugin_url );
                }
            }
    
            function initialize() {
                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '1.0.4',
                );
    
                // set text domain
                load_plugin_textdomain( 'b3-onboarding', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    
                // actions
                register_activation_hook( __FILE__,            array( $this, 'b3_plugin_activation' ) );
                register_deactivation_hook( __FILE__,          array( $this, 'b3_plugin_deactivation' ) );
            
                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_enqueue_scripts_frontend' ), 40 );
                add_action( 'admin_enqueue_scripts',                array( $this, 'b3_enqueue_scripts_backend' ) );
                add_action( 'admin_menu',                           array( $this, 'b3_add_admin_pages' ) );
                add_action( 'widgets_init',                         array( $this, 'b3_register_widgets' ) );
                add_action( 'wp_dashboard_setup',                   array( $this, 'b3_add_dashboard_widget' ) );
                add_action( 'login_redirect',                       array( $this, 'b3_redirect_after_login' ), 10, 3 );
                add_action( 'wp_logout',                            array( $this, 'b3_redirect_after_logout' ) );
                add_action( 'init',                                 array( $this, 'b3_init' ) );
                add_action( 'wp_head',                              array( $this, 'b3_robots' ) );
                add_action( 'template_redirect',                    array( $this, 'b3_template_redirect' ) );
                add_action( 'init',                                 array( $this, 'b3_redirect_to_custom_wpmu_register' ) ); // ???
                add_action( 'login_form_register',                  array( $this, 'b3_redirect_to_custom_register' ) );
                add_action( 'login_form_register',                  array( $this, 'b3_registration_form_handling' ) );
                add_action( 'login_form_login',                     array( $this, 'b3_redirect_to_custom_login' ) );
                add_action( 'login_form_lostpassword',              array( $this, 'b3_redirect_to_custom_lostpassword' ) );
                add_action( 'login_form_resetpass',                 array( $this, 'b3_redirect_to_custom_password_reset' ) );
                add_action( 'login_form_rp',                        array( $this, 'b3_redirect_to_custom_password_reset' ) );
                add_action( 'init',                                 array( $this, 'b3_do_user_activate' ) );
                add_action( 'login_form_lostpassword',              array( $this, 'b3_do_password_lost' ) );
                add_action( 'login_form_resetpass',                 array( $this, 'b3_do_password_reset' ) );
                add_action( 'login_form_rp',                        array( $this, 'b3_do_password_reset' ) );
                // add_action( 'wp_print_footer_scripts',              array( $this, 'b3_add_captcha_js_to_footer' ) );
    
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this, 'b3_settings_link' ) );
                
                add_filter( 'display_post_states',                  array( $this, 'b3_add_post_state' ), 10, 2 );
                add_filter( 'authenticate',                         array( $this, 'b3_maybe_redirect_at_authenticate' ), 101, 3 );
                add_filter( 'wp_mail_from',                         array( $this, 'b3_email_from' ) );
                add_filter( 'wp_mail_from_name',                    array( $this, 'b3_email_from_name' ) );
                add_filter( 'wp_mail_content_type',                 array( $this, 'b3_email_content_type' ) );
                add_filter( 'wp_mail_charset',                      array( $this, 'b3_email_charset' ) );
                add_filter( 'page_link',                            array( $this, 'b3_logout_link' ), 10, 2 );
    
    
                add_shortcode( 'register-form',                array( $this, 'b3_render_register_form' ) );
                add_shortcode( 'login-form',                   array( $this, 'b3_render_login_form' ) );
                add_shortcode( 'forgotpass-form',              array( $this, 'b3_render_forgot_password_form' ) );
                add_shortcode( 'resetpass-form',               array( $this, 'b3_render_reset_password_form' ) );
                add_shortcode( 'account-page',                 array( $this, 'b3_render_account_page' ) );
                add_shortcode( 'user-management',              array( $this, 'b3_render_user_approval_page' ) );
                add_shortcode( 'delete-account',               array( $this, 'b3_render_delete_account_page' ) );
    
                include( 'includes/do-stuff.php' );
                include( 'includes/emails.php' );
                include( 'includes/filters.php' );
                include( 'includes/form-handling.php' );
                include( 'includes/functions.php' );
                include( 'includes/get-stuff.php' );
                include( 'includes/help-tabs.php' );
                include( 'includes/tabs.php' );
            }
            
            /**
             * Do stuff upon plugin activation
             */
            public function b3_plugin_activation() {
            
                // create necessary pages
                b3_create_initial_pages();
                
                $this->b3_set_default_settings();
    
                /**
                 * Independent
                 */
                $aw_activation = get_role( 'b3_activation' );
                if ( ! $aw_activation ) {
                    add_role( 'b3_activation', __( 'Awaiting activation' ), [] );
                }
                $aw_approval = get_role( 'b3_approval' );
                if ( ! $aw_approval ) {
                    add_role( 'b3_approval', __( 'Awaiting approval' ), [] );
                }
    
            }
    
    
            /**
             * Set default settings
             */
            private function b3_set_default_settings() {
                
                if ( ! is_multisite() ) {
                    update_option( 'users_can_register', '0' );
                    update_option( 'b3_registration_type', 'open' );
                } else {
                    
                    // @TODO: check if settings should be preserved
                    $public_registration = get_site_option( 'registration' );
                    
                    if ( is_main_site() ) {
                        if ( 'user' == $public_registration ) {
                            update_blog_option( get_current_blog_id(), 'b3_registration_type', 'ms_register_user' );
                        } elseif ( 'blog' == $public_registration ) {
                            update_blog_option( get_current_blog_id(), 'b3_registration_type', 'ms_loggedin_register' );
                        } elseif ( 'all' == $public_registration ) {
                            update_blog_option( get_current_blog_id(), 'b3_registration_type', 'ms_register_site_user' );
                        }
                    } else {
                        if ( 'user' == $public_registration ) {
                            update_blog_option( get_current_blog_id(), 'b3_registration_type', 'open' );
                        }
                    }
                }
                
                update_option( 'b3_dashboard_widget', '1' );
                update_option( 'b3_email_styling', b3_default_email_styling() );
                update_option( 'b3_email_template', b3_default_email_template() );
                update_option( 'b3_notification_content_type', 'html' );
                update_option( 'b3_notification_sender_email', get_bloginfo( 'admin_email' ) );
                update_option( 'b3_notification_sender_name', get_bloginfo( 'name' ) );
                update_option( 'b3_restrict_admin', [ 'subscriber', 'b3_activation', 'b3_approval' ] );
                update_option( 'b3_sidebar_widget', '1' );
                update_option( 'b3_themed_profile', [ 'subscriber' ] );
                
                // 2 use
                // update_option( 'b3_mail_sending_method', 'wpmail' );
    
            }
    
            /**
             * Do stuff upon plugin activation
             */
            public function b3_plugin_deactivation() {
                
                $meta_keys = b3_get_all_custom_meta_keys();
                foreach ( $meta_keys as $key ) {
                    delete_option( $key );
                }
    
                $roles = array(
                    'b3_activation',
                    'b3_approval',
                );
                foreach( $roles as $role ) {
                    remove_role( $role );
                }
            }
            
            public function b3_init() {
                global $current_user, $pagenow;
                if ( is_user_logged_in() && is_admin() ) {
    
                    $allow_admin  = [ 'administrator' ];
                    $themed_users = [ 'subscriber' ];
                    $redirect_to  = '';
                    if ( in_array( $themed_users, $current_user->roles ) ) {
                        $redirect_to = home_url( 'account' ); // @TODO: make dynamic
                    }
        
                    $user_role = reset( $current_user->roles );
                    
                    if ( is_multisite() && empty( $user_role ) ) {
                        $user_role = 'subscriber';
                    }
    
                    if ( 'profile.php' == $pagenow && ! isset( $_REQUEST[ 'page' ] ) ) {
                        if ( in_array( $themed_users, $current_user->roles ) ) {
                            if ( ! empty( $_GET ) ) {
                                $redirect_to = add_query_arg( (array) $_GET, $redirect_to );
                            }
                            wp_redirect( $redirect_to );
                            exit;
                        }
                    } else {
                        if ( ! in_array( $user_role, $allow_admin ) ) {
                            if ( ! defined( 'DOING_AJAX' ) ) {
                                wp_redirect( $redirect_to ); // to profile
                                exit;
                            }
                        }
                    }
                }
            }
            
            public function b3_robots() {
                if ( is_singular() ) {
                    $has_meta = get_post_meta( get_the_ID(), '_b3_page', true );
                    
                    if ( true == $has_meta && true == get_option( 'blog_public' ) ) {
                        echo '<meta name="robots" content="noindex,follow" />';
                    }
                }
            }
            
    
            public function b3_template_redirect() {
    
                $account_page_id  = get_option( 'b3_account_page_id' );
                $account_url      = ( false != $account_page_id ) ? get_permalink( $account_page_id ) : admin_url( 'profile.php' );
                $approval_page_id = get_option( 'b3_approval_page_id' );
                $login_page_id    = get_option( 'b3_login_page_id' );
                $login_url        = ( false != $login_page_id ) ? get_permalink( $login_page_id ) : wp_login_url();
                $logout_page_id   = get_option( 'b3_logout_page_id' );
                
                if ( false != $account_page_id && is_page( [ $account_page_id ] ) && ! is_user_logged_in() ) {
                    
                    wp_redirect( $login_url );
                    exit;
                    
                } elseif ( false != $approval_page_id && is_page( $approval_page_id ) ) {
                    if ( is_user_logged_in() ) {
                        if ( ! current_user_can( 'promote_users' ) ) {
                            wp_redirect( $account_url );
                            exit;
                        }
                    } else {
                        wp_redirect( $login_url );
                        exit;
                    }
                    
                } elseif ( false != $logout_page_id && is_page( [ $logout_page_id ] ) ) {
    
                    check_admin_referer( 'log-out' );
    
                    $user = wp_get_current_user();
    
                    wp_logout();
    
                    if ( ! empty( $_REQUEST[ 'redirect_to' ] ) ) {
                        $redirect_to           = $_REQUEST[ 'redirect_to' ];
                        $requested_redirect_to = '';
                    } else {
                        $redirect_to           = site_url( 'wp-login.php?loggedout=true' );
                        $requested_redirect_to = '';
                    }
    
                    $redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
                    wp_safe_redirect( $redirect_to );
                    exit;

                }
            }
    
    
            /**
             * Enqueue scripts front-end
             */
            public function b3_enqueue_scripts_frontend() {
                wp_enqueue_style( 'b3-ob-main', plugins_url( 'assets/css/style.css', __FILE__, [], $this->settings['version'] ) );
                wp_enqueue_script( 'b3-ob-js', plugins_url( 'assets/js/js.js', __FILE__ ), array( 'jquery' ), $this->settings['version'] );
            }
        
        
            /**
             * Enqueue scripts in backend
             */
            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3-ob-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), [], $this->settings['version'] );
                wp_enqueue_script( 'b3-ob-js-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->settings['version'] );
            }
        
        
            /**
             * Adds a page to admin sidebar menu
             */
            public function b3_add_admin_pages() {
                include( 'includes/admin-page.php' ); // content for the settings page
                add_menu_page( 'B3 Onboarding', 'B3 Onboarding', 'manage_options', 'b3-onboarding', 'b3_user_register_settings', '', '99' );
                if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                    include( 'includes/user-approval-page.php' ); // content for the settings page
                    add_submenu_page( 'b3-onboarding', 'User Approval', 'User Approval', 'manage_options', 'b3-user-approval', 'b3_user_approval' );
                }
            }
    
    
            /**
             * Register widgets (if activated)
             */
            public function b3_register_widgets() {
                if ( true == get_option( 'b3_sidebar_widget' ) ) {
                    include( 'includes/B3SidebarWidget.php' );
                    register_widget( 'B3SidebarWidget' );
                }
            }
    
    
            /**
             * Add dashboard widget
             */
            public function b3_add_dashboard_widget() {
                if ( true == get_option( 'b3_dashboard_widget' ) ) {
                    include( 'includes/dashboard-widget.php' );
                    wp_add_dashboard_widget( 'b3-dashboard', 'B3 Onboarding', 'b3_dashboard_widget_function' );
                }
            }
    
    
            /**
             * Add post states for plugin pages
             *
             * @param $post_states
             * @param $post
             *
             * @return array
             */
            public function b3_add_post_state( $post_states, $post ) {
        
                $title_suffix = false;
                if ( $post->ID == b3_get_account_id() ) {
                    if ( $post->post_title != 'Account' ) {
                        $title_suffix = ': Account';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                } elseif ( $post->ID == b3_get_register_id() ) {
                    if ( $post->post_title != 'Register' ) {
                        $title_suffix = ': Register';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                } elseif ( $post->ID == b3_get_login_id() ) {
                    if ( $post->post_title != 'Login' ) {
                        $title_suffix = ': Login';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                } elseif ( $post->ID == b3_get_logout_id() ) {
                    if ( $post->post_title != 'Log Out' ) {
                        $title_suffix = ': Log out';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                } elseif ( $post->ID == b3_get_forgotpass_id() ) {
                    if ( $post->post_title != 'Forgot password' ) {
                        $title_suffix = ': Forgot password';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                } elseif ( $post->ID == b3_get_resetpass_id() ) {
                    if ( $post->post_title != 'Reset password' ) {
                        $title_suffix = ': Reset password';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                } elseif ( $post->ID == b3_get_user_approval_id() ) {
                    if ( $post->post_title != 'User approval' ) {
                        $title_suffix = ': User approval';
                    }
                    $post_states[] = 'B3' . $title_suffix;
                }
        
                return $post_states;
            }
    
    
            /**
             * Add settings link to plugin page
             *
             * @param $links
             *
             * @return mixed
             */
            public function b3_settings_link( $links ) {
                $settings_link = '<a href="admin.php?page=b3-onboarding">' . esc_html__( 'Settings', 'b3-onboarding' ) . '</a>';
                // add if for if add-ons are active
                $add_ons_link  = '<a href="admin.php?page=b3-onboarding&tab=addon">' . esc_html__( 'Add-ons', 'b3-onboarding' ) . '</a>';
                array_unshift( $links, $settings_link );
        
                return $links;
            }
    
    
            /**
             * For filter 'wp_mail_from', returns a proper from-address when sending e-mails
             *
             * @param   string $original_email_address
             * @return  string
             */
            public function b3_email_from( $original_email_address ) {
                // Make sure the email adress is from the same domain as your website to avoid being marked as spam.
                $from_email = get_option( 'b3_notification_sender_email' );
                if ( $from_email ) {
                    return $from_email;
                }
        
                return $original_email_address;
            }
    
    
            /**
             * For filter 'wp_mail_from_name', returns a proper from-name when sending e-mails
             *
             * @param   string $original_email_from
             * @return  string
             */
            public function b3_email_from_name( $original_from_name ) {
        
                $sender_name = get_option( 'b3_notification_sender_name' );
                if ( $sender_name ) {
                    return $sender_name;
                }
        
                return $original_from_name;
            }
    
    
            /**
             * For filter 'wp_mail_content_type', overrides content-type
             * Always return HTML
             *
             * @return  string
             */
            public function b3_email_content_type( $content_type ) {
                return 'text/html';
            }
    
    
            /**
             * For filter 'wp_mail_charset', overrides char-set
             *
             * @return  string
             */
            public function b3_email_charset( $charset ) {
                $char_set = get_option( 'b3_email_charset' );
                if ( $char_set ) {
                    return $char_set;
                }
        
                return 'UTF-8';
            }
    
    
            /**
             * Rewrites lug out page link
             *
             * @param $permalink
             * @param $post_id
             *
             * @return string
             */
            public function b3_logout_link( $permalink, $post_id ) {

                if ( get_option( 'b3_logout_page_id' ) == $post_id ) {
                    $permalink = add_query_arg( '_wpnonce', wp_create_nonce( 'log-out' ), $permalink );
                }

                return $permalink;
            }


            /**
             * An action function used to include the reCAPTCHA JavaScript file
             * at the end of the page.
             */
            public function b3_add_captcha_js_to_footer() {
                echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
            }
    
    
            /**
             * Checks that the reCAPTCHA parameter sent with the registration
             * request is valid.
             *
             * @return bool True if the CAPTCHA is OK, otherwise false.
             * is private function
             */
            private function b3_verify_recaptcha() {
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


            /**
             * Handle registration form
             */
            public function b3_registration_form_handling() {
            
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( isset( $_POST[ 'b3_register_user' ] ) ) {
                        $redirect_url = home_url( 'register' ); // make dynamic from function
                        if ( ! wp_verify_nonce( $_POST[ "b3_register_user" ], 'b3-register-user' ) ) {
                            // error ?
                        } else {
    
                            $meta_data                 = [];
                            $user_login                = ( isset( $_POST[ 'user_login' ] ) ) ? $_POST[ 'user_login' ] : false;
                            $user_email                = ( isset( $_POST[ 'user_email' ] ) ) ? $_POST[ 'user_email' ] : false;
                            $role                      = apply_filters( 'b3_filter_default_role', get_option( 'default_role' ) );
                            $registration_type         = get_option( 'b3_registration_type' );
                            $meta_data                 = apply_filters( 'b3_check_custom_registration_fields', $meta_data );
                            $meta_data[ 'first_name' ] = ( isset( $_POST[ 'first_name' ] ) ) ? sanitize_text_field( $_POST[ 'first_name' ] ) : false;
                            $meta_data[ 'last_name' ]  = ( isset( $_POST[ 'last_name' ] ) ) ? sanitize_text_field( $_POST[ 'last_name' ] ) : false;
                            
                            if ( ! is_multisite() ) {
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                    
                                } elseif ( get_option( 'b3_recaptcha' ) && ! $this->b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'captcha', $redirect_url );
    
                                } elseif ( 'closed' != $registration_type ) {
                                    // Registration is not closed
                                    if ( 'request_access' == $registration_type ) {
                                        $role      = 'b3_approval';
                                        $query_arg = 'access_requested';
                                    } elseif ( 'email_activation' == $registration_type ) {
                                        $role      = 'b3_activation';
                                        $query_arg = 'email_confirmation';
                                    } else {
                                        $query_arg = 'success';
                                    }

                                    $result = $this->b3_register_user( $user_email, $user_login, $registration_type, $role, $meta_data );
    
                                    if ( is_wp_error( $result ) ) {
                                        // Parse errors into a string and append as parameter to redirect
                                        $errors       = join( ',', $result->get_error_codes() );
                                        $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                    } else {
                                        // Success, redirect to login page.
                                        $redirect_url = home_url( 'login' ); // @TODO: make dynamic
                                        $redirect_url = add_query_arg( 'registered', $query_arg, $redirect_url );
                                    }
    
                                }
                                
                            } else {
                                
                                // if is_multisite
                                $register = false;
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                } elseif ( get_option( 'b3_recaptcha' ) && ! $this->b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'captcha', $redirect_url );
                                } elseif ( in_array( $registration_type, [ 'request_access', 'email_activation', 'ms_register_site_user' ] ) ) {
                                    $register = true;
                                } else {
                                }

                                if ( true == $register ) {
                                    // is_multisite
                                    $meta_data[ 'blog_public' ] = '1';
                                    $meta_data[ 'lang_id' ]     = '0';
                                    $sub_domain                 = ( isset( $_POST[ 'b3_subdomain' ] ) ) ? $_POST[ 'b3_subdomain' ] : false;
                                    
                                    if ( false != $sub_domain ) {
                                        // @TODO: check this for options
                                        if ( true == domain_exists( $sub_domain, '/' ) ) {
                                            $redirect_url = add_query_arg( 'registration-error', 'domain_exists', $redirect_url );
                                        } else {
                                            $result = $this->b3_register_wpmu_user( $user_login, $user_email, $sub_domain, $meta_data );
        
                                            if ( is_wp_error( $result ) ) {
                                                // Parse errors into a string and append as parameter to redirect
                                                $errors       = join( ',', $result->get_error_codes() );
                                                $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                            } else {
                                                // Success, redirect to login page.
                                                $redirect_url = home_url( 'login' ); // @TODO: make dynamic
                                                $redirect_url = add_query_arg( 'registered', 'confirm_email', $redirect_url );
                                            }
                                        }
                                    } else {
                                        // no subdomain entered
                                        error_log('no subdomain entered');
                                        $result = $this->b3_register_wpmu_user( $user_login, $user_email, $sub_domain, $meta_data );
                                    }
                                }
                            }
                        }
                    
                        wp_redirect( $redirect_url );
                        exit;
                    
                    }
                }
            }
        
            
            ## REDIRECTS
            
            /**
             * Redirect the user after authentication if there were any errors.
             *
             * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
             * @param string            $username   The user name used to log in.
             * @param string            $password   The password used to log in.
             *
             * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
             */
            function b3_maybe_redirect_at_authenticate( $user, $username, $password ) {
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


            ## Redirect away from default WP pages
    
            /**
             * Redirects the user to the custom registration page instead
             * of wp-login.php?action=register.
             */
            public function b3_redirect_to_custom_register() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user();
                    } else {
                        wp_redirect( home_url( 'register' ) );
                    }
                    exit;
                }
            }
    
    
            /**
             * Redirects the user to the custom registration page instead
             * of wp-login.php?action=register.
             */
            public function b3_redirect_to_custom_wpmu_register() {
                if ( '/wp-signup.php' == $_SERVER[ 'REQUEST_URI' ] ) {
                    wp_redirect( home_url( 'register' ) ); // @TODO: get from function
                    exit;
                }
            }
    
    
            /**
             * Redirect the user to the custom login page instead of wp-login.php.
             */
            function b3_redirect_to_custom_login() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            
                    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : null;
            
                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user( $redirect_to );
                        exit;
                    }
            
                    // The rest are redirected to the login page
                    $login_url = home_url( 'login' ); // get from constant
                    if ( ! empty( $redirect_to ) ) {
                        $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
                    }
            
                    wp_redirect( $login_url );
                    exit;
                }
            }
    
    
            /**
             * Redirects the user to the custom "Forgot your password?" page instead of
             * wp-login.php?action=lostpassword.
             */
            public function b3_redirect_to_custom_lostpassword() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user();
                        exit;
                    }
        
                    if ( false != b3_get_forgotpass_id() ) {
                        wp_redirect( get_permalink( b3_get_forgotpass_id() ) );
                        exit;
                    } else {
                        wp_redirect( home_url( 'lostpassword' ) ); // @TODO: make filterable
                        exit;
                    }
                }
            }
    
    
            /**
             * Redirects to the custom password reset page, or the login page
             * if there are errors.
             */
            public function b3_redirect_to_custom_password_reset() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    // Verify key / login combo
                    $user = check_password_reset_key( $_REQUEST[ 'key' ], $_REQUEST[ 'login' ] );
                    if ( ! $user || is_wp_error( $user ) ) {
                        if ( $user && $user->get_error_code() === 'expired_key' ) {
                            wp_redirect( home_url( 'login?login=expiredkey' ) );
                        } else {
                            wp_redirect( home_url( 'login?login=invalidkey' ) );
                        }
                        exit;
                    }
    
                    $redirect_url = b3_get_resetpass_url();
                    $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST[ 'login' ] ), $redirect_url );
                    $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST[ 'key' ] ), $redirect_url );
            
                    wp_redirect( $redirect_url );
                    exit;
                }
            }
            
            /**
             * Redirects the user to the correct page depending on whether he / she
             * is an admin or not.
             *
             * @param string $redirect_to An optional redirect_to URL for admin users
             */
            private function b3_redirect_logged_in_user( $redirect_to = null ) {
                $user = wp_get_current_user();
                if ( user_can( $user, 'manage_options' ) ) {
                    if ( $redirect_to ) {
                        wp_safe_redirect( $redirect_to );
                    } else {
                        wp_redirect( admin_url() );
                    }
                } else {
                    wp_redirect( home_url() );
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
            public function b3_redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
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
                    $redirect_url = home_url( 'account' ); // @TODO: make filterable
                }
        
                return wp_validate_redirect( $redirect_url, home_url() );
            }
            
            
            /**
             * Redirect to custom login page after the user has been logged out.
             */
            function b3_redirect_after_logout() {
                $redirect_url = home_url( 'login?logged_out=true' );
                wp_safe_redirect( $redirect_url );
                exit;
            }
    
    
            /**
             * Initiates user activation
             */
            function b3_do_user_activate() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( ! empty( $_GET[ 'action' ] ) && 'activate' == $_GET[ 'action' ] && ! empty( $_GET[ 'key' ] ) && ! empty( $_GET[ 'user_login' ] ) ) {
    
                        global $wpdb;
                        
                        $key = preg_replace( '/[^a-zA-Z0-9]/i', '', $_GET[ 'key' ] );
    
                        $errors = false;
                        if ( empty( $key ) || ! is_string( $key ) ) {
                            $errors = new WP_Error( 'invalid_key', __( 'Invalid key', 'b3-onboarding' ) );
                        }
    
                        if ( empty( $_GET[ 'user_login' ] ) || ! is_string( $_GET[ 'user_login' ] ) ) {
                            $errors = new WP_Error( 'invalid_key', __( 'Invalid key', 'b3-onboarding' ) );
                        }
    
                        // Validate activation key
                        $user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $_GET[ 'user_login' ] ) );

                        if ( empty( $user ) ) {
                            $errors = new WP_Error( 'invalid_key', __( 'Invalid key', 'b3-onboarding' ) );
                        }

                        if ( is_wp_error( $errors ) ) {
                            // Errors found
                            
                            $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), home_url( 'login' ) ); // @TODO: make dynamic
                        } else {

                            if ( false != b3_get_forgotpass_id() ) {
                                $lostpassword_url = get_permalink( b3_get_forgotpass_id() );
                            } else {
                                $lostpassword_url = home_url( 'lostpassword' );
                            }
                            $redirect_url = add_query_arg( array( 'activate' => 'success' ), $lostpassword_url );

                            // remove user_activation_key
                            $wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'user_login' => $_GET[ 'user_login' ] ) );

                            // activate user, change user role
                            $user_object = new WP_User( $user->ID );
                            $user_object->set_role( get_option( 'default_role' ) );
    
                            do_action( 'b3_new_user_activated', $user->ID );
    
                        }
    
                        wp_redirect( $redirect_url );
                        exit;
                    }
                }
            }
    
    
            /**
             * Initiates password reset.
             */
            public function b3_do_password_lost() {
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    $errors = retrieve_password();
                    if ( is_wp_error( $errors ) ) {
                        // Errors found
                        $redirect_url = home_url( 'lostpassword' );
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
             * Resets the user's password if the password reset form was submitted (with custom passwords)
             */
            public function b3_do_password_reset() {
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    $rp_key   = ( isset( $_REQUEST[ 'rp_key' ] ) ) ? $_REQUEST[ 'rp_key' ] : false;
                    $rp_login = ( isset( $_REQUEST[ 'rp_login' ] ) ) ? $_REQUEST[ 'rp_login' ] : false;
            
                    if ( $rp_key && $rp_login ) {
    
                        $user = check_password_reset_key( $rp_key, $rp_login );
                        if ( ! $user || is_wp_error( $user ) ) {
                            if ( $user && $user->get_error_code() === 'expired_key' ) {
                                wp_redirect( home_url( 'login?login=expiredkey' ) );
                            } else {
                                wp_redirect( home_url( 'login?login=invalidkey' ) );
                            }
                            exit;
                        }
    
                        if ( isset( $_POST[ 'pass1' ] ) ) {
                            if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] ) {
                                // Passwords don't match
                                $redirect_url = b3_get_resetpass_url();
                                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
            
                                wp_redirect( $redirect_url );
                                exit;
                            }
        
                            if ( empty( $_POST[ 'pass1' ] ) ) {
                                // Password is empty
                                $redirect_url = b3_get_resetpass_url();
                                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
            
                                wp_redirect( $redirect_url );
                                exit;
                            }
        
                            // Parameter checks OK, reset password
                            reset_password( $user, $_POST[ 'pass1' ] );
                            wp_redirect( home_url( 'login?password=changed' ) );
        
                        } else {
                            echo "Invalid request.";
                        }
    
                    } else {
                        echo "Invalid request.";
                    }
            
                    exit;
                }
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
            public function b3_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
                // Create new message
                error_log( 'public function b3_replace_retrieve_password_message' );
                $msg = __( 'Hello!', 'b3-onboarding' ) . "\r\n\r\n";
                $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'b3-onboarding' ), $user_login ) . "\r\n\r\n";
                $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\r\n\r\n";
                $msg .= __( 'To reset your password to something you\'d like, visit the following address:', 'b3-onboarding' ) . "\r\n\r\n";
                $msg .= network_site_url( "wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode( $user_data->user_login ), 'login' ) . "\r\n\r\n";
                $msg .= __( 'Thanks!', 'b3-onboarding' ) . "\r\n";
        
                return $msg;
            }
            
            
            /**
             * Finds and returns a matching error message for the given error code.
             *
             * @param string $error_code The error code to look up.
             *
             * @return string               An error message.
             */
            private function b3_get_error_message( $error_code ) {
                switch( $error_code ) {
                
                    // Login errors
                    case 'empty_username':
                        return esc_html__( 'Please enter a user name', 'b3-onboarding' );
                
                    case 'empty_password':
                        return esc_html__( 'You need to enter a password to login.', 'b3-onboarding' );
                
                    case 'invalid_username':
                        return esc_html__( "We don't have any users with that email address. Maybe you used a different one when signing up?", 'b3-onboarding' );
                
                    case 'incorrect_password':
                        $err = __( "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?", 'b3-onboarding' );
                    
                        return sprintf( $err, wp_lostpassword_url() );
                
                    // Registration errors
                    case 'username_exists':
                        return esc_html__( 'This username is already in use.', 'b3-onboarding' );
                
                    case 'email':
                        return esc_html__( 'The email address you entered is not valid.', 'b3-onboarding' );
                
                    case 'email_exists':
                        return esc_html__( 'An account already exists with this email address.', 'b3-onboarding' );
                
                    case 'closed':
                        return esc_html__( 'Registering new users is currently not allowed.', 'b3-onboarding' );
                
                    case 'captcha':
                        return esc_html__( 'The Google reCAPTCHA check failed. Are you a robot?', 'b3-onboarding' );
                
                    case 'access_requested':
                        return esc_html__( 'You have sucessfully requested access. An administrator will check your request.', 'b3-onboarding' );
                
                    case 'confirm_email':
                        return esc_html__( 'You have sucessfully registered but need to confirm your email first.', 'b3-onboarding' );
                
                    // Lost password
                    case 'invalid_email':
                    case 'invalidcombo':
                        return esc_html__( 'There are no users registered with this email address.', 'b3-onboarding' );
                
                    case 'wait_approval':
                        return esc_html__( 'You have to get approved first.', 'b3-onboarding' );
                
                    case 'wait_confirmation':
                        return esc_html__( 'You have to confirm your email first.', 'b3-onboarding' );
                
                    // Activation
                    case 'activate_success':
                        return esc_html__( 'You have succesfully confirmed your email.', 'b3-onboarding' );
                    
                    case 'invalid_key':
                        return esc_html__( 'The activation link you used is not valid.', 'b3-onboarding' );
                    
                    // Reset password
                    case 'expiredkey':
                    case 'invalidkey':
                        return esc_html__( 'The password reset link you used is not valid anymore.', 'b3-onboarding' );
                
                    case 'password_reset_mismatch':
                        return esc_html__( "The two passwords you entered don't match.", 'b3-onboarding' );
                
                    case 'password_reset_empty':
                        return esc_html__( "Sorry, we don't accept empty passwords.", 'b3-onboarding' );
                
                    // Multisite
                    case 'domain_exists':
                        return esc_html__( "Sorry, this subdomain has already been taken.", 'b3-onboarding' );
                
                    case 'user_registered':
                        return esc_html__( "You have successfully registered. Please check your email for an activation link.", 'b3-onboarding' );
                
                    // Admin
                    case 'settings_saved':
                        return esc_html__( "Settings saved", 'b3-onboarding' );
                
                    case 'pages_saved':
                        return esc_html__( "Settings saved", 'b3-onboarding' );
                
                    case 'emails_saved':
                        return esc_html__( "Settings saved", 'b3-onboarding' );
                
                    default:
                        break;
                }
            
                return esc_html__( 'An unknown error occurred. Please try again later.', 'b3-onboarding' );
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
            protected function b3_register_user( $user_email, $user_login, $registration_type, $role = 'subscriber', $meta = array() ) {
                $errors = new WP_Error();
            
                if ( username_exists( $user_login ) ) {
                    $errors->add( 'username_exists', $this->b3_get_error_message( 'username_exists' ) );
                
                    return $errors;
                }
            
                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'email', $this->b3_get_error_message( 'email' ) );
                
                    return $errors;
                }
            
                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->b3_get_error_message( 'email_exists' ) );
                
                    return $errors;
                }
            
                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'email', $this->b3_get_error_message( 'email' ) );
                
                    return $errors;
                }
            
                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->b3_get_error_message( 'email_exists' ) );
                
                    return $errors;
                }
    
                $user_data = array(
                    'user_login' => $user_login,
                    'user_email' => $user_email,
                    'user_pass'  => '',
                    'role'       => $role,
                );

                $first_name = ( ! empty( $meta[ 'first_name' ] ) ) ? $meta[ 'first_name' ] : false;
                if ( false != $first_name ) {
                    $user_data[ 'first_name' ] = $first_name;
                    unset( $meta[ 'first_name' ] );
                }

                $last_name  = ( ! empty( $meta[ 'last_name' ] ) ) ? $meta[ 'last_name' ] : false;
                if ( false != $first_name ) {
                    $user_data[ 'last_name' ] = $last_name;
                    unset( $meta[ 'last_name' ] );
                }
                
                // return 160878; // for testing
            
                $user_id = wp_insert_user( $user_data );
                if ( ! is_wp_error( $user_id ) ) {
                    do_action( 'b3_after_insert_user' );
                    $inform = 'user';
                    if ( 'request_access' == $registration_type || get_option( 'b3_new_user_message', false ) ) {
                        $inform = 'both';
                    }
                    wp_new_user_notification( $user_id, null, $inform ); // @TODO: make notify 'changable' for admin notice
                }
                // @TODO: add if for if user needs to activate
                // @TODO: add if for if admin needs to activate
            
                return $user_id;
            }
        
            private function b3_register_wpmu_user( $user_name, $user_email, $sub_domain, $meta = array() ) {
    
                $errors                = new WP_Error();
                $main_register_type    = get_site_option( 'registration' );
                $subsite_register_type = get_option( 'b3_registration_type' );
                $user_registered       = false;

                if ( is_main_site() ) {
    
                    if ( in_array( $main_register_type, [ 'all', 'blog' ] ) && in_array( $subsite_register_type, [ 'request_access', 'request_access_subdomain', 'ms_loggedin_register', 'ms_register_user', 'ms_register_site_user' ] )) {
                        if ( false == $sub_domain ) {
                            wpmu_signup_user( $user_name, $user_email, $meta );
                            $user_registered = true;
                            do_action( 'b3_after_insert_user' );
                        } else {
                            wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                            // get site id by
                            $site_id = get_id_from_blogname( $sub_domain );
                            do_action( 'b3_after_insert_site', $site_id );
                        }
                    } elseif ( 'none' == $main_register_type ) {
                        // @TODO: add if for user or user + site
                        // @TODO: add if for if user needs to activate
                        // @TODO: add if for if admin needs to activate
                        // wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                    } else {
                        $errors->add( 'unknown', $this->b3_get_error_message( 'unknown' ) );
                    }
                    
                } else {
    
                    if ( 'user' == $main_register_type && 'closed' != $subsite_register_type ) {
                        wpmu_signup_user( $user_name, $user_email, $meta );
                        $user_registered = true;
                        do_action( 'b3_after_insert_user' );
                    } else {
                        $errors->add( 'unknown', $this->b3_get_error_message( 'unknown' ) );
                    }
        
                }
    
                if ( true == $user_registered ) {
                    $errors->add( 'user_registered', $this->b3_get_error_message( 'user_registered' ) );
                }
            
                return $errors;
            }
        
        
            ## FORM RENDERS
        
            /**
             * A shortcode for rendering the new user registration form.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_register_form( $user_variables, $content = null ) {
            
                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'register',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );
            
                // @TODO: IF ??
                // Retrieve recaptcha key
                $attributes[ 'recaptcha_site_key' ] = get_option( 'b3-onboarding-recaptcha-public-key', null );
            
                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already signed in.', 'b3-onboarding' );
                } elseif ( 'closed' == get_option( 'b3_registration_type' ) ) {
                    return apply_filters( 'b3_filter_closed_message', esc_html__( 'Registering new users is currently not allowed.', 'b3-onboarding' ) );
                } else {
                
                    // Retrieve possible errors from request parameters
                    $attributes[ 'errors' ] = array();
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );
                    
                        foreach ( $error_codes as $error_code ) {
                            $attributes[ 'errors' ][] = $this->b3_get_error_message( $error_code );
                        }
                    }
                
                    return $this->get_template_html( $attributes[ 'template' ], $attributes );
                }
            }
        
        
            /**
             * A shortcode for rendering the login form.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_login_form( $user_variables, $content = null ) {
            
                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'login',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );
            
                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already signed in.', 'b3-onboarding' );
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
                        $errors [] = $this->b3_get_error_message( $code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) ) {
                    $attributes[ 'user_activate' ] = isset( $_REQUEST[ 'activate' ] ) && $_REQUEST[ 'activate' ] == 'success';
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
                return $this->get_template_html( $attributes[ 'template' ], $attributes );
            }
        
        
            /**
             * A shortcode for rendering the form used to initiate the password reset.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_forgot_password_form( $user_variables, $content = null ) {
                
                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'lostpassword',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                // Retrieve possible errors from request parameters
                $attributes[ 'errors' ] = array();
                if ( isset( $_REQUEST[ 'errors' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'errors' ] );
                
                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ][] = $this->b3_get_error_message( $error_code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) ) {
                    $attributes[ 'user_activate' ] = isset( $_REQUEST[ 'activate' ] ) && $_REQUEST[ 'activate' ] == 'success';
                }
                
                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already logged in.', 'b3-onboarding' );
                } else {
                    return $this->get_template_html( $attributes[ 'template' ], $attributes );
                }
            }
        
        
            /**
             * A shortcode for rendering the form used to reset a user's password.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_reset_password_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'resetpass',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );
            
                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already signed in.', 'b3-onboarding' );
                } else {
                    if ( isset( $_REQUEST[ 'login' ] ) && isset( $_REQUEST[ 'key' ] ) ) {
                        $attributes[ 'login' ] = $_REQUEST[ 'login' ];
                        $attributes[ 'key' ]   = $_REQUEST[ 'key' ];
                    
                        // Error messages
                        $errors = array();
                        if ( isset( $_REQUEST[ 'errors' ] ) ) {
                            $error_codes = explode( ',', $_REQUEST[ 'errors' ] );
                        
                            foreach ( $error_codes as $code ) {
                                $errors[] = $this->b3_get_error_message( $code );
                            }
                        }
                        $attributes[ 'errors' ] = $errors;
                    
                        return $this->get_template_html( $attributes[ 'template' ], $attributes );
                    } else {
                        return esc_html__( 'Invalid password reset link.', 'b3-onboarding' );
                    }
                }
            }
    
    
            /**
             * Render user/account page
             *
             * @param $user_variables
             * @param null $content
             *
             * @return bool|string
             */
            public function b3_render_account_page( $user_variables, $content = null ) {

                if ( is_user_logged_in() ) {
    
                    wp_enqueue_script( 'user-profile' );

                    // Parse shortcode attributes
                    $default_attributes = array(
                        'title'    => false,
                        'template' => 'account',
                    );
                    $attributes         = shortcode_atts( $default_attributes, $user_variables );
    
                    // Error messages
                    $errors = array();
                    if ( isset( $_REQUEST[ 'errors' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'errors' ] );
        
                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_error_message( $code );
                        }
                    }
                    $attributes[ 'errors' ] = $errors;
    
                    if ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $attributes[ 'updated' ] = $this->b3_get_error_message( $_REQUEST[ 'updated' ] );
                    }
        
                    return $this->get_template_html( $attributes[ 'template' ], $attributes );
    
                } else {
                    // @TODO: redirect
                    error_log('redirect visitor');
                }
                
                return false;
    
            }
    
    
            /**
             * Render user management page
             *
             * @param $user_variables
             * @param null $content
             */
            public function b3_render_user_approval_page( $user_variables, $content = null ) {
    
                $show_first_last_name = get_option( 'b3_activate_first_last' );
                $user_args            = array( 'role' => 'b3_approval' );
                $users                = get_users( $user_args );
                ?>
                <p>
                    <?php echo __( 'On this page you can approve/deny user requests for access.', 'b3-onboaarding' ); ?>
                </p>
                <?php
                    if ( ! empty( $_GET[ 'user' ] ) ) {
                        if ( 'approved' == $_GET[ 'user' ] ) { ?>
                            <p class="b3_message">
                                <?php esc_html_e( 'User is successfully approved', 'b3-onboarding' ); ?>
                            </p>
                        <?php } elseif ( 'rejected' == $_GET[ 'user' ] ) { ?>
                            <p class="b3_message">
                                <?php esc_html_e( 'User is successfully rejected and user is deleted', 'b3-onboarding' ); ?>
                            </p>
                        <?php } ?>
                    <?php } ?>
                <?php if ( $users ) { ?>

                        <table class="b3_table b3_table--user" border="0" cellspacing="0" cellpadding="0" style="">
                            <thead>
                            <tr>
                                <th>
                                    <?php esc_html_e( 'User ID', 'b3-onboarding' ); ?>
                                </th>
                                <?php if ( false != $show_first_last_name ) { ?>
                                    <th>
                                        <?php esc_html_e( 'First name', 'b3-onboarding' ); ?>
                                    </th>
                                    <th>
                                        <?php esc_html_e( 'Last name', 'b3-onboarding' ); ?>
                                    </th>
                                <?php } ?>
                                <th>
                                    <?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
                                </th>
                                <th>
                                    <?php esc_html_e( 'Actions', 'b3-onboarding' ); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $users as $user ) { ?>
                                <tr>
                                    <td><?php echo $user->ID; ?></td>
                                    <?php if ( false != $show_first_last_name ) { ?>
                                        <td><?php echo $user->first_name; ?></td>
                                        <td><?php echo $user->last_name; ?></td>
                                    <?php } ?>
                                    <td><?php echo $user->user_email; ?></td>
                                    <td>
                                        <form name="b3_user_management" action="" method="post">
                                            <input name="b3_manage_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-manage-users-nonce' ); ?>" />
                                            <input name="b3_user_id" type="hidden" value="<?php echo $user->ID; ?>" />
                                            <input name="b3_approve_user" class="button" type="submit" value="<?php esc_html_e( 'Approve', 'b3-onboarding' ); ?>" />
                                            <input name="b3_reject_user" class="button" type="submit" value="<?php esc_html_e( 'Reject', 'b3-onboarding' ); ?>" />
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </form>
                <?php } else { ?>
                    <p><?php esc_html_e( 'No (more) users to approve.', 'b3-onboarding' ); ?></p>
                <?php }
            }
        

            /**
             * Render user management page
             *
             * @param $user_variables
             * @param null $content
             */
            public function b3_render_delete_account_page( $user_variables, $content = null ) {
                
                if ( is_user_logged_in() ) {
                    echo 'This needs to be rendered into a button (from B3 Onboarding)';
                }
            }
        

            /**
             * Renders the contents of the given template to a string and returns it.
             *
             * @param string $template_name The name of the template to render (without .php)
             * @param array $attributes The PHP variables for the template
             *
             * @return string               The contents of the template.
             */
            private function get_template_html( $template_name, $attributes = null ) {
                if ( ! $attributes ) {
                    $attributes = array();
                }
    
                $template_paths = array(
                    get_stylesheet_directory() . '/plugins/b3-onboarding/',
                    get_template_directory() . '/plugins/b3-onboarding/',
                    plugin_dir_path( __FILE__ ) . 'templates/',
                );
                foreach( $template_paths as $possible_location ) {
                    if ( file_exists( $possible_location . $template_name . '.php' )) {
                        $location = $possible_location;
                        break;
                    }
                }
            
                ob_start();
    
                do_action( 'b3_do_before_' . $template_name );

                include( $location . $template_name . '.php' );
            
                do_action( 'b3_do_after_' . $template_name );
            
                $html = ob_get_contents();
                ob_end_clean();
            
                return $html;
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
                $b3_onboarding->initialize();
            }
        
            return $b3_onboarding;
        }
    
        // Initialize the plugin
        init_b3_onboarding();
    
    } // class_exists check
