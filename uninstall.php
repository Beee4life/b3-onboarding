<?php
    
    // If uninstall.php is not called by WordPress, die
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }
    
    // If preserve settings is false
    if ( false == get_option( 'b3_preserve_settings' ) ) {
        
        $actions = array(
            'b3_account_id',
            'b3_custom_passwords',
            'b3_dashboard_widget',
            'b3_forgotpass_id',
            'b3_login_id',
            'b3_register_id',
            'b3_resetpass_id',
            'b3_sidebar_widget',
        );
        
        foreach ( $actions as $action ) {
            delete_option( $action );
        }
        
        $roles = array(
            'b3_activation',
            'b3_approval',
        );
        
    }
