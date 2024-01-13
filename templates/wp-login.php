<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false;
?>

<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
    <?php // Output of fields starts here ?>
    <?php do_action( 'b3_render_form_element', 'login/hidden-fields', $attributes ); ?>
    <?php do_action( 'b3_render_form_element', 'login/user-login' ); ?>
    <?php do_action( 'b3_render_form_element', 'login/password' ); ?>
    <?php do_action( 'b3_render_form_element', 'login/rememberme' ); ?>
    <?php do_action( 'b3_render_form_element', 'general/button', $attributes ); ?>
    <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
</form>
