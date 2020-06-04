<?php
    /**
     * Filter email logo
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_main_logo( $logo ) {

        $logo = B3_PLUGIN_URL . '/assets/images/logo-salesforce.png';

        return $logo;
    }
    // add_filter( 'b3_main_logo', 'b3_main_logo' );

    /**
     * Filter email footer text
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_footer_text( $footer_text ) {

        $footer_text = 'Some test text with a <a href="https://nu.nl">LINK</a>.';

        return $footer_text;
    }
    // add_filter( 'b3_email_footer_text', 'b3_email_footer_text' );

    /**
     * Override link color in email
     *
     * @param $link_color
     *
     * @return string
     */
    function b3_email_link_color( $link_color ) {

        $link_color = '6d32a8'; // purple

        return $link_color;
    }
    // add_filter( 'b3_email_link_color', 'b3_email_link_color' );

    /**
     * Add hidden fields to form
     *
     * @param $fields
     *
     * @return array
     */
    function b3_filter_hidden_fields_values( $fields ) {
        $fields[ 'field_id' ] = 'field_value';

        return $fields;
    }
    // add_filter( 'b3_filter_hidden_fields_values', 'b3_filter_hidden_fields_values' );

    /**
     * Add cusstom fields to form
     *
     * @param $fields
     *
     * @return array
     */
    function b3_add_filter_extra_fields_values( $fields ) {
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
                'options'         => [],
                'placeholder'     => $placeholder,
                'required'        => false,
                'type'            => 'text',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '2',
                'input_class'     => $input_class,
                'label'           => 'Textarea',
                'options'         => [],
                'placeholder'     => $placeholder,
                'required'        => false,
                'type'            => 'textarea',
            ],
            [
                'container_class' => $container_class,
                'id'              => $id . '3',
                'input_class'     => $input_class,
                'label'           => 'Number',
                'options'         => [],
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
    // add_filter( 'b3_add_filter_extra_fields_values', 'b3_add_filter_extra_fields_values' );

    /**
     * Override privacy text
     *
     * @param $privacy_text
     *
     * @return mixed
     */
    function b3_add_privacy_text( $privacy_text ) {

        $privacy_text = '<a href="#">Klik ergens</a> voor meer info';

        return $privacy_text;
    }
    // add_filter( 'b3_add_privacy_text', 'b3_add_privacy_text' );


    /**
     * Set who to inform after (custom) user register
     *
     * @param $inform
     *
     * @return string
     */
    function b3_custom_register_inform( $inform ) {
        return 'none';
    }
    // add_filter( 'b3_custom_register_inform', 'b3_custom_register_inform' );
