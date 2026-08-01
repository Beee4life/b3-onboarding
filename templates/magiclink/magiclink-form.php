<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $label = esc_html__( 'Enter code', 'b3-onboarding' );
?>
<form name="magiclinkform" id="magiclinkform" action="<?php echo esc_attr( $attributes[ 'form_action' ] ); ?>" method="post" autocomplete="off">
    <?php do_action( 'b3_render_form_element', 'general/nonce-fields', $attributes ); ?>
    <?php do_action( 'b3_render_form_element', 'magiclink/user-email', $attributes ); ?>
    <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
    <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
</form>
