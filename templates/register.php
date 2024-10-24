<?php
    /**
     * Ouptuts fields for register form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_add_form_messages', $attributes );

    if ( ! isset( $_REQUEST[ 'registered' ] ) || isset( $_REQUEST[ 'registered' ] ) && 'access_requested' != $_REQUEST[ 'registered' ] ) {
?>
    <div id="b3-register" class="b3_page b3_page--register">
        <?php if ( ! empty( $attributes[ 'title' ] ) ) { ?>
            <?php echo sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ); ?>
        <?php } ?>

        <form name="registerform" id="registerform" class="b3_form b3_form--register" action="<?php echo b3_get_current_url(); ?>" method="post">
            <?php do_action( 'b3_register_form', $attributes ); ?>
        </form>
    </div>
<?php } ?>
