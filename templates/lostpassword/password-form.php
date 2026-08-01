<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<form name="lostpasswordform" id="lostpasswordform" class="b3_form b3_form--lostpass" action="<?php echo esc_url( b3_get_lostpassword_url() ); ?>" method="post">
    <?php do_action( 'b3_render_form_element', 'general/nonce-fields', $attributes ); ?>
    <?php do_action( 'b3_render_form_element', 'lostpassword/hidden-fields', $attributes ); ?>
    <?php do_action( 'b3_render_form_element', 'lostpassword/user-login', $attributes ); ?>
    <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
    <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
</form>
