<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $register_for = apply_filters( 'b3_register_for', false );
    if ( ( false === $register_for || 'blog' == $register_for ) ) {
        $notice = apply_filters( 'b3_message_above_new_blog', esc_html__( 'Here you can register your new site.', 'b3-onboarding' ) );

        if ( $notice ) {
            echo sprintf( '<div class="b3_site-fields-header">%s</div>', $notice );
        }
    }
