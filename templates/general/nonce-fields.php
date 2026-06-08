<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $key = sprintf( 'b3_%s', $attributes[ 'template' ] );
    wp_nonce_field( $key, '_wpnonce' );
