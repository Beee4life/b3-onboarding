<?php
    /**
     * Ouptuts fields for login form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( 'magic-password' !== $attributes[ 'template' ] ) {
        do_action( 'b3_add_form_messages', $attributes );
    }
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php include 'wp-login.php'; ?>
</div>
