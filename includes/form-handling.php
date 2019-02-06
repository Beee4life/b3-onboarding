<?php
    
    /**
     * Handle forgot pass form
     */
    function b3_forgot_pass_form_handling() {
        $show_custom_passwords = get_option( 'b3_custom_passwords' );
        
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_forgot_pass' ] ) ) {
                error_log('hit forgot pass');
                $redirect_url = home_url( 'forgot-password' );
                if ( ! wp_verify_nonce( $_POST[ 'b3_forgot_pass' ], 'b3-forgot-pass' ) ) {
                    // @TODO: add error
                } else {
                    
                    $user_email = ( isset( $_POST[ 'b3_user_email' ] ) ) ? $_POST[ 'b3_user_email' ] : false;
                    if ( true == $show_custom_passwords ) {
                        $pass1 = ( isset( $_POST[ 'pass1' ] ) ) ? $_POST[ 'pass1' ] : false;
                        $pass2 = ( isset( $_POST[ 'pass2' ] ) ) ? $_POST[ 'pass2' ] : false;
                        if ( $pass1 != $pass2 ) {
                            // @TODO: add error
                            $redirect_url = add_query_arg( 'errors', 'password_reset_mismatch', $redirect_url );
                        }
                    } else {
                        
                        $errors = retrieve_password();
                        if ( is_wp_error( $errors ) ) {
                            // Errors found
                            $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
                        } else {
                            // Email sent
                            $redirect_url = home_url( 'login' ); // @TODO: make dynamic/filterable
                            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
                        }
                        
                    }
                    
                    wp_redirect( $redirect_url );
                    exit;
                    
                }
            }
        }
    }
    add_action( 'init', 'b3_forgot_pass_form_handling' );
    
    
    /**
     * Handle reset pass form
     */
    function b3_reset_pass_handling() {
        
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            
            if ( isset( $_REQUEST[ 'rp_key' ] ) && isset( $_REQUEST[ 'rp_login' ] ) ) {
                
                $rp_key   = ( isset( $_REQUEST[ 'rp_key' ] ) ) ? $_REQUEST[ 'rp_key' ] : false;
                $rp_login = ( isset( $_REQUEST[ 'rp_login' ] ) ) ? $_REQUEST[ 'rp_login' ] : false;
                
                $user = check_password_reset_key( $rp_key, $rp_login );
                
                if ( ! $user || is_wp_error( $user ) ) {
                    if ( $user && $user->get_error_code() === 'expired_key' ) {
                        wp_redirect( home_url( 'login/?login=expiredkey' ) ); // @TODO: make dynamic/filterable
                    } else {
                        wp_redirect( home_url( 'login/?login=invalidkey' ) ); // @TODO: make dynamic/filterable
                    }
                    exit;
                }
                
                if ( isset( $_POST[ 'pass1' ] ) ) {
                    if ( $_POST[ 'pass1' ] != $_POST[ 'pass2' ] ) {
                        // Passwords don't match
                        $redirect_url = home_url( 'reset-password' ); // @TODO: make dynamic/filterable
                        $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                        $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                        $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
                        
                        wp_redirect( $redirect_url );
                        exit;
                    }
                    
                    if ( empty( $_POST[ 'pass1' ] ) ) {
                        // Password is empty
                        $redirect_url = home_url( 'reset-password' ); // @TODO: make dynamic/filterable
                        $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                        $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                        $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
                        
                        wp_redirect( $redirect_url );
                        exit;
                    }
                    
                    // Parameter checks OK, reset password
                    reset_password( $user, $_POST[ 'pass1' ] );
                    wp_redirect( home_url( 'login/?password=changed' ) ); // @TODO: make dynamic/filterable
                } else {
                    echo "Invalid request.";
                }
                
                exit;
            }
        }
    }
    
    
    /**
     * Handle user activation
     */
    function b3_activation_handling() {
    }
    
    
    // Admin settings
    
    function b3_settings_form_handling() {
    
        if ( 'POST' == $_SERVER[ 'REQUEST_METHOD' ] ) {
            if ( isset( $_POST[ 'b3_pages_nonce' ] ) ) {
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=pages' );
                if ( ! wp_verify_nonce( $_POST[ "b3_pages_nonce" ], 'b3-pages-nonce' ) ) {
                    // @TODO: add error ?
                } else {
        
                    $loopable_ids = [
                        'b3_account_id',
                        'b3_forgotpass_id',
                        'b3_login_id',
                        'b3_register_id',
                        'b3_resetpass_id',
                    ];
                    foreach( $loopable_ids as $page ) {
                        if ( ! empty( $_POST[ $page ] ) ) {
                            update_option( $page, $_POST[ $page ], true );
                        } else {
                            delete_option( $page );
                        }
                    }
    
                    $redirect_url = add_query_arg( 'success', 'pages_saved', $redirect_url );
                }
    
                wp_redirect( $redirect_url );
                exit;

            } elseif ( isset( $_POST[ 'b3_settings_nonce' ] ) ) {
    
                $redirect_url = admin_url( 'admin.php?page=b3-onboarding&tab=settings' );
                
                if ( ! wp_verify_nonce( $_POST[ "b3_settings_nonce" ], 'b3-settings-nonce' ) ) {
                    $redirect_url = add_query_arg( 'errors', 'nonce_mismatch', $redirect_url );
                } else {

                    // Custom passwords
                    if ( isset( $_POST[ 'b3_activate_custom_passwords' ] ) ) {
                        update_option( 'b3_custom_passwords', '1', true );
                    } else {
                        delete_option( 'b3_custom_passwords' );
                    }
                    
                    // Dashboard widget
                    if ( isset( $_POST[ 'b3_activate_dashboard_widget' ] ) ) {
                        update_option( 'b3_dashboard_widget', '1', true );
                    } else {
                        delete_option( 'b3_dashboard_widget' );
                    }
                    
                    // Sidebar widget
                    if ( isset( $_POST[ 'b3_activate_sidebar_widget' ] ) ) {
                        update_option( 'b3_sidebar_widget', '1', true );
                    } else {
                        delete_option( 'b3_sidebar_widget' );
                    }
                
                    // reCAPTCHA
                    if ( isset( $_POST[ 'b3_activate_recaptcha' ] ) ) {
                        update_option( 'b3_recaptcha', '1', true );
                    } else {
                        delete_option( 'b3_recaptcha' );
                    }
                
                    // Privacy
                    if ( isset( $_POST[ 'b3_activate_privacy' ] ) ) {
                        update_option( 'b3_privacy', '1', true );
                    } else {
                        delete_option( 'b3_privacy' );
                    }
                
                    // reCAPTCHA
                    if ( isset( $_POST[ 'b3_activate_custom_emails' ] ) ) {
                        update_option( 'b3_custom_emails', '1', true );
                    } else {
                        delete_option( 'b3_custom_emails' );
                    }
    
                    $redirect_url = add_query_arg( 'success', 'settings_saved', $redirect_url );
    
                }
    
                wp_redirect( $redirect_url );
                exit;
            }
        }
    }
    add_action( 'admin_init', 'b3_settings_form_handling' );
