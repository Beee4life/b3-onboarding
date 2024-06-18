<?php
    /**
     * Ouptuts fields for get pass form (shortcode not used yet))
     *
     * @since 3.11.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    // echo '<pre>'; var_dump($attributes); echo '</pre>'; exit;
    do_action( 'b3_add_form_messages', $attributes );
?>
<div id="b3-resetpass" class="b3 b3_page b3_page--getpass">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false; ?>

    <form name="getpassform" id="getpassform" action="<?php echo $attributes['form_action']; ?>" method="post" autocomplete="off">
        <?php do_action( 'b3_render_form_element', 'getpass/hidden-fields', $attributes ); ?>
        <?php if ( $attributes[ 'enter_code' ] ) { ?>
            <?php do_action( 'b3_render_form_element', 'getpass/enter-code', $attributes ); ?>
        <?php } else { ?>
            <?php do_action( 'b3_render_form_element', 'getpass/user-login', $attributes ); ?>
        <?php } ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
    </form>
</div>
