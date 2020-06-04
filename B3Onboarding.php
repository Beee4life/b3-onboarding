<?php
    /*
    Plugin Name:    B3 - Onboarding
    Plugin URI:     https://github.com/Beee4life/b3-onboarding
    Description:    This plugin gives you more control over the registration/loginprocess (aka onboarding).
    Version:        1.1.0
    Author:         Beee
    Author URI:     https://berryplasman.com
    Tags:           user, management, registration, login, forgot password, reset password, account
    Text-domain:    b3-onboarding
    License:        GNU v3
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
            protected $b3_get_error_message;

            /**
             * Initializes the plugin.
             *
             * To keep the initialization fast, only add filter and action
             * hooks in the constructor.
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
                    $settings_url = admin_url( 'admin.php?page=b3-onboarding' );
                    define( 'B3_PLUGIN_SETTINGS', $settings_url );
                }

                if ( ! defined( 'B3_PLUGIN_SITE' ) ) {
                    $plugin_site = 'https://b3onboarding.berryplasman.com';
                    define( 'B3_PLUGIN_SITE', $plugin_site );
                }
            }

            function initialize() {
                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '1.1.0',
                );

                // set text domain
                load_plugin_textdomain( 'b3-onboarding', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

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
                add_action( 'init',                                 array( $this, 'b3_init' ) );
                add_action( 'template_redirect',                    array( $this, 'b3_template_redirect' ) );
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
                add_action( 'wp_enqueue_scripts',                   array( $this, 'b3_add_captcha_js_to_footer' ) );
                add_action( 'login_enqueue_scripts',                array( $this, 'b3_add_captcha_js_to_footer' ) );
                add_action( 'admin_init',                           array( $this, 'b3_check_options_post' ) );
                add_action( 'admin_notices',                        array( $this, 'b3_no_approval_page' ) );


                // Multisite specific
                add_action( 'wp_insert_site',                       array( $this, 'b3_new_blog' ) );
                add_action( 'init',                                 array( $this, 'b3_redirect_to_custom_wpmu_register' ) ); // ???
                add_action( 'network_admin_notices',                array( $this, 'b3_not_multisite_ready' ) );


                // Filters
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this, 'b3_settings_link' ) );
                add_filter( 'authenticate',                         array( $this, 'b3_maybe_redirect_at_authenticate' ), 101, 3 );
                add_filter( 'wp_mail_from',                         array( $this, 'b3_email_from' ) );
                add_filter( 'wp_mail_from_name',                    array( $this, 'b3_email_from_name' ) );
                add_filter( 'wp_mail_content_type',                 array( $this, 'b3_email_content_type' ) );


                // WP Login pages
                add_filter( 'login_headerurl',                      array( $this, 'b3_login_logo_url' ) );
                add_filter( 'login_headertext',                     array( $this, 'b3_login_logo_url_title' ) );
                // add_filter( 'login_form_defaults',                  array( $this, 'b3_loginform_defaults' ), 1 );
                // add_filter( 'login_form_top',                       array( $this, 'b3_loginform_top' ), 10, 2 );
                // add_filter( 'login_form_middle',                    array( $this, 'b3_loginform_middle' ), 10, 2 );
                // add_filter( 'login_form_bottom',                    array( $this, 'b3_loginform_footer' ), 10, 2 );

                // @TODO: move to own 'class/file'
                // add_shortcode( 'register-form',                array( $this, 'b3_render_register_form' ) );
                // add_shortcode( 'login-form',                   array( $this, 'b3_render_login_form' ) );
                // add_shortcode( 'forgotpass-form',              array( $this, 'b3_render_forgot_password_form' ) );
                // add_shortcode( 'resetpass-form',               array( $this, 'b3_render_reset_password_form' ) );
                // add_shortcode( 'account-page',                 array( $this, 'b3_render_account_page' ) );
                // add_shortcode( 'user-management',              array( $this, 'b3_render_user_approval_page' ) );

                include( 'includes/actions-wp.php' );
                include( 'includes/actions-b3.php' );
                include( 'includes/do-stuff.php' );
                include( 'includes/emails.php' );
                include( 'includes/filters-wp.php' );
                include( 'includes/filters-b3.php' );
                include( 'includes/form-handling.php' );
                include( 'includes/functions.php' );
                include( 'includes/get-stuff.php' );
                include( 'includes/help-tabs.php' );
                include( 'includes/tabs.php' );

                include( 'includes/B3Shortcodes.php' );

                // add_action( 'init', array( $this, 'b3_test' ) );
            }


            public function b3_test() {

                $style = b3_default_email_styling( $link_color = 'FFA' );
                // echo '<pre>'; var_dump($style); echo '</pre>'; exit;

            }


            /**
             * Do stuff upon plugin activation
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
                    update_option( 'users_can_register', 0 );
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

                update_option( 'b3_dashboard_widget', 1 );
                update_option( 'b3_email_styling', b3_default_email_styling() );
                update_option( 'b3_email_template', b3_default_email_template() );
                update_option( 'b3_logo_in_email', 1 );
                update_option( 'b3_notification_sender_email', get_bloginfo( 'admin_email' ) );
                update_option( 'b3_notification_sender_name', get_bloginfo( 'name' ) );
                update_option( 'b3_restrict_admin', [ 'subscriber', 'b3_activation', 'b3_approval' ] );
                update_option( 'b3_sidebar_widget', 1 );

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
             * Do stuff upon plugin activation
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
             * Redirects "profile.php" to custom account page
             *
             * @TODO: maybe change function name
             */
            public function b3_init() {
                global $current_user, $pagenow;
                if ( is_user_logged_in() && is_admin() ) {

                    $allow_admin  = [ 'administrator' ];
                    $themed_users = [ 'subscriber' ];
                    $redirect_to  = '';
                    if ( in_array( $themed_users, $current_user->roles ) ) {
                        $page_link = b3_get_account_id( true );
                        if ( false != $page_link ) {
                            $redirect_to = $page_link;
                        } else {
                            $redirect_to = home_url();
                        }
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
                            wp_safe_redirect( $redirect_to );
                            exit;
                        }
                    } else {
                        if ( ! in_array( $user_role, $allow_admin ) ) {
                            if ( ! defined( 'DOING_AJAX' ) ) {
                                wp_safe_redirect( $redirect_to ); // to profile
                                exit;
                            }
                        }
                    }
                }
            }


            /**
             * Add login styling
             */
            public function b3_add_login_styling() {

                $bg_color        = get_option( 'b3_loginpage_bg_color', false );
                $font_family     = get_option( 'b3_loginpage_font_family', false );
                $font_size       = get_option( 'b3_loginpage_font_size', false );
                $logo            = apply_filters( 'b3_main_logo', b3_get_main_logo() );
                $logo_height     = get_option( 'b3_loginpage_logo_height', false );
                $logo_width      = get_option( 'b3_loginpage_logo_width', false );
                $recaptcha       = get_option( 'b3_recaptcha', false );
                $recaptcha_login = get_option( 'b3_recaptcha_login', false );
                $style_pages     = get_option( 'b3_style_default_pages', false );

                if ( $style_pages || $recaptcha || $recaptcha_login ) {
                    echo '<style type="text/css">';
                    echo "\n";
                    if ( $bg_color ) {
                        echo "\nbody { background: #" . $bg_color . "; }\n";
                    }
                    if ( $font_family || $font_size || $recaptcha || $recaptcha_login ) {
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
                    echo "\n";
                    echo '</style>';
                    echo "\n";
                }
            }


            /**
             * Protect some pages
             */
            public function b3_template_redirect() {
                $account_page_id  = b3_get_account_id();
                $account_url      = ( false != $account_page_id ) ? get_the_permalink( $account_page_id ) : admin_url( 'profile.php' );
                $approval_page_id = b3_get_user_approval_id();
                $login_page_id    = b3_get_login_id();
                $login_url        = ( false != $login_page_id ) ? get_the_permalink( $login_page_id ) : wp_login_url();
                $logout_page_id   = b3_get_logout_id();

                if ( false != $account_page_id && is_page( [ $account_page_id ] ) && ! is_user_logged_in() ) {

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
                wp_enqueue_style( 'b3-ob-main', plugins_url( 'assets/css/style.css', __FILE__), [], $this->settings['version'] );
                wp_enqueue_script( 'b3-ob-js', plugins_url( 'assets/js/js.js', __FILE__ ), array( 'jquery' ), $this->settings['version'] );
            }


            /**
             * Enqueue scripts in backend
             */
            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3-ob-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), [], $this->settings['version'] );
                wp_enqueue_script( 'b3-ob-js-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->settings['version'] );

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
                        'title'     => __( 'Upload or choose your custom logo', 'b3-onboarding' ),  // This will be used as the title
                        'button'    => __( 'Insert logo', 'b3-onboarding' )                         // This will be used as the button text
                    )
                );
                wp_enqueue_script( 'b3-media' );
            }


            /**
             * Adds a page to admin sidebar menu
             */
            public function b3_add_admin_pages() {
                include( 'includes/admin-page.php' ); // content for the settings page
                add_menu_page( 'B3 Onboarding', 'B3 Onboarding', 'manage_options', 'b3-onboarding', 'b3_user_register_settings', B3_PLUGIN_URL .  '/assets/images/logo-b3onboarding-small.png', '99' );
                if ( 'request_access' == get_option( 'b3_registration_type', false ) ) {
                    include( 'includes/user-approval-page.php' ); // content for the settings page
                    add_submenu_page( 'b3-onboarding', 'User Approval', 'User Approval', 'manage_options', 'b3-user-approval', 'b3_user_approval' );
                }
            }


            /**
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


            /**
             * Register widgets (if activated)
             */
            public function b3_register_widgets() {
                if ( true == get_option( 'b3_sidebar_widget', false ) ) {
                    include( 'includes/B3SidebarWidget.php' );
                }
            }


            /**
             * Add dashboard widget
             */
            public function b3_add_dashboard_widget() {
                if ( true == get_option( 'b3_dashboard_widget', false ) ) {
                    include( 'includes/dashboard-widget.php' );
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
             * Add stuff to wp_login_form (top)
             *
             * @param $content
             * @param $args
             *
             * @return string
             */
            public function b3_loginform_top( $content, $args ) {

                $content = 'top';

                return $content;
            }


            /**
             * Add stuff to wp_login_form (middle, after password)
             *
             * @param $content
             * @param $args
             *
             * @return string
             */
            public function b3_loginform_middle( $content, $args ) {

                $content = '<p>Place for possible reCaptcha</p>';

                return $content;
            }


            /**
             * Add stuff to wp_login_form (bottom)
             *
             * @param $content
             * @param $args
             *
             * @return string
             */

            public function b3_loginform_footer( $content, $args ) {

                $content = 'bottom';

                return $content;
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
                                $prefix       = esc_html__( 'Error', 'action-logger' );
                            } elseif ( strpos( $code, 'warning' ) !== false ) {
                                $notice_class = 'notice notice-warning ';
                                $prefix       = esc_html__( 'Warning', 'action-logger' );
                            } elseif ( strpos( $code, 'info' ) !== false ) {
                                $notice_class = 'notice notice-info ';
                                $prefix       = false;
                            } else {
                                $notice_class = 'notice--error ';
                                $prefix       = esc_html__( 'Error', 'action-logger' );
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
                wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], false, true );
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
                        $redirect_url = b3_get_register_id( true );
                        if ( ! wp_verify_nonce( $_POST[ 'b3_register_user' ], 'b3-register-user' ) ) {
                            B3Onboarding::b3_errors()->add( 'error_no_nonce_match', esc_html__( 'Something went wrong, please try again.', 'b3-onboarding' ) );

                            return;
                        } else {

                            $user_login                = ( isset( $_POST[ 'user_login' ] ) ) ? $_POST[ 'user_login' ] : false;
                            $user_email                = ( isset( $_POST[ 'user_email' ] ) ) ? $_POST[ 'user_email' ] : false;
                            $role                      = get_option( 'default_role' );
                            $registration_type         = get_option( 'b3_registration_type', false );
                            $meta_data[ 'first_name' ] = ( isset( $_POST[ 'first_name' ] ) ) ? sanitize_text_field( $_POST[ 'first_name' ] ) : false; // @TODO: do i need this for MS ?
                            $meta_data[ 'last_name' ]  = ( isset( $_POST[ 'last_name' ] ) ) ? sanitize_text_field( $_POST[ 'last_name' ] ) : false; // @TODO: do i need this for MS ?

                            if ( ! is_multisite() ) {
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );

                                } elseif ( false != get_option( 'b3_recaptcha', false ) && ! $this->b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'captcha', $redirect_url );

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
                                            $reset_password_url = b3_get_forgotpass_id( true );
                                            if ( false != $reset_password_url ) {
                                                $redirect_url = $reset_password_url;
                                                $redirect_url = add_query_arg( 'registered', $query_arg, $redirect_url );
                                                // @TODO: also add to wp form register + MU register
                                                // @TODO: look into filter 'registration_redirect'
                                                $redirect_url = apply_filters( 'b3_redirect_after_register', $redirect_url );
                                            } else {
                                                $login_url    = b3_get_login_id( true );
                                                $redirect_url = $login_url;
                                            }
                                        } else {
                                            // redirect to login page
                                            $login_page_url = b3_get_login_id( true );
                                            if ( false != $login_page_url ) {
                                                $redirect_url = $login_page_url;
                                                $redirect_url = add_query_arg( 'registered', $query_arg, $redirect_url );
                                            } else {
                                                $redirect_url = wp_login_url();
                                            }
                                        }
                                    }
                                }

                            } else {

                                // if is_multisite
                                $register = false;
                                if ( 'closed' == $registration_type ) {
                                    // Registration closed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'closed', $redirect_url );
                                } elseif ( false != get_option( 'b3_recaptcha', false ) && ! $this->b3_verify_recaptcha() ) {
                                    // Recaptcha check failed, display error
                                    $redirect_url = add_query_arg( 'registration-error', 'captcha', $redirect_url );
                                } elseif ( in_array( $registration_type, [ 'request_access', 'email_activation', 'ms_register_site_user' ] ) ) {
                                    $register = true;
                                } else {
                                }

                                if ( true == $register ) {
                                    // is_multisite
                                    $meta_data[ 'blog_public' ] = '1';
                                    $meta_data[ 'lang_id' ]     = '0'; // ????
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
                        $login_url   = b3_get_login_id( true );
                        $login_url   = add_query_arg( 'login', $error_codes, $login_url );

                        wp_safe_redirect( $login_url );
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
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_option( 'b3_force_custom_login_page', false ) ) {
                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user();
                    } else {
                        $page_url = b3_get_register_id( true );
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
                if ( '/wp-signup.php' == $_SERVER[ 'REQUEST_URI' ] ) {
                    $register_url = b3_get_register_id( true );
                    if ( false != $register_url ) {
                        wp_safe_redirect( $register_url );
                        exit;
                    }
                }
            }


            /**
             * Force user to custom login page instead of wp-login.php.
             */
            function b3_redirect_to_custom_login() {
                if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] && 1 == get_option( 'b3_force_custom_login_page', false ) ) {

                    $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : null;

                    if ( is_user_logged_in() ) {
                        $this->b3_redirect_logged_in_user( $redirect_to );
                        exit;
                    }

                    // The rest is redirected to the login page
                    $custom_login_url = b3_get_login_id( true );
                    $login_url        = ( false != $custom_login_url ) ? $custom_login_url : wp_login_url();

                    if ( ! empty( $redirect_to ) ) {
                        $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
                    }

                    wp_safe_redirect( $login_url );
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

                    $forgot_password_url = b3_get_forgotpass_id( true );
                    if ( false != $forgot_password_url ) {
                        wp_safe_redirect( $forgot_password_url );
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
                    $redirect_url = b3_get_resetpass_id( true );

                    if ( isset( $_REQUEST[ 'key' ] ) && isset( $_REQUEST[ 'login' ] ) ) {
                        $user = check_password_reset_key( $_REQUEST[ 'key' ], $_REQUEST[ 'login' ] );
                        if ( ! $user || is_wp_error( $user ) ) {
                            if ( $user && $user->get_error_code() === 'expired_key' ) {
                                // @TODO: maybe change link
                                wp_safe_redirect( home_url( 'login?login=expiredkey' ) );
                            } else {
                                wp_safe_redirect( home_url( 'login?login=invalidkey' ) );
                            }
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
             * @param string           $redirect_to           The redirect destination URL.
             * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
             * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
             *
             * @return string Redirect URL
             */
            public function b3_redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

                $redirect_url = get_home_url();
                $stored_roles = ( is_array( get_option( 'b3_restrict_admin', false ) ) ) ? get_option( 'b3_restrict_admin' ) : [ 'subscriber' ];

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
                    $account_page_url = b3_get_account_id( true );
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
             */
            function b3_redirect_after_logout() {
                $login_url = b3_get_login_id( true );
                if ( false != $login_url ) {
                    $redirect_url = $login_url;
                } else {
                    $redirect_url = wp_login_url();
                }
                $redirect_url = add_query_arg( 'logged_out', 'true', $redirect_url );
                wp_safe_redirect( $redirect_url );
                exit;
            }


            /**
             * Initiates email activation ('normal site')
             */
            function b3_do_user_activate() {
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
                            $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), wp_login_url() );
                        } else {

                            $lostpassword_url = b3_get_forgotpass_id( true );
                            $redirect_url     = add_query_arg( array( 'activate' => 'success' ), $lostpassword_url );

                            // remove user_activation_key
                            $wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'user_login' => $_GET[ 'user_login' ] ) );

                            // activate user, change user role
                            $user_object = new WP_User( $user->ID );
                            $user_object->set_role( get_option( 'default_role' ) );

                            // @TODO: check if still needed
                            do_action( 'b3_new_user_activated', $user->ID );

                        }

                        wp_safe_redirect( $redirect_url );
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
                        // errors found
                        $redirect_url = b3_get_forgotpass_id( true );
                        $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), $redirect_url );
                    } else {
                        // Email sent
                        $redirect_url = b3_get_login_id( true );
                        $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                    }

                    wp_safe_redirect( $redirect_url );
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
                                // @TODO: maybe change link
                                wp_safe_redirect( home_url( 'login?login=expiredkey' ) );
                            } else {
                                wp_safe_redirect( home_url( 'login?login=invalidkey' ) );
                            }
                            exit;
                        }

                        if ( isset( $_POST[ 'pass1' ] ) ) {
                            if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] ) {
                                // Passwords don't match
                                $redirect_url = b3_get_resetpass_id( true );
                                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                                wp_safe_redirect( $redirect_url );
                                exit;
                            }

                            if ( empty( $_POST[ 'pass1' ] ) ) {
                                // Password is empty
                                $redirect_url = b3_get_resetpass_id( true );
                                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                                wp_safe_redirect( $redirect_url );
                                exit;
                            }

                            // Parameter checks OK, reset password
                            reset_password( $user, $_POST[ 'pass1' ] );
                            $redirect_url = b3_get_login_id( true );
                            $redirect_url = add_query_arg( 'password', 'changed', $redirect_url );
                            wp_safe_redirect( $redirect_url );
                            exit;

                        } else {
                            echo "Invalid request.";
                        }

                    } else {
                        echo "Invalid request.";
                    }

                }
            }


            /**
             * Finds and returns a matching error message for the given error code.
             *
             * @param string $error_code The error code to look up.
             *
             * @return string               An error message.
             */
            public function b3_get_error_message( $error_code ) {
                switch( $error_code ) {

                    // Login errors
                    case 'empty_username':
                        return esc_html__( 'Please enter a user name', 'b3-onboarding' );

                    case 'empty_password':
                        return esc_html__( 'Please enter a password.', 'b3-onboarding' );

                    case 'invalid_username':
                        return esc_html__( "We don't have any users with that email address. Maybe you used a different one when signing up?", 'b3-onboarding' );

                    case 'incorrect_password':
                        $err = __( "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?", 'b3-onboarding' );

                        return sprintf( $err, wp_lostpassword_url() );

                    // Registration Logged out
                    case 'logged_out':
                        return esc_html__( 'You are logged out.', 'b3-onboarding' );

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

                    case 'no_privacy':
                        return esc_html__( 'You have to accept the privacy statement.', 'b3-onboarding' );

                    case 'access_requested':
                        return esc_html__( 'You have sucessfully requested access. Someone will check your request.', 'b3-onboarding' );

                    case 'confirm_email':
                        return esc_html__( 'You have sucessfully registered but need to confirm your email first. Please check your email for an activation link.', 'b3-onboarding' );

                    // Lost password
                    case 'invalid_email':
                    case 'invalidcombo':
                        // @TODO: change this for security reasons
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
                        return esc_html__( 'You have successfully activated your account. You can set your password below.', 'b3-onboarding' );

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
                        return esc_html__( 'Sorry, this subdomain has already been taken.', 'b3-onboarding' );

                    case 'user_registered':
                        return esc_html__( 'You have successfully registered. Please check your email for an activation link.', 'b3-onboarding' );

                    // Account remove
                    case 'account_remove':
                        return esc_html__( 'Your account has been deleted.', 'b3-onboarding' );

                    // Admin
                    case 'settings_saved':
                    case 'pages_saved':
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
             * @param string $user_login
             * @param string $user_email
             *
             * @return int|WP_Error
             */
            private function b3_register_user( $user_email, $user_login, $registration_type, $role = 'subscriber' ) {
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

                $privacy_error = b3_verify_privacy();
                if ( true == $privacy_error ) {
                    $errors->add( 'no_privacy', $this->b3_get_error_message( 'no_privacy' ) );

                    return $errors;
                }

                $user_data = array(
                    'user_login' => $user_login,
                    'user_email' => $user_email,
                    'user_pass'  => '', // @TODO: for custom passwords
                    'role'       => $role,
                );

                $user_id = wp_insert_user( $user_data );
                if ( ! is_wp_error( $user_id ) ) {
                    $inform = ( 1 == get_option( 'b3_disable_admin_notification_new_user', false ) ) ? 'user' : 'both';
                    if ( 'email_activation' == $registration_type ) {
                        $inform = 'user';
                    }
                    $inform = apply_filters( 'b3_custom_register_inform', $inform );
                    wp_new_user_notification( $user_id, null, $inform );
                    do_action('b3_after_email_sent', $user_id );
                }

                return $user_id;
            }


            /**
             * Validates and then completes WPMU signup process if all went well.
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
                $subsite_register_type = get_option( 'b3_registration_type', [] );
                $user_registered       = false;

                if ( is_main_site() ) {

                    if ( in_array( $main_register_type, [ 'all', 'blog' ] ) && in_array( $subsite_register_type, [ 'request_access', 'request_access_subdomain', 'ms_loggedin_register', 'ms_register_user', 'ms_register_site_user' ] )) {
                        if ( false == $sub_domain ) {
                            // @TODO: throw error if no subdomain is chosen
                            wpmu_signup_user( $user_name, $user_email, $meta );
                            $user_registered = true;
                        } else {
                            wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                            $site_id         = get_id_from_blogname( $sub_domain );
                            $user_registered = true;
                            do_action( 'b3_after_insert_site', $site_id );
                        }
                    } elseif ( 'none' == $main_register_type ) {
                        // @TODO: add if for user or user + site
                        // @TODO: add if for if user needs to activate
                        // @TODO: add if for if admin needs to activate
                        // wpmu_signup_blog( $sub_domain . '.' . $_SERVER[ 'HTTP_HOST' ], '/', ucfirst( $sub_domain ), $user_name, $user_email, apply_filters( 'add_signup_meta', $meta ) );
                    } else {
                        $errors = new WP_Error( 'unknown', $this->b3_get_error_message( 'unknown' ) );
                    }

                } else {

                    if ( 'user' == $main_register_type && 'closed' != $subsite_register_type ) {
                        wpmu_signup_user( $user_name, $user_email, $meta );
                        $user_registered = true;
                    } else {
                        $errors = new WP_Error( 'unknown', $this->b3_get_error_message( 'unknown' ) );
                    }

                }

                if ( true == $user_registered ) {
                    // $errors = new WP_Error( 'user_registered', $this->b3_get_error_message( 'user_registered' ) );
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
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                // Retrieve recaptcha key
                $attributes[ 'recaptcha_site_key' ] = get_option( 'b3-onboarding-recaptcha-public-key', null );

                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already signed in.', 'b3-onboarding' );
                } elseif ( 'closed' == get_option( 'b3_registration_type', false ) ) {
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

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
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

                // Pass the redirect parameter to the WordPress login functionality: but
                // only if a valid redirect URL has been passed as request parameter, use it.
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
                    $attributes[ 'user_activate' ] = isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ];
                }
                $attributes[ 'errors' ] = $errors;

                // Check if user just updated password
                $attributes[ 'password_updated' ] = isset( $_REQUEST[ 'password' ] ) && 'changed' == $_REQUEST[ 'password' ];
                // Check if the user just requested a new password
                $attributes[ 'lost_password_sent' ] = isset( $_REQUEST[ 'checkemail' ] ) && 'confirm' == $_REQUEST[ 'checkemail' ];
                // Check if user just logged out
                $attributes[ 'logged_out' ] = isset( $_REQUEST[ 'logged_out' ] ) && true == $_REQUEST[ 'logged_out' ];
                // Check if the user just registered
                $attributes[ 'registered' ] = isset( $_REQUEST[ 'registered' ] );
                // Check if the user removed his/her account
                $attributes[ 'account_remove' ] = isset( $_REQUEST[ 'account' ] ) && 'removed' == $_REQUEST[ 'account' ];

                // Render the login form using an external template
                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
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
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already logged in.', 'b3-onboarding' );
                }

                // Retrieve possible errors from request parameters
                $attributes[ 'errors' ] = array();
                if ( isset( $_REQUEST[ 'error' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'error' ] );

                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ][] = $this->b3_get_error_message( $error_code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) ) {
                    $attributes[ 'user_activate' ] = isset( $_REQUEST[ 'activate' ] ) && $_REQUEST[ 'activate' ] == 'success';
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    $attributes[ 'registered' ] = isset( $_REQUEST[ 'registered' ] ) && $_REQUEST[ 'registered' ] == 'success';
                }
                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
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
                    return esc_html__( 'You are already logged in.', 'b3-onboarding' );
                } else {
                    if ( isset( $_REQUEST[ 'login' ] ) && isset( $_REQUEST[ 'key' ] ) ) {
                        $attributes[ 'login' ] = $_REQUEST[ 'login' ];
                        $attributes[ 'key' ]   = $_REQUEST[ 'key' ];

                        // Error messages
                        $errors = array();
                        if ( isset( $_REQUEST[ 'error' ] ) ) {
                            $error_codes = explode( ',', $_REQUEST[ 'error' ] );

                            foreach ( $error_codes as $code ) {
                                $errors[] = $this->b3_get_error_message( $code );
                            }
                        }
                        $attributes[ 'errors' ] = $errors;

                        return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                    } else {
                        return esc_html__( 'Invalid password reset link.', 'b3-onboarding' );
                    }
                }
            }


            /**
             * Render user/account page
             *
             * @param      $user_variables
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
                    $attributes = shortcode_atts( $default_attributes, $user_variables );

                    // error messages
                    $errors = array();
                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );

                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_error_message( $code );
                        }
                    }
                    $attributes[ 'errors' ] = $errors;

                    if ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $attributes[ 'updated' ] = $this->b3_get_error_message( $_REQUEST[ 'updated' ] );
                    }

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

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

                $show_first_last_name = get_option( 'b3_activate_first_last', false );
                $user_args            = array( 'role' => 'b3_approval' );
                $users                = get_users( $user_args );
                if ( current_user_can( 'promote_users' ) ) {
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

                        <table class="b3_table b3_table--user">
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
                    <?php } else { ?>
                        <p><?php esc_html_e( 'No (more) users to approve.', 'b3-onboarding' ); ?></p>
                    <?php }
                } // endif user can promote_users
            }


            /**
             * Renders the contents of the given template to a string and returns it.
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


            /**
             * Check post values of saved options
             */
            public function b3_check_options_post() {
                // @TODO: add nonce check
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
             */
            public function b3_login_logo_url() {
                return get_home_url();
            }

            /**
             * For filter 'login_headertitle', replaces the page-title on the login page
             *
             * @return string
             */
            public function b3_login_logo_url_title() {
                $title            = get_bloginfo( 'name', 'display' );
                $site_description = get_bloginfo( 'description', 'display' );
                if ( $site_description ) {
                    $title = sprintf( '%s | %s', $title, $site_description );
                }

                return $title;
            }

            /**
             * Add network admin message (MS) if plugin is activated
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
             * Add admin message if front-end approval is set, but no page is selected
             */
            public function b3_no_approval_page() {
                if ( false == get_option( 'b3_approval_page_id', false ) && true == get_option( 'b3_front_end_approval', false ) ) {
                    echo sprintf( '<div class="error"><p>'. __( 'You have not set a page for front-end user approval. Set it <a href="%s">%s</a>', 'b3-onboarding' ) . '.</p></div>',
                        esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=pages' ) ),
                        esc_html__( 'here', 'b3-onboarding' )
                    );
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
                $b3_onboarding->initialize();
            }

            return $b3_onboarding;
        }

        // Initialize the plugin
        init_b3_onboarding();

    } // class_exists check
