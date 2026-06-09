<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    /**
     * Add honeypot field
     *
     * @param $fields
     *
     * @return mixed
     */
    function b3_add_honeypot( $fields ) {
        if ( get_option( 'b3_honeypot' ) ) {
            $id          = 'b3_pooh';
            $input_class = '';

            $fields[] = [
                'container_class' => 'pooh',
                'id'              => $id,
                'input_class'     => $input_class,
                'label'           => false,
                'options'         => [
                    [
                        'label'       => '',
                        'name'        => $id,
                        'value'       => '1',
                    ],
                ],
                'required'        => false,
                'type'            => 'checkbox',
            ];
        }
        return $fields;
    }
    add_filter( 'b3_extra_fields', 'b3_add_honeypot' );

    /**
     * Validate custom fields
     *
     * @return array
     */
    function b3_extra_fields_validation( $error_array = [] ) {
        $b3_onboarding      = new B3Onboarding();
        $extra_field_values = apply_filters( 'b3_extra_fields', [] );

        if ( ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $field ) {
                if ( ! empty( $field[ 'id' ] ) ) {
                    $field_id   = $field[ 'id' ];
                    $field_type = $field[ 'type' ];
                    if ( true == $field[ 'required' ] ) {
                        if ( in_array( $field_type, [ 'radio', 'checkbox', 'select' ] ) ) {
                            // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
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
