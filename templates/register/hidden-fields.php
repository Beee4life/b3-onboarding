<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    $hidden_field_values = apply_filters( 'b3_hidden_fields', [] );
    if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
        $hidden_fields = '';
        foreach( $hidden_field_values as $key => $value ) {
            $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
        }
        echo $hidden_fields;
    }
    if ( is_multisite() && 'blog' == get_option( 'b3_registration_type' ) ) {
        echo '<input type="hidden" name="signup_for" value="blog" />';
    }
