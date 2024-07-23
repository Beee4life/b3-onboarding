<?php
    ##################
    ## Form filters ##
    ##################

    /**
     * Add hidden fields to form (filter only)
     *
     * @since 2.0.0
     *
     * @param $fields
     *
     * @return array
     */
    function b3_hidden_fields_example( $fields ) {
        if ( ! is_array( $fields ) ) {
            $fields = array();
        }
        $fields[ 'field_id' ] = 'field_value';

        return $fields;
    }
    add_filter( 'b3_hidden_fields', 'b3_hidden_fields_example' );


    /**
     * Add custom fields to form (filter only)
     *
     * @since 2.0.0
     *
     * @param $fields
     *
     * @return array
     */
    function b3_extra_fields_example( $fields ) {
        $container_class = 'container-class';
        $id              = 'id';
        $input_class     = 'input-class';
        $placeholder     = esc_attr__( 'Placeholder text', 'b3-onboarding' );

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
    add_filter( 'b3_extra_fields', 'b3_extra_fields_example' );


    /**
     * Filters message before request access form (filter only)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_message_above_request_access_example( $message ) {
        return 'Click here';
    }
    add_filter( 'b3_message_above_request_access', 'b3_message_above_request_access_example' );


    /**
     * Filters message before password reset form (filter only)
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_message_above_lost_password_example( $message ) {
        return 'Your message';
    }
    add_filter( 'b3_message_above_lost_password', 'b3_message_above_lost_password_example' );


    /**
     * Filters message above registration form (filter only)
     *
     * @param $registration_message
     *
     * @return string
     */
    function b3_message_above_registration_example( $registration_message ) {
        return 'Filter registration text';
    }
    add_filter( 'b3_message_above_registration', 'b3_message_above_registration_example' );


    /**
     * Filters message above login form (filter only)
     *
     * @param $login_message
     *
     * @return string
     */
    function b3_message_above_login_example( $login_message ) {
        return 'Filter login text';
    }
    add_filter( 'b3_message_above_login', 'b3_message_above_login_example' );


    /**
     * Override registration closed message (filter only)
     *
     * @since 2.0.0
     *
     * @param $registration_closed_message
     *
     * @return mixed
     */
    function b3_registration_closed_message_example( $registration_closed_message ) {
        return '<a href="#">Click</a> here';
    }
    add_filter( 'b3_registration_closed_message', 'b3_registration_closed_message_example' );


    /**
     * Override privacy text
     *
     * @since 2.0.0
     *
     * @param $privacy_text
     *
     * @return mixed
     */
    function b3_privacy_text_example( $privacy_text ) {
        return '<a href="#">Click here</a> for more info';
    }
    add_filter( 'b3_privacy_text', 'b3_privacy_text_example' );


    /**
     * Override email styling
     *
     * @since 2.0.0
     *
     * @param $email_styling
     *
     * @return string
     */
    function b3_email_styling_example( $email_styling ) {
        return '.body {}';
    }
    add_filter( 'b3_email_styling', 'b3_email_styling_example' );


    /**
     * Override email template
     *
     * @since 2.0.0
     *
     * @param $email_template
     *
     * @return string
     */
    function b3_email_template_example( $email_template ) {
        return '<a href="#">Click</a> here';
    }
    add_filter( 'b3_email_template', 'b3_email_template_example' );


    /**
     * Filter a custom username for localhost development
     *
     * @since 2.0.0
     *
     * @param $username
     *
     * @return string
     */
    function b3_localhost_username_example( $username ) {
        return 'dummy';
    }
    add_filter( 'b3_localhost_username', 'b3_localhost_username_example' );


    /**
     * Filter a custom email address for localhost development
     *
     * @since 2.0.0
     *
     * @param $email
     *
     * @return string
     */
    function b3_localhost_email_example( $email ) {
        return 'filter@email.com';
    }
    add_filter( 'b3_localhost_email', 'b3_localhost_email_example' );


    /**
     * Filter a custom blogname for localhost development (Multisite)
     *
     * @since 2.0.0
     *
     * @param $blogname
     *
     * @return string
     */
    function b3_localhost_blogname_example( $blogname ) {
        return 'blogname';
    }
    add_filter( 'b3_localhost_blogname', 'b3_localhost_blogname_example' );


    /**
     * Filter a custom blog title for localhost development (Multisite))
     *
     * @since 2.0.0
     *
     * @param $email
     *
     * @return string
     */
    function b3_localhost_blogtitle_example( $email ) {
        return 'Dummy Title';
    }
    add_filter( 'b3_localhost_blogtitle', 'b3_localhost_blogtitle_example' );


    /**
     * Disable the admin links
     *
     * @param $email
     *
     * @return string
     */
    function b3_disable_action_links_example( $setting ) {
        return true;
    }
    add_filter( 'b3_disable_action_links', 'b3_disable_action_links_example' );


    /**
     * Extend disallowed usernames
     *
     * @since 2.0
     *
     * @param $existing_disallowed_usernames
     *
     * @return array
     */
    function b3_disallowed_usernames_example( $existing_disallowed_usernames ) {
        $your_disallowed_usernames = [
            'username1',
            'username2',
        ];
        $existing_disallowed_usernames = array_merge( $existing_disallowed_usernames, $your_disallowed_usernames );
    
        return $existing_disallowed_usernames;
    }
    add_filter( 'b3_disallowed_usernames', 'b3_disallowed_usernames_example' );


    /**
     * Override label "A site"
     *
     * @since 3.0
     *
     * @param $label
     *
     * @return mixed
     */
    function b3_signup_for_site( $label ) {
        return $label;
    }
    add_filter( 'b3_signup_for_site', 'b3_signup_for_site' );


    /**
     * Override label "Just a user"
     *
     * @since 3.0
     *
     * @param $label
     *
     * @return mixed
     */
    function b3_signup_for_user( $label ) {
        return $label;
    }
    add_filter( 'b3_signup_for_user', 'b3_signup_for_user' );


