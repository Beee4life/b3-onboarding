<?php
    /*
     * This file contains functions hooked to the WordPress' hooks
     */
    
    /**
     * Update usermeta after user register
     *
     * @since 1.0.0
     *
     * @param $user_id
     */
    function b3_update_user_meta_after_register( $user_id ) {
        if ( ! empty( $_POST[ 'first_name' ] ) ) {
            update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST[ 'first_name' ] ) );
        }
        if ( ! empty( $_POST[ 'last_name' ] ) ) {
            update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST[ 'last_name' ] ) );
        }

        $extra_field_values = apply_filters( 'b3_extra_fields', array() );
        if ( ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $field ) {
                if ( isset( $field[ 'id' ] ) ) {
                    if ( ! empty( $_POST[ $field[ 'id' ] ] ) ) {
                        update_user_meta( $user_id, $field[ 'id' ], $_POST[ $field[ 'id' ] ] );
                    }
                }
            }
        }

        $hidden_field_values = apply_filters( 'b3_hidden_fields', array() );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            foreach( $hidden_field_values as $meta_key => $meta_value ) {
                update_user_meta( $user_id, $meta_key, $meta_value );
            }
        }

        if ( isset( $_POST[ 'b3_privacy_accept' ] ) && 1 == $_POST[ 'b3_privacy_accept' ] ) {
            update_user_meta( $user_id, 'privacy_accept', true );
        }
    }
    add_action( 'user_register', 'b3_update_user_meta_after_register' );


    /**
     * Do stuff after user registers.
     *
     * @since 1.0.0
     *
     * @param $user_id
     */
    function b3_do_stuff_after_wp_register( $user_id ) {
        if ( isset( $_POST[ 'action' ] ) && 'createuser' == $_POST[ 'action' ] ) {
            // user is manually added
        } else {
            // get registration type
            $registration_type = get_option( 'b3_registration_type' );
            if ( 'request_access' == $registration_type ) {
                $user_object = new WP_User( $user_id );
                $user_object->set_role( 'b3_approval' );
            } elseif ( 'email_activation' == $registration_type ) {
                $user_object = new WP_User( $user_id );
                $user_object->set_role( 'b3_activation' );
            }
        }
    }
    add_action( 'user_register', 'b3_do_stuff_after_wp_register' );


    /**
     * Add approval to admin bar
     *
     * @since 2.0.0
     *
     * @param $wp_admin_bar
     */
    function b3_add_toolbar( $wp_admin_bar ) {
        if ( current_user_can( 'promote_users' ) ) {
            if ( in_array( get_option( 'b3_registration_type' ), [ 'request_access', 'request_access_subdomain' ] ) ) {
                $approval_users = [];
                if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                    $approval_args  = array( 'role' => 'b3_approval' );
                    $approval_users = get_users( $approval_args );
                } elseif ( 'request_access_subdomain' == get_option( 'b3_registration_type' ) ) {
                    global $wpdb;
                    $query = "SELECT * FROM $wpdb->signups WHERE active = '0'";
                    $approval_users = $wpdb->get_results( $query );
                }

                if ( 0 < count( $approval_users ) ) {
                    $page_link = admin_url( 'admin.php?page=b3-user-approval' );
                    $approval_args = array(
                        'id'    => 'approval',
                        'title' => '&rarr; ' . esc_attr__( 'Approve', 'b3-onboarding' ) . ' (' . count( $approval_users ) . ')',
                        'href'  => $page_link,
                        'meta'  => array( 'class' => 'topbar_approve_user' ),
                    );
                    $wp_admin_bar->add_node( $approval_args );
                }
            }
        }
    }
    add_action( 'admin_bar_menu', 'b3_add_toolbar', 80 );


    /**
     * Remove admin bar for users who are not allowed to access admin
     *
     * @since 2.0.0
     */
    function b3_remove_admin_bar() {
        if ( ! is_multisite() ) {
            $hide_admin_bar = get_option( 'b3_hide_admin_bar' );
            if ( false != $hide_admin_bar ) {
                $user             = wp_get_current_user();
                $restricted_roles = get_option( 'b3_restrict_admin' );
                $result           = ! empty( array_intersect( $restricted_roles, $user->roles ) );

                if ( true == $result ) {
                    show_admin_bar( false );
                }
            }
        }
    }
    add_action( 'after_setup_theme', 'b3_remove_admin_bar' );


    /**
     * Do stuff after signup WPMU user (only)
     *
     * @since 3.0
     *
     * @param       $user_login
     * @param       $user_email
     * @param       $key
     * @param array $meta
     */
    function b3_after_signup_user( $user_login, $user_email, $key, $meta = array() ) {
        if ( ! is_admin() ) {
            $current_network = get_network();
            $subject         = sprintf( apply_filters( 'b3_wpmu_activate_user_subject', b3_get_wpmu_activate_user_subject() ), $current_network->site_name );
            $message         = sprintf( apply_filters( 'b3_wpmu_activate_user_message', b3_get_wpmu_activate_user_message() ), $user_login, b3_get_login_url() . "?activate=user&key={$key}" );
            $message         = b3_replace_template_styling( $message );
            $message         = strtr( $message, b3_replace_email_vars() );
            $message         = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $user_email, $subject, $message, [] );
        }
    }
    add_action( 'after_signup_user', 'b3_after_signup_user', 11, 4 );


    /**
     * Do stuff after activate user (only)
     *
     * @since 3.0
     *
     * @param       $user_id
     * @param       $password
     * @param array $meta
     */
    function b3_after_activate_user( $user_id, $password, $meta = array() ) {
        // @TODO: check if can be replaced by filter
        $current_network = get_network();
        $user            = get_userdata( $user_id );
        $subject         = sprintf( apply_filters( 'b3_wpmu_user_activated_subject', b3_get_wpmu_user_activated_subject() ), $current_network->site_name, $user->user_login );
        $message         = sprintf( apply_filters( 'b3_wpmu_user_activated_message', b3_get_wpmu_user_activated_message() ), $user->user_login, $user->user_login, $password, b3_get_login_url(), $current_network->site_name );
        $message         = b3_replace_template_styling( $message );
        $message         = strtr( $message, b3_replace_email_vars() );
        $message         = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user->user_email, $subject, $message, [] );
    }
    add_action( 'wpmu_activate_user', 'b3_after_activate_user', 10, 3 );


    /**
     * Override activate new wpmu user + blog message
     *
     * @param $domain
     * @param $path
     * @param $title
     * @param $user_login
     * @param $user_email
     * @param $key
     */
    function b3_override_new_mu_user_blog_email( $domain, $path, $title, $user_login, $user_email, $key ) {
        if ( 'request_access_subdomain' == get_option( 'b3_registration_type' ) ) {
            $subject = b3_default_request_access_subject_user();
            $message = b3_default_request_access_message_user();
            do_action( 'b3_inform_admin', 'request_access' );

        } else {
            $blog_id = b3_get_signup_id( $domain );
            $subject = strtr( b3_get_wpmu_activate_user_blog_subject(), b3_replace_subject_vars( array( 'blog_id' => $blog_id ) ) );
            $message = b3_get_wpmu_activate_user_blog_message();
        }
        $message      = b3_replace_template_styling( $message );
        $message      = strtr( $message, b3_replace_email_vars( array( 'domain' => $domain, 'key' => $key, 'path' => $path ), true ) );
        $message      = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user_email, $subject, $message, [] );
    }
    add_action( 'after_signup_site', 'b3_override_new_mu_user_blog_email', 10, 6 );


    /**
     * Override welcome mu user email message
     *
     * @param $blog_id
     * @param $user_id
     * @param $password
     * @param $title
     * @param $meta
     */
    function b3_override_welcome_mu_user_blog_message( $blog_id, $user_id, $password, $title, $meta ) {
        $user_data = get_userdata( $user_id );
        $subject   = strtr( b3_get_wpmu_activated_user_blog_subject(), b3_replace_subject_vars( array( 'blog_id' => $blog_id ) ) );
        $message   = b3_get_wpmu_activated_user_blog_message( $user_data->user_login );
        $message   = b3_replace_template_styling( $message );
        $message   = strtr( $message, b3_replace_email_vars( array( 'blog_id' => $blog_id, 'user_data' => $user_data, 'user_password' => $password ) ) );
        $message   = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user_data->user_email, $subject, $message, [] );
    }
    add_action( 'wpmu_activate_blog', 'b3_override_welcome_mu_user_blog_message', 10, 5 );


    /**
     * Network admin notices
     */
    function b3_network_admin_notices() {
        if ( 'settings-network' == get_current_screen()->id ) {
            echo sprintf( '<div class="notice notice-info"><p>'. esc_html__( "%s overrides the 'Registration' option and the 'Registration notification'. You can change the registration type %s and the registration notification %s.", 'b3-onboarding' ) . '</p></div>',
                'B3 OnBoarding',
                sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=registration' ) ), esc_html__( 'here', 'b3-onboarding' ) ),
                sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) ), esc_html__( 'here', 'b3-onboarding' ) )
            );
        }

        $plugin = get_plugin_data( B3OB_PLUGIN_PATH . '/B3Onboarding.php' );
        if ( strpos( $plugin[ 'Version' ], 'dev' ) !== false || strpos( $plugin[ 'Version' ], 'beta' ) !== false ) {
            $warning_message = sprintf( esc_html__( "You're using a development version of %s, which has not been released yet and can give some unexpected results.", 'b3-onboarding' ), 'B3 OnBoarding' );
            $notice          = sprintf( '<div class="notice notice-warning"><p>%s</p></div>', $warning_message );
            echo apply_filters( 'b3_hide_development_notice', $notice );
        }
    }
    add_action( 'network_admin_notices', 'b3_network_admin_notices' );
