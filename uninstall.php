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

    // If preserve settings is false (but there's no user input option yet, so is always false)
    if ( false == get_option( 'b3_preserve_settings', false ) ) {

        if ( function_exists( 'b3_get_all_custom_meta_keys' ) ) {
            $meta_keys   = b3_get_all_custom_meta_keys();
            $meta_keys[] = 'widget_b3-widget';
            foreach( $meta_keys as $key ) {
                delete_site_option( $key );
            }
        }

        $roles = array(
            'b3_activation',
            'b3_approval',
        );
        // @TODO: change user role for users with this role.
        foreach( $roles as $role ) {
            remove_role( $role );
        }

    }
