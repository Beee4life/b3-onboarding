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
            $input_options     = ( ! empty( $extra_field[ 'options' ] ) ) ? $extra_field[ 'options' ] : [];

            if ( isset( $extra_field[ 'id' ] ) && isset( $extra_field[ 'label' ] ) && isset( $extra_field[ 'type' ] ) && isset( $extra_field[ 'class' ] ) ) {
    
                ob_start();
                ?>
                <div class="b3__form-element b3__form-element--<?php echo $input_type; ?>">
                    <label class="b3__form-label" for="<?php echo $input_id; ?>"><?php echo $input_label; ?> <?php echo $input_required; ?></label>
                    <?php if ( 'text' == $input_type ) { ?>
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3__form--input<?php echo $input_class; ?>"<?php if ( $input_placeholder && 'text' == $extra_field[ 'type' ] ) { echo $input_placeholder; } ?>value=""<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php } elseif ( 'textarea' == $input_type ) { ?>
                        <textarea name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3__form--input<?php echo $input_class; ?>" value=""<?php if ( $input_placeholder ) { echo $input_placeholder; } ?><?php if ( $input_required ) { echo ' required'; }; ?>></textarea>
                    <?php } elseif ( 'radio' == $input_type ) { ?>
                        <?php if ( $input_options ) { ?>
                            <?php $counter = 1; ?>
                            <?php foreach( $input_options as $option ) { ?>
                                <label for="<?php echo $option[ 'value' ]; ?>_<?php echo $counter; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                <input class="b3__form--input<?php echo $input_class; ?>" id="<?php echo $option[ 'value' ]; ?>_<?php echo $counter; ?>" name="<?php echo $option[ 'name' ]; ?>" type="<?php echo $input_type; ?>" value="<?php echo $option[ 'value' ]; ?>"<?php if ( isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) { echo ' checked="checked"'; } ?>> &nbsp;<?php echo $option[ 'label' ]; ?>
                                <?php $counter++; ?>
                            <?php } ?>
                        <?php } ?>
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
            'b3_sidebar_widget',
            'b3_welcome_user_subject',
            'b3_welcome_user_message',
            'b3_new_user_subject',
            'b3_new_user_message',
        );
        
        return $meta_keys;
    }

    function b3_get_email_boxes() {
        
        $settings_box = array(
            array(
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ),
        );
        $request_access_box = [];
        if ( in_array( get_option( 'b3_registration_type' ), [ 'request_access', 'request_access_subdomain' ] ) ) {
            $request_access_box = array(
                array(
                    'id'    => 'request_access',
                    'title' => esc_html__( 'Request access email', 'b3-onboarding' ),
                ),
            );
        }
        $default_boxes1 = array(
            array(
                'id'    => 'welcome_email_user',
                'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            ),
            array(
                'id'    => 'new_user_admin',
                'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
            ),
        );
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
        $email_boxes = array_merge( $settings_box, $request_access_box, $default_boxes1, $default_boxes2 );
    
        if ( is_multisite() ) {
            // $email_boxes = array_merge( $email_boxes, $default_boxes2 );
        }
        
        return $email_boxes;
    
    }
    
    
    /**
     * Return registration options
     *
     * @return array
     */
    function b3_registration_types() {
        $registration_options = array();

        $closed_option = array(
            array(
                'value' => 'closed',
                'label' => esc_html__( 'Closed (for everyone)', 'b3-onboarding' ),
            ),
        );

        $normal_options = array(
            array(
                'value' => 'request_access',
                'label' => esc_html__( 'Request access (admin approval)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'email_activation',
                'label' => esc_html__( 'Open (user needs to confirm email)', 'b3-onboarding' ),
            ),
            array(
                'value' => 'open',
                'label' => esc_html__( 'Open (user is instantly active)', 'b3-onboarding' ),
            ),
        );

        $multisite_options1 = array(
            array(
                'value' => 'request_access',
                'label' => esc_html__( 'Request access (admin approval)', 'b3-onboarding' ),
            ),
        );
        $multisite_options2 = array(
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
            $registration_options = array_merge( $closed_option, $registration_options, $normal_options );
        } else {
            $mu_registration = get_site_option( 'registration' );
            if ( ! is_main_site() ) {
                if ( 'none' != $mu_registration ) {
                    $registration_options = array_merge( $closed_option, $normal_options );
                }
            } else {
                if ( 'none' != $mu_registration ) {
                    $registration_options = array_merge( $closed_option, $multisite_options2 );
                }
            }
            
        }
        
        return $registration_options;
    }
    
    /**
     * Add field for subdomain when WPMU is active
     */
    function b3_add_subdomain_field() {
        
        if ( 'all' == get_site_option( 'registration' ) && in_array( get_option( 'b3_registration_type') , [ 'request_access_subdomain', 'ms_register_site_user' ] ) ) {
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
    
    /**
     * Return register ID page (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_register_id() {
        $id = get_option( 'b3_register_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
        }
        
        return $id;
        
    }
    
    
    /**
     * Return login page id (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_login_id() {
        $id = get_option( 'b3_login_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
        }
        
        return $id;
        
    }
    
    /**
     * Return forgot pass page id (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_forgotpass_id() {
        $id = get_option( 'b3_forgotpass_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
        }
        
        return $id;
        
    }
    
    /**
     * Return reset pass page id (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_resetpass_id() {
        $id = get_option( 'b3_resetpass_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
        }
        
        return $id;
        
    }
    
    function b3_custom_emails_active() {
        
        if ( false != get_option( 'b3_custom_emails', false ) ) {
            return true;
        }
        
        return false;
    }

    function b3_get_welcome_user_subject( $blogname ) {
        $b3_welcome_user_subject = get_option( 'b3_welcome_user_subject', false );
        if ( $b3_welcome_user_subject ) {
            return $b3_welcome_user_subject;
        } else {
            return sprintf( esc_html__( 'Welcome to %s', 'b3-onboarding' ), $blogname );
        }
    }

    function b3_get_welcome_user_message( $blogname, $user ) {
        $b3_welcome_user_message = get_option( 'b3_welcome_user_message', false );
        if ( $b3_welcome_user_message ) {
            return $b3_welcome_user_message;
        } else {
            return sprintf( esc_html__( 'Welcome %s, your registration to %s was successful. You can set your password here: %s.', 'b3-onboarding' ), $user->user_login, $blogname, get_permalink( b3_get_forgotpass_id() ) );
        }
    }
    
    function b3_get_new_user_subject( $blogname ) {
        $b3_new_user_subject = get_option( 'b3_new_user_subject', false );
        if ( $b3_new_user_subject ) {
            return $b3_new_user_subject;
        } else {
            return sprintf( esc_html__( 'New user at %s', 'b3-onboarding' ), $blogname );
        }
    }
    
    function b3_get_new_user_message( $blogname, $user ) {
        $b3_new_user_message = get_option( 'b3_new_user_message', false );
        if ( $b3_new_user_message ) {
            return $b3_new_user_message;
        } else {
            
            $user_ip      = $ip = $_SERVER[ 'REMOTE_ADDR' ] ? : ( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ? : $_SERVER[ 'HTTP_CLIENT_IP' ] );
            $user_message = sprintf( __( 'A new user registered at %s on %s', 'b3-onboarding' ), $blogname, date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $user->user_registered ) ) ) . "\r\n\r\n";
            $user_message .= sprintf( __( 'Login %s', 'b3-onboarding' ), $user->user_login ) . "\r\n\r\n";
            // $user_message .= sprintf( __( 'IP: %s', 'b3-onboarding' ), $user_ip ) . "\r\n\r\n";
            
            return $user_message;
        }
    }
    
    function b3_get_request_access_subject( $blogname ) {
        $b3_request_access_subject = get_option( 'b3_request_access_subject', false );
        if ( $b3_request_access_subject ) {
            return $b3_request_access_subject;
        } else {
            return sprintf( esc_html__( 'Request for access confirmed for %s', 'b3-onboarding' ), $blogname );
        }
    }
    
    function b3_get_request_access_message( $blogname, $user ) {
        $b3_request_access_message = get_option( 'b3_request_access_message', false );
        if ( $b3_request_access_message ) {
            return $b3_request_access_message;
        } else {
            
            return sprintf( esc_html__( "You have successfully requested access to %s. We'll inform you by email.", "b3-onboarding" ), $blogname );

        }
    }
