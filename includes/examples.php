<?php
    
    /**
     * Hook to output any custom fields/info
     */
    function b3_add_custom_fields() {
        echo '<p>Something custom</p>';
    }
    // add_action( 'b3_add_custom_fields_registration', 'b3_add_custom_fields' );

    
    /**
     * Hook to output any custom fields/info
     */
    function b3_before_registration_form() {
        echo '<p>BEFORE FORM</p>';
    }
    // add_action( 'b3_before_registration_form', 'b3_before_registration_form' );

    
    /**
     * Hook to output any custom fields/info
     */
    function b3_after_registration_form() {
        echo '<p>AFTER FORM</p>';
    }
    // add_action( 'b3_after_registration_form', 'b3_after_registration_form' );

    
    function b3_filter_hidden_fields() {
        return [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
    }
    // add_filter( 'b3_filter_hidden_fields', 'b3_filter_hidden_fields' );
