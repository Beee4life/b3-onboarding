<?php
    /**
     * Ouptuts fields for magic link form
     *
     * @since 3.11.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div id="b3-resetpass" class="b3 b3_page b3_page--magiclink">
    <?php do_action( 'b3_add_form_messages', $attributes ); ?>

    <?php if ( ! empty( $attributes[ 'title' ] ) ) { ?>
        <?php echo sprintf( '<h3>%s</h3>', esc_html( $attributes[ 'title' ] ) ); ?>
    <?php } ?>


    <?php include 'magiclink/magiclink-form.php'; ?>
</div>
