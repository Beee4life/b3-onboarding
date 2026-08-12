<?php
    /*
    Plugin Name:        B3 OnBoarding
    Plugin URI:         https://b3onboarding.berryplasman.com
    Description:        This plugin styles the default WordPress pages into your own design. It gives you full control over the registration/login process (aka onboarding).
    Version:            3.19.0-dev
    Requires at least:  6.2
    Tested up to:       7.0
    Requires PHP:       7.4
    Author:             Beee
    Author URI:         https://berryplasman.com
    Tags:               user, management, registration, login, lost password, reset password, account, multisite, wpml, multilang, onboarding, onboard, user registration, user management, forms, email, override, otp, one time password, magic link
    License:            GPLv2 or later
    License URI:        https://www.gnu.org/licenses/gpl.html
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

        class B3Onboarding {
            private array $settings = [];

            public function __construct() {
                $this->settings = [
                    'path'              => trailingslashit( dirname( __FILE__ ) ),
                    'registration_type' => get_option( 'b3_registration_type', 'closed' ),
                    'version'           => '3.19.0',
                ];

                if ( ! defined( 'B3OB_PLUGIN_URL' ) ) {
                    $plugin_url = plugins_url( '/', __FILE__ );
                    define( 'B3OB_PLUGIN_URL', $plugin_url );
                }

                if ( ! defined( 'B3OB_PLUGIN_PATH' ) ) {
                    $plugin_path = dirname( __FILE__ );
                    define( 'B3OB_PLUGIN_PATH', $plugin_path );
                }

                if ( ! defined( 'B3OB_PLUGIN_SETTINGS' ) ) {
                    $settings_url = admin_url( 'admin.php?page=b3-onboarding' );
                    define( 'B3OB_PLUGIN_SETTINGS', $settings_url );
                }

                if ( ! defined( 'B3OB_PLUGIN_SITE' ) ) {
                    $plugin_site = 'https://b3onboarding.berryplasman.com';
                    define( 'B3OB_PLUGIN_SITE', $plugin_site );
                }

                register_activation_hook( __FILE__,             [ $this, 'b3_plugin_activation' ] );
                register_deactivation_hook( __FILE__,           [ $this, 'b3_plugin_deactivation' ] );

                add_action( 'wp_enqueue_scripts',       [ $this, 'b3_enqueue_scripts_frontend' ], 40 );
                add_action( 'wp_enqueue_scripts',       [ $this, 'b3_add_recaptcha_js_to_footer' ] );
                add_action( 'login_enqueue_scripts',    [ $this, 'b3_add_recaptcha_js_to_footer' ] );
                add_action( 'wp_head',                  [ $this, 'b3_add_rc3' ] );
                add_action( 'admin_enqueue_scripts',    [ $this, 'b3_enqueue_scripts_backend' ] );
                add_action( 'admin_enqueue_scripts',    [ $this, 'b3_enqueue_scripts_backend_footer' ], 99 );
                add_action( 'admin_menu',               [ $this, 'b3_add_admin_pages' ] );
                add_action( 'template_redirect',        [ $this, 'b3_template_redirect' ] );
                add_action( 'widgets_init',             [ $this, 'b3_register_widgets' ] );
                add_action( 'wp_dashboard_setup',       [ $this, 'b3_add_dashboard_widget' ] );
                add_action( 'init',                     [ $this, 'b3_registration_form_handling' ] );
                add_action( 'init',                     [ $this, 'b3_reset_user_password' ] );
                add_action( 'init',                     [ $this, 'b3_magic_link_form_handling' ] );
                add_action( 'init',                     [ $this, 'b3_check_magic_link' ] );
                add_action( 'admin_notices',            [ $this, 'b3_admin_notices' ] );
                add_action( 'load-users.php',           [ $this, 'b3_load_users_page' ] );
                add_action( 'plugins_loaded',           [ $this, 'b3_load_textdomain' ] );

                if ( is_multisite() ) {
                    add_action( 'wp_initialize_site', [ $this, 'b3_after_create_site' ] );
                }

                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'b3_settings_link' ] );

                $plugin_dir_path = plugin_dir_path(__FILE__);
                require_once $plugin_dir_path . 'admin/tabs/tabs.php';
                require_once $plugin_dir_path . 'admin/help-tabs.php';
                require_once $plugin_dir_path . 'includes/true-false.php';
                require_once $plugin_dir_path . 'includes/actions.php';
                require_once $plugin_dir_path . 'includes/class-b3-shortcodes.php';
                require_once $plugin_dir_path . 'includes/do-stuff.php';
                require_once $plugin_dir_path . 'includes/filters.php';
                require_once $plugin_dir_path . 'includes/functions.php';
                require_once $plugin_dir_path . 'includes/defaults.php';
                require_once $plugin_dir_path . 'includes/emails.php';
                require_once $plugin_dir_path . 'includes/redirects.php';
                require_once $plugin_dir_path . 'includes/form-handling.php';

                if ( is_admin() ) {
                    require_once $plugin_dir_path . 'admin/admin-ajax.php';
                }
            }

            public function b3_plugin_activation() {
                b3_setup_initial_pages();
                b3_set_default_settings();

                if ( ! is_multisite() ) {
                    $b3_activation = get_role( 'b3_activation' );
                    if ( ! $b3_activation ) {
                        add_role( 'b3_activation', esc_html__( 'Awaiting activation', 'b3-onboarding' ), [] );
                    }
                    $b3_approval = get_role( 'b3_approval' );
                    if ( ! $b3_approval ) {
                        add_role( 'b3_approval', esc_html__( 'Awaiting approval', 'b3-onboarding' ), [] );
                    }
                }
                $this->b3_switch_users_to_fallback_role();
            }

            public function b3_plugin_deactivation() {
                // set registration option accordingly
                $registration_type = get_option( 'b3_registration_type' );
                if ( is_multisite() ) {
                    if ( is_main_site() ) {
                        if ( 'none' === $registration_type ) {
                            update_site_option( 'registration', 'none' );
                        } else {
                            update_site_option( 'registration', 'all' );
                        }
                    }
                } else {
                    if ( 'none' === $registration_type ) {
                        update_option( 'users_can_register', '0' );
                    } else {
                        update_option( 'users_can_register', '1' );
                    }
                }

                $this->b3_switch_users_to_default();
            }

            public function b3_load_textdomain() {
                load_plugin_textdomain( 'b3-onboarding', false, basename( dirname( __FILE__ ) ) . '/languages' );
            }

            public function b3_enqueue_scripts_frontend() {
                if ( ! is_admin() ) {
                    if ( ! wp_script_is( 'jquery' ) ) {
                        wp_enqueue_script( 'jquery' );
                    }
                }

                if ( get_option( 'b3_activate_login_popup' ) ) {
                    wp_enqueue_script(
                        'jquery-modal',
                        plugins_url( 'assets/js/jquery.modal.min.js', __FILE__ ),
                        [ 'jquery' ],
                        '0.9.2',
                        true
                    );
                    wp_enqueue_style(
                        'jquery-modal',
                        plugins_url( 'assets/css/jquery.modal.min.css', __FILE__ ),
                        [],
                        '0.9.2'
                    );                }

                wp_enqueue_style( 'b3ob-main', plugins_url( 'assets/css/style.css', __FILE__ ), [], $this->settings[ 'version' ] );
                wp_enqueue_script( 'b3ob', plugins_url( 'assets/js/js.js', __FILE__ ), [ 'jquery' ], $this->settings[ 'version' ], false );

                wp_localize_script( 'b3ob', 'b3ob_vars', [
                    'use_magic_link'  => esc_attr__( 'Use magic link', 'b3-onboarding' ),
                    'login'           => esc_attr__( 'Login', 'b3-onboarding' ),
                    'login_url'       => b3_get_login_url(),
                    'login_nonce'     => wp_create_nonce( 'b3_login' ),
                    'magiclink_nonce' => wp_create_nonce( 'b3_magiclink' ),
                    'login_message'   => b3_get_message_above_login(),
                    'recaptcha_theme' => get_option( 'b3_recaptcha_theme', 'light' ),
                    'use_both'        => get_option( 'b3_use_magic_link_password' ),
                ] );
            }

            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3ob-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), [], $this->settings[ 'version' ] );

                if ( ! ( 'toplevel_page_b3-onboarding' === get_current_screen()->id ) ) {
                    return;
                }

                wp_enqueue_script( 'b3ob-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), [ 'jquery' ], $this->settings[ 'version' ], false );

                $preview_var = isset( $_GET[ 'preview' ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'preview' ] ) ) : '';
                wp_localize_script( 'b3ob-admin', 'b3Onboarding', [
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce'    => wp_create_nonce( 'b3_send_test_email_nonce' ),
                    'preview'  => $preview_var,
                    'text'     => [
                        'sending' => __( 'Sending...', 'b3-onboarding' ),
                        'success' => __( 'Email sent', 'b3-onboarding' ),
                        'error'   => __( 'Failed to send email. Please try again.', 'b3-onboarding' ),
                    ],
                ] );

                // https://wpreset.com/add-codemirror-editor-plugin-theme/
                $b3cm_settings[ 'codeEditor' ] = wp_enqueue_code_editor( [
                    'type' => 'text/css',
                ] );
                wp_localize_script( 'jquery', 'b3cm_settings', $b3cm_settings );

                wp_enqueue_style( 'wp-codemirror' );

                // @src https://github.com/thomasgriffin/New-Media-Image-Uploader
                // This function loads in the required media files for the media manager.
                wp_enqueue_media();

                // Register, localize and enqueue our custom JS.
                wp_register_script( 'b3-media', plugins_url( '/assets/js/media.js', __FILE__ ), [ 'jquery' ], $this->settings[ 'version' ], true );
                wp_localize_script( 'b3-media', 'b3_media', [
                    'title'  => esc_attr__( 'Upload or choose your custom logo', 'b3-onboarding' ),
                    'button' => esc_attr__( 'Insert logo', 'b3-onboarding' ),
                ] );
                wp_enqueue_script( 'b3-media' );
            }

            public function b3_enqueue_scripts_backend_footer() {
                wp_enqueue_script( 'wp-theme-plugin-editor', '', '', $this->settings[ 'version' ], true );
            }

            public function b3_add_admin_pages() {
                $plugin_dir_path = plugin_dir_path(__FILE__);
                require_once $plugin_dir_path . 'admin/admin-page.php';
                add_menu_page(
                    'B3 OnBoarding',
                    'B3 OnBoarding',
                    apply_filters( 'b3_user_cap', 'manage_options' ),
                    'b3-onboarding',
                    'b3_user_register_settings',
                    B3OB_PLUGIN_URL . 'assets/images/logo-b3onboarding-small.png',
                    99
                );

                if ( get_option( 'b3_needs_admin_approval' ) ) {
                    global $submenu;
                    $approval_exists = false;

                    if ( isset( $submenu[ 'b3-onboarding' ] ) ) {
                        foreach( $submenu[ 'b3-onboarding' ] as $item ) {
                            if ( $item[ 2 ] === 'b3-user-approval' ) { // Index 2 is the menu slug
                                $approval_exists = true;
                                break;
                            }
                        }
                    }

                    if ( ! $approval_exists ) {
                        require_once $plugin_dir_path . 'admin/user-approval-page.php';
                        add_submenu_page(
                            'b3-onboarding',
                            'B3 OnBoarding - ' . esc_html__( 'User Approval', 'b3-onboarding' ),
                            esc_html__( 'User Approval', 'b3-onboarding' ),
                            apply_filters( 'b3_user_cap', 'manage_options' ),
                            'b3-user-approval',
                            'b3_user_approval'
                        );
                    }
                }

                if ( is_localhost() || apply_filters( 'b3_activate_debug_info', get_option( 'b3_activate_debug_info' ) ) ) {
                    global $submenu;
                    $debug_exists = false;

                    if ( isset( $submenu[ 'b3-onboarding' ] ) ) {
                        foreach( $submenu[ 'b3-onboarding' ] as $item ) {
                            if ( $item[ 2 ] === 'b3-debug-info' ) { // Index 2 is the menu slug
                                $debug_exists = true;
                                break;
                            }
                        }
                    }
                    if ( ! $debug_exists ) {
                        require_once $plugin_dir_path . 'admin/debug-page.php';
                        add_submenu_page(
                            'b3-onboarding',
                            'B3 OnBoarding - ' . esc_html__( 'Debug info', 'b3-onboarding' ),
                            esc_html__( 'Debug info', 'b3-onboarding' ),
                            apply_filters( 'b3_user_cap', 'manage_options' ),
                            'b3-debug-info',
                            'b3_debug_page'
                        );
                    }
                }
            }

            public function b3_template_redirect() {
                if ( ! is_main_site() ) {
                    $blog_id   = get_current_blog_id();
                    $is_active = b3_is_site_active( $blog_id );

                    if ( ! $is_active ) {
                        status_header( 503 );
                        header( 'Retry-After: 3600' );
                        $message = esc_html__( 'This site is not approved (yet) and therefore not (yet) available.', 'b3-onboarding' );
                        $message .= ' ';
                        /* translators: link to return to homepage */
                        $message .= sprintf( esc_html__( 'Click %s for the homepage.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( network_home_url() ), esc_html__( 'here', 'b3-onboarding' ) ) );

                        wp_die(
                            wp_kses_post( $message ),
                            esc_html__( 'Site awaiting approval', 'b3-onboarding' ),
                            array( 'response' => 503 )
                        );
                    }
                }

                $account_page_id  = b3_get_account_url( true );
                $account_url      = b3_get_account_url();
                $approval_page_id = b3_get_user_approval_url( true );
                $current_url      = b3_get_current_url();
                $login_page_id    = b3_get_login_url( true );
                $login_url        = b3_get_login_url();
                $logout_page_id   = b3_get_logout_url( true );

                if ( is_page() ) {
                    $current_page = get_post( get_the_ID() );
                    if ( false != $account_page_id ) {
                        if ( ! is_user_logged_in() && ( $account_page_id == $current_page->ID || $account_page_id == $current_page->post_parent ) ) {
                            $login_url    = add_query_arg( 'redirect_to', urlencode( $current_url ), $login_url );
                            $redirect_url = $login_url;
                        }
                    }

                    if ( false != $approval_page_id && $current_page->ID == $approval_page_id ) {
                        if ( is_user_logged_in() ) {
                            if ( ! current_user_can( 'promote_users' ) ) {
                                $redirect_url = $account_url;
                            }
                        } else {
                            $login_url    = add_query_arg( 'redirect_to', urlencode( $current_url ), $login_url );
                            $redirect_url = $login_url;
                        }
                    }

                    if ( false != $logout_page_id && $current_page->ID == $logout_page_id ) {
                        check_admin_referer( 'logout' );

                        $user = wp_get_current_user();
                        wp_logout();

                        if ( ! empty( $_REQUEST[ 'redirect_to' ] ) ) {
                            $redirect_to           = sanitize_text_field( wp_unslash( $_REQUEST[ 'redirect_to' ] ) );
                            $requested_redirect_to = $redirect_to;
                        } else {
                            $redirect_to           = site_url( 'wp-login.php?loggedout=true' );
                            $requested_redirect_to = '';
                        }

                        $redirect_url = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
                    }
                }

                if ( isset( $redirect_url ) ) {
                    wp_safe_redirect( $redirect_url );
                    exit;
                }
            }

            public function b3_register_widgets() {
                if ( is_main_site() ) {
                    require_once plugin_dir_path(__FILE__) . 'includes/class-b3-sidebar-widget.php';
                }
            }

            public function b3_add_dashboard_widget() {
                if ( is_main_site() ) {
                    $plugin_dir_path = plugin_dir_path(__FILE__);
                    require_once $plugin_dir_path . 'admin/dashboard-widget-users.php';

                    if ( is_localhost() || apply_filters( 'b3_show_email_widget', false ) ) {
                        require_once $plugin_dir_path . 'admin/dashboard-widget-emails.php';
                    }
                }
            }

            public function b3_settings_link( $links ) {
                $settings_link = [ 'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-onboarding' ), esc_html__( 'Settings', 'b3-onboarding' ) ) ];

                return array_merge( $settings_link, $links );
            }

            public function b3_load_users_page() {
                add_action( 'admin_notices', [ $this, 'b3_admin_notices' ] );

                if ( isset( $_GET[ 'action' ] ) && in_array( $_GET[ 'action' ], [ 'activate', 'resendactivation' ] ) ) {
                    $user_id = isset( $_GET[ 'user_id' ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'user_id' ] ) ) : false;
                    if ( ! $user_id ) {
                        wp_die( esc_html__( "There's no user with that ID.", 'b3-onboarding' ) );
                    } elseif ( ! current_user_can( 'edit_user', $user_id ) ) {
                        wp_die( esc_html__( "You're not allowed to edit that user.", 'b3-onboarding' ) );
                    }

                    $user              = new WP_User( $user_id );
                    $registration_type = false;

                    if ( in_array( 'b3_activation', $user->roles ) ) {
                        $registration_type = 'email_activation';
                    } elseif ( in_array( 'b3_approval', $user->roles ) ) {
                        $registration_type = 'request_access';
                    }

                    $redirect_to = isset( $_REQUEST[ 'wp_http_referer' ] ) ? remove_query_arg( [
                        'wp_http_referer',
                        'updated',
                    ], sanitize_text_field( wp_unslash( $_REQUEST[ 'wp_http_referer' ] ) ) ) : 'users.php'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

                    switch( $_GET[ 'action' ] ) {
                        case 'activate' :
                            check_admin_referer( 'manual-activation' );
                            do_action( 'b3_manual_user_activate', $user_id );
                            $redirect_to = add_query_arg( 'update', 'activated', $redirect_to );
                            break;

                        case 'resendactivation' :
                            check_admin_referer( 'resend-activation' );
                            if ( 'email_activation' === $registration_type ) {
                                do_action( 'b3_resend_user_activation', $user_id );
                                $redirect_to = add_query_arg( 'update', 'sendactivation', $redirect_to );
                            }
                            break;
                    }

                    wp_safe_redirect( $redirect_to );
                    exit;
                }
            }

            public static function b3_errors() {
                static $wp_error; // Will hold global variable safely

                return isset( $wp_error ) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) );
            }

            public static function b3_show_admin_notices() {
                if ( $codes = B3Onboarding::b3_errors()->get_error_codes() ) {
                    if ( is_wp_error( B3Onboarding::b3_errors() ) ) {

                        // Loop error codes and display errors
                        $notice_class = false;
                        $prefix       = false;

                        foreach( $codes as $code ) {
                            if ( strpos( $code, 'success' ) !== false ) {
                                $notice_class = 'updated notice ';
                                $prefix       = false;
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
                        echo sprintf( '<div class="%sis-dismissible">', esc_attr( $notice_class) );
                        foreach( $codes as $code ) {
                            $message = B3Onboarding::b3_errors()->get_error_message( $code );
                            $message = ( true == $prefix ) ? sprintf( '<strong>%s:</strong> %s', esc_html( $prefix ), esc_html( $message ) ) : esc_html( $message );
                            echo sprintf( '<p>%s</p>', wp_kses_post( $message ) );
                        }
                        echo sprintf( '<button type="button" class="notice-dismiss"><span class="screen-reader-text">%s</span></button>', esc_attr__( 'Dismiss this notice', 'b3-onboarding' ) );
                        echo '</div>';
                    }
                }
            }

            public function b3_add_recaptcha_js_to_footer() {
                if ( get_option( 'b3_activate_recaptcha' ) && is_page( b3_get_register_url( true ) ) ) {
                    wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], $this->settings[ 'version' ], true );
                }
            }

            public function b3_add_rc3() {
                if ( get_option( 'b3_activate_recaptcha' ) && is_page( b3_get_register_url( true ) ) ) {
                    ?>
                    <script>
                        function onSubmit(token) {
                            document.getElementById('registerform').submit();
                        }
                    </script>
                    <?php
                }
            }

            public function b3_registration_form_handling() {
                if ( isset( $_POST[ 'b3_register_nonce' ] ) ) {
                    $redirect_url = b3_get_register_url();

                    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'b3_register_nonce' ] ) ), 'b3_register' ) ) {
                        $redirect_url = add_query_arg( 'registration-error', 'unknown', $redirect_url );
                        wp_safe_redirect( $redirect_url );
                        exit;

                    } else {
                        $meta_data         = [];
                        $registration_type = $this->settings[ 'registration_type' ];
                        $user_email        = ( isset( $_POST[ 'user_email' ] ) ) ? sanitize_email( wp_unslash( $_POST[ 'user_email' ] ) ) : false;

                        if ( get_option( 'b3_activate_honeypot' ) && isset( $_POST[ 'b3_pooh' ] ) ) {
                            $errors = new WP_Error();
                            $errors->add( 'honeypot', $this->b3_get_return_message( 'no_robots' ) );

                            return $errors;
                        }

                        // @TODO: look into this
                        if ( 'blog' != $registration_type && ! is_email( $user_email ) ) {
                            $redirect_url = add_query_arg( 'registration-error', 'invalid_email', $redirect_url );
                            wp_safe_redirect( $redirect_url );
                            exit;
                        }

                        if ( isset( $_POST[ 'first_name' ] ) ) {
                            $meta_data[ 'first_name' ] = sanitize_text_field( wp_unslash( $_POST[ 'first_name' ] ) );
                        }
                        if ( isset( $_POST[ 'last_name' ] ) ) {
                            $meta_data[ 'last_name' ] = sanitize_text_field( wp_unslash( $_POST[ 'last_name' ] ) );
                        }

                        // @TODO: verify other meta

                        if ( ! is_multisite() ) {
                            $redirect_url = $this->b3_single_registration( $redirect_url, $registration_type, $user_email );

                        } else {
                            // if is_multisite
                            $redirect_url = $this->b3_multisite_registration( $redirect_url, $registration_type, $user_email, $meta_data );
                        }

                        wp_safe_redirect( $redirect_url );
                        exit;
                    }
                }
            }

            public function b3_reset_user_password() {
                if ( isset( $_POST[ 'b3_resetpass_nonce' ] ) ) {
                    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'b3_resetpass_nonce' ] ) ), 'b3_resetpass' ) ) {
                        $redirect_url = add_query_arg( 'error', 'unknown', $redirect_url );
                        wp_safe_redirect( $redirect_url );
                        exit;

                    } else {
                        // b3_resetpass
                        $rp_key   = ( isset( $_REQUEST[ 'rp_key' ] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST[ 'rp_key' ] ) ) : false;
                        $rp_login = ( isset( $_REQUEST[ 'rp_login' ] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST[ 'rp_login' ] ) ) : false;

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
                                if ( isset( $_POST[ 'pass2' ] ) && $_POST[ 'pass1' ] != $_POST[ 'pass2' ] || empty( $_POST[ 'pass1' ] ) ) {
                                    // Password is empty or don't match
                                    $redirect_url = b3_get_reset_password_url();
                                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                    $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                                    wp_safe_redirect( $redirect_url );
                                    exit;
                                }

                                // Parameter checks OK, reset password
                                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- reset_password handles sanitation
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
            }

            public function b3_magic_link_form_handling() {
                if ( isset( $_POST[ 'b3_magiclink_nonce' ] ) ) {
                    $redirect_url = b3_get_login_url();
                    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'b3_magiclink_nonce' ] ) ), 'b3_magiclink' ) ) {
                        $redirect_url = add_query_arg( 'error', 'unknown', $redirect_url );
                        wp_safe_redirect( $redirect_url );
                        exit;

                    } elseif ( ! isset( $_POST[ 'email' ] ) || empty( $_POST[ 'email' ] ) ) {
                        $redirect_url = add_query_arg( 'error', 'empty_email', $redirect_url );
                        wp_safe_redirect( $redirect_url );
                        exit;

                    } elseif ( isset( $_POST[ 'email' ] ) ) {
                        $user_email    = sanitize_email( wp_unslash( $_POST[ 'email' ] ) );
                        $existing_user = get_user_by( 'email', $user_email );

                        if ( $existing_user instanceof WP_User ) {
                            if ( in_array( 'b3_activation', (array) $existing_user->roles ) ) {
                                $redirect_url = add_query_arg( 'message', 'activation_needed', $redirect_url );

                            } elseif ( in_array( 'b3_approval', (array) $existing_user->roles ) ) {
                                $redirect_url = add_query_arg( 'message', 'approval_needed', $redirect_url );

                            } else {
                                if ( get_option( 'b3_needs_admin_approval' ) && get_user_meta( $existing_user->ID, 'pending', true ) ) {
                                    // user not approved yet
                                    $redirect_url = add_query_arg( 'message', 'approval_needed', $redirect_url );

                                } else {
                                    $magic_link = b3_get_magic_link_url( $user_email );
                                    $message    = b3_get_magic_link_message( $magic_link );
                                    $subject    = b3_get_magic_link_subject();
                                    $subject    = strtr( $subject, b3_get_replacement_vars( 'subject' ) );
                                    $vars       = []; // empty right now, but might be filled later on...
                                }
                            }

                            if ( ! empty( $message ) ) {
                                $message      = b3_replace_template_styling( $message );
                                $message      = strtr( $message, b3_get_replacement_vars( 'message', $vars ) );
                                $message      = htmlspecialchars_decode( stripslashes( $message ) );
                                $redirect_url = add_query_arg( 'login', 'code_sent', $redirect_url );

                                wp_mail( $user_email, $subject, $message );
                            }

                        } else {
                            // no user object = not approved yet
                            global $wpdb;
                            $signup_info = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE user_email = %s', $wpdb->signups, $user_email ) );

                            if ( ! empty( $signup_info ) ) {
                                if ( isset( $signup_info->active ) && 0 === (int) $signup_info->active ) {
                                    $redirect_url = add_query_arg( 'message', 'activation_needed', $redirect_url );
                                }
                            }
                        }

                        if ( ! empty( $redirect_url ) ) {
                            wp_safe_redirect( $redirect_url );
                            exit;
                        }
                    }
                }
            }

            public function b3_check_magic_link() {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if ( isset( $_GET[ 'otpcode' ] ) ) {
                    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    $verify_otp = b3_verify_otp( sanitize_text_field( wp_unslash( $_GET[ 'otpcode' ] ) ) );

                    if ( $verify_otp instanceof WP_User ) {
                        do_action( 'b3_log_user_in', $verify_otp );
                    } else {
                        $redirect_url = b3_get_login_url();
                        $redirect_url = add_query_arg( 'error', 'verification_fail', $redirect_url );
                        wp_safe_redirect( $redirect_url );
                        exit;
                    }
                }
            }

            private function b3_single_registration( $redirect_url, $registration_type, $user_email ) {
                if ( $redirect_url && $registration_type ) {
                    $register   = true;
                    $role       = get_option( 'default_role', 'subscriber' );

                    if ( 'none' === $registration_type ) {
                        // Registration closed, display error
                        $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                        $register     = false;

                    } elseif ( false != get_option( 'b3_activate_recaptcha' ) && ! b3_verify_recaptcha() ) {
                        // Recaptcha check failed, display error
                        $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );
                        $register     = false;
                    }

                    if ( true === $register && 'none' !== $registration_type ) {
                        $user_login = isset( $_POST[ 'user_login' ] ) ? sanitize_user( wp_unslash( $_POST[ 'user_login' ] ) ) : false;

                        if ( $user_login ) {
                            // Registration is open and user has valid login !
                            if ( 'email_activation' === $registration_type ) {
                                $role      = 'b3_activation';
                                $query_arg = 'confirm_email';
                            } else {
                                $query_arg = 'success';

                                if ( get_option( 'b3_needs_admin_approval' ) ) {
                                    $role      = 'b3_approval';
                                    $query_arg = 'access_requested';
                                }

                                if ( ! get_option( 'b3_activate_custom_passwords' ) && ! get_option( 'b3_use_magic_link' ) ) {
                                    if ( ! get_option( 'b3_needs_admin_approval' ) ) {
                                        $reset_password = true;
                                    }
                                }
                            }

                            $register_args = [
                                'registration_type' => $registration_type,
                                'role'              => $role,
                                'user_email'        => $user_email,
                                'user_login'        => $user_login,
                                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                                'pass1'             => isset( $_POST[ 'pass1' ] ) ? $_POST[ 'pass1' ] : '',
                                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                                'pass2'             => isset( $_POST[ 'pass2' ] ) ? $_POST[ 'pass2' ] : '',
                            ];
                            $result = $this->b3_register_user( $register_args );

                            if ( is_wp_error( $result ) ) {
                                // Parse errors into a string and append as parameter to redirect
                                $errors       = join( ',', $result->get_error_codes() );
                                $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );

                            } else {
                                // Registration was successful
                                if ( isset( $reset_password ) && true == $reset_password ) {
                                    // @TODO: also add to MU register
                                    $redirect_url = add_query_arg( 'registered', $query_arg, b3_get_lostpassword_url() );

                                } else {
                                    // redirect to login page
                                    $redirect_url = add_query_arg( 'registered', $query_arg, b3_get_login_url() );
                                }

                                $redirect_url = apply_filters( 'b3_redirect_after_register', $redirect_url );
                            }
                        }
                    }
                }

                return $redirect_url;
            }

            private function b3_register_user( $args = [] ) {
                $default_args = [
                    'pass1'      => '',
                    'pass2'      => '',
                    'role'       => 'subscriber',
                    'user_email' => '',
                    'user_login' => '',
                    'user_pass'  => time(),
                ];

                $errors               = new WP_Error();
                $use_custom_passwords = get_option( 'b3_activate_custom_passwords' );
                $user_data            = wp_parse_args( $args, $default_args );

                if ( ! get_option( 'b3_register_email_only' ) ) {
                    if ( username_exists( $user_data[ 'user_login' ] ) ) {
                        $errors->add( 'username_exists', $this->b3_get_return_message( 'username_exists' ) );

                        return $errors;
                    }

                    if ( in_array( $user_data[ 'user_login' ], b3_get_disallowed_usernames() ) ) {
                        $errors->add( 'disallowed_username', $this->b3_get_return_message( 'disallowed_username' ) );

                        return $errors;
                    }
                }

                if ( ! is_email( $user_data[ 'user_email' ] ) ) {
                    $errors->add( 'invalid_email', $this->b3_get_return_message( 'invalid_email' ) );

                    return $errors;
                }

                if ( ! b3_verify_email_domain( $user_data[ 'user_email' ] ) ) {
                    $errors->add( 'banned_domain', $this->b3_get_return_message( 'banned_domain' ) );

                    return $errors;
                }

                if ( username_exists( $user_data[ 'user_email' ] ) || email_exists( $user_data[ 'user_email' ] ) ) {
                    $errors->add( 'email_exists', $this->b3_get_return_message( 'email_exists' ) );

                    return $errors;
                }

                if ( true == $use_custom_passwords ) {
                    if ( ! empty( $user_data[ 'pass1' ] ) && ! empty( $user_data[ 'pass2' ] ) ) {
                        $easy_passwords = b3_get_easy_passwords();
                        if ( in_array( $user_data[ 'pass1' ], $easy_passwords ) ) {
                            $errors->add( 'pw_too_easy', $this->b3_get_return_message( 'password_too_easy' ) );

                            return $errors;
                        }

                        if ( $user_data[ 'pass1' ] != $user_data[ 'pass2' ] || empty( $user_data[ 'pass1' ] ) ) {
                            // Password is empty or don't match
                            $errors->add( 'password_mismatch', $this->b3_get_return_message( 'password_mismatch' ) );

                            return $errors;

                        } elseif ( $user_data[ 'pass1' ] === $user_data[ 'pass2' ] ) {
                            // Passwords are OK
                            $user_data[ 'user_pass' ] = $user_data[ 'pass1' ];
                        }
                    }
                }

                if ( ! b3_verify_terms() ) {
                    $errors->add( 'no_terms', $this->b3_get_return_message( 'no_terms' ) );

                    return $errors;
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
                    if ( true == $use_custom_passwords && isset( $user_data[ 'pass1' ] ) ) {
                        wp_set_password( $user_data[ 'pass1' ], $user_id );
                    }

                    $inform = 'both';
                    if ( 'email_activation' === $user_data[ 'registration_type' ] || ! get_option( 'b3_needs_admin_approval' ) ) {
                        // never notify an admin if a user hasn't confirmed email yet or no admin approval is needed
                        $inform = 'user';
                    }
                    $inform = apply_filters( 'b3_custom_register_inform', $inform );
                    wp_new_user_notification( $user_id, null, $inform );
                    do_action( 'b3_after_email_sent', $user_id, true );
                }

                return $user_id;
            }

            private function b3_multisite_registration( $redirect_url, $registration_type, $user_email = '', $meta = [] ) {
                $user_login = ( isset( $_POST[ 'user_name' ] ) ) ? sanitize_user( wp_unslash( $_POST[ 'user_name' ] ) ) : false;
                $register   = false;

                if ( is_main_site() ) {
                    if ( 'none' === $registration_type ) {
                        // Registration closed, display error
                        $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );

                    } elseif ( 'blog' === $registration_type ) {
                        $user = wp_get_current_user();

                        if ( $user && $user->exists() ) {
                            $user_login = $user->user_login;
                            $user_email = $user->user_email;
                            $register   = true;
                        }

                    } elseif ( get_option( 'b3_activate_recaptcha' ) && ! b3_verify_recaptcha() ) {
                        // Recaptcha check failed, display error
                        $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );

                    } else {
                        $register = true;
                    }
                }

                if ( true == $register ) {
                    $untranslated_map = [];
                    $gettext_callback = function( $translated, $text, $domain ) use ( &$untranslated_map ) {
                        if ( ! empty( $translated ) && ! empty( $text ) ) {
                            $untranslated_map[ $translated ] = $text;
                        }
                        return $translated;
                    };
                    add_filter( 'gettext', $gettext_callback, 10, 3 );

                    $admin_approval = get_option( 'b3_needs_admin_approval' );
                    $signup_for     = ( isset( $_POST[ 'signup_for' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'signup_for' ] ) ) : false;
                    $user_valid     = wpmu_validate_user_signup( $user_login, $user_email );
                    $errors         = $user_valid[ 'errors' ];

                    remove_filter( 'gettext', $gettext_callback, 10 );

                    if ( $errors->has_errors() && 'blog' !== $registration_type ) {
                        $error_codes = [];

                        foreach ( $errors->get_error_messages() as $message ) {
                            $english_text = $untranslated_map[ $message ] ?? $message;
                            $clean_text   = wp_strip_all_tags( $english_text );
                            $clean_text   = html_entity_decode( $clean_text, ENT_QUOTES, 'UTF-8' );
                            $slug         = sanitize_key( str_replace( ' ', '_', $clean_text ) );

                            switch ( $slug ) {
                                // Username errors
                                case 'sorry_that_username_already_exists':
                                    $error_codes[] = 'username_exists';
                                    break;
                                case 'usernames_can_only_contain_lowercase_letters_a_z_and_numbers':
                                    $error_codes[] = 'username_no_uppercase';
                                    break;
                                case 'that_username_is_currently_reserved_but_may_be_available_in_a_couple_of_days':
                                    $error_codes[] = 'wpmu_user_reserved';
                                    break;
                                case 'were_sorry_that_username_is_blocked_from_registering':
                                    $error_codes[] = 'username_blocked';
                                    break;

                                // Email errors
                                case 'error_this_email_address_is_already_registered_log_in_with_this_address_or_choose_another_one':
                                    $error_codes[] = 'email_exists';
                                    break;
                                case 'were_sorry_that_domain_is_blocked_from_registering':
                                    $error_codes[] = 'email_domain_banned';
                                    break;
                                case 'that_email_address_is_pending_activation_and_is_not_available_for_new_registration_if_you_made_a_previous_attempt_with_this_email_address_please_check_your_inbox_for_an_activation_email_if_left_unconfirmed_it_will_become_available_in_a_couple_of_days':
                                    $error_codes[] = 'wpmu_email_in_use';
                                    break;

                                default:
                                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                                    error_log( $slug . ' is not yet recognized' );
                                    $error_codes[] = 'unknown';
                                    break;
                            }
                        }

                        $error_string = implode( ',', array_unique( $error_codes ) );
                        $redirect_url = add_query_arg( 'registration-error', $error_string, $redirect_url );
                        wp_safe_redirect( $redirect_url );
                        exit;
                    }

                    if ( 'user' === $signup_for ) {
                        $result = $this->b3_register_wpmu_user( $user_login, $user_email, false, false, false, $meta );

                        if ( true == $result ) {
                            // Success, redirect to login page.
                            $redirect_url = b3_get_login_url();
                            $redirect_url = add_query_arg( 'registered', 'confirm_email', $redirect_url );

                        } elseif ( is_wp_error( $result ) ) {
                            $redirect_url = b3_get_register_url();
                            $errors       = join( ',', $result->get_error_codes() );
                            $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                        }

                    } elseif ( 'blog' === $signup_for ) {
                        $meta_data[ 'lang_id' ] = isset( $_POST[ 'lang_id' ] ) ? (int) $_POST[ 'lang_id' ] : 1;
                        $meta_data[ 'public' ]  = isset( $_POST[ 'blog_public' ] ) ? (int) $_POST[ 'blog_public' ] : 1;
                        $user                   = '';

                        if ( is_user_logged_in() ) {
                            $user = wp_get_current_user();
                        } elseif ( isset( $user_login ) && ! empty( $user_login ) ) {
                            $user             = new WP_User();
                            $user->user_login = $user_login;
                        }

                        $blog_name  = sanitize_text_field( wp_unslash( $_POST[ 'blogname' ] ?? '' ) );
                        $blog_title = sanitize_text_field( wp_unslash( $_POST[ 'blog_title' ] ?? '' ) );
                        $blog_info  = wpmu_validate_blog_signup( $blog_name, $blog_title, $user );

                        $domain      = $blog_info[ 'domain' ];
                        $path        = $blog_info[ 'path' ];
                        $blog_title  = $blog_info[ 'blog_title' ];
                        $errors      = $blog_info[ 'errors' ];
                        $error_codes = [];

                        if ( $errors->has_errors() && 'blog' !== $registration_type ) {
                            foreach ( $errors->get_error_codes() as $code ) {
                                if ( 'blogname' === $code ) {
                                    // Determine the precise error reason using input conditions
                                    if ( empty( $blog_name ) ) {
                                        $error_codes[] = 'no_address';
                                    } elseif ( strlen( $blog_name ) < 4 ) {
                                        $error_codes[] = 'site_min4';
                                    } elseif ( ctype_digit( $blog_name ) ) {
                                        $error_codes[] = 'site_letters';
                                    } elseif ( preg_match( '/[^a-z0-9]/', $blog_name ) ) {
                                        $error_codes[] = 'site_invalid_chars';
                                    } else {
                                        $error_codes[] = 'domain_exists';
                                    }
                                } elseif ( 'blog_title' === $code ) {
                                    $error_codes[] = 'no_title';
                                } else {
                                    $error_codes[] = 'unknown';
                                }
                            }

                            if ( ! empty( $error_codes ) ) {
                                $error_string = implode( ',', array_unique( $error_codes ) );
                                $redirect_url = add_query_arg( 'registration-error', $error_string, $redirect_url );
                                wp_safe_redirect( $redirect_url );
                                exit;
                            }
                        }

                        if ( empty( $error_codes ) ) {
                            $result = $this->b3_register_wpmu_user( $user_login, $user_email, $domain, $blog_title, $path, $meta_data );

                            if ( is_wp_error( $result ) ) {
                                $errors       = join( ',', $result->get_error_codes() );
                                $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                            } else {
                                // new site
                                if ( 'blog' === $registration_type ) {
                                    // Success, redirect to message.
                                    $redirect_url = add_query_arg( 'registered', 'new_blog', $redirect_url );
                                    $redirect_url = add_query_arg( 'site_id', $result, $redirect_url );
                                } else {
                                    // Success, redirect to login page.
                                    $redirect_url = b3_get_login_url();
                                    $redirect_url = add_query_arg( 'registered', 'wpmu_confirm_email', $redirect_url );
                                }
                            }
                        }
                    }
                }

                return $redirect_url;
            }

            private function b3_register_wpmu_user( $user_name, $user_email, $domain, $blog_title, $path, $meta = [] ) {
                $registration_type = $this->settings[ 'registration_type' ];

                if ( is_main_site() ) {
                    if ( in_array( $registration_type, [ 'user', 'all', 'site' ] ) || get_option( 'b3_needs_admin_approval' ) ) {
                        if ( get_option( 'b3_needs_admin_approval' ) ) {
                            $meta[ 'pending' ] = 1;
                        }
                        if ( false == $domain ) {
                            wpmu_signup_user( $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );

                            return true;
                        } else {
                            wpmu_signup_blog( $domain, $path, $blog_title, $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );

                            return true;
                        }
                    } elseif ( 'blog' === $registration_type ) {
                        if ( $user_name ) {
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

            public function b3_get_template_html( $template_name, $attributes = null ) {
                if ( ! $attributes ) {
                    $attributes = [];
                }

                if ( 'user-management' === $template_name ) {
                    $template_paths = [ B3OB_PLUGIN_PATH . '/templates/' ];
                } else {
                    $template_paths = b3_get_template_paths();
                }
                $location = b3_locate_template( $template_name );

                if ( $location ) {
                    ob_start();
                    do_action( 'b3_do_before_template', $template_name );
                    include $location;
                    do_action( 'b3_do_after_template', $template_name );

                    return ob_get_clean();
                }

                return '';
            }

            public function b3_admin_notices() {
                static $dev_message_shown = false;
                static $membership_notice_shown = false;
                static $no_frontend_approval_shown = false;
                static $no_registration_page_shown = false;

                $screen_ids = [
                    'toplevel_page_b3-onboarding',
                    'b3-onboarding_page_b3-debug-info',
                    'b3-onboarding_page_b3-user-approval',
                ];

                if ( in_array( get_current_screen()->id, $screen_ids ) ) {
                    if ( strpos( $this->settings[ 'version' ], 'dev' ) !== false || strpos( $this->settings[ 'version' ], 'beta' ) !== false ) {
                        /* translators: plugin name */
                        $dev_message = sprintf( esc_html__( "You're using a development version of %s, which has not been released yet and can give some unexpected results.", 'b3-onboarding' ), 'B3 OnBoarding' );
                        if ( false == apply_filters( 'b3_hide_development_notice', false ) && ! $dev_message_shown ) {
                            $dev_message_shown = true;
                            echo sprintf( '<div class="notice notice-warning"><p>%s</p></div>', esc_html( $dev_message ) );
                        }
                    }

                    if ( 'none' == get_option( 'b3_registration_type' ) && ! get_option( 'b3_register_page_id' ) && ! $no_registration_page_shown ) {
                        /* translators: here */
                        $no_registration_page       = sprintf( esc_html__( "You haven't set a page yet for registration. Set it %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-onboarding&tab=pages' ), esc_html__( 'here', 'b3-onboarding' ) ) );
                        $no_registration_page_shown = true;
                        echo sprintf( '<div class="error"><p>%s</p></div>', wp_kses_post( $no_registration_page ) );
                    }
                }

                // no page for front-end approval
                if ( ! get_option( 'b3_approval_page_id' ) && 1 == (int) get_option( 'b3_activate_front_end_approval' ) && ! $no_frontend_approval_shown ) {
                    $no_frontend_approval_shown = true;
                    /* translators: here */
                    echo sprintf( '<div class="error"><p>%s</p></div>', sprintf( esc_html__( 'You have not set a page for front-end user approval. Set it %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=pages' ) ), esc_html__( 'here', 'b3-onboarding' ) ) ) );
                }

                // manual actions
                // @TODO: look into this, when is it used (after change from user list in admin ?)
                if ( isset( $_GET[ 'update' ] ) ) {
                    $update_var = sanitize_text_field( wp_unslash( $_GET[ 'update' ] ) );
                    if ( in_array( $update_var, [ 'activated', 'sendactivation' ] ) ) {
                        echo '<div id="message" class="updated"><p>';
                        if ( 'activated' === $update_var ) {
                            esc_html_e( 'User activated.', 'b3-onboarding' );
                        } elseif ( 'sendactivation' === $update_var ) {
                            esc_html_e( 'Activation mail resent.', 'b3-onboarding' );
                        }
                        echo '</p></div>';
                    }
                }

                global $pagenow;
                if ( ! $membership_notice_shown && is_blog_admin() && $pagenow === 'options-general.php' && ! isset ( $_GET[ 'page' ] ) && ! is_multisite() ) {
                    /* translators: 1 Plugin name 2. here */
                    echo sprintf( '<div class="notice notice-info"><p>' . esc_html__( '%1$s takes control over the \'Membership\' option. You can change this %2$s.', 'b3-onboarding' ) . '</p></div>', 'B3 OnBoarding', sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=registration' ) ), esc_html__( 'here', 'b3-onboarding' ) ) );
                }
                $membership_notice_shown = true;

                if ( get_option( 'b3_activate_filter_validation' ) ) {
                    do_action( 'b3_verify_filter_input' );
                }
            }

            public function b3_after_create_site( $site ) {
                // create necessary pages
                b3_setup_initial_pages( $site->blog_id );
                // set default values
                b3_set_default_settings( $site->blog_id );
            }

            private function b3_switch_users_to_fallback_role() {
                $user_args = [
                    'fields'     => 'ID',
                    // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                    'meta_query' => [
                        [
                            'key'     => '_b3_fallback_role',
                            'value'   => '',
                            'compare' => '!=',
                        ],
                    ],
                ];
                $user_ids = get_users( $user_args );

                if ( empty( $user_ids ) ) {
                    return;
                }

                foreach ( $user_ids as $user_id ) {
                    $fallback_role = get_user_meta( $user_id, '_b3_fallback_role', true );

                    if ( $fallback_role ) {
                        $user_object = new WP_User( $user_id );

                        if ( $user_object->exists() ) {
                            $user_object->set_role( $fallback_role );
                            delete_user_meta( $user_id, '_b3_fallback_role' );
                        }
                    }
                }
            }

            private function b3_switch_users_to_default() {
                $roles = [
                    'b3_activation',
                    'b3_approval',
                ];

                foreach ( $roles as $role ) {
                    $user_ids = get_users( [
                        'role'   => $role,
                        'fields' => 'ID',
                    ] );

                    if ( empty( $user_ids ) ) {
                        continue;
                    }

                    foreach ( $user_ids as $user_id ) {
                        $user_object = get_userdata( $user_id );

                        if ( $user_object ) {
                            update_user_meta( $user_id, '_b3_fallback_role', $role );
                            $user_object->set_role( 'subscriber' );
                        }
                    }
                }
            }

            public function b3_get_return_message( $error_code, $label = false ) {
                switch( $error_code ) {
                    case 'banned_domain':
                        return esc_html__( 'This domain is not allowed to register.', 'b3-onboarding' );

                    case 'username_blocked':
                        return esc_html__( 'This username is not allowed to register.', 'b3-onboarding' );

                    case 'empty_username':
                        return esc_html__( 'Please enter a username.', 'b3-onboarding' );

                    case 'empty_password':
                        return esc_html__( 'Please enter a password.', 'b3-onboarding' );

                    case 'empty_email':
                        return esc_html__( 'Please enter an email address.', 'b3-onboarding' );

                    case 'incorrect_password':
                        $error_message = esc_html__( "The username or password you entered wasn't quite right.", 'b3-onboarding' );
                        $error_message .= '<br>';
                        /* translators: password update, forgot */
                        $error_message .= sprintf( esc_html__( 'Did you %s your password ?', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( wp_lostpassword_url() ), esc_html__( 'forget', 'b3-onboarding' ) ) );

                        return $error_message;

                    case 'logged_in':
                        return esc_html__( 'You are now logged in.', 'b3-onboarding' );

                    case 'logged_out':
                        return esc_html__( 'You are logged out.', 'b3-onboarding' );

                    case 'verification_fail':
                        return esc_html__( 'Your code seems to be invalid. Please try again.', 'b3-onboarding' );

                    case 'unknown':
                        return esc_html__( 'An unkown error has occured. Please try again.', 'b3-onboarding' );

                    case 'unlawful_form':
                        return esc_html__( 'Unlawful form entry, try again.', 'b3-onboarding' );

                    // Login errors
                    case 'code_sent':
                        $message = __( 'If your email address is associated with a user, you will receive an email shortly with a magic link.', 'b3-onboarding' );
                        $message .= '&nbsp;';
                        /* translators: amount of minutes for expiry */
                        $message .= sprintf( esc_html__( 'The link is valid for %d minutes.', 'b3-onboarding' ), (int) apply_filters( 'b3_magic_link_time_out', 5 ) );

                        return esc_html( $message );

                    case 'unknown_user':
                        return esc_html__( 'There is no user with this email address.', 'b3-onboarding' );

                    // Registration errors
                    case 'username_exists':
                        return esc_html__( 'This username is already in use.', 'b3-onboarding' );

                    case 'username_no_uppercase':
                        return esc_html__( 'Usernames can only contain lowercase letters (a-z) and numbers.', 'b3-onboarding' );

                    case 'disallowed_username':
                        return esc_html__( 'That username is not allowed, please choose another.', 'b3-onboarding' );

                    case 'invalid_email':
                        return esc_html__( 'The email address you entered is not valid.', 'b3-onboarding' );

                    case 'invalid_username':
                        if ( get_option( 'b3_register_email_only' ) ) {
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

                    case 'no_terms':
                        return esc_html__( 'You have to accept the general terms.', 'b3-onboarding' );

                    case 'empty_field':
                        if ( false != $label ) {
                            /* translators: field label */
                            return sprintf( esc_html__( "You didn't select an option for '%s'.", 'b3-onboarding' ), $label );
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
                        return esc_html__( 'If the email address you used is connected to an account, you will receive an email with a link to reset your password.', 'b3-onboarding' );

                    // Registration
                    case 'pw_too_easy':
                        return esc_html__( 'That password is too easy, please use a better one.', 'b3-onboarding' );

                    case 'registration_success':
                        if ( get_option( 'b3_activate_custom_passwords' ) ) {
                            return esc_html__( 'You have successfully registered. You can now login.', 'b3-onboarding' );
                        } else {
                            return esc_html__( 'You have successfully registered. Please check your email for a link to set your password.', 'b3-onboarding' );
                        }

                    case 'registration_success_enter_password':
                        /* translators: site name */
                        return sprintf( esc_html__( 'You have successfully registered to %s. Enter your email address to set your password.', 'b3-onboarding' ), get_bloginfo( 'name' ) );

                    // Activation
                    case 'activation_needed':
                        return esc_html__( 'You must activate your account first. Please check your email.', 'b3-onboarding' );

                    case 'activate_approval_needed':
                        return esc_html__( "You have successfully activated your account, but the site owner chose to manually approve each regstration. You'll be notified about the outcome.", 'b3-onboarding' );

                    case 'approval_needed':
                        return esc_html__( "You can't request a magic link yet, because your account must be approved first. You'll be notified about the outcome.", 'b3-onboarding' );

                    case 'activate_success':
                        if ( get_option( 'b3_use_magic_link' ) ) {
                            return esc_html__( 'You have successfully activated your account. You can now login through the use of a magic link. Please enter your email address below.', 'b3-onboarding' );
                        } elseif ( get_option( 'b3_activate_custom_passwords' ) ) {
                            return esc_html__( 'You have successfully activated your account. You can now login.', 'b3-onboarding' );
                        } else {
                            return esc_html__( 'You have successfully activated your account. You can initiate a password (re)set below.', 'b3-onboarding' );
                        }

                    case 'activate_success_approval':
                        return esc_html__( 'You have successfully activated your account but the site owner choose to manually approve each account. You will be notified of the outcome.', 'b3-onboarding' );

                    case 'activate_success_magic':
                        return esc_html__( 'You have successfully activated your account and you can find a magic login link in your email or request a new one.', 'b3-onboarding' );

                    case 'mu_activate_success':
                        return esc_html__( 'You have successfully activated your account. Your password has been emailed to you.', 'b3-onboarding' );

                    case 'mu_activate_magic':
                        return esc_html__( 'You have successfully activated your account. Your magic login link has been emailed to you.', 'b3-onboarding' );

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
                        return esc_html__( 'Sorry, that password is too easy.', 'b3-onboarding' );

                    // Multisite
                    case 'domain_exists':
                        return esc_html__( 'Sorry, this domain has already been taken.', 'b3-onboarding' );

                    case 'no_address':
                        return esc_html__( 'Please enter a site address.', 'b3-onboarding' );

                    case 'site_min4':
                        return esc_html__( 'Site name must be at least 4 characters.', 'b3-onboarding' );

                    case 'site_letters':
                        return esc_html__( 'Sorry, site names must have letters too.', 'b3-onboarding' );

                    case 'site_invalid_chars':
                        return esc_html__( 'You have invalid characts in your site name. Only a-z, 0-9 and - are allowed.', 'b3-onboarding' );

                    case 'no_title':
                        return esc_html__( 'Please enter a title.', 'b3-onboarding' );

                    case 'user_registered':
                        return esc_html__( 'You have successfully registered. Please check your email for an activation link.', 'b3-onboarding' );

                    case 'wpmu_user_reserved':
                        return esc_html__( 'That username is currently reserved but may be available in a couple of days.', 'b3-onboarding' );

                    case 'wpmu_email_in_use':
                        return esc_html__( 'That email address has already been used. Please check your inbox for an activation email. It will become available in a couple of days if you do nothing.', 'b3-onboarding' );

                    case 'email_domain_banned':
                        return esc_html__( 'This domain is not allowed to register.', 'b3-onboarding' );

                    // Account
                    case 'account_remove':
                        return esc_html__( 'Your account has been deleted.', 'b3-onboarding' );

                    case 'profile_saved':
                        return esc_html__( 'Profile saved', 'b3-onboarding' );

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
                }

                return esc_html__( 'An unknown error occurred. Please try again later.', 'b3-onboarding' );
            }

            public static function get_instance() {
                static $instance;

                if ( null === $instance ) {
                    $instance = new self();
                }

                return $instance;
            }
        }

        B3Onboarding::get_instance();
    }
