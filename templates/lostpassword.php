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
        <?php echo sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ); ?>
    <?php } ?>

    <form name="lostpasswordform" id="lostpasswordform" class="b3_form b3_form--lostpass" action="<?php echo b3_get_current_url(); ?>" method="post">
        <?php do_action( 'b3_render_form_element', 'lostpassword/hidden-fields', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'lostpassword/user-login', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
    </form>
</div>
