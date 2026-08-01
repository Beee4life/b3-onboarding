<?php
    /**
     * Ouptuts fields for magic link form and normal login form
     *
     * @since 3.11.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_add_form_messages', $attributes );

    include 'magiclink.php';

    include 'login.php';
