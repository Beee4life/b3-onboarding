<?php
    /**
     * Ouptuts fields for magic link form
     *
     * @since 3.11.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( 'magic-password' !== $attributes[ 'template' ] ) {
        do_action( 'b3_add_form_messages', $attributes );
    }
?>
<div id="b3-resetpass" class="b3 b3_page b3_page--magiclink">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', esc_html( $attributes[ 'title' ] ) ) : false; ?>

    <?php include 'magiclink/magiclink-form.php'; ?>
</div>
