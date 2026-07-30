<?php
    /**
     * Ouptuts fields for lost password form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_add_form_messages', $attributes );
?>
<div class="b3_page b3_page--lostpass">
    <?php if ( ! empty( $attributes[ 'title' ] ) ) { ?>
        <?php echo sprintf( '<h3>%s</h3>', esc_html( $attributes[ 'title' ] ) ); ?>
    <?php } ?>

    <?php if ( get_option( 'b3_use_magic_link' ) ) { ?>
        <?php $attributes[ 'template' ] = 'magiclink'; ?>
        <?php include 'magiclink/magiclink-form.php'; ?>
    <?php } else { ?>
        <?php include 'lostpassword/password-form.php'; ?>
    <?php } ?>
</div>
