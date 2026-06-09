<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $action = sprintf( 'b3_%s_nonce', $attributes[ 'template' ] );
    $key    = sprintf( 'b3_%s', $attributes[ 'template' ] );
    wp_nonce_field( $key, $action, false );
    echo "\n";
