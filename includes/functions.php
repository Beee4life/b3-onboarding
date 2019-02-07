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

    function b3_get_all_custom_meta_keys() {
    
        $meta_keys = [
            'b3_account_id',
            // 'b3_custom_emails',
            // 'b3_custom_passwords',
            'b3_dashboard_widget',
            'b3_forgotpass_id',
            // 'b3_html_emails',
            'b3_login_id',
            // 'b3_mail_sending_method',
            // 'b3_add_br_html_email',
            'b3_notification_sender_name',
            'b3_notification_sender_email',
            'b3_register_id',
            'b3_resetpass_id',
            'b3_sidebar_widget',
        ];
        
        return $meta_keys;
    }

    function b3_get_email_boxes( $send_password_mail ) {
    
        $email_boxes = [];
        
        $default_boxes1 = [
            [
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ],
            // [
            //     'id'    => 'welcome_email_user',
            //     'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            // ],
            // [
            //     'id'    => 'new_user_admin',
            //     'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
            // ],
        ];
        if ( true == $send_password_mail ) {
            $default_boxes1[] = [
                'id'    => 'send_password_mail',
                'title' => esc_html__( 'Send password by email', 'b3-onboarding' ),
            ];
        }
        $default_boxes2 = [
            // [
            //     'id'    => 'forgot_password',
            //     'title' => esc_html__( 'Forgot password email', 'b3-onboarding' ),
            // ],
            // [
            //     'id'    => 'password_changed',
            //     'title' => esc_html__( 'Reset password email', 'b3-onboarding' ),
            // ],
        ];
        $email_boxes = array_merge( $default_boxes1, $default_boxes2 );
    
        if ( is_multisite() ) {
            $multisite_boxes = [
                [
                    'id'    => 'email_settings',
                    'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
                ],
                [
                    'id'    => 'welcome_email_user',
                    'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
                ],
                [
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                ],
            ];
            $email_boxes = array_merge( $email_boxes, $multisite_boxes );
        }
        
        return $email_boxes;
    
    }
