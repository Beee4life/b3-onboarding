<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Check if localhost is defined
     *
     * @since 3.1.0
     *
     * @return bool
     */
    function is_localhost() {
        if ( defined( 'LOCALHOST' ) && true == LOCALHOST ) {
            return true;
        } elseif ( true == getenv( 'LOCALHOST' ) ) {
            return true;
        } elseif ( apply_filters( 'b3_localhost', false ) ) {
            return true;
        }

        return false;
    }
