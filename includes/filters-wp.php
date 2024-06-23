<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    include 'filters-wp-mail.php';

    /**
     * Add post states for B3 pages
     *
     * @since 1.0.6
     *
     * @param $post_states
     * @param $post
     *
     * @return mixed
     */
    function b3_add_post_state( $post_states, $post ) {
        if ( $post->ID == get_option( 'b3_account_page_id' ) ) {
            $post_states[] = 'B3 : Account';
        } elseif ( $post->ID == get_option( 'b3_register_page_id' ) ) {
            $post_states[] = 'B3 : Register';
        } elseif ( $post->ID == get_option( 'b3_login_page_id' ) ) {
            $post_states[] = 'B3 : Login';
        } elseif ( $post->ID == get_option( 'b3_logout_page_id' ) ) {
            $post_states[] = 'B3 : Log out';
        } elseif ( $post->ID == get_option( 'b3_lost_password_page_id' ) ) {
            $post_states[] = 'B3 : Lost password';
        } elseif ( $post->ID == get_option( 'b3_reset_password_page_id' ) ) {
            $post_states[] = 'B3 : Reset password';
        } elseif ( $post->ID == get_option( 'b3_approval_page_id' ) ) {
            $post_states[] = 'B3 : User approval';
        }

        return $post_states;
    }
    add_filter( 'display_post_states', 'b3_add_post_state', 10, 2 );


    /**
     * Adds nonce to log out page link
     *
     * @since 1.0.0
     *
     * @param $permalink
     * @param $post_id
     *
     * @return string
     */
    function b3_logout_link( $logout_link, $post_id ) {
        if ( b3_get_logout_url( true ) == $post_id ) {
            $logout_link = add_query_arg( '_wpnonce', wp_create_nonce( 'logout' ), $logout_link );
        }

        return $logout_link;
    }
    add_filter( 'page_link', 'b3_logout_link', 10, 2 );


    /**
     * Filters message on default register form
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function wp_login_message( $message ) {
        if ( isset( $_GET[ 'action' ] ) ) {
            $action = $_GET[ 'action' ];
            if ( 'register' === $action ) {
                $message = b3_get_message_above_registration();
            } elseif ( 'lostpassword' === $action ) {
                $message = b3_get_message_above_lost_password();
            }
        } else {
            $message = b3_get_message_above_login();
        }

        if ( ! empty( $message ) ) {
            $message = '<p class="message">' . $message . '</p>';
        }

        return $message;
    }
    add_filter( 'login_message', 'wp_login_message' );


    /**
     * Check if user may login, if he/she has a custom role.
     *
     * @since 2.4.0
     *
     * @param $user
     * @param $password
     *
     * @return WP_Error
     */
    function b3_login_errors( $user, $password ) {
        if ( $user ) {
            if ( in_array( 'b3_activation', $user->roles ) ) {
                return new WP_Error( 'wait_confirmation', '' );
            } elseif ( in_array( 'b3_approval', $user->roles ) ) {
                return new WP_Error( 'wait_approval', '' );
            }
        }

        return $user;
    }
    add_filter( 'wp_authenticate_user', 'b3_login_errors', 20, 2 );


    /**
     * Check setting to update B3
     *
     * @param $new_value
     * @param $old_value
     *
     * @return false|mixed|string|void
     */
    function b3_prevent_update_registration_option( $new_value, $old_value ) {
        return 0;
    }
    add_filter( 'pre_update_option_users_can_register', 'b3_prevent_update_registration_option', 10, 2 ); // non-multisite || main site


    /**
     * Check setting to update B3
     *
     * @param $new_value
     * @param $old_value
     *
     * @return mixed
     */
    function b3_check_network_registration_option( $new_value, $old_value ) {
        return 'none';
    }
    add_filter( 'pre_update_site_option_registration', 'b3_check_network_registration_option', 10, 2 ); // multisite


    /**
     * Check setting to update B3
     *
     * @param $new_value
     * @param $old_value
     *
     * @return string
     */
    function b3_prevent_update_registration_notification_option( $new_value, $old_value ) {
        return 'no';
    }
    add_filter( 'pre_update_site_option_registrationnotification', 'b3_prevent_update_registration_notification_option', 10, 2 );


    /**
     * Add to admin body class
     *
     * @param $classes
     *
     * @return string
     */
    function b3_admin_body_class( $classes ) {
        if ( 'request_access' != get_option( 'b3_registration_type' ) ) {
            $classes .= 'no-approval-page';
        }

        return $classes;
    }
    add_filter( 'admin_body_class', 'b3_admin_body_class' );


    /**
     * Add user actions on users.php
     *
     * @param $actions
     * @param $user_object
     *
     * @return mixed
     */
    function b3_user_row_actions( $actions, $user_object ) {
        $current_user      = wp_get_current_user();
        $registration_type = get_option( 'b3_registration_type' );

        if ( $current_user->ID != $user_object->ID ) {
            if ( 'email_activation' === $registration_type ) {
                if ( in_array( 'b3_activation', (array) $user_object->roles ) ) {
                    unset( $actions[ 'resetpassword' ] );
                    $actions[ 'resend_activation' ] = sprintf( '<a href="%1$s">%2$s</a>', add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER[ 'REQUEST_URI' ] ) ) ), wp_nonce_url( 'users.php?action=resendactivation&amp;user_id=' . $user_object->ID, 'resend-activation' ) ), esc_attr__( 'Resend activation', 'b3-onboarding' ) );
                    $actions[ 'activate' ]          = sprintf( '<a href="%1$s">%2$s</a>', add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER[ 'REQUEST_URI' ] ) ) ), wp_nonce_url( 'users.php?action=activate&amp;user_id=' . $user_object->ID, 'manual-activation' ) ), esc_attr__( 'Activate', 'b3-onboarding' ) );
                }
            } elseif ( 'request_access' === $registration_type ) {
                if ( in_array( 'b3_approval', (array) $user_object->roles ) ) {
                    unset( $actions[ 'resetpassword' ] );
                    $actions[ 'activate' ] = sprintf( '<a href="%1$s">%2$s</a>',
                        add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER[ 'REQUEST_URI' ] ) ) ),
                            wp_nonce_url( 'users.php?action=activate&amp;user_id=' . $user_object->ID, 'manual-activation' )
                        ),
                        esc_attr__( 'Activate', 'b3-onboarding' )
                    );
                }
            }
        }

        return $actions;
    }
    add_filter( 'user_row_actions', 'b3_user_row_actions', 10, 2 );


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
        if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
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
    add_filter( 'authenticate', 'b3_maybe_redirect_at_authenticate', 101, 3 );


    /**
     * Filter for banned domains in email validation MU signup
     *
     * @param $result
     *
     * @return array
     */
    function b3_check_domain_user_email( $result ) {
        if ( get_option( 'b3_set_domain_restrictions' ) ) {
            $email         = $result[ 'user_email' ];
            $verify_domain = b3_verify_email_domain( $email );

            if ( false === $verify_domain ) {
                $new_errors = new WP_Error();
                $new_errors->add( 'error_banned_domain', esc_html__( "We're sorry, that domain is blocked from registering.", 'b3-onboarding' ) );

                $result[ 'errors' ][] = $new_errors;
            }
        }

        return $result;
    }
    add_filter( 'wpmu_validate_user_signup', 'b3_check_domain_user_email' );
    
    
    /**
     * Filters out any menu items for registered users/visitors
     *
     * @since 3.10.0
     *
     * @param $items
     * @param $menu
     * @param $args
     *
     * @return mixed
     */
    function b3_filter_nav_menus( $items, $menu, $args ) {
        if ( ! is_admin() ) {
            if ( ! empty( $items ) ) {
                $account_page        = get_option( 'b3_account_page_id' );
                $login_page          = get_option( 'b3_login_page_id' );
                $logout_page         = get_option( 'b3_logout_page_id' );
                $lost_password_page  = get_option( 'b3_lost_password_page_id' );
                $register_page       = get_option( 'b3_register_page_id' );
                $reset_password_page = get_option( 'b3_reset_password_page_id' );
    
                foreach( $items as $key => $menu_values ) {
                    if ( ! is_user_logged_in() && in_array( $menu_values->object_id, [
                            $account_page,
                            $logout_page,
                            $reset_password_page,
                        ] ) ) {
                        unset( $items[ $key ] );
                    } elseif ( is_user_logged_in() && in_array( $menu_values->object_id, [
                            $login_page,
                            $lost_password_page,
                            $register_page,
                            $reset_password_page,
                        ] ) ) {
                        unset( $items[ $key ] );
                    }
                }
            }
        }
        
        return $items;
    }
    add_filter( 'wp_get_nav_menu_items', 'b3_filter_nav_menus', 5, 3 );
    
    
    /**
     * Validates allowed usernames
     *
     * @since 3.11.0
     *
     * @param $valid
     * @param $user_name
     *
     * @return false|mixed
     */
    function b3_check_username( $valid, $user_name ) {
        $disallowed_names = b3_get_disallowed_usernames();

        foreach( $disallowed_names as $name ) {
            // If any disallowed string is in the user_name, mark $valid as false.
            if ( $valid && false !== strpos( $user_name, $name ) ) {
                $valid = false;
            }
        }
        
        return $valid;
    }
    add_filter( 'validate_username', 'b3_check_username', 10, 2 );
    
    
    /**
     * Hide password fields (if magic link is active)
     *
     * @since 3.11.0
     *
     * @param $show
     * @param $current_user
     *
     * @return false|mixed
     */
    function b3_show_password_fields( $show, $current_user ) {
        if ( get_option( 'b3_use_magic_link' ) ) {
            $show = false;
        }
        
        return $show;
    }
    add_filter( 'show_password_fields', 'b3_show_password_fields', 10, 2 );
