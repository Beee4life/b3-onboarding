<?php
    
    function b3_get_all_custom_meta_keys() {
    
        $meta_keys = [
            'b3_account_id',
            'b3_forgotpass_id',
            'b3_login_id',
            'b3_notification_sender_name',
            'b3_notification_sender_email',
            'b3_register_id',
            'b3_resetpass_id',
        ];
        
        return $meta_keys;
    }

    function b3_get_email_boxes( $send_password_mail ) {
    
        $default_boxes1 = [
            [
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ],
        ];
        if ( true == $send_password_mail ) {
            $default_boxes1[] = [
                'id'    => 'send_password_mail',
                'title' => esc_html__( 'Send password by email', 'b3-onboarding' ),
            ];
        }
        $default_boxes2 = [];
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
