<?php
    
    /**
     * Output the password fields
     */
    function b3_show_password_fields() {
        
        ob_start();
        ?>
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass1"><?php _e( 'Password', 'b3-onboarding' ); ?></label>
            <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3__form--input" />
        </div>
        
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass2"><?php _e( 'Confirm Password', 'b3-onboarding' ); ?></label>
            <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3__form--input" />
        </div>
        <?php
        $results = ob_get_clean();
        echo $results;
    }
    
    /**
     * Output any hidden fields
     */
    function b3_hidden_fields_registration_form() {
        
        $hidden_fields = '';
        $hidden_field_values = apply_filters( 'b3_do_filter_hidden_fields_values', [] );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            foreach( $hidden_field_values as $key => $value ) {
                $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
            }
        }
        
        echo $hidden_fields;
        
    }
    
    
    /**
     * Output any extra request fields
     */
    function b3_extra_fields_registration() {
        
        $extra_fields       = '';
        $extra_field_values = apply_filters( 'b3_add_filter_extra_fields_values', [] );
        if ( is_array( $extra_field_values ) && ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $extra_field ) {
                echo b3_render_extra_field( $extra_field );
            }
        }
        
        echo $extra_fields;
        
    }
    
    
    /**
     * Render any extra fields
     *
     * @param bool $extra_field
     *
     * @return bool|false|string
     */
    function b3_render_extra_field( $extra_field = false ) {
        
        if ( false != $extra_field ) {
    
            $input_id          = ( ! empty( $extra_field[ 'id' ] ) ) ? $extra_field[ 'id' ] : false;
            $input_class       = ( ! empty( $extra_field[ 'class' ] ) ) ? ' ' . $extra_field[ 'class' ] : false;
            $input_label       = ( ! empty( $extra_field[ 'label' ] ) ) ? $extra_field[ 'label' ] : false;
            $input_placeholder = ( ! empty( $extra_field[ 'placeholder' ] ) ) ? ' placeholder="' . $extra_field[ 'placeholder' ] . '"' : false;
            $input_required    = ( ! empty( $extra_field[ 'required' ] ) ) ? ' <span class="b3__required"><strong>*</strong></span>' : false;
            $input_type        = ( ! empty( $extra_field[ 'type' ] ) ) ? $extra_field[ 'type' ] : false;

            if ( isset( $extra_field[ 'id' ] ) && isset( $extra_field[ 'label' ] ) && isset( $extra_field[ 'type' ] ) && isset( $extra_field[ 'class' ] ) ) {
    
                ob_start();
                ?>
                <div class="b3__form-element b3__form-element--<?php echo $input_type; ?>">
                    <label class="b3__form-label" for="<?php echo $input_id; ?>"><?php echo $input_label; ?> <?php echo $input_required; ?></label>
                    <?php if ( 'textarea' != $input_type ) { ?>
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3__form--input<?php echo $input_class; ?>"<?php if ( $input_placeholder && 'text' == $extra_field[ 'type' ] ) { echo $input_placeholder; } ?>value=""<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php } else { ?>
                        <textarea name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3__form--input<?php echo $input_class; ?>" value=""<?php if ( $input_placeholder ) { echo $input_placeholder; } ?><?php if ( $input_required ) { echo ' required'; }; ?>></textarea>
                    <?php } ?>
                </div>
                <?php
                $output = ob_get_clean();
    
                return $output;
            }
        }
        
        return false;
    }


    /**
     * @param $recaptcha_public
     * @param string $form_type
     */
    function b3_add_captcha_registration( $recaptcha_public, $form_type = 'register' ) {
        
        do_action( 'b3_before_recaptcha_' . $form_type );
        ?>
        <div class="recaptcha-container">
            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
        </div>
        <p></p>
        <?php
        do_action( 'b3_after_recaptcha_' . $form_type );
    }


    function b3_get_all_custom_meta_keys() {
    
        $meta_keys = array(
            'b3_account_id',
            'b3_custom_emails',
            'b3_custom_passwords',
            'b3_dashboard_widget',
            'b3_forgotpass_page_id',
            'b3_html_emails',
            'b3_login_page_id',
            'b3_mail_sending_method',
            'b3_add_br_html_email',
            'b3_notification_sender_name',
            'b3_notification_sender_email',
            'b3_register_page_id',
            'b3_resetpass_page_id',
            'b3_sidebar_widget'
        );
        
        return $meta_keys;
    }

    function b3_get_email_boxes( $send_password_mail ) {
        
        $default_boxes1 = array(
            array(
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ),
            // array(            //     'id'    => 'welcome_email_user',
            //     'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            // ),
            // array(            //     'id'    => 'new_user_admin',
            //     'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
            // ),
        );
        if ( true == $send_password_mail ) {
            $default_boxes1[] = array(
                'id'    => 'send_password_mail',
                'title' => esc_html__( 'Send password by email', 'b3-onboarding' ),
            );
        }
        $default_boxes2 = array(
            // array(
            //     'id'    => 'forgot_password',
            //     'title' => esc_html__( 'Forgot password email', 'b3-onboarding' ),
            // ),
            // array(
            //     'id'    => 'password_changed',
            //     'title' => esc_html__( 'Reset password email', 'b3-onboarding' ),
            // ),
        );
        $email_boxes = array_merge( $default_boxes1, $default_boxes2 );
    
        if ( is_multisite() ) {
            $multisite_boxes = array(
                array(
                    'id'    => 'email_settings',
                    'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'welcome_email_user',
                    'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
                ),
                array(
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                ),
            );
            $email_boxes = array_merge( $email_boxes, $multisite_boxes );
        }
        
        return $email_boxes;
    
    }
    
    
    /**
     * Return registration options
     *
     * @return array
     */
    function b3_registration_types() {
        $registration_options = array(
            array(
                'value' => 'closed',
                'label' => esc_html__( 'Closed (for everyone)', 'b3-onboarding' ),
            ),
        );

        $normal_options = array(
            array(
                'value' => 'request_access',
                'label' => esc_html__( 'Request access (admin needs to approve user registration)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'open',
                'label' => esc_html__( 'Open (user is instantly active)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'email_activation',
                'label' => esc_html__( 'Open (user needs to confirm email)', 'b3-onboarding' ),
            ),
        );

        $multisite_options = array(
            array(
                'value' => 'request_access',
                'label' => esc_html__( 'Request access (admin approval)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'request_access_subdomain',
                'label' => esc_html__( 'Request access (admin approval + user domain request)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'ms_loggedin_register',
                'label' => esc_html__( 'Logged in user may register a site', 'b3-onboarding' ),
            ),
            array(
                'value' => 'ms_register_user',
                'label' => esc_html__( 'Visitor may register user', 'b3-onboarding' ),
            ),
            array(
                'value' => 'ms_register_site_user',
                'label' => esc_html__( 'Visitor may register user + site', 'b3-onboarding' ),
            ),
        );

        if ( ! is_multisite() ) {
            $registration_options = array_merge( $registration_options, $normal_options );
        } else {
            if ( ! is_main_site() ) {
                $registration_options = array_merge( $registration_options, $normal_options );
            } else {
                $registration_options = array_merge( $registration_options, $multisite_options );
            }
            
        }
        
        return $registration_options;
    }
    
    function b3_add_subdomain_field() {
        
        if ( 'request_access_subdomain' == get_option( 'b3_registration_type' ) ) {
            ob_start();
            ?>
            <?php // @TODO: add more fields for Multisite ?>
            <div class="b3__form-element b3__form-element--register">
                <label class="b3__form-label" for="b3_subdomain"><?php esc_html_e( 'Desired (sub) domain', 'b3-user-register' ); ?></label>
                <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3__form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-user-register' ); ?>        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
            </div>
            <?php
            $output = ob_get_clean();
    
            echo $output;
        }
        
    }
