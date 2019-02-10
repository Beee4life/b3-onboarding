<?php
    
    function b3_do_before_request_access() {
        echo '<p>Before request access<p>';
    }
    // add_action( 'b3_do_before_request_access', 'b3_do_before_request_access' );
    
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
            'key4' => 'value4',
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
