<?php
    
    ###########
    # ACTIONS #
    ###########
    
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
        echo '<p>Add something before form</p>';
    }
    // add_action( 'b3_do_before_registration_form', 'b3_do_before_registration_form' );

    
    /**
     * Hook to output any custom fields/info
     */
    function b3_do_after_registration_form() {
        echo '<p>AFTER FORM</p>';
    }
    // add_action( 'b3_do_after_registration_form', 'b3_do_after_registration_form' );
    
    
    function b3_do_stuff_after_new_user_activated( $user_id ) {
        // Do stuff when user is activated
    }
    // add_action( 'b3_new_user_activated', 'b3_do_stuff_after_new_user_activated' );
    
    function b3_do_stuff_after_new_user_activated_by_admin( $user_id ) {
        // Do stuff when user is activated by admin
    }
    // add_action( 'b3_new_user_activated_by_admin', 'b3_do_stuff_after_new_user_activated_by_admin' );
    
    
    ###########
    # FILTERS #
    ###########
    
    /**
     * @return array
     */
    function b3_do_filter_hidden_fields_values() {
    
        $hidden_fields = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        
        return $hidden_fields;
        
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
                'label'       => 'A value',
                'class'       => 'some_class',
                'placeholder' => 'placeholder',
                'required'    => true,
            ),
            array(
                'id'          => 'id2',
                'type'        => 'textarea',
                'label'       => 'Textarea',
                'class'       => 'some_class',
                'placeholder' => 'placeholder',
                'required'    => true,
            ),
        );
        
        return $new_fields;
        
    }
    // add_filter( 'b3_add_filter_extra_fields_values', 'b3_add_filter_extra_fields_values' );
    
    /**
     * Override custom closed message
     *
     * @return string
     */
    function b3_override_filter_closed_message() {
        return "A custom 'registration closed' message";
    }
    // add_filter( 'b3_filter_closed_message', 'b3_override_filter_closed_message' );
    
    /**
     * @param $blogname
     *
     * @return mixed|string
     */
    function b3_override_welcome_user_subject( $blogname ) {
        return sprintf( esc_html__( 'Welcome to %s', 'b3-onboarding' ), $blogname );
    }
    // add_filter( 'b3_welcome_user_subject', 'b3_override_welcome_user_subject' );
    
    
    function b3_override_welcome_user_message( $blogname ) {
        return 'This is test content';
    }
    // add_filter( 'b3_welcome_user_message', 'b3_override_welcome_user_message' );
