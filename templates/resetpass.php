<?php
    /**
     * Ouptuts fields for reset pass form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_add_form_messages', $attributes );
?>
<div id="b3-resetpass" class="b3 b3_page b3_page--resetpass">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false; ?>

    <form name="resetpassform" id="resetpassform" action="<?php echo b3_get_reset_password_url(); ?>" method="post" autocomplete="off">
        <?php do_action( 'b3_render_form_element', 'resetpass/hidden-fields', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'resetpass/passwords', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
    </form>
</div>
