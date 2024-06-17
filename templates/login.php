<?php
    /**
     * Ouptuts fields for login form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_add_form_messages', $attributes );
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php if ( isset( $attributes[ 'one_time_password' ] ) && '1' == $attributes[ 'one_time_password' ] ) { ?>
        <?php include 'one-time-login.php'; ?>
    <?php } else { ?>
        <?php include 'wp-login.php'; ?>
    <?php } ?>
</div>
