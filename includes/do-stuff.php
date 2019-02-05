<?php
    /**
     * Create initial pages upon activation
     */
    function b3_create_initial_pages() {
        
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            // 'account' => array(
            //     'title' => __( 'Your Account', 'b3-user-register' ),
            //     'content' => '[account]',
            //     'meta' => 'b3_account'
            // ),
            'forgot-password' => array(
                'title'   => __( 'Forgot password', 'b3-user-register' ),
                'content' => '[forgotpass-form]',
                'meta'    => 'forgotpass'
            ),
            'login'           => array(
                'title'   => __( 'Log In', 'b3-user-register' ),
                'content' => '[login-form]',
                'meta'    => 'login'
            ),
            'register'        => array(
                'title'   => __( 'Register', 'b3-user-register' ),
                'content' => '[register-form]',
                'meta'    => 'register'
            ),
            'reset-password'  => array(
                'title'   => __( 'Reset password', 'b3-user-register' ),
                'content' => '[resetpass-form]',
                'meta'    => 'resetpass'
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
                
                if ( is_int( $result ) ) {
                    update_option( 'b3_' . $page[ 'meta' ], $result, true );
                } elseif ( is_wp_error( $result) ) {
                    // @TODO: notify user ?
                }
            }
        }
    }
