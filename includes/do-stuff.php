<?php
    /**
     * Create initial pages upon activation
     */
    function b3_create_initial_pages() {
        
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'account' => array(
                'title'   => esc_html__( 'Account', 'b3-onboarding' ),
                'content' => '[account]',
                'meta'    => 'b3_account_page_id'
            ),
            'lostpassword' => array(
                'title'   => esc_html__( 'Forgot password', 'b3-onboarding' ),
                'content' => '[forgotpass-form]',
                'meta'    => 'b3_forgotpass_page_id'
            ),
            'login'           => array(
                'title'   => esc_html__( 'Login', 'b3-onboarding' ),
                'content' => '[login-form]',
                'meta'    => 'b3_login_page_id'
            ),
            'register'        => array(
                'title'   => esc_html__( 'Register', 'b3-onboarding' ),
                'content' => '[register-form]',
                'meta'    => 'b3_register_page_id'
            ),
            'reset-password'  => array(
                'title'   => esc_html__( 'Reset password', 'b3-onboarding' ),
                'content' => '[resetpass-form]',
                'meta'    => 'b3_resetpass_page_id'
            ),
        );
        
        foreach ( $page_definitions as $slug => $page ) {

            // Check if there's a page assigned already
            $stored_id = get_option( $slug, false );
            if ( $stored_id ) {
                $check_page = get_post( $stored_id );
                if ( ! $check_page ) {
                    delete_option( $slug );
                }
            } else {
                // no stored id, so continue
            }
    
            $post_id = false;
            $query   = new WP_Query( 'pagename=' . $slug );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) : $query->the_post();
                    $post_id = get_the_ID();
                    break;
                endwhile;
            }
            
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
                // if page doesn't return an error (thus successful)
                if ( ! is_wp_error( $result) ) {
                    update_option( $page[ 'meta' ], $result, true );
                } else {
                    // if page does return an error (thus a page exists with $slug)
                    $alternative_result = wp_insert_post( array(
                            'post_title'     => $page[ 'title' ],
                            'post_name'      => $slug . '-2',
                            'post_content'   => $page[ 'content' ],
                            'post_status'    => 'publish',
                            'post_type'      => 'page',
                            'ping_status'    => 'closed',
                            'comment_status' => 'closed',
                        ),
                        false
                    );
                    if ( ! is_wp_error( $alternative_result) ) {
                        update_option( $page[ 'meta' ], $alternative_result, true );
                    }
                }

            } else {
                // page exists
                update_option( $page[ 'meta' ], $post_id, true );
            }
        }
    }
