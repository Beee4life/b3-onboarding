<?php
    
    function b3_do_before_request_access() {
        echo '<p>You have to request access before you can use this platform.<p>';
    }
    add_action( 'b3_do_before_request_access', 'b3_do_before_request_access' );
    
    function b3_do_after_request_access() {
        echo '<p>After request access<p>';
    }
    // add_action( 'b3_do_after_request_access', 'b3_do_after_request_access' );
    
    /**
     * Hook to output any custom fields/info
     */
    function b3_add_custom_fields_registration() {
        echo '<p>Some custom fields or info</p>';
    }
    // add_action( 'b3_add_custom_fields_registration', 'b3_add_custom_fields_registration' );

    
    /**
     * Hook to output any custom fields/info
     */
    function b3_do_before_registration_form() {
        echo '<p>BEFORE FORM</p>';
    }
    // add_action( 'b3_do_before_registration_form', 'b3_do_before_registration_form' );

    
    /**
     * Hook to output any custom fields/info
     */
    function b3_do_after_registration_form() {
        echo '<p>AFTER FORM</p>';
    }
    // add_action( 'b3_do_after_registration_form', 'b3_do_after_registration_form' );
    
    
    /**
     * @return array
     */
    function b3_do_filter_hidden_fields_values() {
        
        return [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        
    }
    // add_filter( 'b3_do_filter_hidden_fields_values', 'b3_do_filter_hidden_fields_values' );
    
    
    /**
     * @return array
     */
    function b3_add_filter_extra_fields_values() {
    
        $new_fields = array(
            array(
                'id'          => 'id1', // will be forced to lowercase (no spaces)
                'type'        => 'text',
                'label'       => __( 'A value', 'b3-onboarding' ),
                'class'       => 'some_class',
                'placeholder' => 'placeholder',
                'required'    => true,
            ),
            array(
                'id'          => 'id2',
                'type'        => 'textarea',
                'label'       => __( 'Textarea', 'b3-onboarding' ),
                'class'       => 'some_class',
                'placeholder' => 'placeholder',
                'required'    => true,
            ),
        );
        
        return $new_fields;
        
    }
    // add_filter( 'b3_add_filter_extra_fields_values', 'b3_add_filter_extra_fields_values' );
    
    function b3_filter_closed_message() {
        return "A custom 'registration closed' message";
    }
    // add_filter( 'b3_filter_closed_message', 'b3_filter_closed_message' );
    
    function b3_filter_closed_messagex() {
        return "A custom 'registration closed' message";
    }
    // add_filter( 'b3_filter_closed_message', 'b3_filter_closed_message' );
    
    
    /**
     * Override new user notification for admin
     *
     * @param $wp_new_user_notification_email_admin
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_new_user_notification_email_admin( $wp_new_user_notification_email_admin, $user, $blogname ) {
    
        if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
            $wp_new_user_notification_email_admin[ 'to' ]      = get_option( 'admin_email' ); // add filter for override
            $wp_new_user_notification_email_admin[ 'subject' ] = __( 'New user access request', 'b3-onboarding' );
            $wp_new_user_notification_email_admin[ 'message' ] = __( 'A new user has requested access. You can approve/deny him/her in the User approval panel.', 'b3-onboarding' );
        } elseif ( 'open' == get_option( 'b3_registration_type' ) ) {
            // @TODO: add if user wants to receive admin notification on open registration
        }
        
        return $wp_new_user_notification_email_admin;
        
    }
    add_filter( 'wp_new_user_notification_email_admin', 'b3_new_user_notification_email_admin', 10, 3 );
    
    
    /**
     * Override new user notification email for user
     *
     * @param $wp_new_user_notification_email
     * @param $user
     * @param $blogname
     *
     * @return mixed
     */
    function b3_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
    
        global $wpdb;
        
        if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
    
            $wp_new_user_notification_email[ 'subject' ] = sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), $blogname );
            $wp_new_user_notification_email[ 'message' ] = sprintf( esc_html__( "You have successfully requested access to %s. We'll inform you by email.", "b3-onboarding" ), $blogname );
    
        } elseif ( 'email_activation' == get_option( 'b3_registration_type' ) ) {
            // Generate an activation key
            $key = wp_generate_password( 20, false );
    
            // Set the activation key for the user
            $wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user->user_login ) );
    
            $activation_url = add_query_arg( array( 'action' => 'activate', 'key' => $key, 'user_login' => rawurlencode( $user->user_login ) ), home_url( 'login' ) );
    
            $wp_new_user_notification_email[ 'subject' ] = esc_html__( 'Activate your account', 'b3-onboarding' );
            $wp_new_user_notification_email[ 'message' ] = 'Add a link here: ' . $activation_url;
        
        } elseif ( 'open' == get_option( 'b3_registration_type' ) ) {
    
            $wp_new_user_notification_email[ 'subject' ] = sprintf( esc_html__( 'Welcome to %s', 'b3-onboarding' ), $blogname );
            $wp_new_user_notification_email[ 'message' ] = sprintf( esc_html__( 'Welcome %s, your registration to %s was successful. You can now set your password here: %s.', 'b3-onboarding' ), $user->user_login, $blogname, get_permalink( b3_get_forgotpass_id() ) );

        }
        $wp_new_user_notification_email[ 'to' ]      = $user->user_email;
    
        return $wp_new_user_notification_email;
    
    }
    add_filter( 'wp_new_user_notification_email', 'b3_new_user_notification_email', 10, 3 );

    
    function b3_new_user_activated( $user_id ) {
        // Do stuff when user is activated
    }
    add_action( 'b3_new_user_activated', 'b3_new_user_activated' );
    
    function b3_new_user_activated_by_admin( $user_id ) {
        // Do stuff when user is activated by admin
    }
    add_action( 'b3_new_user_activated_by_admin', 'b3_new_user_activated_by_admin' );
