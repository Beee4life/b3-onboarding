<?php
    /**
     * Uninstall functions
     *
     * @since 1.0.0
     */

    // If uninstall.php is not called by WordPress, die
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }

    // If preserve settings is false
    if ( false == get_option( 'b3_preserve_settings', false ) ) {

        $meta_keys = array();
        if ( function_exists( 'b3_get_all_custom_meta_keys' ) ) {
            $meta_keys = b3_get_all_custom_meta_keys();
            foreach( $meta_keys as $key ) {
                delete_option( $key );
            }
        }

        $roles = array(
            'b3_activation',
            'b3_approval',
        );
        foreach( $roles as $role ) {
            remove_role( $role );
        }

    }
