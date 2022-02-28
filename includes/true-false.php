<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Check if localhost is defined (not in use yet)
     *
     * @since 3.1.0
     *
     * @return bool
     */
    function is_localhost() {
        if ( defined( 'LOCALHOST' ) ) {
            if ( true == LOCALHOST ) {
                return true;
            }
        } elseif ( true == getenv( 'LOCALHOST' ) ) {
            return true;
        }

        // @TODO: document
        if ( apply_filters( 'b3_localhost', false ) ) {
            return true;
        }

        return false;
    }
