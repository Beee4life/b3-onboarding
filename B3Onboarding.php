<?php
    /*
    Plugin Name:        B3 OnBoarding
    Plugin URI:         https://b3onboarding.berryplasman.com
    Description:        This plugin styles the default WordPress pages into your own design. It gives you more control over the registration/login process (aka onboarding).
    Version:            2.6.0
    Requires at least:  4.3
    Tested up to:       5.5.1
    Requires PHP:       5.6
    Author:             Beee
    Author URI:         https://berryplasman.com
    Tags:               user, management, registration, login, lost password, reset password, account
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
        exit; // Exit if accessed directly
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
            }


            /**
             * This initializes the whole shabang
             */
            public function init() {
                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '2.5.0',
                );

                // actions
                register_activation_hook( __FILE__,            array( $this, 'b3_plugin_activation' ) );
                register_deactivation_hook( __FILE__,          array( $this, 'b3_plugin_deactivation' ) );

                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_enqueue_scripts_frontend' ), 40 );
                add_action( 'login_head',                           array( $this, 'b3_add_login_styling' ) );
                add_action( 'admin_enqueue_scripts',                array( $this, 'b3_enqueue_scripts_backend' ) );
                add_action( 'admin_menu',                           array( $this, 'b3_add_admin_pages' ) );
                add_action( 'widgets_init',                         array( $this, 'b3_register_widgets' ) );
                add_action( 'wp_dashboard_setup',                   array( $this, 'b3_add_dashboard_widget' ) );
                add_action( 'wp_logout',                            array( $this, 'b3_redirect_after_logout' ) );
                add_action( 'init',                                 array( $this, 'b3_load_plugin_text_domain' ) );
                add_action( 'template_redirect',                    array( $this, 'b3_template_redirect' ) );
                add_action( 'init',                                 array( $this, 'b3_redirect_to_custom_profile' ) );
                add_action( 'login_form_register',                  array( $this, 'b3_redirect_to_custom_register' ) );
                add_action( 'login_form_login',                     array( $this, 'b3_redirect_to_custom_login' ) );
                add_action( 'login_form_lostpassword',              array( $this, 'b3_redirect_to_custom_lostpassword' ) );
                add_action( 'login_form_resetpass',                 array( $this, 'b3_redirect_to_custom_reset_password' ) );
                add_action( 'login_form_rp',                        array( $this, 'b3_redirect_to_custom_reset_password' ) );
                add_action( 'init',                                 array( $this, 'b3_registration_form_handling' ) );
                add_action( 'init',                                 array( $this, 'b3_do_user_activate' ) );
                add_action( 'init',                                 array( $this, 'b3_do_password_lost' ) );
                add_action( 'init',                                 array( $this, 'b3_reset_user_password' ) );
                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_add_captcha_js_to_footer' ) );
                add_action( 'login_enqueue_scripts',                array( $this, 'b3_add_captcha_js_to_footer' ) );
                add_action( 'admin_notices',                        array( $this, 'b3_admin_notices' ) );
                add_action( 'load-users.php',                       array( $this, 'b3_load_users_page' ) );

                // Multisite specific
                add_action( 'wp_initialize_site',                   array( $this, 'b3_new_blog' ) );

                // Filters
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this, 'b3_settings_link' ) );
                add_filter( 'admin_body_class',                     array( $this, 'b3_admin_body_class' ) );
                add_filter( 'authenticate',                         array( $this, 'b3_maybe_redirect_at_authenticate' ), 101, 3 );
                add_filter( 'login_redirect',                       array( $this, 'b3_redirect_after_login' ), 10, 3 );
                add_filter( 'wp_mail_from',                         array( $this, 'b3_email_from' ) );
                add_filter( 'wp_mail_from_name',                    array( $this, 'b3_email_from_name' ) );
                add_filter( 'wp_mail_content_type',                 array( $this, 'b3_email_content_type' ) );
                add_filter( 'user_row_actions',                     array( $this, 'b3_user_row_actions' ), 10, 2 );

                // WP Login pages
                add_filter( 'login_headerurl',                      array( $this, 'b3_login_logo_url' ) );
                add_filter( 'login_headertext',                     array( $this, 'b3_login_logo_url_title' ) );

                include 'includes/actions-b3.php';
                include 'includes/actions-wp.php';
                include 'includes/class-b3-shortcodes.php';
                include 'includes/do-stuff.php';
                if ( defined( 'LOCALHOST' ) && true == LOCALHOST ) {
                    include 'includes/examples.php';
                }
                include 'includes/filters-b3.php';
                include 'includes/filters-wp.php';
                include 'includes/functions.php';
                include 'includes/defaults.php';
                include 'includes/emails.php';
                include 'includes/form-handling.php';
                include 'includes/tabs/tabs.php';
                include 'includes/admin/help-tabs.php';
                if ( get_site_option( 'b3_activate_filter_validation' ) ) {
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
                $this->b3_set_default_settings();

                if ( ! is_multisite() ) {
                    $b3_activation = get_role( 'b3_activation' );
                    if ( ! $b3_activation ) {
                        add_role( 'b3_activation', __( 'Awaiting activation' ), array() );
                    }
                    $b3_approval = get_role( 'b3_approval' );
                    if ( ! $b3_approval ) {
                        add_role( 'b3_approval', __( 'Awaiting approval' ), array() );
                    }
                }

            }


            /**
             * Do stuff upon plugin deactivation
             */
            public function b3_plugin_deactivation() {
                // set registration option accordingly
                $registration_type = get_site_option( 'b3_registration_type' );
                if ( is_multisite() ) {
                    if ( is_main_site() ) {
                        if ( 'closed' == $registration_type ) {
                            update_site_option( 'registration', 'none' );
                        } else {
                            update_site_option( 'registration', 'all' );
                        }
                    }
                } else {
                    if ( 'closed' == $registration_type ) {
                        update_option( 'users_can_register', '0' );
                    } else {
                        update_option( 'users_can_register', '1' );
                    }
                }

                if ( function_exists( 'b3_get_all_custom_meta_keys' ) ) {
                    $meta_keys   = b3_get_all_custom_meta_keys();
                    $meta_keys[] = 'widget_b3-widget';
                    foreach( $meta_keys as $key ) {
                        delete_site_option( $key );
                        delete_option( $key );
                    }
                }
            }


            /**
             * Set default settings
             *
             * @since 2.0.0
             */
            public function b3_set_default_settings() {

                update_site_option( 'b3_activate_custom_emails', 1 );
                update_site_option( 'b3_dashboard_widget', 1 );
                update_site_option( 'b3_disable_wordpress_forms', 1 );
                update_site_option( 'b3_logo_in_email', 1 );
                update_site_option( 'b3_notification_sender_email', get_bloginfo( 'admin_email' ) );
                update_site_option( 'b3_notification_sender_name', get_bloginfo( 'name' ) );
                update_site_option( 'b3_registration_type', 'open' );

                if ( ! is_multisite() ) {
                    update_site_option( 'b3_hide_admin_bar', 1 );
                    update_site_option( 'b3_restrict_admin', array( 'subscriber', 'b3_activation', 'b3_approval' ) );
                    update_site_option( 'users_can_register', 0 );

                } else {

                    if ( is_main_site() ) {
                        update_site_option( 'b3_disable_admin_notification_new_user', 1 );
                        update_site_option( 'registrationnotification', 'no' );

                        $public_registration = get_site_option( 'registration' );
                        if ( 'user' == $public_registration ) {
                            update_site_option( 'b3_registration_type', 'user' );
                        } elseif ( 'blog' == $public_registration ) {
                            update_site_option( 'b3_registration_type', 'blog' );
                        } elseif ( 'all' == $public_registration ) {
                            update_site_option( 'b3_registration_type', 'all' );
                        } elseif ( 'none' == $public_registration ) {
                            update_site_option( 'b3_registration_type', 'closed' );
                        }
                    }
                }

                if ( false != get_option( 'wp_page_for_privacy_policy' ) ) {
                    update_option( 'b3_privacy_page', get_option( 'wp_page_for_privacy_policy' ) );
                }
            }

            /**
             * Do upon new blog
             *
             * @param $new_site
             */
            public function b3_new_blog( $new_site ) {
                // @TODO: add setting if local account page is 'wanted'

                /*
                 * Available vars:
                 * - blog_id (= site id)
                 * - domain
                 * - path
                 * - site id (= network id)
                 * - lang_id
                 */

                // switch_to_blog( $new_site->blog_id );
                // @TODO: create new page account
                // restore_current_blog();

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


            /**
             * Add login styling
             *
             * @since 2.0.0
             */
            public function b3_add_login_styling() {
                $extra_fields    = apply_filters( 'b3_extra_fields', array() );
                $logo            = apply_filters( 'b3_main_logo', b3_get_main_logo() );
                $logo_height     = get_site_option( 'b3_loginpage_logo_height' );
                $logo_width      = get_site_option( 'b3_loginpage_logo_width' );
                $privacy         = get_site_option( 'b3_privacy' );
                $recaptcha       = get_site_option( 'b3_activate_recaptcha' );
                $recaptcha_login = get_site_option( 'b3_recaptcha_login' );
                $style_pages     = get_site_option( 'b3_style_wordpress_forms' );

                echo '<style type="text/css">';
                echo "\n";
                if ( $style_pages ) {
                    $bg_color    = get_site_option( 'b3_loginpage_bg_color' );
                    $font_family = get_site_option( 'b3_loginpage_font_family' );
                    $font_size   = get_site_option( 'b3_loginpage_font_size' );

                    if ( $bg_color ) {
                        echo "\nbody { background: #" . $bg_color . "; }\n";
                    }
                    if ( $font_family || $font_size ) {
                        echo '#login { ';
                        if ( $font_family ) {
                            echo 'font-family: ' . $font_family . ';';
                        }
                        if ( $font_size ) {
                            echo 'font-size: ' . $font_size . 'px;';
                        }
                        if ( $recaptcha || $recaptcha_login ) {
                            echo 'min-width: 352px;';
                        }
                        echo " }\n";
                        if ( $recaptcha || $recaptcha_login ) {
                            echo '.recaptcha-container {';
                            echo 'margin: 0 0 1rem 0;';
                            echo '}';
                            echo "\n";
                        }
                        if ( $font_size ) {
                            echo '.login label { font-size: ' . $font_size . 'px; }';
                            echo "\n";
                        }
                    }
                }

                if ( $logo ) {
                    echo '.login h1 a { ';
                    echo 'background-image: url(' . $logo . '); ';
                    echo 'background-image: none, url(' . $logo . '); ';
                    echo 'background-repeat: no-repeat; ';
                    echo 'padding: 0; ';
                    if ( $logo_width ) {
                        echo 'background-size: ' . $logo_width . 'px; ';
                        echo 'width: ' . $logo_width . 'px; ';
                    }
                    if ( $logo_height ) {
                        echo 'height: ' . $logo_height . 'px; ';
                    }
                    echo 'max-width: 320px; ';
                    echo 'max-height: 150px;';
                    echo ' }';
                }

                if ( ! $style_pages && ( $recaptcha || $recaptcha_login ) ) {
                    echo '#login { min-width: 352px; }';
                    echo "\n";
                }

                echo '.login form .input, .login input[type="text"], .login input[type="password"] { margin: 0 6px 0 0; }';
                echo "\n";
                echo '.login form#lostpasswordform input[type="text"] { margin: 0 6px 16px 0;; }';
                echo "\n";
                echo '.login form p, .b3_form-element { margin: 1em 0 0 0; }';
                echo "\n";
                echo '.login form#loginform .user-pass-wrap { margin: 1em 0; }';
                echo "\n";
                echo '.login form p:first-child { margin-top: 0; }';
                echo "\n";

                if ( ! empty( $extra_fields ) ) {
                    echo '.login label.b3_form-label { width: 100%; }';
                    echo "\n";

                    echo '.login input[type="text"].b3_form-input--text { ';
                    echo 'font-size: 14px;';
                    echo 'min-height: 30px;';
                    echo 'padding: 0 8px;';
                    echo ' }';
                    echo "\n";

                    echo '.login input[type="text"].b3_form-input--text input { padding: 0 8px; }';
                    echo "\n";

                    echo 'input.b3_form-input--number, input.b3_form-input--url { ';
                    echo 'line-height: 1.33333333;';
                    echo 'width: 100%;';
                    echo ' }';
                    echo "\n";

                    echo '.b3_form-input--textarea { ';
                    echo 'border-width: 0.0625rem;';
                    echo 'line-height: 1.33333333;';
                    echo 'padding: 8px;';
                    echo 'width: 100%;';
                    echo ' }';
                    echo "\n";

                    echo '.b3_input-option { margin-bottom: 0.5em; }';
                    echo "\n";

                    echo '.b3_form-element--select select { ';
                    echo 'line-height: 1.33333333;';
                    echo 'padding: 8px;';
                    echo 'width: 100%;';
                    echo ' }';
                    echo "\n";
                }

                echo '.recaptcha-container {margin: 0 0 1rem 0; }';
                echo "\n";

                if ( $privacy ) {
                    echo '.b3_form-element--privacy { margin-bottom: 1em; }';
                }

                echo '</style>';
                echo "\n";
            }


            /*
             * Enqueue scripts front-end
             */
            public function b3_enqueue_scripts_frontend() {
                wp_enqueue_style( 'b3-ob-main', plugins_url( 'assets/css/style.css', __FILE__ ), array(), $this->settings[ 'version' ] );
                wp_enqueue_script( 'b3-ob-js', plugins_url( 'assets/js/js.js', __FILE__ ), array( 'jquery' ), $this->settings[ 'version' ] );
            }


            /*
             * Enqueue scripts in backend
             */
            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3-ob-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->settings[ 'version' ] );
                wp_enqueue_script( 'b3-ob-js-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->settings[ 'version' ] );

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
                        'title'     => __( 'Upload or choose your custom logo', 'b3-onboarding' ),
                        'button'    => __( 'Insert logo', 'b3-onboarding' ),
                    )
                );
                wp_enqueue_script( 'b3-media' );
            }


            /*
             * Adds a page to admin sidebar menu
             */
            public function b3_add_admin_pages() {
                include 'includes/admin/admin-page.php';
                add_menu_page( 'B3 OnBoarding', 'B3 OnBoarding', 'manage_options', 'b3-onboarding', 'b3_user_register_settings', B3_PLUGIN_URL .  'assets/images/logo-b3onboarding-small.png', '83' );

                if ( is_main_site() ) {
                    if ( in_array( get_site_option( 'b3_registration_type' ), [ 'request_access', 'request_access_subdomain' ] ) ) {
                        include 'includes/admin/user-approval-page.php';
                        add_submenu_page( 'b3-onboarding', 'B3 OnBoarding ' . __( 'User Approval', 'b3-onboarding' ), __( 'User Approval', 'b3-onboarding' ), 'manage_options', 'b3-user-approval', 'b3_user_approval' );
                    }

                    if ( true == get_site_option( 'b3_debug_info' ) ) {
                        include 'includes/admin/debug-page.php';
                        add_submenu_page( 'b3-onboarding', 'B3 OnBoarding ' . __( 'Debug info', 'b3-onboarding' ), __( 'Debug info', 'b3-onboarding' ), 'manage_options', 'b3-debug', 'b3_debug_page' );
                    }
                }
            }


            /*
             * Register widgets (if activated)
             */
            public function b3_register_widgets() {
                /*
                 * Includes sidebar widget function + call
                 */
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
                    include 'includes/admin/dashboard-widget.php';
                    if ( defined( 'LOCALHOST' ) && true == LOCALHOST ) {
                        include 'includes/admin/dashboard-widget-debug.php';
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
                $settings_link = '<a href="admin.php?page=b3-onboarding">' . esc_html__( 'Settings', 'b3-onboarding' ) . '</a>';
                // add if for if add-ons are active
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

                $from_email = apply_filters( 'b3_notification_sender_email', b3_get_notification_sender_email() );
                if ( false != $from_email ) {
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

                $sender_name = apply_filters( 'b3_notification_sender_name', b3_get_notification_sender_name() );
                if ( false != $sender_name ) {
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
             * Add user actions on users.php
             *
             * @param $actions
             * @param $user_object
             *
             * @return mixed
             */
            public function b3_user_row_actions( $actions, $user_object ) {

                $current_user      = wp_get_current_user();
                $registration_type = get_site_option( 'b3_registration_type' );

                if ( $current_user->ID != $user_object->ID ) {
                    if ( 'email_activation' == $registration_type ) {
                        if ( in_array( 'b3_activation', (array) $user_object->roles ) ) {
                            $actions[ 'activate' ]          = sprintf( '<a href="%1$s">%2$s</a>', add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER[ 'REQUEST_URI' ] ) ) ), wp_nonce_url( 'users.php?action=activate&amp;user_id=' . $user_object->ID, 'manual-activation' ) ), __( 'Activate', 'b3-onboarding' ) );
                            $actions[ 'resend_activation' ] = sprintf( '<a href="%1$s">%2$s</a>', add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER[ 'REQUEST_URI' ] ) ) ), wp_nonce_url( 'users.php?action=resendactivation&amp;user_id=' . $user_object->ID, 'resend-activation' ) ), __( 'Resend activation', 'b3-onboarding' ) );
                        }
                    } elseif ( 'request_access' == $registration_type ) {
                        if ( in_array( 'b3_approval', (array) $user_object->roles ) ) {
                            $actions[ 'activate' ] = sprintf( '<a href="%1$s">%2$s</a>',
                                add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER[ 'REQUEST_URI' ] ) ) ),
                                    wp_nonce_url( 'users.php?action=activate&amp;user_id=' . $user_object->ID, 'manual-activation' )
                                ),
                                __( 'Activate', 'b3-onboarding' )
                            );
                        }
                    }
                }

                return $actions;
            }


            /**
             * Check if user actions need to be taken
             */
            public function b3_load_users_page() {
                add_action( 'admin_notices', array( $this, 'b3_admin_notices' ) );

                if ( isset( $_GET[ 'action' ] ) && in_array( $_GET[ 'action' ], array( 'activate', 'resendactivation' ) ) ) {
                    $user_id = isset( $_GET[ 'user_id' ] ) ? $_GET[ 'user_id' ] : false;
                    if ( ! $user_id ) {
                        wp_die( __( "There's no user with that ID.", 'b3-onboarding' ) );
                    } elseif ( ! current_user_can( 'edit_user', $user_id ) ) {
                        wp_die( __( "You're not allowed to edit that user.", 'b3-onboarding' ) );
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
                            $redirect_to = add_query_arg( 'update', 'manually-activated', $redirect_to );
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


            /**
             * Add to admin body class
             *
             * @param $classes
             *
             * @return string
             */
            public function b3_admin_body_class( $classes ) {

                if ( 'request_access' != get_site_option( 'b3_registration_type' ) ) {
                    $classes .= 'no-approval-page';
                }

                return $classes;
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
                            echo '<p>';
                            if ( true == $prefix ) {
                                echo '<strong>' . $prefix . ':</strong> ';
                            }
                            echo $message;
                            echo '</p>';
                        }
                        echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss this notice', 'b3-onboarding' ) . '</span></button>';
                        echo '</div>';
                    }
                }
            }


            /**
             * An action function used to include the reCAPTCHA JavaScript file
             * at the end of the page.
             */
            public function b3_add_captcha_js_to_footer() {
                $recaptcha    = get_site_option( 'b3_activate_recaptcha' );
                $recaptcha_on = get_site_option( 'b3_recaptcha_on', [] );
                if ( true == $recaptcha && ! empty( $recaptcha_on ) ) {
                    wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array(), false, true );
                }
            }


            /**
             * Checks that the reCAPTCHA parameter sent with the registration
             * request is valid.
             *
             * @return bool True if the CAPTCHA is OK, otherwise false.
             */
            public function b3_verify_recaptcha() {
                if ( isset ( $_POST[ 'g-recaptcha-response' ] ) ) {
                    $recaptcha_response = $_POST[ 'g-recaptcha-response' ];
                } else {
                    return false;
                }

                $recaptcha_secret = get_site_option( 'b3_recaptcha_secret' );
                $success          = false;
                if ( false != $recaptcha_secret ) {
                    // Verify the captcha response from Google
                    $response = wp_remote_post(
                        'https://www.google.com/recaptcha/api/siteverify',
                        array(
                            'body' => array(
                                'secret' => $recaptcha_secret,
                                'response' => $recaptcha_response
                            )
                        )
                    );

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
                            $user_email        = ( isset( $_POST[ 'user_email' ] ) ) ? sanitize_email( $_POST[ 'user_email' ] ) : false;
                            if ( ! is_email( $user_email ) ) {
                                $redirect_url = add_query_arg( 'registration-error', 'invalid_email', $redirect_url );
                                wp_safe_redirect( $redirect_url );
                                exit;
                            }
                            $registration_type = get_site_option( 'b3_registration_type' );
                            if ( is_multisite() ) {
                                $user_login = ( isset( $_POST[ 'user_name' ] ) ) ? sanitize_user( $_POST[ 'user_name' ] ) : false;
                            } else {
                                $user_login = ( isset( $_POST[ 'user_login' ] ) ) ? sanitize_user( $_POST[ 'user_login' ] ) : false;
                            }

                            if ( isset( $_POST[ 'first_name' ] ) ) {
                                $meta_data[ 'first_name' ] = sanitize_text_field( $_POST[ 'first_name' ] );
                            }
                            if ( isset( $_POST[ 'last_name' ] ) ) {
                                $meta_data[ 'last_name' ] = sanitize_text_field( $_POST[ 'last_name' ] );
                            }

                            if ( ! is_multisite() ) {
                                $role = get_option( 'default_role', 'subscriber' );
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );

                                } elseif ( false != get_site_option( 'b3_activate_recaptcha' ) ) {
                                    $recaptcha_on = get_site_option( 'b3_recaptcha_on' );
                                    if ( is_array( $recaptcha_on ) && in_array( 'register', $recaptcha_on ) ) {
                                        if ( ! $this->b3_verify_recaptcha() ) {
                                            // Recaptcha check failed, display error
                                            $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );
                                        }
                                    }
                                } elseif ( 'closed' != $registration_type ) {
                                    // Registration is not closed
                                    if ( 'request_access' == $registration_type ) {
                                        $role      = 'b3_approval';
                                        $query_arg = 'access_requested';
                                    } elseif ( 'email_activation' == $registration_type ) {
                                        $role      = 'b3_activation';
                                        $query_arg = 'confirm_email';
                                    } else {
                                        $query_arg      = 'success';
                                        $reset_password = ( true == get_site_option( 'b3_redirect_set_password' ) ) ? true : false;
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
                                                // @TODO: also add to wp form register + MU register
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
                                $register = false;
                                if ( is_main_site() ) {
                                    if ( 'closed' == $registration_type ) {
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
                                    } elseif ( false != get_site_option( 'b3_activate_recaptcha' ) ) {
                                        $recaptcha_on = get_site_option( 'b3_recaptcha_on' );
                                        if ( is_array( $recaptcha_on ) && in_array( 'register', $recaptcha_on ) ) {
                                            if ( ! $this->b3_verify_recaptcha() ) {
                                                // Recaptcha check failed, display error
                                                $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );
                                            }
                                        }
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


            ## REDIRECTS

            /*
             * Protect pages + redirect user (if needed)
             */
            public function b3_template_redirect() {
                $account_page_id  = b3_get_account_url( true );
                $account_url      = b3_get_account_url();
                $approval_page_id = b3_get_user_approval_link( true );
                $current_url      = b3_get_current_url();
                $login_page_id    = b3_get_login_url( true );
                $login_url        = ( false != $login_page_id ) ? get_the_permalink( $login_page_id ) : wp_login_url();
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
                            $redirect_to           = $_REQUEST[ 'redirect_to' ];
                            $requested_redirect_to = $_REQUEST[ 'redirect_to' ];
                        } else {
                            $redirect_to           = site_url( 'wp-login.php?loggedout=true' );
                            $requested_redirect_to = '';
                        }

                        $redirect_url = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
                    }

                    if ( is_home() || is_front_page() ) {
                        if ( isset( $_REQUEST[ 'logout' ] ) ) {
                            check_admin_referer( 'logout' );
                            $user = wp_get_current_user();
                            wp_logout();
                            $redirect_to  = home_url();
                            $redirect_url = apply_filters( 'logout_redirect', $redirect_to, '', $user );
                        }
                    }
                }

                if ( isset( $redirect_url ) ) {
                    wp_safe_redirect( $redirect_url );
                    exit;
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
            function b3_maybe_redirect_at_authenticate( $user, $username, $password ) {
                // Check if the earlier authenticate filter (most likely, the default WordPress authentication) functions have found errors
                if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
                    if ( is_wp_error( $user ) ) {
                        $error_codes = join( ',', $user->get_error_codes() );
                        $login_url   = b3_get_login_url();
                        $login_url   = add_query_arg( 'login', $error_codes, $login_url );

                        wp_safe_redirect( $login_url );
                        exit;
                    }
                }

                return $user;
            }


            ## Redirect away from default WP pages

            /**
             * Redirects "profile.php" to custom account page
             */
            public function b3_redirect_to_custom_profile() {
                global $current_user;
                if ( is_user_logged_in() && is_admin() ) {
                    $user_role = reset( $current_user->roles );
                    if ( in_array( $user_role, get_site_option( 'b3_restrict_admin', [] ) ) ) {
                        $frontend_account_url = b3_get_account_url();
                        if ( false != $frontend_account_url ) {
                            $redirect_to = $frontend_account_url;
                        } else {
                            $redirect_to = get_home_url();
                        }

                        if ( ! defined( 'DOING_AJAX' ) ) {
                            wp_safe_redirect( $redirect_to );
                            exit;
                        }
                    }
                }
            }


            /**
             * Redirects the user to the custom registration page instead
             * of wp-login.php?action=register.
             */
            public function b3_redirect_to_custom_register() {

                if ( ! is_multisite() ) {
                    if ( 'request_access' == get_site_option( 'b3_registration_type' ) ) {
                        if ( ! isset( $_REQUEST[ 'b3_form' ] ) || isset( $_REQUEST[ 'b3_form' ] ) && 'register' != $_REQUEST[ 'b3_form' ] ) {
                            $register_url = b3_get_register_url();
                            if ( false != $register_url ) {
                                wp_safe_redirect( $register_url );
                                exit;
                            }
                        }
                    } else {
                        if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_site_option( 'b3_disable_wordpress_forms' ) ) {
                            if ( is_user_logged_in() ) {
                                $this->b3_redirect_logged_in_user();
                            } else {
                                $register_url = b3_get_register_url();
                                if ( false != $register_url ) {
                                    wp_safe_redirect( $register_url );
                                } else {
                                    wp_safe_redirect( wp_registration_url() );
                                }
                                exit;
                            }
                        }
                    }
                } else {
                    if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_site_option( 'b3_disable_wordpress_forms' ) ) {
                        if ( is_user_logged_in() ) {
                            $this->b3_redirect_logged_in_user();
                        } else {
                            $register_url = b3_get_register_url();
                            if ( false != $register_url ) {
                                wp_safe_redirect( $register_url );
                            } else {
                                wp_safe_redirect( wp_registration_url() );
                            }
                            exit;
                        }
                    }
                }
            }


            /**
             * Force user to custom login page instead of wp-login.php.
             */
            public function b3_redirect_to_custom_login() {

                $disable_wp_forms = get_site_option( 'b3_disable_wordpress_forms' );
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == $disable_wp_forms ) {
                    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? urlencode( $_REQUEST[ 'redirect_to' ] ) . '&reauth=1' : null;

                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user( $redirect_to );
                        exit;
                    }

                    $blog_id = ( is_multisite() ) ? get_current_blog_id() : false;
                    $login_url = b3_get_login_url( '', $blog_id );

                    if ( ! empty( $redirect_to ) ) {
                        $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
                    }

                    wp_safe_redirect( $login_url );
                    exit;
                }
            }


            /**
             * Redirects the user to the custom "Lost password?" page instead of
             * wp-login.php?action=lostpassword.
             */
            public function b3_redirect_to_custom_lostpassword() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_site_option( 'b3_disable_wordpress_forms' ) ) {
                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user();
                        exit;
                    }

                    $lost_password_url = b3_get_lostpassword_url();
                    if ( false != $lost_password_url ) {
                        wp_safe_redirect( $lost_password_url );
                        exit;
                    }
                }
            }


            /**
             * Redirects to the custom password reset page,
             * or the login page if there are errors.
             */
            public function b3_redirect_to_custom_reset_password() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    // Verify key / login combo
                    $redirect_url = b3_get_reset_password_url();

                    if ( isset( $_REQUEST[ 'key' ] ) && isset( $_REQUEST[ 'login' ] ) ) {
                        $key   = sanitize_key( $_REQUEST[ 'key' ] );
                        $login = sanitize_user( $_REQUEST[ 'login' ] );
                        $user  = check_password_reset_key( $key, $login );

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
                        $redirect_url = add_query_arg( 'login', esc_attr( $login ), $redirect_url );
                        $redirect_url = add_query_arg( 'key', esc_attr( $key ), $redirect_url );
                    }

                    wp_safe_redirect( $redirect_url );
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
                $current_user = wp_get_current_user();
                $user_role    = reset( $current_user->roles );
                if ( in_array( $user_role, get_site_option( 'b3_restrict_admin', [] ) ) ) {
                    $redirect_url = b3_get_account_url();
                    if ( false == $redirect_url ) {
                        $redirect_url = home_url();
                    }
                } else {
                    if ( $redirect_to ) {
                        $redirect_url = $redirect_to;
                    } else {
                        $redirect_url = admin_url();
                    }
                }
                wp_safe_redirect( $redirect_url );
                exit;
            }


            /**
             * Returns the URL to which the user should be redirected after a (successful) login.
             *
             * @since 1.0.6
             *
             * @param string           $redirect_to           The redirect destination URL.
             * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
             * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
             *
             * @return string Redirect URL
             */
            public function b3_redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

                $stored_roles  = ( is_array( get_site_option( 'b3_restrict_admin' ) ) ) ? get_site_option( 'b3_restrict_admin' ) : array( 'subscriber' );
                $redirect_url  = get_home_url();

                if ( ! $user ) {
                    return $redirect_url;
                } elseif ( is_wp_error( $user ) ) {
                    // check if is
                    if ( is_multisite() && ! is_main_site() ) {
                        // a suser has not been created since it needs to be confirmed
                        $no_user = true;
                    }
                }

                if ( $requested_redirect_to ) {
                    $redirect_to = $requested_redirect_to;
                } else {

                    if ( isset( $no_user ) && true == $no_user ) {
                        die('NO USER');
                    } elseif ( ! user_can( $user, 'manage_options' ) ) {
                        // Non-admin users always go to their account page after login, if it's defined
                        $account_page_url = b3_get_account_url();
                        if ( false != $account_page_url ) {
                            if ( ! in_array( $stored_roles, $user->roles ) ) {
                                $redirect_to = $account_page_url;
                            }
                        } elseif ( current_user_can( 'read' ) ) {
                            $redirect_to = get_edit_user_link( get_current_user_id() );
                        }
                    }
                }

                return $redirect_to;
            }


            /**
             * Redirect to custom login page after the user has been logged out.
             *
             * @since 1.0.6
             */
            public function b3_redirect_after_logout() {
                if ( is_multisite() ) {
                    if ( is_main_site()) {
                        $redirect_url = add_query_arg( 'logout', 'true', b3_get_login_url() );
                    } else {
                        $redirect_url = get_home_url();
                    }
                } else {
                    $redirect_url = add_query_arg( 'logout', 'true', b3_get_login_url() );
                }
                wp_safe_redirect( $redirect_url );
                exit;
            }


            /**
             * Initiates email activation
             *
             * @since 1.0.6
             * @since 2.6.0 wpmu user activation
             */
            public function b3_do_user_activate() {
                if ( is_multisite() ) {
                    if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && isset( $_GET[ 'activate' ] ) && 'user' == $_GET[ 'activate' ] ) {

                        $redirect_url      = b3_get_login_url();
                        $valid_error_codes = array( 'already_active', 'blog_taken' );
                        list( $activate_path ) = explode( '?', wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) );
                        $activate_cookie = 'wp-activate-' . COOKIEHASH;
                        $key             = '';
                        $result          = null;

                        if ( isset( $_GET[ 'key' ] ) && isset( $_POST[ 'key' ] ) && $_GET[ 'key' ] !== $_POST[ 'key' ] ) {
                            wp_die( __( 'A key value mismatch has been detected. Please follow the link provided in your activation email.' ), __( 'An error occurred during the activation' ), 400 );
                        } elseif ( ! empty( $_GET[ 'key' ] ) ) {
                            $key = sanitize_key( $_GET[ 'key' ] );
                        } elseif ( ! empty( $_POST[ 'key' ] ) ) {
                            $key = sanitize_key( $_POST[ 'key' ] );
                        }

                        if ( $key ) {
                            $redirect_url = remove_query_arg( 'key' );

                            if ( remove_query_arg( false ) !== $redirect_url ) {
                                setcookie( $activate_cookie, $key, 0, $activate_path, COOKIE_DOMAIN, is_ssl(), true );
                                wp_safe_redirect( $redirect_url );
                                exit;
                            } else {
                                $result = wpmu_activate_signup( $key );
                            }
                        }

                        if ( null === $result && isset( $_COOKIE[ $activate_cookie ] ) ) {
                            $key    = $_COOKIE[ $activate_cookie ];
                            $result = wpmu_activate_signup( $key );
                            setcookie( $activate_cookie, ' ', time() - YEAR_IN_SECONDS, $activate_path, COOKIE_DOMAIN, is_ssl(), true );
                        }

                        if ( null === $result || ( is_wp_error( $result ) && 'invalid_key' === $result->get_error_code() ) ) {
                            status_header( 404 );
                        } elseif ( is_wp_error( $result ) ) {
                            $error_code = $result->get_error_code();

                            if ( ! in_array( $error_code, $valid_error_codes, true ) ) {
                                status_header( 400 );
                            }
                        }

                        if ( ! is_wp_error( $result ) ) {
                            $redirect_url = add_query_arg( array( 'mu-activate' => 'success' ), $redirect_url );
                            wp_safe_redirect( $redirect_url );
                            exit;
                        }

                    }
                } else {

                    if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                        if ( ! empty( $_GET[ 'action' ] ) && 'activate' == $_GET[ 'action' ] && ! empty( $_GET[ 'key' ] ) && ! empty( $_GET[ 'user_login' ] ) ) {

                            global $wpdb;

                            $errors = false;
                            $key    = preg_replace( '/[^a-zA-Z0-9]/i', '', sanitize_key( $_GET[ 'key' ] ) );

                            if ( empty( $key ) || ! is_string( $key ) ) {
                                $errors = new WP_Error( 'invalid_key', __( 'Invalid key', 'b3-onboarding' ) );
                            }

                            if ( empty( $_GET[ 'user_login' ] ) || ! is_string( $_GET[ 'user_login' ] ) ) {
                                $errors = new WP_Error( 'invalid_key', __( 'Invalid key', 'b3-onboarding' ) );
                            }

                            // Validate activation key
                            $user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, sanitize_user( $_GET[ 'user_login' ] ) ) );

                            if ( empty( $user ) ) {
                                $errors = new WP_Error( 'invalid_user', __( 'Invalid user', 'b3-onboarding' ) );
                            }

                            if ( is_wp_error( $errors ) ) {
                                // errors found
                                $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), b3_get_login_url() );
                            } else {

                                // remove user_activation_key
                                $wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'user_login' => sanitize_user( $_GET[ 'user_login' ] ) ) );

                                // activate user, change user role
                                $user_object = new WP_User( $user->ID );
                                $user_object->set_role( get_option( 'default_role' ) );

                                if ( false == get_site_option( 'b3_activate_custom_passwords' ) ) {
                                    $redirect_url = b3_get_lostpassword_url();
                                } else {
                                    $redirect_url = b3_get_login_url();
                                }
                                $redirect_url = add_query_arg( array( 'activate' => 'success' ), $redirect_url );

                                do_action( 'b3_after_user_activated', $user->ID );
                            }

                            wp_safe_redirect( $redirect_url );
                            exit;
                        }
                    }
                }
            }


            /**
             * Initiates password reset.
             *
             * @since 1.0.6
             */
            public function b3_do_password_lost() {
                if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {

                    if ( isset( $_POST[ 'b3_form' ] ) && 'lostpass' == $_POST[ 'b3_form' ] ) {
                        $errors = b3_retrieve_password();

                        if ( is_wp_error( $errors ) ) {
                            // errors found
                            $redirect_url = b3_get_lostpassword_url();
                            $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), $redirect_url );
                        } else {
                            // Email sent
                            $redirect_url = b3_get_login_url();
                            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                        }

                        wp_safe_redirect( $redirect_url );
                        exit;
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

                    // Return messages
                    case 'empty_username':
                        return esc_html__( 'Please enter a user name', 'b3-onboarding' );

                    case 'empty_password':
                        return esc_html__( 'Please enter a password.', 'b3-onboarding' );

                    case 'incorrect_password':
                        $error_message = esc_html__( "The username or password you entered wasn't quite right.", 'b3-onboarding' );
                        $error_message .= '<br />';
                        $error_message .= __( 'Did you <a href="%s">forget</a> your password ?', 'b3-onboarding' );

                        return sprintf( $error_message, wp_lostpassword_url() );

                    case 'logged_out':
                        return esc_html__( "You are logged out.", 'b3-onboarding' );

                    // Registration errors
                    case 'username_exists':
                        return esc_html__( 'This username is already in use.', 'b3-onboarding' );

                    case 'reserved_username':
                        return esc_html__( 'That user name is reserved, please choose another.', 'b3-onboarding' );

                    case 'invalid_email':
                        return esc_html__( 'The email address you entered is not valid.', 'b3-onboarding' );

                    case 'invalid_username':
                        if ( 1 == get_site_option( 'b3_register_email_only' ) ) {
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
                            return sprintf( esc_html__( 'You didn\'t select an option for "%s".', 'b3-onboarding' ), $sprintf );
                        } else {
                            return esc_html__( 'You didn\'t select an option.', 'b3-onboarding' );
                        }

                    case 'access_requested':
                        $access_requested_string = esc_html__( 'You have sucessfully requested access. Someone will check your request.', 'b3-onboarding' );
                        return apply_filters( 'b3_registration_access_requested_message', $access_requested_string );

                    case 'confirm_email':
                        $confirm_email_string = esc_html__( 'You have sucessfully registered but need to confirm your email address first. Please check your email for an activation link.', 'b3-onboarding' );
                        return apply_filters( 'b3_registration_confirm_email_message', $confirm_email_string );

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
                        if ( false == get_site_option( 'b3_activate_custom_passwords' ) ) {
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
                        if ( false == get_site_option( 'b3_activate_custom_passwords' ) ) {
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

                    // Website
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
             * @param string $user_login
             * @param string $user_email
             *
             * @return int|WP_Error
             */
            private function b3_register_user( $user_email, $user_login, $registration_type, $role = 'subscriber' ) {
                $errors                       = new WP_Error();
                $privacy_error                = b3_verify_privacy();
                $registration_with_email_only = get_site_option( 'b3_register_email_only' );
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

                    if ( in_array( $user_login, b3_get_reserved_usernames() ) ) {
                        $errors->add( 'reserved_username', $this->b3_get_return_message( 'reserved_username' ) );

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

                if ( true == $privacy_error ) {
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

                $b3_register_type = get_site_option( 'b3_registration_type' );

                if ( is_main_site() ) {
                    if ( in_array( $b3_register_type, [ 'request_access', 'request_access_subdomain', 'user', 'all' ] )) {
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

                $template_paths = array(
                    get_stylesheet_directory() . '/b3-onboarding/',
                    get_stylesheet_directory() . '/plugins/b3-onboarding/',
                    get_template_directory() . '/b3-onboarding/',
                    get_template_directory() . '/plugins/b3-onboarding/',
                    B3_PLUGIN_PATH . '/templates/',
                );
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
             * For filter 'login_headerurl', replaces the url of the logo on the login page
             *
             * @since 1.0.6
             *
             * @param $login_header_url
             *
             * @return string
             */
            public function b3_login_logo_url( $login_header_url ) {
                return get_home_url();
            }

            /**
             * For filter 'login_headertitle', replaces the page-title on the login page
             *
             * @since 1.0.6
             *
             * @param $login_headertext
             *
             * @return string|void
             */
            public function b3_login_logo_url_title( $login_headertext ) {
                $title            = get_bloginfo( 'name', 'display' );
                $site_description = get_bloginfo( 'description', 'display' );
                if ( $site_description ) {
                    $title = sprintf( '%s | %s', $title, $site_description );
                }

                return $title;
            }

            /**
             * Add admin notices
             *
             * @since 1.0.6
             */
            public function b3_admin_notices() {

                // beta notice
                if ( strpos( $this->settings[ 'version' ], 'beta' ) !== false ) {
                    $message = __( "You're using a beta version, which is not finished yet and can give unexpected results.", 'b3-onboarding' );
                    if ( ! defined( 'LOCALHOST' ) || defined( 'LOCALHOST' ) && false == LOCALHOST ) {
                        echo '<div class="error"><p>' . $message . '.</p></div>';
                    } else {
                        echo '<div class="notice notice-warning"><p>' . $message . '.</p></div>';
                    }
                }

                // no page for front-end approval
                if ( false == get_site_option( 'b3_approval_page_id' ) && true == get_site_option( 'b3_front_end_approval' ) ) {
                    echo sprintf( '<div class="error"><p>'. __( 'You have not set a page for front-end user approval. Set it <a href="%s">%s</a>', 'b3-onboarding' ) . '.</p></div>',
                        esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=pages' ) ),
                        esc_html__( 'here', 'b3-onboarding' )
                    );
                }

                // manual actions
                if ( isset( $_GET[ 'update' ] ) && in_array( $_GET[ 'update' ], array( 'activate', 'sendactivation' ) ) ) {
                    echo '<div id="message" class="updated"><p>';
                    if ( 'activate' == $_GET[ 'update' ] ) {
                        _e( 'User activated.', 'b3-onboarding' );
                    } elseif ( 'sendactivation' == $_GET[ 'update' ] ) {
                        _e( 'Activation mail resent.', 'b3-onboarding' );
                    }
                    echo '</p></div>';
                }

                global $pagenow;
                if ( is_blog_admin() && $pagenow === "options-general.php" && ! isset ( $_GET['page'] ) && ! is_multisite() ) {
                    echo sprintf( '<div class="notice notice-info"><p>'. __( 'B3 OnBoarding takes control over the \'Membership\' option. You can change this <a href="%s">%s</a>', 'b3-onboarding' ) . '.</p></div>',
                        esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=registration' ) ),
                        esc_html__( 'here', 'b3-onboarding' )
                    );
                }

                if ( get_site_option( 'b3_activate_filter_validation' ) ) {
                    do_action( 'b3_verify_filter_input' );
                }
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
