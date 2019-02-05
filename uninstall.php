<?php
    
    // if uninstall.php is not called by WordPress, die
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }
    
    // Delete stuff
    if ( false == get_option( 'b3_preserve_settings' ) ) {
    }
