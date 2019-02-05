<?php
    /**
     * Create initial pages upon activation
     */
    function b3_create_initial_pages() {
        
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            // 'account' => array(
            //     'title' => esc_html__( 'Your Account', 'b3-onboarding' ),
            //     'content' => '[account]',
            //     'meta' => 'b3_account'
            // ),
            'forgot-password' => array(
                'title'   => esc_html__( 'Forgot password', 'b3-onboarding' ),
                'content' => '[forgotpass-form]',
                'meta'    => 'b3_forgotpass_id'
            ),
            'login'           => array(
                'title'   => esc_html__( 'Log In', 'b3-onboarding' ),
                'content' => '[login-form]',
                'meta'    => 'b3_login_id'
            ),
            'register'        => array(
                'title'   => esc_html__( 'Register', 'b3-onboarding' ),
                'content' => '[register-form]',
                'meta'    => 'b3_register_id'
            ),
            'reset-password'  => array(
                'title'   => esc_html__( 'Reset password', 'b3-onboarding' ),
                'content' => '[resetpass-form]',
                'meta'    => 'b3_resetpass_id'
            ),
        );
        
        foreach ( $page_definitions as $slug => $page ) {
            // Check that the page doesn't exist already
            $query = new WP_Query( 'pagename=' . $slug );
            if ( ! $query->have_posts() ) {
                // Add the page using the data from the array above
                $errors = new WP_Error();
                $result = wp_insert_post( array(
                        'post_title'     => $page[ 'title' ],
                        'post_name'      => $slug,
                        'post_content'   => $page[ 'content' ],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    ),
                    false
                );
                
                if ( ! is_wp_error( $result) ) {
                    update_option( $page[ 'meta' ], $result, true );
                }
            }
        }
    }
