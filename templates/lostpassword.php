<?php
    /**
     * Ouptuts fields for lost password form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $localhost_email = is_localhost() ? apply_filters( 'b3_localhost_email', false ) : false;

    do_action( 'b3_add_form_messages', $attributes );
?>
<div class="b3_page b3_page--lostpass">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false; ?>

    <form name="lostpasswordform" id="lostpasswordform" class="b3_form b3_form--lostpass" action="<?php echo b3_get_current_url(); ?>" method="post">
        <?php do_action( 'b3_render_form_element', 'lostpassword/hidden-fields', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'general/email', $attributes ); ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
    </form>

</div>
