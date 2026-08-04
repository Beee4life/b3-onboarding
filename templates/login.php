<?php
    /**
     * Ouptuts fields for login form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( 'magic-password' === $attributes[ 'template' ] ) {
        $message_above_login = b3_get_message_above_login();
        if ( is_string( $message_above_login ) && ! empty( $message_above_login ) ) {
            $attributes[ 'messages' ][] = $message_above_login;
        } else {
            $attributes[ 'messages' ] = [];
        }
    }
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php do_action( 'b3_add_form_messages', $attributes ); ?>

    <?php if ( ! empty( $attributes[ 'title' ] ) ) { ?>
        <?php echo sprintf( '<h3>%s</h3>', esc_html( $attributes[ 'title' ] ) ); ?>
    <?php } ?>


    <?php include 'wp-login.php'; ?>
</div>
