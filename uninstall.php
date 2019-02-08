<?php
    
    // If uninstall.php is not called by WordPress, die
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }
    
    // If preserve settings is false
    if ( false == get_option( 'b3_preserve_settings' ) ) {
        $meta_keys = b3_get_all_custom_meta_keys();
        foreach ( $meta_keys as $key ) {
            delete_option( $key );
        }
    }
