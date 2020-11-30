<?php
    /**
     * Validate custom fields
     *
     * @return array
     */
    function b3_extra_fields_validation() {
        $b3_onboarding      = new B3Onboarding();
        $extra_field_values = apply_filters( 'b3_extra_fields', array() );
        $error_array = [];
        if ( ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $field ) {
                if ( ! empty( $field[ 'id' ] ) ) {
                    $field_id   = $field[ 'id' ];
                    $field_type = $field[ 'type' ];
                    if ( true == $field[ 'required' ] ) {
                        if ( in_array( $field_type, [ 'radio', 'checkbox', 'select' ] ) ) {
                            if ( ! isset( $_POST[ $field_id ] ) || ( isset( $_POST[ $field_id ] ) && empty( $_POST[ $field_id ] ) ) ) {
                                $error_code = 'empty_field';
                            }
                        }
                        if ( isset( $error_code ) ) {
                            $error_array[] = [
                                'error_code'    => $error_code,
                                'error_message' => $b3_onboarding->b3_get_return_message( $error_code ),
                                'id'            => $field_id,
                                'label'         => $field[ 'label' ],
                            ];
                        }
                    }
                }
            }
        }

        return $error_array;

    }
    add_filter( 'b3_extra_fields_validation', 'b3_extra_fields_validation' );
    
    
    /**
     * Prevent update registration option
     *
     * @param $new_value
     * @param $old_value
     *
     * @return false|mixed|string|void
     */
    function b3_prevent_update_registration_option( $new_value, $old_value ) {
        $b3_setting = get_option( 'b3_registration_type' );
        if ( is_multisite() && is_main_site() ) {
            if ( 'closed' == $b3_setting ) {
                $b3_setting = 'none';
            } elseif ( 'ms_register_user' == $b3_setting ) {
                $b3_setting = 'user';
            } elseif ( 'ms_loggedin_register' == $b3_setting ) {
                $b3_setting = 'blog';
            } elseif ( 'ms_register_site_user' == $b3_setting ) {
                $b3_setting = 'all';
            }
        } elseif ( ! is_multisite() ) {
            // @TODO: test this
            if ( 'closed' == $b3_setting ) {
                $b3_setting = '0';
            } elseif ( in_array( $b3_setting, [ 'request_access', 'email_activation', 'open' ] ) ) {
                $b3_setting = '1';
            }
        }

        $new_value = $b3_setting;

        return $new_value;
        
    }
    add_filter( 'pre_update_option_users_can_register', 'b3_prevent_update_registration_option', 10, 2 ); // non-multissite
    add_filter( 'pre_update_site_option_registration', 'b3_prevent_update_registration_option', 10, 2 ); // multisite
