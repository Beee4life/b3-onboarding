<?php
    /**
     * Filter email logo
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_logo( $logo ) {

        $logo = B3_PLUGIN_URL . '/assets/images/logo-salesforce.png';

        return $logo;
    }
    // add_filter( 'b3_email_logo', 'b3_email_logo' );

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
        $fields = [
            [
                'container_class' => 'container_class',
                'id'              => 'id1',
                'input_class'     => 'input_class',
                'label'           => 'text',
                'options'         => [],
                'placeholder'     => 'placeholder',
                'required'        => false,
                'type'            => 'text',
            ],
            [
                'container_class' => 'container_class',
                'id'              => 'id2',
                'input_class'     => 'input_class',
                'label'           => 'textarea',
                'options'         => [],
                'placeholder'     => 'placeholder',
                'required'        => false,
                'type'            => 'textarea',
            ],
            [
                'container_class' => 'container_class',
                'id'              => 'id3',
                'input_class'     => 'input_class',
                'label'           => 'number',
                'options'         => [],
                'placeholder'     => 'placeholder',
                'required'        => false,
                'type'            => 'number',
            ],
            [
                'container_class' => 'container_class',
                'id'              => 'id5',
                'input_class'     => 'input_class',
                'label'           => 'url',
                'placeholder'     => 'placeholder',
                'required'        => false,
                'type'            => 'url',
            ],
            [
                'container_class' => 'container_class',
                'id'              => 'id4',
                'input_class'     => 'input_class',
                'label'           => 'radio',
                'options'         => [
                    [
                        'input_class' => 'input_class1',
                        'label'      => 'option label 1',
                        'name'       => 'name1',
                        'value'       => 'value1',
                    ],
                ],
                'required'        => false,
                'type'            => 'radio',
            ],
            [
                'container_class' => 'container_class',
                'id'              => 'id5',
                'input_class'     => 'input_class',
                'label'           => 'checkbox',
                'options'         => [
                    [
                        'input_class' => 'input_class',
                        'label'      => 'checkbox1',
                        'name'       => 'name',
                        'value'       => 'value',
                    ],
                    [
                        'input_class' => 'input_class',
                        'label'      => 'checkbox2',
                        'name'       => 'name',
                        'value'       => 'value',
                    ],
                ],
                'required'        => false,
                'type'            => 'checkbox',
            ],
        ];

        return $fields;
    }
    // add_filter( 'b3_add_filter_extra_fields_values', 'b3_add_filter_extra_fields_values' );
