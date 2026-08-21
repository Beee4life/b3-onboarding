<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    /**
     * Redirect to custom login page after the user has been logged out.
     *
     * @since 1.0.6
     */
    function b3_redirect_after_logout() {
        $login_url = b3_get_login_url();
        if ( ! empty( $_REQUEST[ 'redirect_to' ] ) ) {
            $redirect_url = sanitize_text_field( wp_unslash( $_REQUEST[ 'redirect_to' ] ) );
        } else {
            $redirect_url = add_query_arg( 'logout', 'true', $login_url );
        }
        wp_safe_redirect( $redirect_url );
        exit;
    }
    add_action( 'wp_logout', 'b3_redirect_after_logout', 1 );

    /**
     * Redirects the user to the custom registration page instead
     * of wp-signup.php
     */
    function b3_redirect_to_custom_mu_register() {
        if ( is_user_logged_in() ) {
            // only redirect if blog != registration_type
            if ( 'blog' != get_option( 'b3_registration_type' ) ) {
                do_action( 'b3_redirect', 'logged_in' );
            }
        } else {
            $register_url = b3_get_register_url();
            if ( ! $register_url ) {
                $register_url = wp_registration_url();
            }
            wp_safe_redirect( $register_url );
            exit;
        }
    }
    add_action( 'before_signup_header', 'b3_redirect_to_custom_mu_register' );

    /**
     * Redirects the user to the custom registration page instead
     * of wp-login.php?action=register.
     */
    function b3_redirect_to_custom_register() {
        if ( ! is_multisite() && isset( $_GET[ 'action' ] ) && 'register' == $_GET[ 'action' ] ) {
            if ( is_user_logged_in() ) {
                do_action( 'b3_redirect', 'logged_in' );
            } else {
                $registration_url = b3_get_register_url();
                if ( ! $registration_url ) {
                    $registration_url = wp_registration_url();
                }
                wp_safe_redirect( $registration_url );
                exit;
            }
        }
    }
    add_action( 'login_form_register', 'b3_redirect_to_custom_register' );

    /**
     * Force user to custom login page instead of wp-login.php.
     */
    function b3_redirect_to_custom_login() {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && 'GET' !== $_SERVER[ 'REQUEST_METHOD' ] ) {
            return;
        }

        $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? urlencode( sanitize_text_field( wp_unslash( $_REQUEST[ 'redirect_to' ] ) ) ) . '&reauth=1' : null;

        if ( is_user_logged_in() ) {
            do_action( 'b3_redirect', 'logged_in', $redirect_to );
        }

        $login_url = b3_get_login_url();
        if ( ! empty( $redirect_to ) ) {
            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
        }

        wp_safe_redirect( $login_url );
        exit;
    }
    add_action( 'login_form_login', 'b3_redirect_to_custom_login' );

    /**
     * Redirects the user to the custom "Lost password?" page instead of
     * wp-login.php?action=lostpassword.
     */
    function b3_redirect_to_custom_lostpassword() {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && 'GET' !== $_SERVER[ 'REQUEST_METHOD' ] ) {
            return;
        }

        if ( is_user_logged_in() ) {
            do_action( 'b3_redirect', 'logged_in' );
        }

        $lost_password_url = b3_get_lostpassword_url();
        if ( false != $lost_password_url ) {
            wp_safe_redirect( $lost_password_url );
            exit;
        }
    }
    add_action( 'login_form_lostpassword', 'b3_redirect_to_custom_lostpassword' );

    /**
     * Redirects to the custom password reset page,
     * or the login page if there are errors.
     */
    function b3_redirect_to_custom_reset_password() {
        if ( isset( $_SERVER[ 'REQUEST_METHOD' ] ) && 'GET' !== $_SERVER[ 'REQUEST_METHOD' ] ) {
            return;
        }

        $redirect_url = b3_get_reset_password_url();

        // Verify key / login combo
        if ( isset( $_REQUEST[ 'key' ] ) && isset( $_REQUEST[ 'login' ] ) ) {
            $key   = sanitize_key( $_REQUEST[ 'key' ] );
            $login = sanitize_text_field( wp_unslash( $_REQUEST[ 'login' ] ) );
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
    add_action( 'login_form_resetpass', 'b3_redirect_to_custom_reset_password' );
    add_action( 'login_form_rp', 'b3_redirect_to_custom_reset_password' );

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
    function b3_redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
        $exclude_from_admin = ( is_array( get_option( 'b3_restrict_admin' ) ) ) ? get_option( 'b3_restrict_admin' ) : [ 'subscriber' ];

        if ( ! $user ) {
            return get_home_url();
        } elseif ( is_wp_error( $user ) ) {
            if ( is_multisite() && ! is_main_site() ) {
                // a user has not been created since it needs to be confirmed
                $no_user = true;
            }
        }

        if ( $requested_redirect_to ) {
            $redirect_to = $requested_redirect_to;
        } else {
            if ( is_wp_error( $user ) ) {
                return b3_get_login_url();
            }

            if ( isset( $no_user ) && true == $no_user ) {
                return get_home_url();
            } else {
                if ( ! user_can( $user, 'manage_options' ) ) {
                    // Non-admin users always go to their account page after login, if it's defined and if no welcome page is set
                    $account_page_url = b3_get_account_url();
                    $welcome_page     = apply_filters( 'b3_welcome_page', false );

                    if ( $welcome_page && false == get_user_meta( $user->ID, 'b3_welcome_page_seen', true ) ) {
                        update_user_meta( $user->ID, 'b3_welcome_page_seen', 'true' );
                        $redirect_to = $welcome_page;
                    } elseif ( false != $account_page_url ) {
                        if ( ! in_array( $exclude_from_admin, $user->roles ) ) {
                            $redirect_to = $account_page_url;
                        }
                    } elseif ( current_user_can( 'read' ) ) {
                        $redirect_to = get_edit_user_link( get_current_user_id() );
                    }
                } else {
                    $redirect_to = admin_url();
                }
            }
        }

        return $redirect_to;
    }
    add_filter( 'login_redirect', 'b3_redirect_after_login', 10, 3 );

    /**
     * Redirects "profile.php" to custom account page
     */
    function b3_redirect_to_custom_profile() {
        global $current_user;
        if ( is_user_logged_in() && is_admin() ) {
            $user_role = reset( $current_user->roles );
            if ( in_array( $user_role, get_option( 'b3_restrict_admin', [] ) ) ) {
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
    add_action( 'init', 'b3_redirect_to_custom_profile' );
