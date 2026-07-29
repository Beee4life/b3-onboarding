<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $nonce_key = 'magic-password' == $attributes[ 'template' ] ? 'magiclink' : $attributes[ 'template' ];
    $action    = sprintf( 'b3_%s_nonce', $nonce_key );
    $key       = sprintf( 'b3_%s', $nonce_key );
    wp_nonce_field( $key, $action, false );
    echo "\n";
