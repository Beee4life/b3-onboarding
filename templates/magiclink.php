<?php
    /**
     * Ouptuts fields for magic link form
     *
     * @since 3.11.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    do_action( 'b3_add_form_messages', $attributes );
?>
<div id="b3-resetpass" class="b3 b3_page b3_page--magiclink">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false; ?>

    <form name="magiclinkform" id="magiclinkform" action="<?php echo $attributes['form_action']; ?>" method="post" autocomplete="off">
        <?php do_action( 'b3_render_form_element', 'magiclink/hidden-fields', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'magiclink/user-email', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
    </form>
</div>
