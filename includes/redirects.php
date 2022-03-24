<?php
    /**
     * Redirect to custom login page after the user has been logged out.
     *
     * @since 1.0.6
     */
    function b3_redirect_after_logout() {
        $redirect_url = add_query_arg( 'logout', 'true', b3_get_login_url() );
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
            if ( false != $register_url ) {
                wp_safe_redirect( $register_url );
            } else {
                wp_safe_redirect( wp_registration_url() );
            }
            exit;
        }
    }
    add_action( 'before_signup_header', 'b3_redirect_to_custom_mu_register' );

    /**
     * Redirects the user to the custom registration page instead
     * of wp-login.php?action=register.
     */
    function b3_redirect_to_custom_register() {
        if ( ! is_multisite() ) {
            if ( isset( $_GET[ 'action' ] ) && 'register' == $_GET[ 'action' ] ) {
                if ( is_user_logged_in() ) {
                    do_action( 'b3_redirect', 'logged_in' );
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
    add_action( 'login_form_register', 'b3_redirect_to_custom_register' );

    /**
     * Force user to custom login page instead of wp-login.php.
     */
    function b3_redirect_to_custom_login() {
        if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            $redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? urlencode( $_REQUEST[ 'redirect_to' ] ) . '&reauth=1' : null;

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
    }
    add_action( 'login_form_login', 'b3_redirect_to_custom_login' );

    /**
     * Redirects the user to the custom "Lost password?" page instead of
     * wp-login.php?action=lostpassword.
     */
    function b3_redirect_to_custom_lostpassword() {
        if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( is_user_logged_in() ) {
                do_action( 'b3_redirect', 'logged_in' );
            }

            $lost_password_url = b3_get_lostpassword_url();
            if ( false != $lost_password_url ) {
                wp_safe_redirect( $lost_password_url );
                exit;
            }
        }
    }
    add_action( 'login_form_lostpassword', 'b3_redirect_to_custom_lostpassword' );

    /**
     * Redirects to the custom password reset page,
     * or the login page if there are errors.
     */
    function b3_redirect_to_custom_reset_password() {
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
        $stored_roles  = ( is_array( get_option( 'b3_restrict_admin' ) ) ) ? get_option( 'b3_restrict_admin' ) : array( 'subscriber' );
        $redirect_url  = get_home_url();

        if ( ! $user ) {
            return $redirect_url;
        } elseif ( is_wp_error( $user ) ) {
            // check if is
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
            } else {
                $redirect_to = admin_url();
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

    /**
     * Initiates email activation
     *
     * @since 1.0.6
     * @since 3.0 wpmu user activation
     */
    function b3_do_user_activate() {
        if ( is_multisite() ) {
            if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                if ( isset( $_GET[ 'activate' ] ) && 'user' == $_GET[ 'activate' ] ) {
                    $redirect_url = b3_get_login_url();
                    $valid_error_codes = array( 'already_active', 'blog_taken' );
                    list( $activate_path ) = explode( '?', wp_unslash( $_SERVER[ 'REQUEST_URI' ] ) );
                    $activate_cookie = 'wp-activate-' . COOKIEHASH;
                    $key             = '';
                    $result          = null;

                    if ( isset( $_GET[ 'key' ] ) && isset( $_POST[ 'key' ] ) && $_GET[ 'key' ] !== $_POST[ 'key' ] ) {
                        wp_die( esc_html__( 'A key value mismatch has been detected. Please follow the link provided in your activation email.' ), esc_html__( 'An error occurred during the activation' ), 400 );
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
            }

        } else {
            if ( 'GET' == $_SERVER[ 'REQUEST_METHOD' ] ) {
                if ( ! empty( $_GET[ 'action' ] ) && 'activate' == $_GET[ 'action' ] && ! empty( $_GET[ 'key' ] ) && ! empty( $_GET[ 'user_login' ] ) ) {

                    global $wpdb;

                    $errors = false;
                    $key    = preg_replace( '/[^a-zA-Z0-9]/i', '', sanitize_key( $_GET[ 'key' ] ) );

                    if ( empty( $key ) || ! is_string( $key ) ) {
                        $errors = new WP_Error( 'invalid_key', esc_attr__( 'Invalid key', 'b3-onboarding' ) );
                    }

                    if ( empty( $_GET[ 'user_login' ] ) || ! is_string( $_GET[ 'user_login' ] ) ) {
                        $errors = new WP_Error( 'invalid_key', esc_attr__( 'Invalid key', 'b3-onboarding' ) );
                    }

                    // Validate activation key
                    $user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, sanitize_user( $_GET[ 'user_login' ] ) ) );

                    if ( empty( $user ) ) {
                        $errors = new WP_Error( 'invalid_user', esc_attr__( 'Invalid user', 'b3-onboarding' ) );
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

                        if ( false == get_option( 'b3_activate_custom_passwords' ) ) {
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
    add_action( 'init', 'b3_do_user_activate' );


    /**
     * Initiates password reset.
     *
     * @since 1.0.6
     */
    function b3_do_password_lost() {
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_form' ] ) && 'lostpass' == $_POST[ 'b3_form' ] ) {
                $errors = b3_retrieve_password();

                if ( is_wp_error( $errors ) ) {
                    // errors found
                    $redirect_url = b3_get_lostpassword_url();
                    $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), $redirect_url );
                } else {
                    // Email sent
                    $site_id = get_current_blog_id();
                    if ( isset( $_POST[ 'b3_site_id' ] ) ) {
                        $site_id = $_POST[ 'b3_site_id' ];
                    }
                    $redirect_url = b3_get_login_url( false, $site_id );
                    $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                }

                wp_safe_redirect( $redirect_url );
                exit;
            }
        }
    }
    add_action( 'init', 'b3_do_password_lost' );
