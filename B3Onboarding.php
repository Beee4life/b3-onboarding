<?php
    /*
    Plugin Name:        B3 OnBoarding
    Plugin URI:         https://github.com/Beee4life/b3-onboarding
    Description:        This plugin styles the default WordPress pages into your own design. It gives you more control over the registration/login process (aka onboarding).
    Version:            2.0.3
    Requires at least:  4.3
    Author:             Beee
    Author URI:         https://berryplasman.com
    Tags:               user, management, registration, login, lost password, reset password, account
    Text-domain:        b3-onboarding
    License:            GPL v2 (or later)
    License URI:        https://www.gnu.org/licenses/gpl-2.0.html
    Domain Path:        /languages
       ___  ____ ____ ____
      / _ )/ __/  __/  __/
     / _  / _/   _/   _/
    /____/___/____/____/

    */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    if ( ! class_exists( 'B3Onboarding' ) ) {

        class B3Onboarding {

            protected $b3_get_template_html;
            protected $b3_get_return_message;

            /**
             * Initializes the plugin.
             *
             * To keep the initialization fast, only add filter and action
             * hooks in the constructor.
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
                    'version' => '2.0.3',
                );

                // actions
                register_activation_hook( __FILE__,            array( $this, 'b3_plugin_activation' ) );
                register_deactivation_hook( __FILE__,          array( $this, 'b3_plugin_deactivation' ) );

                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_enqueue_scripts_frontend' ), 40 );
                add_action( 'login_head',                           array( $this, 'b3_add_login_styling' ) );
                add_action( 'admin_enqueue_scripts',                array( $this, 'b3_enqueue_scripts_backend' ) );
                add_action( 'admin_menu',                           array( $this, 'b3_add_admin_pages' ) );
                add_action( 'admin_head',                           array( $this, 'b3_add_js_head' ) );
                add_action( 'widgets_init',                         array( $this, 'b3_register_widgets' ) );
                add_action( 'wp_dashboard_setup',                   array( $this, 'b3_add_dashboard_widget' ) );
                add_action( 'login_redirect',                       array( $this, 'b3_redirect_after_login' ), 10, 3 );
                add_action( 'wp_logout',                            array( $this, 'b3_redirect_after_logout' ) );
                add_action( 'init',                                 array( $this, 'b3_load_plugin_text_domain' ) );
                add_action( 'init',                                 array( $this, 'b3_redirect_to_custom_profile' ) );
                add_action( 'template_redirect',                    array( $this, 'b3_template_redirect' ) );
                add_action( 'login_form_register',                  array( $this, 'b3_redirect_to_custom_register' ) );
                add_action( 'login_form_register',                  array( $this, 'b3_registration_form_handling' ) );
                add_action( 'login_form_login',                     array( $this, 'b3_redirect_to_custom_login' ) );
                add_action( 'login_form_lostpassword',              array( $this, 'b3_redirect_to_custom_lostpassword' ) );
                add_action( 'login_form_resetpass',                 array( $this, 'b3_redirect_to_custom_reset_password' ) );
                add_action( 'login_form_rp',                        array( $this, 'b3_redirect_to_custom_reset_password' ) );
                add_action( 'init',                                 array( $this, 'b3_do_user_activate' ) );
                add_action( 'login_form_lostpassword',              array( $this, 'b3_do_password_lost' ) );
                add_action( 'login_form_resetpass',                 array( $this, 'b3_reset_user_password' ) );
                add_action( 'login_form_rp',                        array( $this, 'b3_reset_user_password' ) );
                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_add_captcha_js_to_footer' ) );
                add_action( 'login_enqueue_scripts',                array( $this, 'b3_add_captcha_js_to_footer' ) );
                add_action( 'admin_init',                           array( $this, 'b3_check_options_post' ) );
                add_action( 'admin_notices',                        array( $this, 'b3_admin_notices' ) );

                // Multisite specific
                // add_action( 'wp_insert_site',                       array( $this, 'b3_new_blog' ) );
                // add_action( 'init',                                 array( $this, 'b3_redirect_to_custom_wpmu_register' ) ); // ???
                add_action( 'network_admin_notices',                array( $this, 'b3_not_multisite_ready' ) );

                // Filters
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this, 'b3_settings_link' ) );
                add_filter( 'admin_body_class',                     array( $this, 'b3_admin_body_class' ) );
                add_filter( 'authenticate',                         array( $this, 'b3_maybe_redirect_at_authenticate' ), 101, 3 );
                add_filter( 'wp_mail_from',                         array( $this, 'b3_email_from' ) );
                add_filter( 'wp_mail_from_name',                    array( $this, 'b3_email_from_name' ) );
                add_filter( 'wp_mail_content_type',                 array( $this, 'b3_email_content_type' ) );

                // WP Login pages
                add_filter( 'login_headerurl',                      array( $this, 'b3_login_logo_url' ) );
                add_filter( 'login_headertext',                     array( $this, 'b3_login_logo_url_title' ) );

                /*
                 * This file contains all actions on plugin hooks
                 */
                include( 'includes/actions-b3.php' );
                /*
                 * This file contains important actions on WordPress hooks
                 */
                include( 'includes/actions-wp.php' );
                /*
                 * This file contains functions which return default values
                 */
                include( 'includes/defaults.php' );
                /*
                 * This file contains functions which 'do' something with a value
                 */
                include( 'includes/do-stuff.php' );
                /*
                 * Renders admin input fields
                 */
                include( 'includes/emails.php' );
                /*
                 * This file contains functions/includes for various example filters.
                 * They're only loaded for testing purposes, when LOCALHOST is defined as true
                 */
                if ( defined( 'LOCALHOST' ) && true == LOCALHOST ) {
                    include( 'includes/examples.php' );
                }
                /*
                 * This file contains simple functions which are called throughout the plugin
                 */
                include( 'includes/functions.php' );
                /*
                 * This file contains all filters on plugin hooks
                 */
                include( 'includes/filters-b3.php' );
                /*
                 * This file contains all 'WordPress' hooks
                 */
                include( 'includes/filters-wp.php' );
                /*
                 * Processes most forms
                 */
                include( 'includes/form-handling.php' );
                /*
                 * This file contains all content for the help tabs/contextual help
                 */
                include( 'includes/help-tabs.php' );
                /*
                 * Functions + renders for admin pages/tabs
                 */
                include( 'includes/tabs/tabs.php' );
                if ( get_option( 'b3_activate_filter_validation', false ) ){
                    /*
                     * Functions to verify filtered output
                     */
                    include( 'includes/verify-filters.php' );
                }
                /*
                 * Functions + renders for shortcodes/front-end forms
                 */
                include( 'includes/class-b3-shortcodes.php' );
            }


            /*
             * Do stuff upon plugin activation
             *
             * @since 2.0.0
             */
            public function b3_plugin_activation() {

                // create necessary pages
                b3_setup_initial_pages();

                $this->b3_set_default_settings();

                /**
                 * Independent
                 */
                $aw_activation = get_role( 'b3_activation' );
                if ( ! $aw_activation ) {
                    add_role( 'b3_activation', __( 'Awaiting activation' ), array() );
                }
                $aw_approval = get_role( 'b3_approval' );
                if ( ! $aw_approval ) {
                    add_role( 'b3_approval', __( 'Awaiting approval' ), array() );
                }

            }


            /**
             * Do stuff upon plugin deactivation
             */
            public function b3_plugin_deactivation() {
                // set registration option accordingly
                $registration_type = get_option( 'b3_registration_type', false );
                if ( 'closed' != $registration_type ) {
                    update_option( 'users_can_register', '1' );
                } else {
                    update_option( 'users_can_register', '0' );
                }
            }


            /**
             * Set default settings
             *
             * @since 2.0.0
             */
            public function b3_set_default_settings() {

                if ( ! is_multisite() ) {
                    update_option( 'users_can_register', 0 );
                    update_option( 'b3_registration_type', 'open' );
                } else {

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

                update_option( 'b3_account_activated_message', b3_get_account_activated_message_user() );
                update_option( 'b3_account_approved_message', b3_get_account_approved_message() );
                update_option( 'b3_account_rejected_message', b3_get_account_rejected_message() );
                update_option( 'b3_activate_custom_emails', 1 );
                update_option( 'b3_dashboard_widget', 1 );
                update_option( 'b3_disable_wordpress_forms', 1 );
                update_option( 'b3_email_activation_message', b3_get_email_activation_message_user() );
                update_option( 'b3_email_styling', b3_default_email_styling( apply_filters( 'b3_link_color', b3_get_link_color() ) ) );
                update_option( 'b3_email_template', b3_default_email_template() );
                update_option( 'b3_hide_admin_bar', 1 );
                update_option( 'b3_logo_in_email', 1 );
                update_option( 'b3_lost_password_message', b3_get_lost_password_message() );
                update_option( 'b3_new_user_message', b3_get_new_user_message() );
                update_option( 'b3_notification_sender_email', get_bloginfo( 'admin_email' ) );
                update_option( 'b3_notification_sender_name', get_bloginfo( 'name' ) );
                update_option( 'b3_request_access_message_admin', b3_get_request_access_message_admin() );
                update_option( 'b3_request_access_message_user', b3_get_request_access_message_user() );
                update_option( 'b3_restrict_admin', array( 'subscriber', 'b3_activation', 'b3_approval' ) );
                update_option( 'b3_welcome_user_message', b3_get_welcome_user_message() );
                update_option( 'b3_version', $this->settings[ 'version' ] );

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
                if ( is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
                    switch_to_blog( $new_site->blog_id );
                    b3_setup_initial_pages( true );
                    restore_current_blog();
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


            /**
             * Add login styling
             *
             * @since 2.0.0
             */
            public function b3_add_login_styling() {
                $extra_fields    = apply_filters( 'b3_extra_fields', array() );
                $logo            = apply_filters( 'b3_main_logo', b3_get_main_logo() );
                $logo_height     = get_option( 'b3_loginpage_logo_height', false );
                $logo_width      = get_option( 'b3_loginpage_logo_width', false );
                $privacy         = get_option( 'b3_privacy', false );
                $recaptcha       = get_option( 'b3_activate_recaptcha', false );
                $recaptcha_login = get_option( 'b3_recaptcha_login', false );
                $style_pages     = get_option( 'b3_style_wordpress_forms', false );

                echo '<style type="text/css">';
                echo "\n";
                if ( $style_pages ) {
                    $bg_color    = get_option( 'b3_loginpage_bg_color', false );
                    $font_family = get_option( 'b3_loginpage_font_family', false );
                    $font_size   = get_option( 'b3_loginpage_font_size', false );

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
                include( 'includes/admin-page.php' ); // content for the settings page
                add_menu_page( 'B3 OnBoarding', 'B3 OnBoarding', 'manage_options', 'b3-onboarding', 'b3_user_register_settings', B3_PLUGIN_URL .  'assets/images/logo-b3onboarding-small.png', '81' );
                include( 'includes/user-approval-page.php' ); // content for the settings page
                add_submenu_page( 'b3-onboarding', 'B3 OnBoarding ' . __( 'User Approval', 'b3-onboarding' ), __( 'User Approval', 'b3-onboarding' ), 'promote_users', 'b3-user-approval', 'b3_user_approval' );
                if ( ( defined( 'LOCALHOST' ) && true == LOCALHOST ) || true == get_option( 'b3_debug_info', false ) ) {
                    include( 'includes/debug-page.php' ); // content for the settings page
                    add_submenu_page( 'b3-onboarding', 'B3 OnBoarding ' . __( 'Debug info', 'b3-onboarding' ), __( 'Debug info', 'b3-onboarding' ), 'manage_options', 'b3-debug', 'b3_debug_page' );
                }
            }


            /*
             * Inline js to disable registration option
             */
            public function b3_add_js_head() {
                if ( is_multisite() ) {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery('.form-table input[name="registration"]').prop('disabled', true);
                        });
                    </script>
                    <?php
                } else {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery('.form-table input[name="users_can_register"]').prop('disabled', true);
                        });
                    </script>
                    <?php
                }
            }


            /*
             * Register widgets (if activated)
             */
            public function b3_register_widgets() {
                /*
                 * Includes sidebar widget function + call
                 */
                include( 'includes/class-b3-sidebar-widget.php' );
            }


            /*
             * Add dashboard widget
             */
            public function b3_add_dashboard_widget() {
                /*
                 * Includes dashboard widget function + call
                 */
                include( 'includes/dashboard-widget.php' );
                if ( defined( 'LOCALHOST' ) && true == LOCALHOST ) {
                    include( 'includes/dashboard-widget-debug.php' );
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
             * Add to admin body class
             *
             * @param $classes
             *
             * @return string
             */
            public function b3_admin_body_class( $classes ) {

                if ( 'request_access' != get_option( 'b3_registration_type', false ) ) {
                    $classes .= ' no-approval-page';
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
                wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array(), false, true );
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

                $recaptcha_secret = get_option( 'b3_recaptcha_secret', false );
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
                            B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                            return;
                        } else {

                            $user_login                = ( isset( $_POST[ 'user_login' ] ) ) ? $_POST[ 'user_login' ] : false;
                            $user_email                = ( isset( $_POST[ 'user_email' ] ) ) ? $_POST[ 'user_email' ] : false;
                            $role                      = get_option( 'default_role' );
                            $registration_type         = get_option( 'b3_registration_type', false );
                            $meta_data[ 'first_name' ] = ( isset( $_POST[ 'first_name' ] ) ) ? sanitize_text_field( $_POST[ 'first_name' ] ) : false;
                            $meta_data[ 'last_name' ]  = ( isset( $_POST[ 'last_name' ] ) ) ? sanitize_text_field( $_POST[ 'last_name' ] ) : false;

                            if ( ! is_multisite() ) {
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );

                                } elseif ( false != get_option( 'b3_activate_recaptcha', false ) && ! $this->b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );

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
                                        $reset_password = true;
                                    }

                                    $result = $this->b3_register_user( $user_email, $user_login, $registration_type, $role );

                                    if ( is_wp_error( $result ) ) {
                                        // Parse errors into a string and append as parameter to redirect
                                        $errors       = join( ',', $result->get_error_codes() );
                                        $redirect_url = add_query_arg( 'registration-error', $errors, $redirect_url );
                                    } else {
                                        // Success
                                        if ( isset( $reset_password ) ) {
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
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                } elseif ( false != get_option( 'b3_activate_recaptcha', false ) && ! $this->b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'recaptcha_failed', $redirect_url );
                                } elseif ( in_array( $registration_type, array( 'request_access', 'email_activation', 'ms_register_site_user' ) ) ) {
                                    $register = true;
                                } else {
                                }

                                if ( true == $register ) {
                                    // is_multisite
                                    $meta_data[ 'blog_public' ] = '1';
                                    $meta_data[ 'lang_id' ]     = '0'; // ????
                                    $sub_domain                 = ( isset( $_POST[ 'b3_subdomain' ] ) ) ? $_POST[ 'b3_subdomain' ] : false;

                                    if ( false != $sub_domain ) {
                                        // @TODO: check this for options (MS)
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
                                                $redirect_url = wp_login_url();
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

                        wp_safe_redirect( $redirect_url );
                        exit;

                    }
                }
            }


            ## REDIRECTS

            /*
             * Protect some pages
             */
            public function b3_template_redirect() {
                $account_page_id  = b3_get_account_url( true );
                $account_url      = b3_get_account_url();
                $approval_page_id = b3_get_user_approval_link( true );
                $login_page_id    = b3_get_login_url( true );
                $login_url        = ( false != $login_page_id ) ? get_the_permalink( $login_page_id ) : wp_login_url();
                $logout_page_id   = b3_get_logout_url( true );

                if ( false != $account_page_id && is_page( array( $account_page_id ) ) && ! is_user_logged_in() ) {

                    wp_safe_redirect( $login_url );
                    exit;

                } elseif ( false != $approval_page_id && is_page( $approval_page_id ) ) {

                    if ( is_user_logged_in() ) {
                        if ( ! current_user_can( 'promote_users' ) ) {
                            wp_safe_redirect( $account_url );
                            exit;
                        }
                    } else {
                        wp_safe_redirect( $login_url );
                        exit;
                    }

                } elseif ( false != $logout_page_id && is_page( array( $logout_page_id ) ) ) {

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
                global $current_user, $pagenow;
                if ( is_user_logged_in() && is_admin() ) {
                    $account_url = b3_get_account_url();
                    if ( false != $account_url ) {
                        $redirect_to = $account_url;
                    } else {
                        $redirect_to = get_home_url();
                    }

                    $user_role = reset( $current_user->roles );
                    if ( is_multisite() && empty( $user_role ) ) {
                        // or get default role
                        $user_role = 'subscriber';
                    }

                    if ( 'profile.php' == $pagenow && ! isset( $_REQUEST[ 'page' ] ) ) {
                        if ( isset( $_GET[ 'redirect_to' ] ) && ! empty( $_GET[ 'redirect_to' ] ) ) {
                            $redirect_to = add_query_arg( 'redirect_to', $_GET[ 'redirect_to' ], $redirect_to );
                        }
                        wp_safe_redirect( $redirect_to );
                        exit;
                    } else {
                        if ( in_array( $user_role, get_option( 'b3_restrict_admin', [] ) ) ) {
                            if ( ! defined( 'DOING_AJAX' ) ) {
                                wp_safe_redirect( $redirect_to );
                                exit;
                            }
                        }
                    }
                }
            }


            /**
             * Redirects the user to the custom registration page instead
             * of wp-login.php?action=register.
             */
            public function b3_redirect_to_custom_register() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_option( 'b3_disable_wordpress_forms', false ) ) {
                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user();
                    } else {
                        $page_url = b3_get_register_url();
                        if ( false != $page_url ) {
                            wp_safe_redirect( $page_url );
                        } else {
                            wp_safe_redirect( wp_registration_url() );
                        }
                        exit;
                    }
                }
            }


            /**
             * Redirects the user to the custom MU registration page instead
             * of wp-login.php?action=register.
             */
            public function b3_redirect_to_custom_wpmu_register() {
                if ( '/wp-signup.php' == $_SERVER[ 'REQUEST_URI' ] && 1 == get_option( 'b3_disable_wordpress_forms', false ) ) {
                    $register_url = b3_get_register_url();
                    if ( false != $register_url ) {
                        wp_safe_redirect( $register_url );
                        exit;
                    }
                }
            }


            /**
             * Force user to custom login page instead of wp-login.php.
             */
            public function b3_redirect_to_custom_login() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_option( 'b3_disable_wordpress_forms', false ) ) {

                    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : null;

                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user( $redirect_to );
                        exit;
                    }

                    // The rest is redirected to the login page
                    $custom_login_url = b3_get_login_url();
                    $login_url        = ( false != $custom_login_url ) ? $custom_login_url : wp_login_url();

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
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_option( 'b3_disable_wordpress_forms', false ) ) {
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
             * Redirects to the custom password reset page, or the login page
             * if there are errors.
             */
            public function b3_redirect_to_custom_reset_password() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    // Verify key / login combo
                    $redirect_url = b3_get_reset_password_url();

                    if ( isset( $_REQUEST[ 'key' ] ) && isset( $_REQUEST[ 'login' ] ) ) {
                        $user = check_password_reset_key( $_REQUEST[ 'key' ], $_REQUEST[ 'login' ] );
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
                        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST[ 'login' ] ), $redirect_url );
                        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST[ 'key' ] ), $redirect_url );
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
                $user = wp_get_current_user();
                if ( user_can( $user, 'manage_options' ) ) {
                    if ( $redirect_to ) {
                        wp_safe_redirect( $redirect_to );
                    } else {
                        wp_safe_redirect( admin_url() );
                    }
                } else {
                    wp_safe_redirect( home_url() );
                }
                exit;
            }


            /**
             * Returns the URL to which the user should be redirected after the (successful) login.
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

                $redirect_url = get_home_url();
                $stored_roles = ( is_array( get_option( 'b3_restrict_admin', false ) ) ) ? get_option( 'b3_restrict_admin' ) : array( 'subscriber' );

                if ( ! isset( $user->ID ) ) {
                    return $redirect_url;
                }

                if ( user_can( $user, 'manage_options' ) ) {
                    // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
                    $redirect_url = admin_url();
                    if ( $requested_redirect_to != '' ) {
                        $redirect_url = add_query_arg( 'redirect_to', $requested_redirect_to, $redirect_url );
                    }
                } else {

                    // Non-admin users always go to their account page after login, if defined
                    $account_page_url = b3_get_account_url();
                    if ( false != $account_page_url ) {
                        if ( ! in_array( $stored_roles, $user->roles ) ) {
                            $redirect_url = $account_page_url;
                        } else {
                            // non-admin logged in
                            // $redirect_url set at start
                        }
                    } elseif ( $requested_redirect_to ) {
                        $redirect_url = add_query_arg( 'redirect_to', $requested_redirect_to, $redirect_url );
                    } elseif ( current_user_can( 'read' ) ) {
                        $redirect_url = get_edit_user_link( get_current_user_id() );
                    }

                }

                return $redirect_url;
            }


            /**
             * Redirect to custom login page after the user has been logged out.
             *
             * @since 1.0.6
             */
            public function b3_redirect_after_logout() {
                $login_url = b3_get_login_url();
                if ( false != $login_url ) {
                    $redirect_url = $login_url;
                } else {
                    $redirect_url = wp_login_url();
                }
                $redirect_url = add_query_arg( 'logout', 'true', $redirect_url );
                wp_safe_redirect( $redirect_url );
                exit;
            }


            /**
             * Initiates email activation ('normal site')
             *
             * @since 1.0.6
             */
            public function b3_do_user_activate() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                    if ( ! empty( $_GET[ 'action' ] ) && 'activate' == $_GET[ 'action' ] && ! empty( $_GET[ 'key' ] ) && ! empty( $_GET[ 'user_login' ] ) ) {

                        global $wpdb;

                        $errors = false;
                        $key    = preg_replace( '/[^a-zA-Z0-9]/i', '', $_GET[ 'key' ] );

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
                            // errors found
                            $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), b3_get_login_url() );
                        } else {

                            $lostpassword_url = b3_get_lostpassword_url();
                            $redirect_url     = add_query_arg( array( 'activate' => 'success' ), $lostpassword_url );

                            // remove user_activation_key
                            $wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'user_login' => $_GET[ 'user_login' ] ) );

                            // activate user, change user role
                            $user_object = new WP_User( $user->ID );
                            $user_object->set_role( get_option( 'default_role' ) );

                            do_action( 'b3_after_user_activated', $user->ID );

                        }

                        wp_safe_redirect( $redirect_url );
                        exit;
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
                    $errors = retrieve_password();

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
                            if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] ) {
                                // Passwords don't match
                                $redirect_url = b3_get_reset_password_url();
                                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                                wp_safe_redirect( $redirect_url );
                                exit;
                            }

                            if ( empty( $_POST[ 'pass1' ] ) ) {
                                // Password is empty
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
                            echo "Invalid request1.";
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
                        return esc_html__( 'You have sucessfully requested access. Someone will check your request.', 'b3-onboarding' );

                    case 'confirm_email':
                        return esc_html__( 'You have sucessfully registered but need to confirm your email first. Please check your email for an activation link.', 'b3-onboarding' );

                    // Lost password
                    case 'invalidcombo':
                        return esc_html__( 'There are no users registered with this email address.', 'b3-onboarding' );

                    case 'wait_approval':
                        return esc_html__( 'You have to get approved first.', 'b3-onboarding' );

                    case 'wait_confirmation':
                        return esc_html__( 'You have to confirm your email first.', 'b3-onboarding' );

                    case 'password_updated':
                        return esc_html__( 'Your password has been changed. You can login now.', 'b3-onboarding' );

                    case 'lost_password_sent':
                        return esc_html__( 'Check your email for a link to reset your password.', 'b3-onboarding' );

                    // Registration
                    case 'registration_success':
                        return esc_html__( 'You have successfully registered.', 'b3-onboarding' );

                    case 'registration_success_enter_password':
                        return sprintf(
                            esc_html__( 'You have successfully registered to %s. Enter your email address to set your password.', 'b3-onboarding' ),
                            get_bloginfo( 'name' )
                        );

                    // Activation
                    case 'activate_success':
                        return esc_html__( 'You have successfully activated your account. You can initiate a password (re)set below.', 'b3-onboarding' );

                    case 'invalid_key':
                        return esc_html__( 'The activation link you used is not valid.', 'b3-onboarding' );

                    // Reset password
                    case 'expiredkey':  // same error as next
                    case 'invalidkey':
                        return esc_html__( 'The password reset link you used is not valid anymore.', 'b3-onboarding' );

                    case 'password_reset_mismatch':
                        return esc_html__( "The two passwords you entered don't match.", 'b3-onboarding' );

                    case 'password_reset_empty':
                        return esc_html__( "Sorry, we don't accept empty passwords.", 'b3-onboarding' );

                    // Multisite
                    case 'domain_exists':
                        return esc_html__( 'Sorry, this subdomain has already been taken.', 'b3-onboarding' );

                    case 'user_registered':
                        return esc_html__( 'You have successfully registered. Please check your email for an activation link.', 'b3-onboarding' );

                    // Account remove
                    case 'account_remove':
                        return esc_html__( 'Your account has been deleted.', 'b3-onboarding' );

                    // Admin
                    case 'settings_saved': // same message
                    case 'pages_saved': // same message
                    case 'emails_saved':
                        return esc_html__( 'Settings saved', 'b3-onboarding' );

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
                $errors        = new WP_Error();
                $privacy_error = b3_verify_privacy();
                $user_data     = array(
                    'user_login' => $user_login,
                    'user_email' => $user_email,
                    'user_pass'  => '', // for possible/future custom passwords
                    'role'       => $role,
                );

                if ( username_exists( $user_login ) ) {
                    $errors->add( 'username_exists', $this->b3_get_return_message( 'username_exists' ) );

                    return $errors;
                }

                if ( in_array( $user_login, apply_filters( 'b3_reserved_usernames', b3_get_reserved_usernames() ) ) ) {
                    $errors->add( 'reserved_username', $this->b3_get_return_message( 'reserved_username' ) );

                    return $errors;
                }

                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'email', $this->b3_get_return_message( 'invalid_email' ) );

                    return $errors;
                }

                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->b3_get_return_message( 'email_exists' ) );

                    return $errors;
                }

                if ( ! is_email( $user_email ) ) {
                    $errors->add( 'email', $this->b3_get_return_message( 'invalid_email' ) );

                    return $errors;
                }

                if ( username_exists( $user_email ) || email_exists( $user_email ) ) {
                    $errors->add( 'email_exists', $this->b3_get_return_message( 'email_exists' ) );

                    return $errors;
                }

                if ( true == $privacy_error ) {
                    $errors->add( 'no_privacy', $this->b3_get_return_message( 'no_privacy' ) );

                    return $errors;
                }

                $extra_field_errors = apply_filters( 'b3_extra_fields_validation', [] );
                if ( ! empty( $extra_field_errors ) ) {
                    $errors->add( $extra_field_errors[ 'error_code' ], $extra_field_errors[ 'error_message' ] );
                    $errors->add( 'field_' . $extra_field_errors[ 'id' ], '' );

                    return $errors;
                }

                $user_id = wp_insert_user( $user_data );
                if ( ! is_wp_error( $user_id ) ) {
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
             * @param       $sub_domain
             * @param array $meta
             *
             * @return bool|WP_Error
             */
            private function b3_register_wpmu_user( $user_name, $user_email, $sub_domain, $meta = array() ) {

                $errors                = false;
                $main_register_type    = get_site_option( 'registration' );
                $subsite_register_type = get_option( 'b3_registration_type', array() );
                $user_registered       = false;

                if ( is_main_site() ) {

                    if ( in_array( $main_register_type, [ 'all', 'blog' ] ) && in_array( $subsite_register_type, [ 'request_access', 'request_access_subdomain', 'ms_loggedin_register', 'ms_register_user', 'ms_register_site_user' ] )) {
                        if ( false == $sub_domain ) {
                            // @TODO: throw error if no subdomain is chosen (MS)
                            wpmu_signup_user( $user_name, $user_email, $meta );
                            $user_registered = true;
                        } else {
                            wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                            $site_id         = get_id_from_blogname( $sub_domain );
                            $user_registered = true;
                            do_action( 'b3_after_insert_site', $site_id );
                        }
                    } elseif ( 'none' == $main_register_type ) {
                        // @TODO: add if for user or user + site (MS)
                        // @TODO: add if for if user needs to activate
                        // @TODO: add if for if admin needs to activate
                        // wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                    } else {
                        $errors = new WP_Error( 'unknown', $this->b3_get_return_message( 'unknown' ) );
                    }

                } else {

                    if ( 'user' == $main_register_type && 'closed' != $subsite_register_type ) {
                        wpmu_signup_user( $user_name, $user_email, $meta );
                        $user_registered = true;
                    } else {
                        $errors = new WP_Error( 'unknown', $this->b3_get_return_message( 'unknown' ) );
                    }

                }

                if ( true == $user_registered ) {
                    // $errors = new WP_Error( 'user_registered', $this->b3_get_return_message( 'user_registered' ) );
                }

                return $errors;
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

                include( $location . $template_name . '.php' );

                do_action( 'b3_do_after_' . $template_name );

                $html = ob_get_contents();
                ob_end_clean();

                return $html;
            }


            /**
             * Check post values of saved options
             *
             * @TODO: add nonce check
             *
             * @since 2.0.0
             */
            public function b3_check_options_post() {
                if ( isset( $_POST[ 'option_page' ] ) ) {
                    if ( ! isset( $_POST[ 'users_can_register' ] ) ) {
                        if ( 'closed' != get_option( 'b3_registration_type', false ) ) {
                            $_POST[ 'users_can_register' ] = 1;
                        }
                    } else {
                        if ( 'closed' == get_option( 'b3_registration_type', false ) ) {
                            $_POST[ 'users_can_register' ] = 0;
                        }
                    }
                }
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
             * Add network admin message (MS) if plugin is activated
             *
             * @since 2.0.0
             */
            public function b3_not_multisite_ready() {
                if ( isset( get_site_option( 'active_sitewide_plugins' )[ 'b3-onboarding/B3Onboarding.php' ] ) ) {
                    echo sprintf( '<div class="error"><p>'. __( 'This plugin is not meant (yet) for network activation. Please deactivate it <a href="%s">%s</a> and activate on a per-site bases', 'b3-onboarding' ) . '.</p></div>',
                        esc_url( network_admin_url( 'plugins.php?plugin_status=active' ) ),
                        esc_html__( 'here', 'b3-onboarding' )
                    );
                }
            }

            /**
             * Add admin notices
             *
             * @since 1.0.6
             */
            public function b3_admin_notices() {

                if ( false == get_option( 'b3_approval_page_id', false ) && true == get_option( 'b3_front_end_approval', false ) ) {
                    echo sprintf( '<div class="error"><p>'. __( 'You have not set a page for front-end user approval. Set it <a href="%s">%s</a>', 'b3-onboarding' ) . '.</p></div>',
                        esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=pages' ) ),
                        esc_html__( 'here', 'b3-onboarding' )
                    );
                }
                do_action( 'b3_verify_filter_input' );
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

        // Initialize the plugin
        init_b3_onboarding();

    } // class_exists check
