<?php
    
    ###########
    # ACTIONS #
    ###########
    
    /* BEFORE */
    
    /**
     * Echo something custom before the register form
     */
    function b3_do_before_register() {
        echo '<p>Echo something before the register form.<p>';
    }
    // add_action( 'b3_do_before_register', 'b3_do_before_register' );
    
    /**
     * Echo something custom before the request access form
     */
    function b3_do_before_request_access() {
        echo '<p>You have to request access before you can use this platform.<p>';
    }
    // add_action( 'b3_do_before_request_access', 'b3_do_before_request_access' );
    
    /**
     * Echo something custom after the register form
     */
    /* AFTER */
    function b3_do_after_register() {
        echo '<p>Echo something after the register form.<p>';
    }
    // add_action( 'b3_do_after_register', 'b3_do_after_register' );
    
    /**
     * Echo something custom after the request access form
     */
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
     * Do stuff after new user is activated
     *
     * @param $user_id
     */
    function b3_do_stuff_after_new_user_activated( $user_id ) {
        // Do stuff when user is activated
    }
    // add_action( 'b3_new_user_activated', 'b3_do_stuff_after_new_user_activated' );
    
    /**
     * Do stuff after new user is activated by admin
     *
     * @param $user_id
     */
    function b3_do_stuff_after_new_user_activated_by_admin( $user_id ) {
        // Do stuff when user is activated by admin
    }
    // add_action( 'b3_new_user_activated_by_admin', 'b3_do_stuff_after_new_user_activated_by_admin' );
    
    
    ###########
    # FILTERS #
    ###########
    
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

    /**
     * Override welcome user message
     *
     * @param $blogname
     *
     * @return string
     */
    function b3_override_welcome_user_message( $message, $user ) {
        return 'This is test content';
    }
    add_filter( 'b3_welcome_user_message', 'b3_override_welcome_user_message', 10, 2 );

    /**
     * Filter for message before request access
     *
     * @param $message
     *
     * @return string
     */
    function b3_filter_before_request_access( $message ) {
        return esc_html__( 'This is a custom text before the request access form.', 'b3-onboarding' );
    }
    // add_filter( 'b3_filter_before_request_access', 'b3_filter_before_request_access' );

    /**
     * Add hidden fields
     *
     * @return array
     */
    function b3_do_filter_hidden_fields_values( $default = array() ) {
    
        $hidden_fields = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        
        return $hidden_fields;
        
    }
    // add_filter( 'b3_do_filter_hidden_fields_values', 'b3_do_filter_hidden_fields_values' );
    
    /**
     * Add extra field values
     *
     * @return array
     */
    function b3_add_filter_extra_fields_values( $default = array() ) {
    
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
