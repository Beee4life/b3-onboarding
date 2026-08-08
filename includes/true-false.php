<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Check if localhost is defined
    function is_localhost() {
        if ( apply_filters( 'b3_localhost', false ) ) {
            return true;
        } elseif ( true == getenv( 'LOCALHOST' ) ) {
            return true;
        }

        return false;
    }

    function b3_is_site_active( $blog_id ) {
        if ( ! $blog_id ) {
            return false;
        }

        switch_to_blog( $blog_id );
        $awaiting_approval = get_option( 'site_awaiting_approval' );
        restore_current_blog();

        if ( ! $awaiting_approval ) {
            return true;
        }

        return false;
    }
