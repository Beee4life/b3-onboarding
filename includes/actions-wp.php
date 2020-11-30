<?php
    /*
     * This file contains functions hooked to the WordPress' hooks
     */

    /**
     * Add custom fields to WordPress' default register form
     *
     * @since 1.0.0
     */
    function b3_add_registration_fields() {

        do_action( 'b3_add_first_last_name_fields' );
        do_action( 'b3_add_extra_fields_registration' );
        do_action( 'b3_add_privacy_checkbox' );
        do_action( 'b3_add_recaptcha_fields', 'register' );

    }
    add_action( 'register_form', 'b3_add_registration_fields' );
    add_action( 'b3_register_form', 'b3_add_registration_fields' );


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
        // get registration type
        $registration_type = get_option( 'b3_registration_type', false );
        if ( 'request_access' == $registration_type ) {
            // change user role
            $user_object = new WP_User( $user_id );
            $user_object->set_role( 'b3_approval' );
        } elseif ( 'email_activation' == $registration_type ) {
            $user_object = new WP_User( $user_id );
            $user_object->set_role( 'b3_activation' );
        }
    }
    add_action( 'user_register', 'b3_do_stuff_after_wp_register' );


    /**
     * Add recaptcha to login form
     *
     * @since 2.0.0
     *
     * @param $user_id
     */
    function b3_add_login_form_fields() {
        $show_recaptcha   = get_option( 'b3_recaptcha_login', false );
        if ( $show_recaptcha ) {
            do_action( 'b3_add_recaptcha_fields' );
        }
    }
    add_action( 'login_form', 'b3_add_login_form_fields' );


    /**
     * Add approval to admin bar
     *
     * @since 2.0.0
     *
     * @param $wp_admin_bar
     */
    function b3_add_toolbar( $wp_admin_bar ) {
        if ( current_user_can( 'promote_users' ) ) {
            if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                $approval_args  = array( 'role' => 'b3_approval' );
                $approval_users = get_users( $approval_args );
                if ( 0 < count( $approval_users ) ) {
                    $page_link = admin_url( 'admin.php?page=b3-user-approval' );
                    $approval_args = array(
                        'id'    => 'approval',
                        'title' => '&rarr; ' . __( 'Approve', 'b3-onboarding' ) . ' (' . count( $approval_users ) . ')',
                        'href'  => $page_link,
                        'meta'  => array( 'class' => 'topbar_approve_user' ),
                    );
                    $wp_admin_bar->add_node( $approval_args );
                }
            }
        }
    }
    add_action( 'admin_bar_menu', 'b3_add_toolbar', 9999 );


    /**
     * Remove admin bar for users who are not allowed to access admin
     *
     * @since 2.0.0
     */
    function b3_remove_admin_bar() {
        $hide_admin_bar = get_option( 'b3_hide_admin_bar', false );
        if ( false != $hide_admin_bar ) {
            $restricted_roles = get_option( 'b3_restrict_admin' );
            $user             = wp_get_current_user();
            $result           = ! empty( array_intersect( $restricted_roles, $user->roles ) );

            if ( true == $result ) {
                show_admin_bar( false );
            }
        }
    }
    add_action( 'after_setup_theme', 'b3_remove_admin_bar' );


    /**
     * Do stuff after signup WPMU user
     *
     * @since 2.6.0
     *
     * @param       $user_login
     * @param       $user_email
     * @param       $key
     * @param array $meta
     */
    function b3_after_signup_user( $user_login, $user_email, $key, $meta = array() ) {
        $subject = sprintf( apply_filters( 'b3_wpmu_activate_user_subject', b3_get_wpmu_activate_user_subject() ), get_option( 'blogname' ) );
        $message = sprintf( apply_filters( 'b3_wpmu_activate_user_email', b3_get_wpmu_activate_user_email() ), $user_login, site_url( "wp-activate.php?key=$key" ) );
        $message = b3_replace_template_styling( $message );
        $message = strtr( $message, b3_replace_email_vars() );
        $message = htmlspecialchars_decode( stripslashes( $message ) );

        wp_mail( $user_email, $subject, $message, [] );

    }
    add_action( 'after_signup_user', 'b3_after_signup_user', 11, 4 );
