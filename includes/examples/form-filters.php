<?php

    ##################
    ## Form filters ##
    ##################

    /**
     * Add hidden fields to form
     *
     * @since 2.0.0
     *
     * @param $fields
     *
     * @return array
     */
    function b3_hidden_fields( $fields ) {
        if ( ! is_array( $fields ) ) {
            $fields = array();
        }
        $fields[ 'field_id' ] = 'field_value';

        return $fields;
    }
    add_filter( 'b3_hidden_fields', 'b3_hidden_fields' );

    /**
     * Add custom fields to form
     *
     * @since 2.0.0
     *
     * @param $fields
     *
     * @return array
     */
    function b3_extra_fields( $fields ) {
        $container_class = 'container-class';
        $id              = 'id';
        $input_class     = 'input-class';
        $placeholder     = __( 'Placeholder text', 'b3-onboarding' );

        $fields = [
            [
                'container_class' => $container_class,
                'id'              => $id . '1',
                'input_class'     => $input_class,
                'label'           => 'Text',
                'options'         => array(),
                'placeholder'     => $placeholder,
                'required'        => false,
                'type'            => 'text',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '2',
                'input_class'     => $input_class,
                'label'           => 'Textarea',
                'options'         => array(),
                'placeholder'     => $placeholder,
                'required'        => false,
                'type'            => 'textarea',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '3',
                'input_class'     => $input_class,
                'label'           => 'Number',
                'options'         => array(),
                'placeholder'     => $placeholder,
                'required'        => false,
                'type'            => 'number',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '4',
                'input_class'     => $input_class,
                'label'           => 'URL',
                'placeholder'     => $placeholder,
                'required'        => false,
                'type'            => 'url',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '5',
                'input_class'     => $input_class,
                'label'           => 'Radio',
                'options'         => [
                    [
                        'input_class' => $input_class . '--radio',
                        'label'       => 'Radio label 1',
                        'name'        => $id . '5',
                        'value'       => 'value1',
                    ],
                    [
                        'input_class' => $input_class . '--radio',
                        'label'       => 'Radio label 2',
                        'name'        => $id . '5',
                        'value'       => 'value2',
                    ],
                ],
                'required'        => false,
                'type'            => 'radio',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '6',
                'input_class'     => $input_class,
                'label'           => 'Checkbox',
                'options'         => [
                    [
                        'input_class' => $input_class . '--checkbox',
                        'label'       => 'Checkbox label 1',
                        'name'        => $id . '6',
                        'value'       => 'value1',
                    ],
                    [
                        'input_class' => $input_class . '--checkbox',
                        'label'       => 'Checkbox label 2',
                        'name'        => $id . '6',
                        'value'       => 'value2',
                    ],
                ],
                'required'        => false,
                'type'            => 'checkbox',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '7',
                'input_class'     => $input_class,
                'label'           => 'Select',
                'options'         => [
                    [
                        'label' => 'Select label 1',
                        'value' => 'value1',
                    ],
                    [
                        'label' => 'Select label 2',
                        'value' => 'value2',
                    ],
                ],
                'placeholder'     => 'Default/empty first option text',
                'required'        => false,
                'type'            => 'select',
            ],
        ];

        return $fields;
    }
    // add_filter( 'b3_extra_fields', 'b3_extra_fields' );

    /**
     * Override privacy text
     *
     * @since 2.0.0
     *
     * @param $privacy_text
     *
     * @return mixed
     */
    function b3_privacy_text( $privacy_text ) {

        $privacy_text = '<a href="#">Click here</a> for more info';

        return $privacy_text;
    }
    // add_filter( 'b3_privacy_text', 'b3_privacy_text' );

    /**
     * Override closed message
     *
     * @TODO: create user input
     *
     * @since 2.0.0
     *
     * @param $registration_closed_message
     *
     * @return mixed
     */
    function b3_registration_closed_message( $registration_closed_message ) {

        $registration_closed_message = '<a href="#">Click</a> here';

        return $registration_closed_message;
    }
    // add_filter( 'b3_registration_closed_message', 'b3_registration_closed_message' );

    /**
     * Override email styling
     *
     * @since 2.0.0
     *
     * @param $email_styling
     *
     * @return string
     */
    function b3_email_styling( $email_styling ) {

        $email_styling = '<a href="#">Click</a> here';

        return $email_styling;
    }
    // add_filter( 'b3_email_styling', 'b3_email_styling' );

    /**
     * Override email template
     *
     * @since 2.0.0
     *
     * @param $email_template
     *
     * @return string
     */
    function b3_email_template( $email_template ) {

        $email_template = '<a href="#">Click</a> here';

        return $email_template;
    }
    // add_filter( 'b3_email_template', 'b3_email_template' );
