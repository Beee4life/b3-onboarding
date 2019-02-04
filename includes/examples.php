<?php
    
    /**
     * Output any hidden fields
     */
    function b3_hidden_fields_registration_form() {
    
        $hidden_fields = '';
        $hidden_field_values = apply_filters( 'b3_filter_hidden_fields', [] );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            foreach( $hidden_field_values as $key => $value ) {
                $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
        }

        echo $hidden_fields;
        
    }
    add_action( 'b3_add_hidden_fields_registration', 'b3_hidden_fields_registration_form' );


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
            'key4' => 'value4',
        ];
        
    }
    // add_filter( 'b3_filter_hidden_fields', 'b3_filter_hidden_fields' );
